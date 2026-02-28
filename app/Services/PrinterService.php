<?php

namespace App\Services;

use Exception;

class PrinterService
{
    private $printerName;
    private $isEthernet = false;
    private $ipAddress;
    private $port = null; // optional custom port for ethernet
    private $logoPath = null;
    private $logoMaxWidth = 384;

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
     * @param string $ipAddress - Printer IP address or network share path
     */
    /**
     * Initialize Ethernet Printer
     * @param string $ipAddress - Printer IP address or network share path
     * @param int|null $port - optional custom port to connect to (raw/9100 etc)
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
     * Optional: set a custom logo path (absolute or relative to public/)
     */
    public function setLogoPath($path)
    {
        $this->logoPath = $path;
        return $this;
    }

    /**
     * Optional: set the maximum logo width in pixels for raster conversion
     */
    public function setLogoMaxWidth($width)
    {
        $this->logoMaxWidth = (int) $width;
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

        // Attempt to print logo if provided or default exists
        try {
            // prefer explicitly set logo path, otherwise look for public/images/logo.png
            $logoPath = $this->logoPath ?: (function_exists('public_path') ? public_path('images/logo.png') : __DIR__ . '/../../public/images/logo.png');
            if ($logoPath && file_exists($logoPath)) {
                // center alignment for logo
                $receipt .= "\x1B\x61\x01"; // Center
                $receipt .= $this->imageToEscPos($logoPath, $this->logoMaxWidth);
                $receipt .= "\n"; // small gap after logo
                $receipt .= "\x1B\x61\x00"; // Left align back
            }
        } catch (Exception $e) {
            // continue without logo if conversion fails
        }

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
     * Convert image to ESC/POS raster bit image (GS v 0) and return bytes
     * Requires GD extension. Resizes image to max width (pixels) if larger.
     * @param string $imagePath
     * @param int $maxWidth
     * @return string
     */
    private function imageToEscPos($imagePath, $maxWidth = 384)
    {
        if (!function_exists('imagecreatefromstring')) {
            return '';
        }

        $contents = @file_get_contents($imagePath);
        if ($contents === false) return '';

        $img = @imagecreatefromstring($contents);
        if (!$img) return '';

        $width = imagesx($img);
        $height = imagesy($img);

        // resize if wider than maxWidth
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($height * ($newWidth / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            // fill white
            $white = imagecolorallocate($resized, 255, 255, 255);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $white);
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($img);
            $img = $resized;
            $width = $newWidth;
            $height = $newHeight;
        }

        // convert to 1-bit bitmap (threshold)
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
                        // luminance
                        $lum = (int) (0.2126 * $r + 0.7152 * $g + 0.0722 * $b);
                        if ($lum < 127) { // dark pixel -> black
                            $byte |= (0x80 >> $bit);
                        }
                    }
                }
                $row .= chr($byte);
            }
            $data .= $row;
        }

        // GS v 0 raster bitmap: 1D 76 30 00 [xL xH yL yH] + data
        $xLxH = pack('v', $widthBytes);
        $yLyH = pack('v', $height);
        $cmd = "\x1D\x76\x30\x00" . $xLxH . $yLyH . $data;

        imagedestroy($img);

        return $cmd;
    }

    /**
     * Print via Ethernet (Windows - tries multiple methods)
     */
    private function printEthernet($content)
    {
        if (!$this->ipAddress) {
            throw new Exception("Printer IP address not set");
        }

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
     * Close connection
     */
    public function disconnect()
    {
        // No persistent connection to close
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}
