@extends('layouts.app')

@section('content')
<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    
    h1, h2, h3, h4 {
        font-family: 'Cormorant Garamond', serif;
        letter-spacing: -1px;
        font-weight: 600;
    }
    
    body {
        background: #0f1419;
        color: #d1d5db;
    }
    
    .header-divider {
        border-color: #d4af37;
    }
    
    /* PAGE HEADER */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    }
    
    .page-header h1 {
        color: #fff;
        font-size: 2rem;
        margin: 0;
    }
    
    .page-header-subtext {
        color: #9ca3af;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .header-actions {
        display: flex;
        gap: 1rem;
    }
    
    /* BUTTONS */
    .btn-gold {
        background: #d4af37;
        color: #0f1419;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 2rem;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .btn-gold:hover {
        background: #e6c547;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2);
    }
    
    .btn-small {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
    
    /* SUCCESS ALERT */
    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid #10b981;
        color: #10b981;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }
    
    /* TABLE STYLES */
    .table-wrapper {
        background: #1a1f2e;
        border: 1px solid #d4af37;
        overflow: hidden;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead tr {
        background: rgba(212, 175, 55, 0.08);
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    }
    
    .data-table th {
        padding: 1.25rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #d4af37;
        text-transform: uppercase;
    }
    
    .data-table td {
        padding: 1.25rem;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
        color: #d1d5db;
        font-size: 0.875rem;
    }
    
    .data-table tbody tr {
        transition: all 0.3s ease;
    }
    
    .data-table tbody tr:hover {
        background: rgba(212, 175, 55, 0.05);
    }
    
    .data-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* ACTION LINKS */
    .action-link {
        color: #d4af37;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .action-link:hover {
        color: #e6c547;
    }
    
    .action-link-danger {
        color: #ef4444;
        border: none;
        background: none;
        padding: 0;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .action-link-danger:hover {
        color: #f87171;
    }
    
    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
        font-size: 0.875rem;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-7xl mx-auto">

        <!-- PAGE HEADER WITH ACTIONS -->
        <div class="page-header">
            <div>
                <h1>Activity Limits</h1>
                <p class="page-header-subtext">Manage activity limits per membership tier</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('membership-activity-limits.create') }}" class="btn-gold btn-small">+ Add Limit</a>
            </div>
        </div>

        <!-- SUCCESS MESSAGE (CONDITIONAL) -->
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- ACTIVITY LIMITS TABLE CARD -->
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Membership</th>
                        <th style="width: 25%;">Activity</th>
                        <th style="width: 15%; text-align: center;">Max / Year</th>
                        <th style="width: 15%; text-align: center;">Max / Day</th>
                        <th style="width: 20%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($limits as $limit)
                        <tr>
                            <td>{{ $limit->membership->name }}</td>
                            <td>{{ $limit->activity->name }}</td>
                            <td style="text-align: center;">{{ $limit->max_per_year }}</td>
                            <td style="text-align: center;">{{ $limit->max_per_day ?? 'Unlimited' }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('membership-activity-limits.edit', $limit) }}" class="action-link" style="margin-right: 1rem;">Edit</a>
                                <form action="{{ route('membership-activity-limits.destroy', $limit) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this activity limit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 3rem 2rem; color: #6b7280;">
                                No activity limits found. <a href="{{ route('membership-activity-limits.create') }}" class="action-link">Create one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
