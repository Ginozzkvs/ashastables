<?php

namespace App\Http\Controllers;

use App\Models\Membership;
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
        return view('memberships.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        Membership::create($request->only('name','price','duration_days'));

        return redirect()
            ->route('memberships.index')
            ->with('success', 'Membership created successfully');
    }

    public function edit(Membership $membership)
    {
        return view('memberships.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        $membership->update($request->only('name','price','duration_days'));

        return redirect()
            ->route('memberships.index')
            ->with('success', 'Membership updated successfully');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();

        return redirect()
            ->route('memberships.index')
            ->with('success', 'Membership deleted successfully');
    }
}
