<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * PrinterService - Main unified printer controller
 * 
 * Now uses Windows Print Spooler approach (Lao-compatible) by default
 * Falls back to socket-based printing for network printers
 */
class PrinterService
{
    private $windowsService = null;
    private $printerName;
    private $isEthernet = false;
    private $ipAddress;
    private $port = 9100;
    private $socket = null;

    /**
     * Initialize USB Printer (uses Windows Print Spooler)
     */
    /**
     * Initialize USB Printer (uses Windows Print Spooler)
     */
    public function connectUSB($printerName)
    {
        $this->printerName = $printerName;
        $this->isEthernet = false;
        
        try {
            // Initialize Windows print spooler service (Lao-compatible)
            $this->windowsService = new PrinterServiceWindows();
            $this->windowsService->connect($printerName);
        } catch (Exception $e) {
            $this->log('warning', "Windows service failed: " . $e->getMessage());
        }
        
        return $this;
    }

    /**
     * Initialize Ethernet Printer
     */
    public function connectEthernet($ipAddress, $port = null)
    {
        $this->ipAddress = $ipAddress;
        $this->isEthernet = true;
        if ($port) {
            $this->port = (int) $port;
        }
        return $this;
    }

    /**
     * Print Receipt
     */
    public function printReceipt($receiptData)
    {
        $this->log('info', '=== Starting Receipt Print ===');

        try {
            // Try Windows service first (USB - Lao-compatible)
            if ($this->windowsService) {
                $this->log('info', 'Using Windows Print Spooler (Lao support)');
                $this->windowsService->printReceipt($receiptData);
                return $this;
            }

            // Ethernet: render receipt as image for Lao language support
            if ($this->isEthernet) {
                $this->log('info', 'Using Ethernet with image rendering (Lao support)');
                $this->printEthernetWithImage($receiptData);
            } else {
                throw new Exception('Printer not properly configured');
            }
        } catch (Exception $e) {
            $this->log('error', 'Print failed: ' . $e->getMessage());
            throw $e;
        }

        $this->log('info', '=== Receipt Print Complete ===');
        return $this;
    }

    /**
     * Print via Ethernet using image rendering (supports Lao text)
     */
    private function printEthernetWithImage($receiptData)
    {
        $fontPath = base_path('resources/fonts/NotoSansLao-Regular.ttf');
        if (!file_exists($fontPath)) {
            $this->log('warning', 'Lao font not found, falling back to raw text');
            $formatted = $this->formatReceipt($receiptData);
            return $this->print($formatted);
        }

        // Render the receipt as an image using GD
        $image = $this->generateReceiptImage($receiptData, $fontPath);
        
        // Convert image to ESC/POS raster data
        $escPos = $this->imageToEscPosRaster($image);
        imagedestroy($image);

        // Send raster data over the socket
        return $this->print($escPos);
    }

    /**
     * Generate receipt as GD image with Lao font support
     */
    private function generateReceiptImage($data, $fontPath)
    {
        $width = 384;  // 48mm @ 203 DPI (standard 80mm thermal printer)
        $height = 700;

        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefilledrectangle($image, 0, 0, $width, $height, $white);

        $y = 25;

        // Header
        $this->imgCenterText($image, "ASHA STABLES", $width, $y, 14, $black, $fontPath);
        $y += 30;
        $this->imgCenterText($image, "Member Activity Receipt", $width, $y, 11, $black, $fontPath);
        $y += 30;
        imageline($image, 10, $y, $width - 10, $y, $black);
        $y += 20;

        // Receipt info
        $this->imgText($image, "Receipt ID: " . ($data['receipt_id'] ?? 'AUTO'), 15, $y, 10, $black, $fontPath);
        $y += 22;
        $this->imgText($image, "Date: " . (isset($data['timestamp']) ? $data['timestamp'] : date('d-m-Y H:i')), 15, $y, 10, $black, $fontPath);
        $y += 30;
        imageline($image, 10, $y, $width - 10, $y, $black);
        $y += 20;

        // Member info
        $this->imgText($image, "Member Information", 15, $y, 11, $black, $fontPath);
        $y += 25;
        $this->imgText($image, "Name: " . ($data['member_name'] ?? '-'), 15, $y, 10, $black, $fontPath);
        $y += 22;
        $this->imgText($image, "Card ID: " . ($data['card_uid'] ?? '-'), 15, $y, 10, $black, $fontPath);
        $y += 30;
        imageline($image, 10, $y, $width - 10, $y, $black);
        $y += 20;

        // Activity details
        $this->imgText($image, "Activity Details", 15, $y, 11, $black, $fontPath);
        $y += 25;
        $this->imgText($image, "Activity: " . ($data['activity_name'] ?? '-'), 15, $y, 10, $black, $fontPath);
        $y += 22;
        $this->imgText($image, "Sessions Used: 1", 15, $y, 10, $black, $fontPath);
        $y += 30;
        imageline($image, 10, $y, $width - 10, $y, $black);
        $y += 20;

        // Session balance
        $used = $data['used_count'] ?? $data['used_sessions'] ?? 0;
        $remaining = $data['remaining_count'] ?? $data['remaining_sessions'] ?? 0;
        $total = $used + $remaining;

        $this->imgText($image, "Session Balance", 15, $y, 11, $black, $fontPath);
        $y += 25;
        $this->imgText($image, "Sessions Used:  $used", 15, $y, 10, $black, $fontPath);
        $y += 22;
        $this->imgText($image, "Sessions Left:  $remaining", 15, $y, 10, $black, $fontPath);
        $y += 22;
        $this->imgText($image, "Total Sessions: $total", 15, $y, 10, $black, $fontPath);
        $y += 30;
        imageline($image, 10, $y, $width - 10, $y, $black);
        $y += 25;

        // Status
        $this->imgCenterText($image, "COMPLETED + APPROVED", $width, $y, 12, $black, $fontPath);
        $y += 35;

        // Footer
        $this->imgCenterText($image, "Thank you for using", $width, $y, 10, $black, $fontPath);
        $y += 22;
        $this->imgCenterText($image, "ASHA STABLES", $width, $y, 11, $black, $fontPath);
        $y += 30;

        // Crop to actual content height
        $cropped = imagecreatetruecolor($width, $y);
        $cropWhite = imagecolorallocate($cropped, 255, 255, 255);
        imagefilledrectangle($cropped, 0, 0, $width, $y, $cropWhite);
        imagecopy($cropped, $image, 0, 0, 0, 0, $width, $y);
        imagedestroy($image);

        return $cropped;
    }

    private function imgText(&$image, $text, $x, $y, $size, $color, $font)
    {
        imagettftext($image, $size, 0, $x, $y, $color, $font, $text);
    }

    private function imgCenterText(&$image, $text, $imgWidth, $y, $size, $color, $font)
    {
        $bbox = imagettfbbox($size, 0, $font, $text);
        $textWidth = abs($bbox[4] - $bbox[0]);
        $x = ($imgWidth - $textWidth) / 2;
        imagettftext($image, $size, 0, (int)$x, $y, $color, $font, $text);
    }

    /**
     * Convert GD image to ESC/POS raster format (GS v 0)
     */
    private function imageToEscPosRaster($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $widthBytes = (int)ceil($width / 8);

        $output = "\x1B\x40"; // Reset printer

        // GS v 0 - Print raster bit image
        $output .= "\x1D\x76\x30\x00"; // GS v 0, mode 0 (normal)
        $output .= chr($widthBytes & 0xFF) . chr(($widthBytes >> 8) & 0xFF); // xL xH
        $output .= chr($height & 0xFF) . chr(($height >> 8) & 0xFF);         // yL yH

        for ($y = 0; $y < $height; $y++) {
            for ($bx = 0; $bx < $widthBytes; $bx++) {
                $byte = 0;
                for ($bit = 0; $bit < 8; $bit++) {
                    $px = $bx * 8 + $bit;
                    if ($px < $width) {
                        $rgb = imagecolorat($image, $px, $y);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;
                        $lum = (int)(0.299 * $r + 0.587 * $g + 0.114 * $b);
                        if ($lum < 128) {
                            $byte |= (0x80 >> $bit);
                        }
                    }
                }
                $output .= chr($byte);
            }
        }

        // Feed and cut
        $output .= "\n\n\n\n\n";
        $output .= "\x1D\x56\x01"; // Partial cut
        $output .= "\x1B\x40";     // Reset

        return $output;
    }

    private function formatReceipt($data)
    {
        $receipt = "";
        $divider = str_repeat("=", 42);

        // Initialize Printer for Xprinter XP-T80 (TEXT ONLY MODE)
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

        // Cut paper (with delay)
        $receipt .= "\x1B\x69"; // ESC i - Cut
        $receipt .= "\x1B\x40"; // ESC @ Reset
        return $receipt;
    }


    private function textToEscPos($text)
    {
        if (!function_exists('imagecreatetruecolor')) {
            $msg = 'GD extension not available - Lao text rendering disabled';
            if ($this->canUseFacadeLog()) { Log::warning($msg); } else { echo "[WARN] $msg\n"; }
            return ''; // GD not available
        }

        try {
            // Create minimal Lao text image for Xprinter compatibility
            $tempPath = sys_get_temp_dir() . '/lao_' . uniqid() . '.png';
            
            // Very narrow image (180px) - minimal but readable
            $img = imagecreatetruecolor(180, 35);
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 180, 35, $white);

            // Use Noto Sans Lao font
            $fontPath = __DIR__ . '/../../resources/fonts/NotoSansLao-Regular.ttf';
            if (!file_exists($fontPath)) {
                $fontPath = 'C:\\Windows\\Fonts\\arial.ttf';
            }

            if ($this->canUseFacadeLog()) { Log::info("Using font: $fontPath for Lao text"); } else { echo "[LOG] Using font: $fontPath for Lao text\n"; }

            if (file_exists($fontPath)) {
                // Very small font (10pt) for narrow image
                $result = imagettftext($img, 10, 0, 3, 22, $black, $fontPath, $text);
                if (!$result) {
                    if ($this->canUseFacadeLog()) { Log::warning("imagettftext failed for text: $text"); } else { echo "[WARN] imagettftext failed for text: $text\n"; }
                }
            } else {
                if ($this->canUseFacadeLog()) { Log::warning("Font not found at: $fontPath"); } else { echo "[WARN] Font not found at: $fontPath\n"; }
                imagestring($img, 5, 10, 15, $text, $black);
            }

            // Save to temp file
            if (!imagepng($img, $tempPath)) {
                if ($this->canUseFacadeLog()) { Log::warning("Failed to save image to: $tempPath"); } else { echo "[WARN] Failed to save image to: $tempPath\n"; }
                imagedestroy($img);
                return '';
            }
            
            imagedestroy($img);

            // Convert to ESC/POS raster with optimized width
            if (file_exists($tempPath)) {
                $escpos = $this->imageToEscPos($tempPath, 200);  // Max width 200px
                @unlink($tempPath);
                if ($this->canUseFacadeLog()) { Log::info("Lao text rendered successfully, ESC/POS size: " . strlen($escpos)); } else { echo "[LOG] Lao text rendered successfully, ESC/POS size: " . strlen($escpos) . "\n"; }
                return $escpos . "\n";
            }
        } catch (Exception $e) {
            $msg = "textToEscPos error: " . $e->getMessage();
            if ($this->canUseFacadeLog()) { Log::error($msg); } else { echo "[ERR] $msg\n"; }
        }
        
        return '';
    }

    /**
     * Multi-byte aware string padding (public for potential reuse)
     */
    public function mbPad($str, $length, $pad = ' ', $type = STR_PAD_RIGHT)
    {
        $str = (string)$str;
        $current = mb_strlen($str, 'UTF-8');
        if ($current >= $length) {
            return $str;
        }
        $padLen = $length - $current;
        switch ($type) {
            case STR_PAD_LEFT:
                return str_repeat($pad, $padLen) . $str;
            case STR_PAD_BOTH:
                $left = floor($padLen / 2);
                $right = $padLen - $left;
                return str_repeat($pad, $left) . $str . str_repeat($pad, $right);
            case STR_PAD_RIGHT:
            default:
                return $str . str_repeat($pad, $padLen);
        }
    }

    /**
     * Send content to printer
     */
    private function print($content)
    {
        // Ensure content is UTF-8 encoded
        if (!mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8');
        }
        
        if ($this->isEthernet) {
            $result = $this->printEthernet($content);
        } else {
            $result = $this->printUSB($content);
        }
        
        // Small delay after printing to let printer finish before cutting
        sleep(0.6);
        
        return $result;
    }

    /**
     * Convert image to ESC/POS raster bit image (GS v 0) and return bytes
     * Requires GD extension. Resizes image to max width (pixels) if larger.
     * @param string $imagePath
     * @param int $maxWidth
     * @return string
     */
    private function canUseFacadeLog()
    {
        // ensure the Log facade is bound to an application instance
        return class_exists('Illuminate\\Support\\Facades\\Log') && class_exists('Illuminate\\Support\\Facades\\Facade')
            && \Illuminate\Support\Facades\Facade::getFacadeApplication() !== null;
    }

    private function imageToEscPos($imagePath, $maxWidth = 180)
    {
        // Try GD first with narrower width for better printer compatibility
        if (function_exists('imagecreatefromstring')) {
            if ($this->canUseFacadeLog()) { Log::info('Using GD for logo conversion'); } else { echo "[LOG] Using GD for logo conversion\n"; }
            $contents = @file_get_contents($imagePath);
            if ($contents === false) {
                if ($this->canUseFacadeLog()) { Log::warning('Failed to read logo file: ' . $imagePath); } else { echo "[WARN] Failed to read logo file: $imagePath\n"; }
                return '';
            }

            $img = @imagecreatefromstring($contents);
            if (!$img) {
                if ($this->canUseFacadeLog()) { Log::warning('imagecreatefromstring failed for: ' . $imagePath); } else { echo "[WARN] imagecreatefromstring failed for: $imagePath\n"; }
                return '';
            }

            $width = imagesx($img);
            $height = imagesy($img);

            // resize if wider than maxWidth (narrower width = better compatibility)
            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) round($height * ($newWidth / $width));
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                $white = imagecolorallocate($resized, 255, 255, 255);
                imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $white);
                imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($img);
                $img = $resized;
                $width = $newWidth;
                $height = $newHeight;
            }

            // convert to 1-bit bitmap (threshold) - higher threshold for better contrast
            $widthBytes = (int) ceil($width / 8);
            $data = "";

            for ($y = 0; $y < $height; $y++) {
                $row = "";
                for ($bx = 0; $bx < $widthBytes; $bx++) {
                    $byte = 0;
                    for ($bit = 0; $bit < 8; $bit++) {
                        $x = $bx * 8 + $bit;
                        if ($x < $width) {
                            $rgb = imagecolorat($img, $x, $y);
                            $r = ($rgb >> 16) & 0xFF;
                            $g = ($rgb >> 8) & 0xFF;
                            $b = $rgb & 0xFF;
                            // Luminance calculation with higher threshold for better print quality
                            $lum = (int) (0.2126 * $r + 0.7152 * $g + 0.0722 * $b);
                            if ($lum < 100) {  // Changed from 127 to 100 for darker threshold
                                $byte |= (0x80 >> $bit);
                            }
                        }
                    }
                    $row .= chr($byte);
                }
                $data .= $row;
            }

            // Use ESC * format - older raster command, more compatible with Xprinter
            // ESC * m nL nH [data]
            // m=0: 8-dot single-density (most compatible)
            $nL = $widthBytes & 0xFF;
            $nH = ($widthBytes >> 8) & 0xFF;
            
            // Build command: each scan line needs ESC * 0 nL nH + line data
            $cmd = "";
            for ($line = 0; $line < $height; $line++) {
                $cmd .= "\x1B\x2A\x00" . chr($nL) . chr($nH);
                $startByte = $line * $widthBytes;
                $lineData = substr($data, $startByte, $widthBytes);
                $cmd .= $lineData;
            }

            imagedestroy($img);

            if ($this->canUseFacadeLog()) { Log::info('ESC* conversion resulted in ' . strlen($cmd) . ' bytes'); } else { echo "[LOG] ESC* conversion resulted in " . strlen($cmd) . " bytes\n"; }
            return $cmd;
        }

        // Fallback to Imagick if available
        if (class_exists('Imagick')) {
            if ($this->canUseFacadeLog()) { Log::info('GD missing, using Imagick for logo conversion'); } else { echo "[LOG] GD missing, using Imagick for logo conversion\n"; }
            try {
                $im = new \Imagick();
                $im->readImage($imagePath);
                $width = $im->getImageWidth();
                $height = $im->getImageHeight();

                if ($width > $maxWidth) {
                    $im->resizeImage($maxWidth, 0, \Imagick::FILTER_LANCZOS, 1);
                    $width = $im->getImageWidth();
                    $height = $im->getImageHeight();
                }

                // Ensure RGB pixels
                $im->setImageColorspace(\Imagick::COLORSPACE_RGB);

                $pixels = $im->exportImagePixels(0, 0, $width, $height, "RGB", \Imagick::PIXEL_CHAR);

                $widthBytes = (int) ceil($width / 8);
                $data = "";

                for ($y = 0; $y < $height; $y++) {
                    $row = "";
                    for ($bx = 0; $bx < $widthBytes; $bx++) {
                        $byte = 0;
                        for ($bit = 0; $bit < 8; $bit++) {
                            $x = $bx * 8 + $bit;
                            if ($x < $width) {
                                $idx = ($y * $width + $x) * 3;
                                $r = $pixels[$idx];
                                $g = $pixels[$idx + 1];
                                $b = $pixels[$idx + 2];
                                $lum = (int) (0.2126 * $r + 0.7152 * $g + 0.0722 * $b);
                                if ($lum < 127) {
                                    $byte |= (0x80 >> $bit);
                                }
                            }
                        }
                        $row .= chr($byte);
                    }
                    $data .= $row;
                }

                $xLxH = pack('v', $widthBytes);
                $yLyH = pack('v', $height);
                $cmd = "\x1D\x76\x30\x00" . $xLxH . $yLyH . $data;

                $im->clear();
                $im->destroy();

                if ($this->canUseFacadeLog()) { Log::info('Imagick conversion resulted in ' . strlen($cmd) . ' bytes'); } else { echo "[LOG] Imagick conversion resulted in " . strlen($cmd) . " bytes\n"; }
                return $cmd;
            } catch (Exception $e) {
                if ($this->canUseFacadeLog()) { Log::warning('Imagick conversion failed: ' . $e->getMessage()); } else { echo "[WARN] Imagick conversion failed: " . $e->getMessage() . "\n"; }
                return '';
            }
        }

        if ($this->canUseFacadeLog()) { Log::warning('GD and Imagick unavailable: cannot convert logo to raster.'); } else { echo "[WARN] GD and Imagick unavailable: cannot convert logo to raster.\n"; }
        return '';
    }

    /**
     * Print via Ethernet (Windows - tries multiple methods)
     */
    private function printEthernet($content)
    {
        if (!$this->ipAddress) {
            throw new Exception("Printer IP address not set");
        }

        // Ensure UTF-8 encoding before sending
        $content = $this->ensureUTF8($content);
        
        $errors = [];

        // Try custom port first if provided
        $ports = [];
        if ($this->port) {
            $ports[] = $this->port;
        }
        // then fall back to common thermal printer ports
        $ports = array_merge($ports, [9100, 9101, 9102, 9103, 515, 631, 6101, 4000, 8000]);
        
        foreach ($ports as $port) {
            $socket = @fsockopen($this->ipAddress, $port, $errno, $errstr, 2);
            if ($socket) {
                fwrite($socket, $content);
                fclose($socket);
                return true;
            }
        }
        $errors[] = "No open printer port found";

        // Method 2: Try Windows network share
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $printerPath = "\\\\" . $this->ipAddress . "\\Receipt";
            $handle = @fopen($printerPath, "w");
            if ($handle) {
                fwrite($handle, $content);
                fclose($handle);
                return true;
            }
        }

        throw new Exception("Failed to print. Could not connect to " . $this->ipAddress . " on any printer port");
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
                // Scan custom port first
                $ports = [];
                if ($this->port) {
                    $ports[] = $this->port;
                }

                // Scan common printer ports
                $ports = array_merge($ports, [9100, 9101, 9102, 9103, 515, 631, 6101, 4000, 8000]);
                
                $foundPort = false;
                foreach ($ports as $port) {
                    $socket = @fsockopen($this->ipAddress, $port, $errno, $errstr, 1);
                    if ($socket) {
                        fclose($socket);
                        $foundPort = true;
                        break;
                    }
                }

                if ($foundPort) {
                    return true; // Found usable printer port
                }

                // Fallback to ping only
                exec("ping -n 1 -w 1000 " . escapeshellarg($this->ipAddress), $output, $returnVar);
                if ($returnVar === 0) {
                    // reachable but nothing listening on expected ports
                    throw new Exception("Printer reachable via ping but no open printer port found");
                }

                throw new Exception("Cannot connect to printer at " . $this->ipAddress);
            } else {
                return true; // USB will error if unavailable
            }
        } catch (Exception $e) {
            throw new Exception("Printer test failed: " . $e->getMessage());
        }
    }

    /**
     * Ensure string is properly UTF-8 encoded
     */
    private function ensureUTF8($str)
    {
        if (!mb_check_encoding($str, 'UTF-8')) {
            $str = mb_convert_encoding($str, 'UTF-8');
        }
        return $str;
    }

    /**
     * Close connection
     */
    public function disconnect()
    {
        // No persistent connection to close
    }

    /**
     * Logging helper
     */
    private function log($level, $message)
    {
        if (class_exists('Illuminate\\Support\\Facades\\Log')) {
            Log::$level($message);
        } else {
            echo "[" . strtoupper($level) . "] $message\n";
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->windowsService) {
            $this->windowsService->disconnect();
        }
        if ($this->socket) {
            @fclose($this->socket);
        }
    }
}
