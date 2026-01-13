<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
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
        $request->validate(['card_uid' => 'required|string']);

        $member = Member::where('card_uid', $request->card_uid)->first();

        if (!$member) {
            return response()->json(['error' => 'Member not found']);
        }

        $activities = $member->activityBalances()->with('activity')->get();

        return response()->json([
            'member' => $member,
            'activities' => $activities
        ]);
    }

    // AJAX: Use an activity
    public function useActivity(Request $request)
    {
        $request->validate([
            'card_uid' => 'required|string',
            'activity_id' => 'required|integer'
        ]);

        try {
            ActivityService::useActivity($request->card_uid, $request->activity_id);

            return response()->json([
                'success' => true,
                'message' => 'Activity used successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
