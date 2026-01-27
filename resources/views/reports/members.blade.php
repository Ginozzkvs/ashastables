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
    
    .export-btn { display: inline-block; padding: 0.75rem 1.5rem; border: 1px solid #d4af37; color: #d4af37; text-decoration: none; font-weight: 600; text-transform: uppercase; font-size: 0.875rem; margin-bottom: 2rem; }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="container">
    <div class="content-wrapper">
        <div class="page-header">
            <h1>Member Analytics</h1>
            <p style="color: #9ca3af; font-size: 0.875rem;">Member growth and distribution analysis</p>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('reports.members') }}">
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
                <a href="{{ route('reports.export.members', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="export-btn">Export CSV</a>
            </div>
        </form>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Members</div>
                <div class="stat-value">{{ $totalMembers }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">New Members</div>
                <div class="stat-value">{{ $newMembers }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Members</div>
                <div class="stat-value">{{ $activeMembers }}</div>
            </div>
        </div>

        <!-- Members by Type -->
        <h3 style="color: #d4af37; font-size: 1.5rem; margin: 2rem 0 1.5rem 0; font-family: 'Cormorant Garamond', serif;">Members by Membership Type</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Membership Type</th>
                        <th>Member Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($membersByMembership as $item)
                    <tr>
                        <td><strong>{{ $item->membership->name ?? 'No Membership' }}</strong></td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $totalMembers > 0 ? round(($item->count / $totalMembers) * 100) : 0 }}%</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #9ca3af;">No members</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Growth Trend -->
        <h3 style="color: #d4af37; font-size: 1.5rem; margin: 2rem 0 1.5rem 0; font-family: 'Cormorant Garamond', serif;">Member Growth Trend</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>New Members</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memberGrowth as $day)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</strong></td>
                        <td>{{ $day->count }}</td>
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
