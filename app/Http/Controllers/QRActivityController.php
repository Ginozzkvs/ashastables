<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Services\ActivityService;
use Exception;

class QRActivityController extends Controller
{
    public function showForm()
    {
        return view('scan-activity');
    }

    public function scanActivity(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
            'activity_id' => 'required|integer',
            'minutes' => 'required|integer|min:1'
        ]);

        try {
            $result = ActivityService::useActivity(
                $request->qr_code,
                $request->activity_id,
                $request->minutes
            );

            return back()->with('success', $result);

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
