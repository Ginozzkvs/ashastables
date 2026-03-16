<?php

namespace App\Http\Controllers;

use App\Services\PrinterService;
use App\Models\PrintJob;
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

            $testData = [
                'member_name' => 'Test Member',
                'card_uid' => 'ABC123456',
                'activity_name' => 'Horse Riding',
                'membership_name' => 'Gold Membership',
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
     * Save print job to database (polled by local print agent)
     */
    public function printReceipt(Request $request)
    {
        try {
            $receipt = $request->input('receipt');

            if (!$receipt) {
                return response()->json([
                    'success' => false,
                    'message' => 'No receipt data provided'
                ], 400);
            }

            $receiptData = [
                'member_name' => $receipt['member_name'] ?? 'Member',
                'card_uid' => $receipt['member_id'] ?? '',
                'activity_name' => $receipt['activity_name'] ?? 'Activity',
                'timestamp' => $receipt['timestamp'] ?? now()->format('Y-m-d H:i:s'),
                'remaining_count' => $receipt['remaining_sessions'] ?? 0,
                'used_count' => $receipt['used_sessions'] ?? 0,
                'membership_name' => $receipt['membership_name'] ?? 'Standard Membership'
            ];

            PrintJob::create([
                'receipt_data' => $receiptData,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Print job queued'
            ]);
        } catch (\Exception $e) {
            \Log::error('Print Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending print jobs (called by local print agent)
     */
    public function getPendingJobs(Request $request)
    {
        $token = $request->query('token');
        if ($token !== config('app.print_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $jobs = PrintJob::where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        return response()->json($jobs);
    }

    /**
     * Mark print job as done (called by local print agent)
     */
    public function markJobDone(Request $request, $id)
    {
        $token = $request->query('token');
        if ($token !== config('app.print_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $job = PrintJob::findOrFail($id);
        $job->status = $request->input('status', 'printed');
        $job->save();

        return response()->json(['success' => true]);
    }
}
