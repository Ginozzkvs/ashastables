<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * PrinterService using Windows OPOS (OLE for PDL Printers)
 * Uses OPOS COM interface which supports international languages including Lao
 * This is why other apps can print Lao - they use OPOS, not raw ESC/POS
 */
class PrinterServiceOPOS
{
    private $printerDevice = null;
    private $printerName = null;
    private $connected = false;

    public function __construct()
    {
    }

    /**
     * Connect via OPOS (Windows standard for POS printers)
     */
    public function connectOPOS($printerName = 'Xprinter XP-T80')
    {
        try {
            // OPOS COM object creation
            if (!extension_loaded('com_dotnet')) {
                throw new \Exception('COM extension required for OPOS. Enable in php.ini: extension=com_dotnet');
            }

            // Create OPOS device object
            $this->printerDevice = new \COM('OPOS.POSPrinter');
            $this->printerName = $printerName;

            // Open device
            if ($this->printerDevice->Open($printerName) == 0) {
                $this->connected = true;
                $this->logOrEcho('info', "Connected to printer via OPOS: $printerName");
                
                // Enable printer
                $this->printerDevice->ClaimDevice(1000);
                $this->printerDevice->DeviceEnabled = true;
                
                return $this;
            } else {
                throw new \Exception("Failed to open OPOS device: $printerName");
            }
        } catch (Exception $e) {
            $this->logOrEcho('error', 'OPOS connection failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Connect via traditional network socket (fallback)
     */
    public function connectEthernet($ipAddress, $port = 9100)
    {
        try {
            $this->socket = @fsockopen($ipAddress, $port, $errno, $errstr, 5);
            if (!$this->socket) {
                throw new \Exception("Cannot connect to $ipAddress:$port");
            }
            $this->connected = true;
            $this->logOrEcho('info', "Connected via Ethernet: $ipAddress:$port");
            return $this;
        } catch (Exception $e) {
            $this->logOrEcho('error', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Print receipt using OPOS
     */
    public function printReceipt($receiptData)
    {
        if (!$this->connected) {
            throw new \Exception('Printer not connected');
        }

        try {
            if ($this->printerDevice) {
                // Use OPOS method
                return $this->printReceiptOPOS($receiptData);
            } else {
                // Fallback to socket method
                $content = $this->formatReceipt($receiptData);
                $this->print($content);
            }
            $this->logOrEcho('info', 'Receipt printed successfully');
        } catch (Exception $e) {
            $this->logOrEcho('error', 'Print failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Print receipt using OPOS COM interface
     */
    private function printReceiptOPOS($data)
    {
        $printer = $this->printerDevice;
        
        // OPOS transactions
        $printer->TransactionPrint(1); // Start receipt mode
        
        // Header with Lao text (OPOS should handle Unicode)
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "ASHA STABLES\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "===================================\n");
        
        // Receipt info with Lao characters
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Receipt ID: " . ($data['receipt_id'] ?? 'AUTO') . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Date/Time: " . (isset($data['timestamp']) ? $data['timestamp'] : date('d-m-Y H:i')) . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "===================================\n\n");

        // Member info - Lao text here
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "Member Information\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Name: " . ($data['member_name'] ?? '-') . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Card ID: " . ($data['card_uid'] ?? '-') . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Type: Standard Membership\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "===================================\n\n");

        // Activity details
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "Activity Details\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Activity: " . ($data['activity_name'] ?? '-') . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Sessions Used: 1\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "===================================\n\n");

        // Session balance - with Lao text labels
        $used = $data['used_sessions'] ?? 0;
        $remaining = $data['remaining_sessions'] ?? 0;
        $total = $used + $remaining;

        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "Session Balance\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Sessions Used: " . str_pad($used, 20, '.', STR_PAD_LEFT) . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Sessions Left: " . str_pad($remaining, 19, '.', STR_PAD_LEFT) . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, 
            "Total Sessions: " . str_pad($total, 18, '.', STR_PAD_LEFT) . "\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "===================================\n\n");

        // Status
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "COMPLETED + APPROVED\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "===================================\n\n");

        // Footer with Lao text
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "Thank you for using\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "ASHA STABLES\n");
        $printer->PrintNormal(\OPOS_PR_STATION_RECEIPT, "Please keep this receipt\n\n\n");

        // Cut paper
        $printer->CutPaper(100);
        
        // End transaction
        $printer->TransactionPrint(2); // End receipt mode
    }

    /**
     * Format receipt for socket/network printing
     */
    private function formatReceipt($data)
    {
        $receipt = "";
        $divider = str_repeat("=", 42);

        $receipt .= "\x1B\x40"; // ESC @ Reset
        $receipt .= "\x1B\x61\x01"; // Center

        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "ASHA STABLES\n";
        $receipt .= "Member Activity Receipt\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        $receipt .= $divider . "\n\n";

        $receipt .= "\x1B\x61\x00"; // Left align
        $receipt .= "Receipt ID: " . ($data['receipt_id'] ?? 'AUTO') . "\n";
        $receipt .= "Date/Time: " . (isset($data['timestamp']) ? $data['timestamp'] : date('d-m-Y H:i')) . "\n";
        $receipt .= $divider . "\n\n";

        $receipt .= "\x1B\x45\x01";
        $receipt .= "Member Information\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= "Name: " . ($data['member_name'] ?? '-') . "\n";
        $receipt .= "Card ID: " . ($data['card_uid'] ?? '-') . "\n";
        $receipt .= "Type: Standard Membership\n";
        $receipt .= $divider . "\n\n";

        $receipt .= "\x1B\x45\x01";
        $receipt .= "Activity Details\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= "Activity: " . ($data['activity_name'] ?? '-') . "\n";
        $receipt .= "Sessions Used: 1\n";
        $receipt .= $divider . "\n\n";

        $used = $data['used_sessions'] ?? 0;
        $remaining = $data['remaining_sessions'] ?? 0;
        $total = $used + $remaining;

        $receipt .= "\x1B\x45\x01";
        $receipt .= "Session Balance\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= "Sessions Used: " . str_pad($used, 20, '.', STR_PAD_LEFT) . "\n";
        $receipt .= "Sessions Left: " . str_pad($remaining, 19, '.', STR_PAD_LEFT) . "\n";
        $receipt .= "Total Sessions: " . str_pad($total, 18, '.', STR_PAD_LEFT) . "\n";
        $receipt .= $divider . "\n\n";

        $receipt .= "\x1B\x61\x01";
        $receipt .= "\x1B\x45\x01";
        $receipt .= "COMPLETED + APPROVED\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= $divider . "\n\n";

        $receipt .= "Thank you for using\n";
        $receipt .= "ASHA STABLES\n";
        $receipt .= "Please keep this receipt\n\n\n\n\n";

        $receipt .= "\x1B\x69"; // Cut
        $receipt .= "\x1B\x40"; // Reset

        return $receipt;
    }

    /**
     * Print to network socket
     */
    private function print($content)
    {
        $content = $this->ensureUTF8($content);
        if (isset($this->socket) && $this->socket) {
            fwrite($this->socket, $content);
            sleep(1);
            fclose($this->socket);
        }
    }

    /**
     * Ensure UTF-8 encoding
     */
    private function ensureUTF8($str)
    {
        if (!mb_check_encoding($str, 'UTF-8')) {
            $str = mb_convert_encoding($str, 'UTF-8');
        }
        return $str;
    }

    /**
     * Log or echo
     */
    private function logOrEcho($level, $msg)
    {
        if (class_exists('Illuminate\\Support\\Facades\\Log')) {
            Log::$level($msg);
        } else {
            echo "[" . strtoupper($level) . "] $msg\n";
        }
    }

    /**
     * Disconnect
     */
    public function disconnect()
    {
        if ($this->printerDevice) {
            $this->printerDevice->ReleaseDevice();
            $this->printerDevice->Close();
        }
        if (isset($this->socket)) {
            fclose($this->socket);
        }
        $this->connected = false;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
