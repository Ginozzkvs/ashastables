@extends('layouts.app')

@section('content')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { font-family: 'Inter', sans-serif; background: #0f1419; color: #d1d5db; line-height: 1.6; }
    h1, h2, h3, h4, h5, h6 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; text-transform: uppercase; }
    .container { min-height: 100vh; padding: 2rem 1rem; background: #0f1419; }
    @media (min-width: 640px) { .container { padding: 2rem; } }
    .content-wrapper { max-width: 88rem; margin: 0 auto; }
    
    .page-header { margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(212, 175, 55, 0.2); }
    .page-header h1 { color: #fff; font-size: 2.5rem; margin-bottom: 0.5rem; }
    .page-header-subtext { color: #9ca3af; font-size: 0.875rem; font-weight: 300; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
    .stat-card { background: #1a1f2e; border: 1px solid #d4af37; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); transition: all 0.3s ease; }
    .stat-card:hover { box-shadow: 0 8px 20px rgba(212, 175, 55, 0.1); }
    .stat-label { color: #9ca3af; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.75rem; font-weight: 600; }
    .stat-value { color: #d4af37; font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; margin-bottom: 0.5rem; }
    .stat-change { font-size: 0.875rem; color: #10b981; }
    .stat-change.negative { color: #ef4444; }
    
    .section-title { color: #d4af37; font-size: 1.5rem; margin: 3rem 0 1.5rem 0; font-family: 'Cormorant Garamond', serif; padding-bottom: 1rem; border-bottom: 1px solid rgba(212, 175, 55, 0.2); }
    
    .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem; }
    @media (max-width: 768px) { .dashboard-grid { grid-template-columns: 1fr; } }
    
    .card-base { background: #1a1f2e; border: 1px solid #d4af37; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); }
    
    .table-wrapper { background: #1a1f2e; border: 1px solid #d4af37; overflow-x: auto; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); }
    table { width: 100%; border-collapse: collapse; }
    thead { background: rgba(212, 175, 55, 0.1); border-bottom: 2px solid #d4af37; }
    th { padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.1em; color: #d4af37; text-transform: uppercase; }
    td { padding: 1rem; border-bottom: 1px solid rgba(212, 175, 55, 0.1); color: #d1d5db; }
    tbody tr:hover { background: rgba(212, 175, 55, 0.05); transition: all 0.3s ease; }
    tbody tr:last-child td { border-bottom: none; }
    
    .activity-bar { background: rgba(212, 175, 55, 0.2); height: 8px; border-radius: 2px; margin-top: 0.5rem; overflow: hidden; }
    .activity-bar-fill { background: #d4af37; height: 100%; border-radius: 2px; transition: width 0.3s ease; }
    
    .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 0; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-primary { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .badge-success { background: rgba(16, 185, 129, 0.15); color: #10b981; }
    
    .empty-state { text-align: center; padding: 3rem; color: #9ca3af; }
    .empty-state p { font-size: 0.875rem; }
    
    .chart-container { height: 300px; background: rgba(212, 175, 55, 0.05); border: 1px dashed rgba(212, 175, 55, 0.2); display: flex; align-items: center; justify-content: center; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>{{ __('messages.dashboard') }}</h1>
            <p class="page-header-subtext">{{ __('messages.dashboard_subtitle') }}</p>
        </div>

        <!-- Key Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">{{ __('messages.total_members') }}</div>
                <div class="stat-value">{{ $totalMembers }}</div>
                <div class="stat-change">+{{ $membersThisMonth }} {{ __('messages.this_month') }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">{{ __('messages.active_memberships') }}</div>
                <div class="stat-value">{{ $activeMemberships }}</div>
                <div class="stat-change">{{ $totalMembers > 0 ? round(($activeMemberships / $totalMembers) * 100) : 0 }}% {{ __('messages.coverage') }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">{{ __('messages.monthly_revenue') }}</div>
                <div class="stat-value" style="font-size: 1.75rem;">${{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-change">{{ __('messages.annual_value') }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">{{ __('messages.activities_this_month') }}</div>
                <div class="stat-value">{{ $activitiesThisMonth }}</div>
                <div class="stat-change">{{ __('messages.sessions_booked') }}</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="dashboard-grid">
            <!-- Most Popular Activities -->
            <div class="card-base">
                <h3 class="section-title" style="margin-top: 0; border: none; padding-bottom: 0;">{{ __('messages.most_popular_activities') }}</h3>
                @if($popularActivities->count() > 0)
                    <div style="margin-top: 1.5rem;">
                        @foreach($popularActivities as $activity)
                        <div style="margin-bottom: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span style="font-weight: 500;">{{ $activity->activity->name ?? __('messages.unknown_activity') }}</span>
                                <span class="badge badge-primary">{{ $activity->usage_count }} {{ __('messages.uses') }}</span>
                            </div>
                            <div class="activity-bar">
                                <div class="activity-bar-fill" style="width: {{ ($activity->usage_count / $popularActivities->first()->usage_count) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p>{{ __('messages.no_activity_data') }}</p>
                    </div>
                @endif
            </div>

            <!-- Members by Membership Type -->
            <div class="card-base">
                <h3 class="section-title" style="margin-top: 0; border: none; padding-bottom: 0;">{{ __('messages.members_by_type') }}</h3>
                @if($membersByMembership->count() > 0)
                    <div style="margin-top: 1.5rem;">
                        @foreach($membersByMembership as $member)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid rgba(212, 175, 55, 0.1);">
                            <span>{{ $member->membership->name ?? 'No Membership' }}</span>
                            <span class="badge badge-success">{{ $member->count }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p>{{ __('messages.no_members_yet') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity Trend (Last 7 Days) -->
        <div class="card-base">
            <h3 class="section-title" style="margin-top: 0; border: none;">{{ __('messages.activity_trend') }}</h3>
            @if($activityTrend->count() > 0)
                <div style="margin-top: 1.5rem;">
                    @php
                        $maxCount = $activityTrend->max('count');
                    @endphp
                    @foreach($activityTrend as $trend)
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="font-weight: 500; color: #d1d5db;">{{ \Carbon\Carbon::parse($trend->date)->format('M d, Y') }}</span>
                            <span style="color: #d4af37; font-weight: 600;">{{ $trend->count }} {{ __('messages.sessions') }}</span>
                        </div>
                        <div class="activity-bar">
                            <div class="activity-bar-fill" style="width: {{ ($trend->count / $maxCount) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>{{ __('messages.no_activity_7_days') }}</p>
                </div>
            @endif
        </div>

        <!-- Recent Activity Log -->
        <div class="card-base">
            <h3 class="section-title" style="margin-top: 0; border: none;">{{ __('messages.expiring_memberships') }}</h3>
            @if($expiringMemberships->count() > 0)
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('messages.member') }}</th>
                                <th>{{ __('messages.membership') }}</th>
                                <th>{{ __('messages.expires_in') }}</th>
                                <th>{{ __('messages.expiry_date') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiringMemberships as $member)
                            <tr>
                                <td>
                                    <strong>{{ $member->name }}</strong><br>
                                    <span style="color: #6b7280; font-size: 0.875rem;">{{ $member->card_id }}</span>
                                </td>
                                <td>{{ $member->membership->name }}</td>
                                <td>
                                    <span class="badge" style="background: #92400e; color: #fcd34d;">
                                        {{ $member->daysUntilExpiry() }} {{ __('messages.days') }}
                                    </span>
                                </td>
                                <td>{{ $member->expiry_date->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('memberships.renewal.form', $member->card_id) }}" style="color: #d4af37; text-decoration: none; font-weight: 600;">{{ __('messages.renew_arrow') }}</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($expiringMemberships->count() > 0)
                    <div style="margin-top: 1rem; text-align: center;">
                        <a href="{{ route('memberships.renewal.index') }}" class="badge badge-primary" style="padding: 0.5rem 1rem; cursor: pointer;">
                            {{ __('messages.view_all_expiring') }}
                        </a>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <p>✓ {{ __('messages.no_expiring_30_days') }}</p>
                </div>
            @endif
            @if($expiredCount > 0)
                <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; border-radius: 0;">
                    <p style="color: #ef4444; font-weight: 600;">{{ $expiredCount }} {{ __('messages.expired_membership_warning') }}</p>
                    <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.25rem;">{{ __('messages.renew_access_warning') }}</p>
                </div>
            @endif
        </div>

        <!-- Recent Activity Log -->
        <div class="card-base" style="margin-top: 2rem;">
            <h3 class="section-title" style="margin-top: 0; border: none;">{{ __('messages.recent_activity_log') }}</h3>
            @if($recentActivities->count() > 0)
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('messages.member') }}</th>
                                <th>{{ __('messages.activity') }}</th>
                                <th>{{ __('messages.date_time') }}</th>
                                <th>{{ __('messages.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->member->name ?? 'N/A' }}</strong><br>
                                    <span style="color: #6b7280; font-size: 0.875rem;">{{ $log->member->card_id ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $log->activity->name ?? __('messages.unknown_activity') }}</td>
                                <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                <td><span class="badge badge-success">{{ __('messages.completed') }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>{{ __('messages.no_activities_recorded') }}</p>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
