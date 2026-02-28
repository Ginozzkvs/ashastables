<?php

namespace App\Http\Controllers;

use App\Services\PrinterService;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    /**
     * Get available USB printers
     */
    public function getUSBPrinters()
    {
        try {
            $printers = PrinterService::getUSBPrinters();
            return response()->json([
                'success' => true,
                'printers' => $printers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test printer connection
     */
    public function testPrinter(Request $request)
    {
        try {
            $type = $request->input('type'); // 'usb' or 'ethernet'

            if ($type === 'usb') {
                $printerName = $request->input('printer_name');
                $printer = new PrinterService();
                $printer->connectUSB($printerName);
                \Log::info('Testing USB printer', ['name' => $printerName]);
            } else {
                $ipAddress = $request->input('ip_address');
                $port = $request->input('port');
                $printer = new PrinterService();
                $printer->connectEthernet($ipAddress, $port);
                \Log::info('Testing Ethernet printer', ['ip' => $ipAddress, 'port' => $port]);
            }

            $printer->testConnection();

            return response()->json([
                'success' => true,
                'message' => 'Printer connected successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Printer test failed: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print test receipt
     */
    public function printTestReceipt(Request $request)
    {
        try {
            $type = $request->input('type');
            $printer = new PrinterService();

            if ($type === 'usb') {
                $printer->connectUSB($request->input('printer_name'));
            } else {
                $ip = $request->input('ip_address');
                $port = $request->input('port');
                $printer->connectEthernet($ip, $port);
            }

            // Apply optional logo settings passed from client
            $logoPath = $request->input('logo_path');
            $logoWidth = $request->input('logo_width');
            if ($logoPath) {
                $printer->setLogoPath($logoPath);
            }
            if ($logoWidth) {
                $printer->setLogoMaxWidth($logoWidth);
            }

            $testData = [
                'member_name' => 'Test Member',
                'card_uid' => 'ABC123456',
                'activity_name' => 'Horse Riding',
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'remaining_count' => 5,
                'used_count' => 19
            ];

            \Log::info('Printing test receipt', $testData);
            $printer->printReceipt($testData);

            return response()->json([
                'success' => true,
                'message' => 'Test receipt printed successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Test print failed: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print actual receipt from activity
     */
    public function printReceipt(Request $request)
    {
        try {
            $type = $request->input('type');
            $receipt = $request->input('receipt');
            $port = $request->input('port');
            
            if (!$receipt) {
                return response()->json([
                    'success' => false,
                    'message' => 'No receipt data provided'
                ], 400);
            }
            
            $printer = new PrinterService();

            if ($type === 'usb') {
                    $printerName = $request->input('printer_name');
                    if (!$printerName) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No USB printer selected'
                        ], 400);
                    }
                    $printer->connectUSB($printerName);
                } else {
                    $ipAddress = $request->input('ip_address');
                    if (!$ipAddress) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Ethernet printer IP provided'
                        ], 400);
                    }
                    $port = $request->input('port');
                    $printer->connectEthernet($ipAddress, $port);
                }
            // Apply optional logo settings for actual receipts
            $logoPath = $request->input('logo_path');
            $logoWidth = $request->input('logo_width');
            if ($logoPath) {
                $printer->setLogoPath($logoPath);
            }
            if ($logoWidth) {
                $printer->setLogoMaxWidth($logoWidth);
            }
            $receiptData = [
                'member_name' => $receipt['member_name'] ?? 'Member',
                'card_uid' => $receipt['member_id'] ?? '',
                'activity_name' => $receipt['activity_name'] ?? 'Activity',
                'timestamp' => $receipt['timestamp'] ?? now()->format('Y-m-d H:i:s'),
                'remaining_count' => $receipt['remaining_sessions'] ?? 0,
                'used_count' => $receipt['used_sessions'] ?? 0
            ];

            // log the data we send to the printer for debugging
            \Log::info('Printing receipt', $receiptData);

            $success = $printer->printReceipt($receiptData);

            return response()->json([
                'success' => $success,
                'message' => $success ? 'Receipt printed successfully' : 'Printer returned false'
            ]);
        } catch (\Exception $e) {
            \Log::error('Print Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
