@extends('layouts.app')

@section('content')
<style>
    * { font-family: 'Inter', sans-serif; }
    h1, h2, h3 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; }
    
    body {
        background: #0f1419;
        color: #e0e0e0;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .header {
        margin-bottom: 3rem;
        border-bottom: 2px solid #d4af37;
        padding-bottom: 1.5rem;
    }

    .header h1 {
        font-size: 2.5rem;
        color: #fff;
        margin: 0 0 0.5rem;
    }

    .header p {
        color: #9ca3af;
        margin: 0;
    }

    .card-base {
        background: #1a1f2e;
        border: 1px solid #d4af37;
        padding: 2rem;
        margin-bottom: 2rem;
        border-radius: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #d4af37;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-input,
    .form-select {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #d4af37;
        color: #fff;
        padding: 0.75rem;
        font-size: 0.875rem;
        transition: all 0.3s;
        font-family: 'Inter', sans-serif;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        background: rgba(212, 175, 55, 0.1);
        border-color: #e6c547;
        box-shadow: 0 0 12px rgba(212, 175, 55, 0.15);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        cursor: pointer;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.3s;
        font-size: 0.875rem;
        border-radius: 0;
    }

    .btn-gold {
        background: #d4af37;
        color: #0f1419;
    }

    .btn-gold:hover {
        background: #e6c547;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2);
    }

    .btn-danger {
        background: #ef4444;
        color: #fff;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-outline {
        border: 1px solid #d4af37;
        color: #d4af37;
        background: transparent;
    }

    .btn-outline:hover {
        background: rgba(212, 175, 55, 0.1);
    }

    .activity-row {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid #d4af37;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-radius: 0;
    }

    .activity-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1rem;
    }

    .activity-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1rem;
        align-items: flex-end;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state p {
        margin: 0.5rem 0;
    }

    .success-message {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid #10b981;
        color: #10b981;
        padding: 1rem;
        margin-bottom: 2rem;
        border-radius: 0;
    }

    .back-link {
        color: #d4af37;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        margin-bottom: 2rem;
        display: inline-block;
    }

    .back-link:hover {
        color: #e6c547;
    }
</style>

<div class="container">
    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <a href="{{ route('memberships.index') }}" class="back-link">← Back to Memberships</a>

    <div class="header">
        <h1>{{ $membership->name }}</h1>
        <p>Manage activity limits for this membership</p>
    </div>

    <form action="{{ route('memberships.update-activity-limits', $membership) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-base">
            <h3 style="color: #fff; margin-top: 0;">Current Activities</h3>
            
            @if($membership->activityLimits->isEmpty())
                <div class="empty-state">
                    <p>No activities assigned to this membership yet.</p>
                    <p>Add an activity below to get started.</p>
                </div>
            @else
                @foreach($membership->activityLimits as $limit)
                    <div class="activity-row">
                        <input type="hidden" name="limits[{{ $loop->index }}][activity_id]" value="{{ $limit->activity_id }}">
                        <div class="activity-name">{{ $limit->activity->name }}</div>
                        <div class="activity-inputs">
                            <div class="form-group">
                                <label class="form-label">Max Per Year</label>
                                <input 
                                    type="number" 
                                    name="limits[{{ $loop->index }}][max_per_year]" 
                                    value="{{ $limit->max_per_year }}"
                                    class="form-input"
                                    required
                                    min="1">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Max Per Day</label>
                                <input 
                                    type="number" 
                                    name="limits[{{ $loop->index }}][max_per_day]" 
                                    value="{{ $limit->max_per_day }}"
                                    class="form-input"
                                    required
                                    min="1">
                            </div>
                            <form action="{{ route('memberships.remove-activity', [$membership, $limit->activity]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Remove this activity?')">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="form-group" style="text-align: right; margin-top: 2rem;">
            <button type="submit" class="btn btn-gold">Save Changes</button>
        </div>
    </form>

    <div class="card-base">
        <h3 style="color: #fff; margin-top: 0;">Add New Activity</h3>
        <form action="{{ route('memberships.update-activity-limits', $membership) }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end;">
            @csrf
            @method('PUT')

            @php
                $assignedActivityIds = $membership->activityLimits->pluck('activity_id')->toArray();
                $availableActivities = $allActivities->filter(fn($a) => !in_array($a->id, $assignedActivityIds));
            @endphp

            @if($availableActivities->isEmpty())
                <p style="color: #6b7280; margin: 0;">All activities are already assigned to this membership.</p>
            @else
                <div style="flex: 1;">
                    <label class="form-label">Select Activity</label>
                    <select name="limits[0][activity_id]" class="form-select" required>
                        <option value="">Choose an activity...</option>
                        @foreach($availableActivities as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1;">
                    <label class="form-label">Max Per Year</label>
                    <input 
                        type="number" 
                        name="limits[0][max_per_year]" 
                        class="form-input"
                        required
                        min="1"
                        placeholder="e.g., 12">
                </div>

                <div style="flex: 1;">
                    <label class="form-label">Max Per Day</label>
                    <input 
                        type="number" 
                        name="limits[0][max_per_day]" 
                        class="form-input"
                        required
                        min="1"
                        placeholder="e.g., 1">
                </div>

                <button type="submit" class="btn btn-gold">Add Activity</button>
            @endif
        </form>
    </div>
</div>

@endsection
