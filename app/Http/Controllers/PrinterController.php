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
     * Test printer connection via public IP
     */
    public function testPrinter(Request $request)
    {
        try {
            $socket = @fsockopen('115.84.114.224', 9100, $errno, $errstr, 5);
            if (!$socket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reach printer at 115.84.114.224:9100. Is port forwarding configured on your router?'
                ], 500);
            }
            fclose($socket);
            return response()->json([
                'success' => true,
                'message' => 'Connected to printer successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print test receipt to public IP
     */
    public function printTestReceipt(Request $request)
    {
        try {
            $testData = [
                'member_name' => 'Test Member',
                'card_uid' => 'ABC123456',
                'activity_name' => 'Horse Riding',
                'membership_name' => 'Gold Membership',
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'remaining_count' => 5,
                'used_count' => 19
            ];

            $printer = new PrinterService();
            $printer->connectEthernet('115.84.114.224', 9100);
            $printer->printReceipt($testData);

            return response()->json([
                'success' => true,
                'message' => 'Test receipt sent to printer!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Test print failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to print. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print receipt directly to office printer via public IP
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

            $printer = new PrinterService();
            $printer->connectEthernet('115.84.114.224', 9100);
            $printer->printReceipt($receiptData);

            return response()->json([
                'success' => true,
                'message' => 'Receipt printed'
            ]);
        } catch (\Exception $e) {
            \Log::error('Print Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to print. Port forwarding not set up? Error: ' . $e->getMessage()
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

        // Return raw ESC/POS bytes encoded as base64 for the local agent.
        $printerService = new PrinterService();
        $formattedJobs = $jobs->map(function ($job) use ($printerService) {
            $reflection = new \ReflectionClass($printerService);
            $method = $reflection->getMethod('formatReceipt');
            $method->setAccessible(true);
            $bytes = $method->invoke($printerService, $job->receipt_data);

            return [
                'id' => $job->id,
                'payload_base64' => base64_encode($bytes),
            ];
        });

        return response()->json($formattedJobs);
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
