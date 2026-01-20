@extends('layouts.app')

@section('content')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { font-family: 'Inter', sans-serif; background: #0f1419; color: #d1d5db; line-height: 1.6; }
    h1, h2, h3 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; text-transform: uppercase; }
    .container { min-height: 100vh; padding: 2rem 1rem; background: #0f1419; }
    @media (min-width: 640px) { .container { padding: 2rem; } }
    .content-wrapper { max-width: 88rem; margin: 0 auto; }
    
    .page-header { margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(212, 175, 55, 0.2); }
    .page-header h1 { color: #fff; font-size: 2rem; margin-bottom: 0.5rem; }
    
    .filters-section { background: #1a1f2e; border: 1px solid #d4af37; padding: 1.5rem; margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end; }
    .form-group { display: flex; flex-direction: column; }
    .form-label { color: #d4af37; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem; }
    .form-input { background: rgba(255, 255, 255, 0.05); border: 1px solid #d4af37; color: #fff; padding: 0.75rem; font-size: 0.875rem; }
    
    .btn { padding: 0.75rem 1.5rem; background: #d4af37; color: #0f1419; border: none; font-weight: 600; cursor: pointer; text-transform: uppercase; font-size: 0.875rem; }
    .btn:hover { background: #e6c547; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }
    .stat-card { background: #1a1f2e; border: 1px solid #d4af37; padding: 2rem; text-align: center; }
    .stat-label { color: #9ca3af; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.5rem; }
    .stat-value { color: #d4af37; font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; }
    
    .table-wrapper { background: #1a1f2e; border: 1px solid #d4af37; overflow-x: auto; margin-bottom: 2rem; }
    table { width: 100%; border-collapse: collapse; }
    thead { background: rgba(212, 175, 55, 0.1); border-bottom: 2px solid #d4af37; }
    th { padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: #d4af37; text-transform: uppercase; }
    td { padding: 1rem; border-bottom: 1px solid rgba(212, 175, 55, 0.1); }
    tbody tr:hover { background: rgba(212, 175, 55, 0.05); }
    
    .activity-bar { background: rgba(212, 175, 55, 0.2); height: 8px; border-radius: 2px; margin-top: 0.5rem; }
    .activity-bar-fill { background: #d4af37; height: 100%; border-radius: 2px; }
    
    .export-btn { display: inline-block; padding: 0.75rem 1.5rem; border: 1px solid #d4af37; color: #d4af37; text-decoration: none; font-weight: 600; text-transform: uppercase; font-size: 0.875rem; margin-bottom: 2rem; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>Activity Usage Report</h1>
            <p style="color: #9ca3af; font-size: 0.875rem;">Track activity popularity and booking patterns</p>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('reports.activities') }}">
            <div class="filters-section">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-input" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-input" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <button type="submit" class="btn">Filter</button>
                <a href="{{ route('reports.export.activities', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="export-btn">📥 Export CSV</a>
            </div>
        </form>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Sessions</div>
                <div class="stat-value">{{ $totalUsage }}</div>
            </div>
        </div>

        <!-- Activity Usage -->
        <h3 style="color: #d4af37; font-size: 1.5rem; margin: 2rem 0 1.5rem 0; font-family: 'Cormorant Garamond', serif;">Most Popular Activities</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Usage Count</th>
                        <th>Popularity</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $maxUsage = $usageByActivity->max('usage_count') ?? 1;
                    @endphp
                    @forelse($usageByActivity as $item)
                    <tr>
                        <td><strong>{{ $item->activity->name ?? 'Unknown Activity' }}</strong></td>
                        <td>{{ $item->usage_count }} sessions</td>
                        <td>
                            <div class="activity-bar">
                                <div class="activity-bar-fill" style="width: {{ ($item->usage_count / $maxUsage) * 100 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #9ca3af;">No activity data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Daily Usage -->
        <h3 style="color: #d4af37; font-size: 1.5rem; margin: 2rem 0 1.5rem 0; font-family: 'Cormorant Garamond', serif;">Daily Usage Trend</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sessions Booked</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usageByDay as $day)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</strong></td>
                        <td>{{ $day->count }} sessions</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="text-align: center; color: #9ca3af; padding: 2rem;">No data for selected period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
