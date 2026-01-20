<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Activity;
use App\Models\MembershipActivityLimit;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::latest()->get();
        return view('memberships.index', compact('memberships'));
    }

    public function create()
    {
        $allActivities = Activity::all();
        return view('memberships.create', compact('allActivities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'activity_limits' => 'required|json',
        ]);

        $membership = Membership::create($request->only('name','price','duration_days'));
        
        // Handle activity limits
        $limits = json_decode($request->activity_limits, true);
        foreach ($limits as $limit) {
            MembershipActivityLimit::create([
                'membership_id' => $membership->id,
                'activity_id' => $limit['activity_id'],
                'max_per_year' => $limit['max_per_year'],
                'max_per_day' => $limit['max_per_day'],
            ]);
        }

        return redirect()
            ->route('memberships.index')
            ->with('success', 'Membership created successfully with activities configured');
    }

    public function edit(Membership $membership)
    {
        $allActivities = Activity::all();
        return view('memberships.edit', compact('membership', 'allActivities'));
    }

    public function update(Request $request, Membership $membership)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'activity_limits' => 'required|json',
        ]);

        $membership->update($request->only('name','price','duration_days'));
        
        // Delete existing limits and create new ones
        MembershipActivityLimit::where('membership_id', $membership->id)->delete();
        
        // Handle activity limits
        $limits = json_decode($request->activity_limits, true);
        foreach ($limits as $limit) {
            MembershipActivityLimit::create([
                'membership_id' => $membership->id,
                'activity_id' => $limit['activity_id'],
                'max_per_year' => $limit['max_per_year'],
                'max_per_day' => $limit['max_per_day'],
            ]);
        }

        return redirect()
            ->route('memberships.index')
            ->with('success', 'Membership updated successfully with activities configured');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();

        return redirect()
            ->route('memberships.index')
            ->with('success', 'Membership deleted successfully');
    }

    // Activity Limits Management
    public function activityLimits(Membership $membership)
    {
        $membership->load('activityLimits.activity');
        $allActivities = Activity::all();
        
        return view('memberships.activity-limits', compact('membership', 'allActivities'));
    }

    public function updateActivityLimits(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'limits' => 'required|array',
            'limits.*.activity_id' => 'required|exists:activities,id',
            'limits.*.max_per_year' => 'required|integer|min:1',
            'limits.*.max_per_day' => 'required|integer|min:1',
        ]);

        foreach ($validated['limits'] as $limitData) {
            MembershipActivityLimit::updateOrCreate(
                [
                    'membership_id' => $membership->id,
                    'activity_id' => $limitData['activity_id'],
                ],
                [
                    'max_per_year' => $limitData['max_per_year'],
                    'max_per_day' => $limitData['max_per_day'],
                ]
            );
        }

        return redirect()
            ->route('memberships.activity-limits', $membership)
            ->with('success', 'Activity limits updated successfully!');
    }

    public function removeActivityLimit(Membership $membership, Activity $activity)
    {
        MembershipActivityLimit::where('membership_id', $membership->id)
            ->where('activity_id', $activity->id)
            ->delete();

        return redirect()
            ->route('memberships.activity-limits', $membership)
            ->with('success', 'Activity removed from membership');
    }
}
