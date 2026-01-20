<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MembershipRenewalController extends Controller
{
    /**
     * Show expiring and expired memberships
     */
    public function index()
    {
        // Get members with expiring memberships (within 30 days)
        $expiringMembers = Member::with('membership')
            ->where('active', true)
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->paginate(20);

        // Get expired members
        $expiredMembers = Member::with('membership')
            ->where('active', true)
            ->where('expiry_date', '<=', now())
            ->orderBy('expiry_date', 'desc')
            ->paginate(20, ['*'], 'expired_page');

        return view('memberships.renewal.index', compact('expiringMembers', 'expiredMembers'));
    }

    /**
     * Show renewal form for a member
     */
    public function renewForm(Member $member)
    {
        // Check if member's membership can be renewed
        if (!$member->isExpired() && !$member->isExpiring()) {
            return redirect()->route('memberships.renewal.index')
                ->with('error', 'This membership is not expiring or expired');
        }

        $memberships = Membership::all();

        return view('memberships.renewal.form', compact('member', 'memberships'));
    }

    /**
     * Process membership renewal
     */
    public function renew(Request $request, Member $member)
    {
        $validated = $request->validate([
            'membership_id' => 'required|exists:memberships,id',
            'expiry_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        try {
            $member->update([
                'membership_id' => $validated['membership_id'],
                'expiry_date' => $validated['expiry_date'],
                'renewed_at' => now(),
                'active' => true,
            ]);

            // Reset activity balances for the new membership period
            if ($member->activityBalances()->exists()) {
                $membership = $member->membership()->with('activityLimits')->first();
                
                foreach ($member->activityBalances as $balance) {
                    $limit = $membership->activityLimits()
                        ->where('activity_id', $balance->activity_id)
                        ->first();

                    if ($limit) {
                        $balance->update([
                            'remaining_count' => $limit->max_per_year,
                            'used_today' => 0,
                        ]);
                    }
                }
            }

            return redirect()->route('memberships.renewal.index')
                ->with('success', "Membership renewed successfully for {$member->name}. New expiry: {$validated['expiry_date']}");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to renew membership: ' . $e->getMessage());
        }
    }

    /**
     * Show renewal statistics
     */
    public function statistics()
    {
        $totalMembers = Member::where('active', true)->count();
        $expiringCount = Member::where('active', true)
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->count();
        $expiredCount = Member::where('active', true)
            ->where('expiry_date', '<=', now())
            ->count();

        // Members expiring in next 7 days
        $expiringNextWeek = Member::where('active', true)
            ->where('expiry_date', '<=', now()->addDays(7))
            ->where('expiry_date', '>', now())
            ->count();

        // Revenue at risk (sum of membership prices for expiring members)
        $revenueAtRisk = Member::where('active', true)
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->with('membership')
            ->get()
            ->sum(fn($m) => $m->membership->price ?? 0);

        return view('memberships.renewal.statistics', compact(
            'totalMembers',
            'expiringCount',
            'expiredCount',
            'expiringNextWeek',
            'revenueAtRisk'
        ));
    }
}
