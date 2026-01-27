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
            } else {
                $ipAddress = $request->input('ip_address');
                $printer = new PrinterService();
                $printer->connectEthernet($ipAddress);
            }

            $printer->testConnection();

            return response()->json([
                'success' => true,
                'message' => 'Printer connected successfully'
            ]);
        } catch (\Exception $e) {
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
                $printer->connectEthernet($request->input('ip_address'));
            }

            $testData = [
                'member_name' => 'Test Member',
                'card_uid' => 'ABC123456',
                'activity_name' => 'Horse Riding',
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'remaining_count' => 5,
                'used_count' => 19
            ];

            $printer->printReceipt($testData);

            return response()->json([
                'success' => true,
                'message' => 'Test receipt printed successfully'
            ]);
        } catch (\Exception $e) {
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
                $printer->connectEthernet($ipAddress);
            }

            $receiptData = [
                'member_name' => $receipt['member_name'] ?? 'Member',
                'card_uid' => $receipt['member_id'] ?? '',
                'activity_name' => $receipt['activity_name'] ?? 'Activity',
                'timestamp' => $receipt['timestamp'] ?? now()->format('Y-m-d H:i:s'),
                'remaining_count' => $receipt['remaining_sessions'] ?? 0,
                'used_count' => $receipt['used_sessions'] ?? 0
            ];

            $printer->printReceipt($receiptData);

            return response()->json([
                'success' => true,
                'message' => 'Receipt printed successfully'
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
