<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    // Show reports dashboard
    public function index()
    {
        return view('reports.index');
    }
    
    // Revenue Report
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();
        
        // Total revenue
        $totalRevenue = Member::whereBetween('created_at', [$startDate, $endDate])
            ->with('membership')
            ->get()
            ->sum(function($member) {
                return $member->membership->price ?? 0;
            });
        
        // Revenue by membership type
        $revenueByMembership = Member::whereBetween('created_at', [$startDate, $endDate])
            ->select('membership_id', DB::raw('COUNT(*) as member_count'))
            ->groupBy('membership_id')
            ->with('membership')
            ->get()
            ->map(function($item) {
                return [
                    'membership_name' => $item->membership->name ?? 'Unknown',
                    'member_count' => $item->member_count,
                    'price' => $item->membership->price ?? 0,
                    'total' => ($item->membership->price ?? 0) * $item->member_count,
                ];
            });
        
        // Daily revenue trend
        $dailyRevenue = Member::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'))
            ->with('membership')
            ->get()
            ->groupBy('date')
            ->map(function($group) {
                return [
                    'date' => $group->first()->date,
                    'count' => $group->count(),
                    'revenue' => $group->sum(function($m) { return $m->membership->price ?? 0; })
                ];
            })
            ->values();
        
        return view('reports.revenue', compact('totalRevenue', 'revenueByMembership', 'dailyRevenue', 'startDate', 'endDate'));
    }
    
    // Member Analytics Report
    public function members(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subMonths(6);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();
        
        $totalMembers = Member::count();
        $newMembers = Member::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeMembers = Member::whereNotNull('membership_id')->count();
        
        // Members by membership
        $membersByMembership = Member::select('membership_id', DB::raw('COUNT(*) as count'))
            ->groupBy('membership_id')
            ->with('membership')
            ->get();
        
        // Member growth over time
        $memberGrowth = Member::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('reports.members', compact('totalMembers', 'newMembers', 'activeMembers', 'membersByMembership', 'memberGrowth', 'startDate', 'endDate'));
    }
    
    // Activity Usage Report
    public function activities(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();
        
        $totalUsage = ActivityLog::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Usage by activity
        $usageByActivity = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->select('activity_id', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('activity_id')
            ->with('activity')
            ->orderByDesc('usage_count')
            ->get();
        
        // Usage by day
        $usageByDay = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('reports.activities', compact('totalUsage', 'usageByActivity', 'usageByDay', 'startDate', 'endDate'));
    }
    
    // Export Revenue Report to CSV
    public function exportRevenue(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();
        
        $members = Member::whereBetween('created_at', [$startDate, $endDate])
            ->with('membership')
            ->latest('created_at')
            ->get();
        
        $filename = 'revenue-report-' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'r+');
        
        // CSV Header
        fputcsv($handle, ['Member ID', 'Member Name', 'Membership Type', 'Price', 'Join Date']);
        
        // CSV Data
        foreach ($members as $member) {
            fputcsv($handle, [
                $member->card_id,
                $member->name,
                $member->membership->name ?? 'N/A',
                $member->membership->price ?? 0,
                $member->created_at->format('Y-m-d'),
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }
    
    // Export Members Report to CSV
    public function exportMembers(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subMonths(6);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();
        
        $members = Member::whereBetween('created_at', [$startDate, $endDate])
            ->with('membership')
            ->latest('created_at')
            ->get();
        
        $filename = 'members-report-' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'r+');
        
        // CSV Header
        fputcsv($handle, ['Card ID', 'Name', 'Phone', 'Email', 'Membership', 'Join Date', 'Status']);
        
        // CSV Data
        foreach ($members as $member) {
            fputcsv($handle, [
                $member->card_id,
                $member->name,
                $member->phone ?? 'N/A',
                $member->email ?? 'N/A',
                $member->membership->name ?? 'N/A',
                $member->created_at->format('Y-m-d'),
                $member->membership_id ? 'Active' : 'Inactive',
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }
    
    // Export Activity Usage Report to CSV
    public function exportActivities(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->subMonths(3);
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now();
        
        $logs = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->with(['member', 'activity', 'staff'])
            ->latest('created_at')
            ->get();
        
        $filename = 'activity-usage-report-' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'r+');
        
        // CSV Header
        fputcsv($handle, ['Member ID', 'Member Name', 'Activity', 'Date & Time', 'Staff', 'Status']);
        
        // CSV Data
        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->member->card_id ?? 'N/A',
                $log->member->name ?? 'N/A',
                $log->activity->name ?? 'Unknown',
                $log->created_at->format('Y-m-d H:i:s'),
                $log->staff->name ?? 'N/A',
                'Completed',
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }
}
