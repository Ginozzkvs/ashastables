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
    
    /* CARDS */
    .card-base {
        background: #1a1f2e;
        border: 1px solid #d4af37;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
    .card-base:hover {
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.1);
    }
    
    /* FORM ELEMENTS */
    .form-group {
        margin-bottom: 2rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #d4af37;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }
    
    .form-input,
    .form-select {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #d4af37;
        color: #fff;
        padding: 0.875rem 1rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
        box-sizing: border-box;
    }
    
    .form-input:focus,
    .form-select:focus {
        outline: none;
        background: rgba(212, 175, 55, 0.1);
        border-color: #e6c547;
        box-shadow: 0 0 12px rgba(212, 175, 55, 0.15);
    }
    
    .form-input::placeholder {
        color: #6b7280;
    }
    
    .form-select option {
        background: #1a1f2e;
        color: #fff;
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
    
    .btn-outline {
        border: 1px solid #d4af37;
        color: #d4af37;
        background: transparent;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 2rem;
        cursor: pointer;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-outline:hover {
        background: rgba(212, 175, 55, 0.1);
        transform: translateY(-2px);
    }
    
    /* FORM ACTIONS */
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: space-between;
    }
    
    .form-actions-right {
        display: flex;
        gap: 1rem;
    }
    
    /* HELP TEXT */
    .help-text {
        color: #6b7280;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }
    
    /* PAGE HEADER */
    .page-header {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    }
    
    .page-header h1 {
        color: #fff;
        font-size: 2rem;
        margin: 0 0 0.5rem 0;
        text-transform: uppercase;
    }
    
    .page-header-subtext {
        color: #9ca3af;
        font-size: 0.875rem;
        margin: 0;
    }
    
    /* INFO NOTE */
    .info-note {
        background: rgba(212, 175, 55, 0.05);
        border-left: 3px solid #d4af37;
        padding: 1rem;
        margin-bottom: 2rem;
        font-size: 0.875rem;
        color: #d1d5db;
    }
    
    .info-note strong {
        color: #d4af37;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-3xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1>Create Activity Limit</h1>
            <p class="page-header-subtext">Set usage boundaries for a membership-activity pair</p>
        </div>

        <!-- INFO NOTE -->
        <div class="info-note">
            <strong>NEW ACTIVITY LIMIT:</strong> Define how many times members in a tier can use each activity per year and per day.
        </div>

        <!-- CREATE FORM CARD -->
        <div class="card-base p-8" style="border-radius: 0;">
            <form method="POST" action="{{ route('membership-activity-limits.store') }}">
                @csrf

                <!-- Membership Selection -->
                <div class="form-group">
                    <label class="form-label">Membership</label>
                    <select name="membership_id" class="form-select" required>
                        <option value="">Select membership...</option>
                        @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}" {{ old('membership_id') == $membership->id ? 'selected' : '' }}>
                                {{ $membership->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="help-text">Choose the membership tier for this limit</p>
                </div>

                <!-- Activity Selection -->
                <div class="form-group">
                    <label class="form-label">Activity</label>
                    <select name="activity_id" class="form-select" required>
                        <option value="">Select activity...</option>
                        @foreach($activities as $activity)
                            <option value="{{ $activity->id }}" {{ old('activity_id') == $activity->id ? 'selected' : '' }}>
                                {{ $activity->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="help-text">Choose the activity this limit applies to</p>
                </div>

                <!-- Max Per Year -->
                <div class="form-group">
                    <label class="form-label">Max Per Year</label>
                    <input 
                        type="number" 
                        name="max_per_year" 
                        class="form-input"
                        value="{{ old('max_per_year') }}"
                        min="1"
                        required>
                    <p class="help-text">Maximum times this activity can be used per year</p>
                </div>

                <!-- Max Per Day -->
                <div class="form-group">
                    <label class="form-label">Max Per Day</label>
                    <input 
                        type="number" 
                        name="max_per_day" 
                        class="form-input"
                        value="{{ old('max_per_day') }}"
                        min="1"
                        placeholder="Leave blank for unlimited">
                    <p class="help-text">Maximum times this activity can be used per day (optional)</p>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('membership-activity-limits.index') }}" class="btn-outline">Cancel</a>
                    <div class="form-actions-right">
                        <button type="submit" class="btn-gold">Create Limit</button>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection
