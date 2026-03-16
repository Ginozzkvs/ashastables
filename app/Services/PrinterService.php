<?php

namespace App\Services;

use Exception;

class PrinterService
{
    private $printerName;
    private $isEthernet = false;
    private $ipAddress;
    private $port = 9100;

    public function connectUSB($printerName)
    {
        $this->printerName = $printerName;
        $this->isEthernet = false;
        return $this;
    }

    public function connectEthernet($ipAddress, $port = null)
    {
        $this->ipAddress = $ipAddress;
        $this->isEthernet = true;
        if ($port) {
            $this->port = (int) $port;
        }
        return $this;
    }

    public function printReceipt($receiptData)
    {
        $content = $this->formatReceipt($receiptData);

        if ($this->isEthernet) {
            $this->sendEthernet($content);
        } else {
            $this->sendUSB($content);
        }

        return $this;
    }

    private function formatReceipt($data)
    {
        $receipt = "";
        $divider = str_repeat("=", 42);

        // Reset printer
        $receipt .= "\x1B\x40";
        // Center align
        $receipt .= "\x1B\x61\x01";

        // Header
        $receipt .= "\x1B\x45\x01"; // Bold ON
        $receipt .= "ASHA STABLES\n";
        $receipt .= "Member Activity Receipt\n";
        $receipt .= "\x1B\x45\x00"; // Bold OFF
        $receipt .= $divider . "\n\n";

        // Receipt info - left align
        $receipt .= "\x1B\x61\x00";
        $receipt .= "Receipt ID: " . ($data['receipt_id'] ?? 'AUTO') . "\n";
        $receipt .= "Date/Time: " . ($data['timestamp'] ?? date('d-m-Y H:i')) . "\n";
        $receipt .= $divider . "\n\n";

        // Member info
        $receipt .= "\x1B\x45\x01";
        $receipt .= "Member Information\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= "Name: " . ($data['member_name'] ?? '-') . "\n";
        $receipt .= "Card ID: " . ($data['card_uid'] ?? '-') . "\n";
        $receipt .= "Type: " . ($data['membership_name'] ?? 'Standard Membership') . "\n";
        $receipt .= $divider . "\n\n";

        // Activity
        $receipt .= "\x1B\x45\x01";
        $receipt .= "Activity Details\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= "Activity: " . ($data['activity_name'] ?? '-') . "\n";
        $receipt .= "Sessions Used: 1\n";
        $receipt .= "Staff: Member Staff\n";
        $receipt .= $divider . "\n\n";

        // Session balance
        $used = $data['used_count'] ?? $data['used_sessions'] ?? 0;
        $remaining = $data['remaining_count'] ?? $data['remaining_sessions'] ?? 0;
        $total = $used + $remaining;

        $receipt .= "\x1B\x45\x01";
        $receipt .= "Session Balance\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= "Sessions Used: " . str_pad($used, 20, '.', STR_PAD_LEFT) . "\n";
        $receipt .= "Sessions Left: " . str_pad($remaining, 19, '.', STR_PAD_LEFT) . "\n";
        $receipt .= "Total Sessions: " . str_pad($total, 18, '.', STR_PAD_LEFT) . "\n";
        $receipt .= $divider . "\n\n";

        // Status - center
        $receipt .= "\x1B\x61\x01";
        $receipt .= "\x1B\x45\x01";
        $receipt .= "COMPLETED + APPROVED\n";
        $receipt .= "\x1B\x45\x00";
        $receipt .= $divider . "\n\n";

        // Footer
        $receipt .= "Thank you for using\n";
        $receipt .= "ASHA STABLES\n";
        $receipt .= "Please keep this receipt\n\n\n\n\n";

        // Cut paper
        $receipt .= "\x1D\x56\x01"; // Partial cut
        $receipt .= "\x1B\x40";     // Reset

        return $receipt;
    }

    private function sendEthernet($content)
    {
        if (!$this->ipAddress) {
            throw new Exception("Printer IP address not set");
        }

        $socket = @fsockopen($this->ipAddress, $this->port, $errno, $errstr, 5);
        if (!$socket) {
            throw new Exception("Cannot connect to printer at {$this->ipAddress}:{$this->port} - $errstr");
        }

        fwrite($socket, $content);
        fclose($socket);
    }

    private function sendUSB($content)
    {
        if (!$this->printerName) {
            throw new Exception("Printer name not set");
        }

        $handle = @fopen("\\\\.\\" . $this->printerName, "w");
        if (!$handle) {
            throw new Exception("Failed to open printer: " . $this->printerName);
        }

        fwrite($handle, $content);
        fclose($handle);
    }

    public static function getUSBPrinters()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            return [];
        }

        $output = [];
        exec('powershell -Command "Get-Printer -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Name"', $output);

        return $output ?? [];
    }

    public function testConnection()
    {
        if ($this->isEthernet) {
            $socket = @fsockopen($this->ipAddress, $this->port, $errno, $errstr, 3);
            if (!$socket) {
                throw new Exception("Cannot connect to printer at {$this->ipAddress}:{$this->port} - $errstr");
            }
            fclose($socket);
            return true;
        }

        if (!$this->printerName) {
            throw new Exception("Printer name not set");
        }
        return true;
    }

    public function disconnect()
    {
        // Nothing to close
    }
}
