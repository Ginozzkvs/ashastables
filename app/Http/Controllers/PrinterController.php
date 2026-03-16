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
        return response()->json([
            'success' => true,
            'message' => 'Connection testing is now handled by the local print agent.'
        ]);
    }

    /**
     * Queue test receipt for local agent
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

            \Log::info('Queuing test receipt');
            
            PrintJob::create([
                'receipt_data' => $testData,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test receipt added to queue successfully. The local agent will print it shortly.'
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
     * Queue receipt for local agent
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
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Receipt added to queue. The local agent will print it shortly.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Print Queue Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to queue print job. Error: ' . $e->getMessage()
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
