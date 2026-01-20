<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\ActivityLog;
use App\Models\MemberActivityBalance;
use App\Services\ActivityService;

class StaffActivityController extends Controller
{
    // Show NFC scan page
    public function scanPage()
    {
        return view('staff.scan');
    }

    // AJAX: Find member by card_uid
    public function findMember(Request $request)
    {
        try {
            $request->validate(['card_uid' => 'required|string']);

            $member = Member::where('card_uid', $request->card_uid)->first();

            if (!$member) {
                return response()->json(['error' => 'Member not found'], 404);
            }

            // Get member's membership to fetch activity limits
            $member->load('membership.activityLimits');
            $activityLimits = $member->membership->activityLimits->keyBy('activity_id');

            $activities = $member->activityBalances()
                ->with('activity')
                ->get()
                ->map(function ($balance) use ($activityLimits) {
                    // Calculate used_count as max_per_year - remaining_count
                    $limit = $activityLimits->get($balance->activity_id);
                    $maxPerYear = $limit?->max_per_year ?? 0;
                    $balance->used_count = $maxPerYear - $balance->remaining_count;
                    return $balance;
                });

            return response()->json([
                'member' => $member,
                'activities' => $activities
            ]);
        } catch (\Exception $e) {
            \Log::error('findMember error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    // AJAX: Use an activity
    public function useActivity(Request $request)
    {
        $request->validate([
            'card_uid' => 'required|string',
            'activity_id' => 'required|integer'
        ]);

        try {
            $activityLog = ActivityService::useActivity($request->card_uid, $request->activity_id);

            return response()->json([
                'success' => true,
                'message' => 'Activity reserved successfully',
                'receipt_url' => route('staff.receipt', ['log_id' => $activityLog->id])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Show receipt for activity
    public function receipt($log_id)
    {
        $activity_log = ActivityLog::with(['member', 'activity', 'staff'])->findOrFail($log_id);
        
        $balance = MemberActivityBalance::where('member_id', $activity_log->member->card_id)
            ->where('activity_id', $activity_log->activity_id)
            ->first();

        return view('receipts.activity', compact('activity_log', 'balance'));
    }
}
