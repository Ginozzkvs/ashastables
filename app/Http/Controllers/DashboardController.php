<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\Membership;
use App\Models\MemberActivityBalance;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Statistics
        $totalMembers = Member::count();
        $activeMemberships = Membership::count();
        $totalRevenue = Membership::sum('price');
        
        // Members joined this month
        $membersThisMonth = Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Activities used this month
        $activitiesThisMonth = ActivityLog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Most popular activities (top 5)
        $popularActivities = ActivityLog::select('activity_id', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('activity_id')
            ->orderByDesc('usage_count')
            ->limit(5)
            ->with('activity')
            ->get();
        
        // Recent activity logs (last 10)
        $recentActivities = ActivityLog::latest('created_at')
            ->limit(10)
            ->with(['member', 'activity', 'staff'])
            ->get();
        
        // Members by membership type
        $membersByMembership = Member::select('membership_id', DB::raw('COUNT(*) as count'))
            ->groupBy('membership_id')
            ->with('membership')
            ->get();
        
        // Activity usage by day this month (last 7 days)
        $activityTrend = ActivityLog::whereDate('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Calculate total activities for percentage calculation
        $totalActivitiesTrend = $activityTrend->sum('count');
        $activityTrend = $activityTrend->map(function($item) use ($totalActivitiesTrend) {
            $item->percentage = $totalActivitiesTrend > 0 ? round(($item->count / $totalActivitiesTrend) * 100) : 0;
            return $item;
        });
        
        // Staff count
        $staffCount = User::where('role', 'staff')->count();

        // Expiring memberships (within 30 days)
        $expiringMemberships = Member::where('active', true)
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date', 'asc')
            ->limit(5)
            ->with('membership')
            ->get();

        // Expired memberships
        $expiredCount = Member::where('active', true)
            ->where('expiry_date', '<=', now())
            ->count();

        return view('dashboard.index', compact(
            'totalMembers',
            'activeMemberships',
            'totalRevenue',
            'membersThisMonth',
            'activitiesThisMonth',
            'popularActivities',
            'recentActivities',
            'membersByMembership',
            'activityTrend',
            'staffCount',
            'expiringMemberships',
            'expiredCount'
        ));
    }
}