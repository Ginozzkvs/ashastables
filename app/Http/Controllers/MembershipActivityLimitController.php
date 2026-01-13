<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipActivityLimit;
use App\Models\Membership;
use App\Models\Activity;

class MembershipActivityLimitController extends Controller
{
    public function index()
    {
        $limits = MembershipActivityLimit::with(['membership','activity'])->get();
        return view('membership_activity_limits.index', compact('limits'));
    }

    public function create()
    {
        $memberships = Membership::all();
        $activities = Activity::all();
        return view('membership_activity_limits.create', compact('memberships','activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'membership_id' => 'required',
            'activity_id' => 'required',
            'max_per_year' => 'required|integer',
            'max_per_day' => 'nullable|integer',
        ]);

        MembershipActivityLimit::create($request->all());

        return redirect()->route('membership-activity-limits.index')
            ->with('success','Limit created');
    }

    public function edit(MembershipActivityLimit $membershipActivityLimit)
    {
        $memberships = Membership::all();
        $activities = Activity::all();
        return view('membership_activity_limits.edit', compact(
            'membershipActivityLimit',
            'memberships',
            'activities'
        ));
    }

    public function update(Request $request, MembershipActivityLimit $membershipActivityLimit)
    {
        $request->validate([
            'max_per_year' => 'required|integer',
            'max_per_day' => 'nullable|integer',
        ]);

        $membershipActivityLimit->update($request->all());

        return redirect()->route('membership-activity-limits.index')
            ->with('success','Updated');
    }

    public function destroy(MembershipActivityLimit $membershipActivityLimit)
    {
        $membershipActivityLimit->delete();
        return back()->with('success','Deleted');
    }
}
