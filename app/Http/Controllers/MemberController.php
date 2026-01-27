<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Membership;
use Illuminate\Support\Facades\DB;

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
    $expiryDate = now()->addDays($membership->duration_days)->toDateString();

    Member::create([
        'card_id' => Member::generateCardId(),
        'name' => $request->name,
        'phone' => $request->phone,
        'membership_id' => $membership->id,
        'start_date' => now(),
        'expiry_date' => now()->addDays($membership->duration_days),
        'card_uid' => $request->card_uid,
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
    $oldCardId = $member->card_id;
    $newCardId = $request->input('card_id');
    
    // Validate the new card_id is unique
    if ($newCardId !== $oldCardId) {
        $request->validate([
            'card_id' => 'required|string|max:255|unique:members,card_id',
        ]);
    }

    // Update all fields using raw query to handle primary key change
    DB::table('members')
        ->where('card_id', $oldCardId)
        ->update([
            'card_id' => $newCardId,
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'card_uid' => $request->input('card_uid'),
            'membership_id' => $request->input('membership_id'),
            'start_date' => $request->input('start_date'),
            'expiry_date' => $request->input('expiry_date'),
            'active' => $request->has('active') ? 1 : 0,
            'updated_at' => now(),
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
