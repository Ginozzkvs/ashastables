<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Membership;

class MemberController extends Controller
{
    // Show all members
    public function index()
    {
        $members = Member::latest()->get();
        return view('members.index', compact('members'));
    }

    // Show form to add member
    public function create()
{
    $memberships = Membership::all();
    return view('members.create', compact('memberships'));
}


    // Store new member
    public function store(Request $request)
    {
    $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'membership_id' => 'required|exists:memberships,id',
	'card_uid' => 'required|unique:members,card_uid',
    ]);
    $membership = Membership::findOrFail($request->membership_id);

    $startDate = now()->toDateString();
    $endDate = now()->addDays($membership->duration_days)->toDateString();

    Member::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'membership_id' => $membership->id,
        'start_date' => now(),
        'end_date' => now()->addDays($membership->duration_days),
	'card_uid' => $request->card_uid, // RFID / NFC UID
        'active' => 1,
    ]);

    return redirect()
        ->route('members.index')
        ->with('success', 'Member added successfully');
}    // Show form to edit member
   public function edit(Member $member)
{
    $memberships = Membership::all();

    return view('members.edit', compact('member', 'memberships'));
}

    // Update member
   public function update(Request $request, Member $member)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'card_uid' => 'nullable|string|max:255|unique:members,card_uid,' . $member->id,
        'membership_id' => 'required|exists:memberships,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'active' => 'nullable|boolean',
    ]);

    $member->update([
        'name' => $validated['name'],
        'phone' => $validated['phone'] ?? null,
        'email' => $validated['email'] ?? null,
        'card_uid' => $validated['card_uid'] ?? null,
        'membership_id' => $validated['membership_id'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'active' => $request->has('active'), // ✅ correct checkbox handling
    ]);

    return redirect()
        ->route('members.index')
        ->with('success', 'Member updated successfully!');
}


    // Delete member
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully!');
    }
}
