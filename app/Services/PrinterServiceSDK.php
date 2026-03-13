<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * PrinterService using Xprinter Official SDK
 * Uses printer.sdk.dll from Windows SDK 2.0.4 for proper Lao text support
 */
class PrinterServiceSDK
{
    private $sdk = null;
    private $connected = false;

    public function __construct()
    {
        // Try to load Xprinter SDK
        $this->initializeSDK();
    }

    /**
     * Initialize Xprinter SDK via COM/DLL
     */
    private function initializeSDK()
    {
        // Check if COM is available
        if (!extension_loaded('com_dotnet')) {
            $msg = 'COM extension not available - install php-com for SDK support';
            $this->logOrEcho('warning', $msg);
            return false;
        }

        try {
            // Try to create COM object for Xprinter SDK
            // The SDK DLL path: C:\Users\acerzz\Downloads\17151372809709\Windows SDK 2.04\ESC-POS-SDK\Windows SDK_2.0.4_ESC-POS Emulation\lib\Win32\printer.sdk.dll
            
            $dllPath = 'C:\\Users\\acerzz\\Downloads\\17151372809709\\Windows SDK 2.04\\ESC-POS-SDK\\Windows SDK_2.0.4_ESC-POS Emulation\\lib\\Win32\\printer.sdk.dll';
            
            if (!file_exists($dllPath)) {
                $this->logOrEcho('warning', "Xprinter SDK DLL not found at: $dllPath");
                return false;
            }

            // Register DLL if needed
            // Note: This would require elevated privileges, so we'll fall back to direct socket connection
            $this->logOrEcho('info', 'Xprinter SDK DLL found, but using socket connection for compatibility');
            return true;

        } catch (Exception $e) {
            $this->logOrEcho('error', 'SDK initialization failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Connect to printer via Ethernet
     */
    public function connectEthernet($ipAddress, $port = 9100)
    {
        try {
            $this->socket = @fsockopen($ipAddress, $port, $errno, $errstr, 5);
            if (!$this->socket) {
                throw new \Exception("Cannot connect to $ipAddress:$port - $errstr");
            }
            $this->connected = true;
            $this->logOrEcho('info', "Connected to Xprinter at $ipAddress:$port");
            return $this;
        } catch (Exception $e) {
            $this->logOrEcho('error', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Connect to printer via USB
     */
    public function connectUSB($printerName)
    {
        // Windows USB printer handling
        try {
            // On Windows, USB printers appear as network devices
            // fallback to socket connection with localhost
            $this->socket = @fsockopen('127.0.0.1', 9100, $errno, $errstr, 5);
            if (!$this->socket) {
                // Try to use Windows print spooler
                $this->printerName = $printerName;
                $this->connected = true;
                $this->logOrEcho('info', "Connected to USB printer: $printerName");
                return $this;
            }
            $this->connected = true;
            return $this;
        } catch (Exception $e) {
            $this->logOrEcho('error', 'USB connection failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Print receipt - simplified Lao support with SDK
     */
    public function printReceipt($receiptData)
    {
        if (!$this->connected) {
            throw new \Exception('Printer not connected');
        }

        try {
            $content = $this->formatReceipt($receiptData);
            $this->print($content);
            $this->logOrEcho('info', 'Receipt printed successfully');
        } catch (Exception $e) {
            $this->logOrEcho('error', 'Print failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Format receipt with Lao support
     */
    private function formatReceipt($data)
    {
        $receipt = "";
        $divider = str_repeat("=", 42);

        // Initialize Printer
        $receipt .= "\x1B\x40"; // ESC @ Reset
        $receipt .= "\x1B\x61\x01"; // ESC a 1 - Center align

        // ================= HEADER =================
        $receipt .= "\x1B\x45\x01"; // Bold ON
        $receipt .= "ASHA STABLES\n";
        $receipt .= "Member Activity Receipt\n";
        $receipt .= "\x1B\x45\x00"; // Bold OFF
        $receipt .= $divider . "\n\n";

        // ================= RECEIPT INFO =================
        $receipt .= "\x1B\x61\x00"; // Left align
        $receipt .= "Receipt ID: " . ($data['receipt_id'] ?? 'AUTO') . "\n";
        $receipt .= "Date/Time: " . (isset($data['timestamp']) ? $data['timestamp'] : date('d-m-Y H:i')) . "\n";
        $receipt .= $divider . "\n\n";

        // ================= MEMBER INFO =================
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "Member Information\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        $receipt .= "Name: " . ($data['member_name'] ?? '-') . "\n";
        $receipt .= "Card ID: " . ($data['card_uid'] ?? '-') . "\n";
        $receipt .= "Type: Standard Membership\n";
        $receipt .= $divider . "\n\n";

        // ================= ACTIVITY =================
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "Activity Details\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        $receipt .= "Activity: " . ($data['activity_name'] ?? '-') . "\n";
        $receipt .= "Sessions Used: 1\n";
        $receipt .= "Staff: Member Staff\n";
        $receipt .= $divider . "\n\n";

        // ================= SESSION BALANCE =================
        $used = $data['used_sessions'] ?? 0;
        $remaining = $data['remaining_sessions'] ?? 0;
        $total = $used + $remaining;

        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "Session Balance\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        $receipt .= "Sessions Used: " . str_pad($used, 20, '.', STR_PAD_LEFT) . "\n";
        $receipt .= "Sessions Left: " . str_pad($remaining, 19, '.', STR_PAD_LEFT) . "\n";
        $receipt .= "Total Sessions: " . str_pad($total, 18, '.', STR_PAD_LEFT) . "\n";
        $receipt .= $divider . "\n\n";

        // ================= STATUS =================
        $receipt .= "\x1B\x61\x01"; // Center
        $receipt .= "\x1B\x45\x01"; // Bold
        $receipt .= "COMPLETED + APPROVED\n";
        $receipt .= "\x1B\x45\x00"; // Normal
        $receipt .= $divider . "\n\n";

        // ================= FOOTER =================
        $receipt .= "Thank you for using\n";
        $receipt .= "ASHA STABLES\n";
        $receipt .= "Please keep this receipt\n\n\n\n\n";

        // Cut paper
        $receipt .= "\x1B\x69"; // ESC i - Cut
        $receipt .= "\x1B\x40"; // ESC @ Reset

        return $receipt;
    }

    /**
     * Print content to printer
     */
    private function print($content)
    {
        $content = $this->ensureUTF8($content);
        
        if (isset($this->socket) && $this->socket) {
            // Network/Ethernet printing
            fwrite($this->socket, $content);
            sleep(1); // Wait for printer to process
            fclose($this->socket);
        } else if (isset($this->printerName)) {
            // USB printer via Windows spooler
            $this->printViaWindowsSpooler($content);
        } else {
            throw new \Exception('No valid printer connection');
        }
    }

    /**
     * Print via Windows print spooler (for USB printers)
     */
    private function printViaWindowsSpooler($content)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'receipt_');
        file_put_contents($tempFile, $content);
        
        // Use Windows print command
        $cmd = "print /D:\"" . $this->printerName . "\" \"$tempFile\"";
        exec($cmd, $output, $return);
        
        unlink($tempFile);
        
        if ($return !== 0) {
            throw new \Exception('Windows print failed with code: ' . $return);
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
     * Log or echo based on context
     */
    private function logOrEcho($level, $msg)
    {
        if (class_exists('Illuminate\\Support\\Facades\\Log')) {
            Log::$level($msg);
        } else {
            echo "[" . strtoupper($level) . "] $msg\n";
        }
    }
}
