@extends('layouts.app')

@section('content')
<style>
    * { font-family: 'Inter', sans-serif; }
    h1, h2, h3, h4 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; }
    
    body {
        background: #0f1419;
    }
    
    .header-divider {
        border-color: #d4af37;
    }
    
    .card-base {
        background: #1a1f2e;
        border: 1px solid #d4af37;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    
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
    }
    
    .btn-outline:hover {
        background: rgba(212, 175, 55, 0.1);
        transform: translateY(-2px);
    }
    
    .btn-danger {
        border: 1px solid #ef4444;
        color: #ef4444;
        background: transparent;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 2rem;
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.1);
        transform: translateY(-1px);
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .help-text {
        color: #6b7280;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-2xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="mb-8">
            <p class="text-xs uppercase tracking-widest font-bold mb-2" style="color: #d4af37;">Program Editor</p>
            <h2 class="text-4xl font-bold text-white" style="letter-spacing: -1px; font-family: 'Cormorant Garamond', serif;">Edit Activity</h2>
            <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.5rem;">Update activity details and session unit settings</p>
        </div>

        <!-- EDIT FORM CARD -->
        <div class="card-base rounded-none p-8">

            <form method="POST" action="{{ route('activities.update', $activity->id) }}">
                @csrf
                @method('PUT')

                <!-- Activity Name Field -->
                <div class="form-group">
                    <label class="form-label">Activity Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ $activity->name }}"
                        placeholder="Enter activity name"
                        class="form-input"
                        required>
                    <p class="help-text">The official name of this equestrian program</p>
                </div>

                <!-- Unit Selection Field -->
                <div class="form-group">
                    <label for="unit" class="form-label">Session Unit</label>
                    <select id="unit" name="unit" class="form-select" required>
                        <option value="minutes" @selected($activity->unit === 'minutes')>Minutes</option>
                        <option value="times" @selected($activity->unit === 'times')>Times</option>
                    </select>
                    <p class="help-text">How sessions are counted for this activity (duration or frequency)</p>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-gold">Update Activity</button>
                    <a href="{{ route('activities.index') }}" class="btn-outline" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">Cancel</a>
                </div>
            </form>

        </div>

        <!-- ACTIVITY INFO CARD -->
        <div class="card-base rounded-none p-8 mt-8">
            <p class="text-xs uppercase tracking-widest font-bold mb-4" style="color: #d4af37;">Activity Details</p>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Activity ID</p>
                    <p class="text-gray-200 font-mono text-sm">{{ $activity->id }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Created</p>
                    <p class="text-gray-200 text-sm">{{ $activity->created_at->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Status</p>
                    <p style="color: #10b981; font-weight: 700; text-transform: uppercase; font-size: 0.875rem;">Active</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Last Updated</p>
                    <p class="text-gray-200 text-sm">{{ $activity->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- DELETE SECTION -->
        <div class="mt-12 pt-8 border-t" style="border-color: #d4af37;">
            <p class="text-xs uppercase tracking-widest font-bold mb-4" style="color: #ef4444;">Danger Zone</p>
            <p style="color: #d1d5db; font-size: 0.875rem; margin-bottom: 1.5rem;">Once you delete an activity, there is no going back. Please be certain.</p>
            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('Are you sure you want to delete this activity?');">
                    Delete Activity
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
