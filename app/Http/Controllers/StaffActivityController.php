<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Services\ActivityService;

class StaffActivityController extends Controller
{
    public function index()
    {
        return view('staff.activity');
    }

    public function findMember(Request $request)
    {
        $member = Member::where('qr_code', $request->qr)->first();

        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        if (!$member->active || now()->gt($member->end_date)) {
            return response()->json(['error' => 'Membership expired'], 403);
        }

        return response()->json([
            'member' => $member,
            'activities' => $member->activityBalances()->with('activity')->get()
        ]);
    }

    public function useActivity(Request $request)
    {
        try {
            ActivityService::useActivity(
                $request->qr,
                $request->activity_id,
                1 // 1 time per click
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
