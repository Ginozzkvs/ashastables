<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Activity;
use App\Services\ActivityService;

class StaffController extends Controller
{
    public function scan()
    {
        return view('staff.scan', [
            'activities' => Activity::all()
        ]);
    }

    public function useActivity(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
            'activity_id' => 'required|exists:activities,id',
        ]);

        try {
            ActivityService::useActivity(
                $request->qr_code,
                $request->activity_id,
                1 // 1 time only
            );

            return back()->with('success', 'Activity used successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
