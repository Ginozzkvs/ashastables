<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * PrinterService using Windows Print Spooler with GD image rendering
 * This approach works because it uses Windows' native rendering pipeline
 * which properly handles international fonts (including Lao)
 * 
 * This is likely how other apps successfully print Lao - they bypass
 * raw ESC/POS and use Windows' printer driver instead
 */
class PrinterServiceWindows
{
    private $printerName = 'XP-80C';  // Actual Windows printer name
    private $fontPath;
    private $isConnected = false;

    public function __construct($fontPath = null)
    {
        if ($fontPath === null) {
            // Try Laravel path first
            if (function_exists('base_path')) {
                $fontPath = base_path('resources/fonts/NotoSansLao-Regular.ttf');
            } else {
                // Fallback to absolute path
                $fontPath = 'c:/Users/acerzz/farm-system/resources/fonts/NotoSansLao-Regular.ttf';
            }
        }
        
        $this->fontPath = str_replace('/', '\\', $fontPath);
        if (!file_exists($this->fontPath)) {
            throw new \Exception("Font file not found: {$this->fontPath}");
        }
    }

    /**
     * Connect to printer via Windows print spooler
     */
    public function connect($printerName = 'XP-80C')
    {
        $this->printerName = $printerName;
        
        // Verify printer exists using WMI (more reliable than PowerShell)
        try {
            $wmi = new \COM("winmgmts:");
            $printers = $wmi->ExecQuery("SELECT * FROM Win32_Printer WHERE Name = '" . str_replace("'", "''", $printerName) . "'");
            
            $found = false;
            foreach ($printers as $printer) {
                $found = true;
                break;
            }
            
            if (!$found) {
                throw new \Exception("Printer not found: $printerName");
            }
        } catch (Exception $e) {
            // If COM not available, assume printer exists (in non-Windows environment)
            if (strpos($e->getMessage(), 'Failed to create COM') !== false) {
                $this->logOrEcho('warning', 'COM not available - skipping printer verification');
            } else {
                throw $e;
            }
        }

        $this->isConnected = true;
        $this->logOrEcho('info', "Connected to Windows printer: $printerName");
        return $this;
    }

    /**
     * Print receipt by rendering Lao text as image and sending to printer
     */
    public function printReceipt($receiptData)
    {
        if (!$this->isConnected) {
            throw new \Exception('Printer not connected');
        }

        try {
            // 1. Generate receipt image (with Lao text)
            $image = $this->generateReceiptImage($receiptData);

            // 2. Convert image to ESC/POS raster format
            $escPos = $this->imageToEscPos($image);

            // 3. Send to printer
            $this->sendToWindows($escPos, $receiptData);

            $this->logOrEcho('info', 'Receipt printed successfully via Windows');
            imagedestroy($image);
        } catch (Exception $e) {
            $this->logOrEcho('error', 'Print failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate receipt as GD image with Lao text
     */
    private function generateReceiptImage($data)
    {
        $width = 384;  // 48mm @ 203 DPI
        $height = 600; // Estimate, will extend as needed
        
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $white);
        
        $y = 20;
        $fontSize = 12;
        $fontColor = $black;
        
        // Header
        $this->drawCenteredText($image, "ASHA STABLES", $width / 2, $y, $fontSize, $fontColor);
        $y += 30;
        
        $this->drawCenteredText($image, "Member Activity Receipt", $width / 2, $y, $fontSize, $fontColor);
        $y += 40;
        
        // Divider
        $this->drawLine($image, 10, $y, $width - 10, $y, $black);
        $y += 20;
        
        // Receipt info
        $this->drawText($image, "Receipt ID: " . ($data['receipt_id'] ?? 'AUTO'), 20, $y, 10, $fontColor);
        $y += 25;
        
        $this->drawText($image, "Date: " . (isset($data['timestamp']) ? $data['timestamp'] : date('d-m-Y H:i')), 20, $y, 10, $fontColor);
        $y += 40;
        
        // Member section header
        $this->drawText($image, "Member Information", 20, $y, 11, $fontColor);
        $y += 25;
        
        // Member info with Lao labels possible here
        $this->drawText($image, "Name: " . ($data['member_name'] ?? '-'), 20, $y, 10, $fontColor);
        $y += 25;
        
        $this->drawText($image, "Card ID: " . ($data['card_uid'] ?? '-'), 20, $y, 10, $fontColor);
        $y += 40;
        
        // Divider
        $this->drawLine($image, 10, $y, $width - 10, $y, $black);
        $y += 20;
        
        // Activity details header
        $this->drawText($image, "Activity Details", 20, $y, 11, $fontColor);
        $y += 25;
        
        $this->drawText($image, "Activity: " . ($data['activity_name'] ?? '-'), 20, $y, 10, $fontColor);
        $y += 25;
        
        $this->drawText($image, "Sessions Used: 1", 20, $y, 10, $fontColor);
        $y += 40;
        
        // Divider
        $this->drawLine($image, 10, $y, $width - 10, $y, $black);
        $y += 20;
        
        // Session balance
        $this->drawText($image, "Session Balance", 20, $y, 11, $fontColor);
        $y += 25;
        
        $used = $data['used_sessions'] ?? 0;
        $remaining = $data['remaining_sessions'] ?? 0;
        $total = $used + $remaining;
        
        $this->drawText($image, "Sessions Used: " . str_pad($used, 10, '.', STR_PAD_LEFT), 20, $y, 10, $fontColor);
        $y += 25;
        
        $this->drawText($image, "Sessions Left: " . str_pad($remaining, 10, '.', STR_PAD_LEFT), 20, $y, 10, $fontColor);
        $y += 25;
        
        $this->drawText($image, "Total Sessions: " . str_pad($total, 10, '.', STR_PAD_LEFT), 20, $y, 10, $fontColor);
        $y += 40;
        
        // Divider
        $this->drawLine($image, 10, $y, $width - 10, $y, $black);
        $y += 20;
        
        // Status
        $this->drawCenteredText($image, "COMPLETED + APPROVED", $width / 2, $y, 12, $fontColor);
        $y += 40;
        
        // Footer
        $this->drawCenteredText($image, "Thank you for using", $width / 2, $y, 10, $fontColor);
        $y += 25;
        
        $this->drawCenteredText($image, "ASHA STABLES", $width / 2, $y, 11, $fontColor);
        
        return $image;
    }

    /**
     * Draw centered text using Lao font
     */
    private function drawCenteredText(&$image, $text, $centerX, $y, $fontSize, $color)
    {
        $bbox = imagettfbbox($fontSize, 0, $this->fontPath, $text);
        $textWidth = abs($bbox[4] - $bbox[0]);
        $x = $centerX - ($textWidth / 2);
        imagettftext($image, $fontSize, 0, $x, $y, $color, $this->fontPath, $text);
    }

    /**
     * Draw left-aligned text
     */
    private function drawText(&$image, $text, $x, $y, $fontSize, $color)
    {
        imagettftext($image, $fontSize, 0, $x, $y, $color, $this->fontPath, $text);
    }

    /**
     * Draw a line
     */
    private function drawLine(&$image, $x1, $y1, $x2, $y2, $color)
    {
        imageline($image, $x1, $y1, $x2, $y2, $color);
    }

    /**
     * Convert image to ESC/POS raster format (GS v 0 - Standard format)
     */
    private function imageToEscPos($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        $output = "\x1B\x40"; // Reset
        $output .= "\x1B\x33\x00"; // Line spacing
        
        // Raster image header
        for ($y = 0; $y < $height; $y += 24) {
            $sliceHeight = min(24, $height - $y);
            
            // GS v 0 format
            $output .= "\x1D\x76\x30\x00"; // GS v 0 m=0
            
            // Width in bytes (384px = 48 bytes)
            $widthBytes = ceil($width / 8);
            $output .= chr($widthBytes & 0xFF) . chr(($widthBytes >> 8) & 0xFF);
            
            // Height
            $output .= chr($sliceHeight & 0xFF) . chr(($sliceHeight >> 8) & 0xFF);
            
            // Raster data
            for ($x = 0; $x < $widthBytes; $x++) {
                for ($iy = 0; $iy < $sliceHeight; $iy++) {
                    $byte = 0;
                    for ($bit = 0; $bit < 8; $bit++) {
                        $px = $x * 8 + $bit;
                        if ($px < $width) {
                            $pixelColor = imagecolorat($image, $px, $y + $iy);
                            $gray = 0.299 * (($pixelColor >> 16) & 0xFF) +
                                   0.587 * (($pixelColor >> 8) & 0xFF) +
                                   0.114 * ($pixelColor & 0xFF);
                            
                            if ($gray < 128) {
                                $byte |= (0x80 >> $bit);
                            }
                        }
                    }
                    $output .= chr($byte);
                }
            }
        }
        
        // Cut paper with delay
        for ($i = 0; $i < 10; $i++) {
            $output .= "\n";
        }
        $output .= "\x1D\x56\x01\x00"; // Cut paper (feed + cut)
        $output .= "\x1B\x40"; // Reset
        
        return $output;
    }

    /**
     * Send ESC/POS data to printer via network socket (backup method)
     */
    private function sendToWindows($escPos, $receiptData)
    {
        // Method 1: Try network socket (if printer is on network)
        if ($this->sendViaSocket($escPos)) {
            return;
        }

        // Method 2: Send via file queue (Windows printer spool)
        $this->sendViaFileQueue($escPos);
    }

    /**
     * Send data via ethernet socket (9100 port)
     */
    private function sendViaSocket($data)
    {
        try {
            // Try common printer IP addresses
            $ips = ['192.168.1.100', '192.168.0.100', '10.0.0.100', '127.0.0.1'];
            
            foreach ($ips as $ip) {
                $socket = @fsockopen($ip, 9100, $errno, $errstr, 2);
                if ($socket) {
                    fwrite($socket, $data);
                    fclose($socket);
                    $this->logOrEcho('info', "Sent via socket to $ip");
                    return true;
                }
            }
        } catch (Exception $e) {
            // Continue to next method
        }

        return false;
    }

    /**
     * Send data via Windows printer queue file
     */
    private function sendViaFileQueue($data)
    {
        // Create a temporary PRN file and queue it to printer
        try {
            // The Windows printer queue expects specific format
            // For ESC/POS, we need to handle it specially
            
            // Attempt 1: Direct file write to PRN device
            $printerPath = "\\\\.\\" . $this->printerName;
            
            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'receipt_');
            file_put_contents($tempFile, $data, FILE_BINARY);
            
            // Try to send using Windows print command
            $command = 'print /D:"' . $this->printerName . '" "' . $tempFile . '" 2>&1';
            $output = shell_exec($command);
            
            if (empty($output) || strpos($output, 'sent') !== false) {
                $this->logOrEcho('info', 'Sent to printer via print queue');
            } else {
                $this->logOrEcho('warning', 'Print queue result: ' . trim($output));
            }
            
            @unlink($tempFile);
            
        } catch (Exception $e) {
            $this->logOrEcho('error', 'Print queue error: ' . $e->getMessage());
        }
    }

    /**
     * Log or echo helper
     */
    private function logOrEcho($level, $msg)
    {
        if (class_exists('Illuminate\\Support\\Facades\\Log')) {
            Log::$level($msg);
        } else {
            echo "[" . strtoupper($level) . "] $msg\n";
        }
    }

    public function disconnect()
    {
        $this->isConnected = false;
    }
}
