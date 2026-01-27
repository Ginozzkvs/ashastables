<?php

namespace App\Services;

use Exception;

class PrinterService
{
    private $printerName;
    private $isEthernet = false;
    private $ipAddress;
    private $port = 9100; // Default thermal printer port
    private $socket;

    /**
     * Initialize USB Printer
     * @param string $printerName - Printer name from Windows
     */
    public function connectUSB($printerName)
    {
        $this->printerName = $printerName;
        $this->isEthernet = false;
        return $this;
    }

    /**
     * Initialize Ethernet Printer
     * @param string $ipAddress - Printer IP address
     */
    public function connectEthernet($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        $this->port = 9100; // Always use 9100
        $this->isEthernet = true;

        // Connect with longer timeout for reliability
        $this->socket = @fsockopen($ipAddress, $this->port, $errno, $errstr, 10);
        if (!$this->socket) {
            throw new Exception("Failed to connect to printer at $ipAddress:$this->port - $errstr");
        }
        
        // Set socket timeout
        stream_set_timeout($this->socket, 10);

        return $this;
    }

    /**
     * Print Receipt
     */
    public function printReceipt($receiptData)
    {
        $content = $this->formatReceipt($receiptData);
        $this->print($content);
        return $this;
    }

    /**
     * Format receipt content with ESC/POS commands - Professional Style (Matches activity.blade.php)
     */
    private function formatReceipt($data)
    {
        $receipt = "";
        $divider = str_repeat("-", 48);
        
        // Initialize printer
        $receipt .= "\x1B\x40"; // ESC @ (Initialize)

        // ============ HEADER ============
        $receipt .= "\x1B\x61\x01"; // Center alignment
        $receipt .= "\x1B\x45\x01"; // Bold on
        $receipt .= "★ ASHA STABLES ★\n";
        $receipt .= "\x1B\x45\x00"; // Bold off
        $receipt .= "EQUESTRIAN RESORT\n";
        $receipt .= "Activity Session Receipt\n";
        $receipt .= "\n";
        
        // Dashed border
        $receipt .= $divider . "\n\n";
        
        // ============ RECEIPT META ============
        $receipt .= "\x1B\x61\x00"; // Left alignment
        
        // Parse timestamp
        $dateTime = isset($data['timestamp']) ? $data['timestamp'] : date('m/d/Y h:i A');
        $parts = explode(' ', $dateTime);
        $date = $parts[0] ?? '';
        $time = (isset($parts[1]) && isset($parts[2])) ? $parts[1] . ' ' . $parts[2] : '';
        
        $receipt .= "RECEIPT #     " . (isset($data['receipt_id']) ? $data['receipt_id'] : 'AUTO') . "\n";
        $receipt .= "DATE          " . $date . "\n";
        $receipt .= "TIME          " . $time . "\n";
        $receipt .= "\n" . $divider . "\n\n";
        
        // ============ MEMBER SECTION ============
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "— MEMBER —\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        
        if (isset($data['member_name'])) {
            $receipt .= $data['member_name'] . "\n";
        }
        if (isset($data['card_uid'])) {
            $receipt .= "ID: " . $data['card_uid'] . "\n";
        }
        if (isset($data['card_uid'])) {
            $receipt .= "Membership: Standard\n";
        }
        $receipt .= "\n" . $divider . "\n\n";
        
        // ============ ACTIVITY SECTION ============
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "— ACTIVITY —\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        
        if (isset($data['activity_name'])) {
            $receipt .= $data['activity_name'] . "\n";
        }
        
        $receipt .= "Duration:     1 time\n";
        $receipt .= "Instructor:   Staff\n";
        $receipt .= "\n" . $divider . "\n\n";
        
        // ============ SESSION STATUS ============
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "— SESSION STATUS —\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        
        $used = isset($data['used_sessions']) ? $data['used_sessions'] : 0;
        $remaining = isset($data['remaining_sessions']) ? $data['remaining_sessions'] : 0;
        $total = $used + $remaining;
        
        // Format with right alignment
        $receipt .= "Sessions Used:    " . str_pad($used, 10, ' ', STR_PAD_LEFT) . "\n";
        $receipt .= "Sessions Left:    " . str_pad($remaining, 10, ' ', STR_PAD_LEFT) . "\n";
        $receipt .= "Total Allowed:    " . str_pad($total, 10, ' ', STR_PAD_LEFT) . "\n";
        $receipt .= "\n" . $divider . "\n\n";
        
        // ============ SESSION DETAILS ============
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "— SESSION DETAILS —\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        
        $receipt .= "Status:       RESERVED\n";
        $receipt .= "\n" . $divider . "\n\n";
        
        // ============ FOOTER ============
        $receipt .= "\x1B\x61\x01"; // Center
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "THANK YOU!\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        $receipt .= "Please keep this receipt for your records.\n";
        $receipt .= "\n";
        $receipt .= "For support, contact staff.\n";
        $receipt .= "\n";
        $receipt .= "★ ★ ★ ★ ★\n";
        $receipt .= "\n";

        // Cut paper
        $receipt .= "\x1B\x69"; // ESC i (Partial cut)

        // Reset
        $receipt .= "\x1B\x40"; // Reset

        return $receipt;
    }

    /**
     * Send content to printer
     */
    private function print($content)
    {
        if ($this->isEthernet) {
            return $this->printEthernet($content);
        } else {
            return $this->printUSB($content);
        }
    }

    /**
     * Print via Ethernet
     */
    private function printEthernet($content)
    {
        if (!$this->socket) {
            throw new Exception("Printer not connected");
        }

        // Send data in chunks
        $chunkSize = 1024;
        $totalBytes = 0;
        $contentLength = strlen($content);
        
        for ($i = 0; $i < $contentLength; $i += $chunkSize) {
            $chunk = substr($content, $i, $chunkSize);
            $written = fwrite($this->socket, $chunk);
            
            if ($written === false) {
                throw new Exception("Failed to send data to ethernet printer");
            }
            
            $totalBytes += $written;
            usleep(100000); // 100ms delay between chunks
        }

        // Flush the socket
        fflush($this->socket);
        usleep(500000); // 500ms to allow printer to process
        
        // Close socket after printing
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }

        return true;
    }

    /**
     * Print via USB (Windows only)
     */
    private function printUSB($content)
    {
        // For Windows, use printer directly
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $handle = fopen("\\\\.\\" . $this->printerName, "w");
            if (!$handle) {
                throw new Exception("Failed to open printer: " . $this->printerName);
            }

            fwrite($handle, $content);
            fclose($handle);
            return true;
        }

        // For Linux/Mac, use lp or lpr command
        $escapedContent = escapeshellarg($content);
        exec("echo " . $escapedContent . " | lp -d " . $this->printerName, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new Exception("Failed to print: " . implode("\n", $output));
        }

        return true;
    }

    /**
     * Get available USB printers (Windows)
     */
    public static function getUSBPrinters()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            return [];
        }

        $printers = [];
        exec('Get-Printer -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Name', $output);

        return $output ?? [];
    }

    /**
     * Test printer connection
     */
    public function testConnection()
    {
        try {
            if ($this->isEthernet) {
                $test = "\x1B\x40"; // Reset command
                fwrite($this->socket, $test);
                return true;
            } else {
                return true; // USB will error if unavailable
            }
        } catch (Exception $e) {
            throw new Exception("Printer test failed: " . $e->getMessage());
        }
    }

    /**
     * Close connection
     */
    public function disconnect()
    {
        if ($this->isEthernet && $this->socket) {
            fclose($this->socket);
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}
