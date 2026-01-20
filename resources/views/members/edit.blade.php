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
    
    .card-base:hover {
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.1);
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
        justify-content: space-between;
    }
    
    .form-actions-right {
        display: flex;
        gap: 1rem;
    }
    
    .help-text {
        color: #6b7280;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }
    
    .page-header {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(212, 175, 55, 0.2);
    }
    
    .page-header h1 {
        color: #fff;
        font-size: 2rem;
        margin: 0 0 0.25rem 0;
    }
    
    .page-header-subtext {
        color: #9ca3af;
        font-size: 0.875rem;
    }
    
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.2);
        margin-bottom: 2rem;
    }
    
    .checkbox-wrapper input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #d4af37;
        cursor: pointer;
    }
    
    .checkbox-wrapper label {
        cursor: pointer;
        color: #fff;
        font-weight: 600;
        margin: 0;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-3xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1>Edit Member Profile</h1>
            <p class="page-header-subtext">Update member information and membership details</p>
        </div>

        <!-- EDIT FORM CARD -->
        <div class="card-base rounded-none p-8">
            <form method="POST" action="{{ route('members.update', $member->card_id) }}">
                @csrf
                @method('PUT')
                
                <!-- Card ID (Display Only) -->
                <div class="form-group">
                    <label class="form-label">Card ID</label>
                    <div class="form-input" style="background: rgba(212, 175, 55, 0.1); border-style: dashed; display: flex; align-items: center; padding: 0.875rem 1rem; font-weight: 600; letter-spacing: 0.05em; color: #d4af37;">
                        {{ $member->card_id ?? 'Not assigned' }}
                    </div>
                    <p class="help-text">Unique card identifier for this member</p>
                </div>
                
                <!-- Name Field -->
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $member->name) }}"
                        placeholder="Member's full name"
                        class="form-input"
                        required>
                    <p class="help-text">The member's legal name for official records</p>
                </div>

                <!-- Two-Column Layout -->
                <div class="form-grid">
                    <!-- Phone Field -->
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone', $member->phone) }}"
                            placeholder="Phone number"
                            class="form-input">
                        <p class="help-text">Primary contact number</p>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $member->email) }}"
                            placeholder="Email address"
                            class="form-input">
                        <p class="help-text">Email for member communications</p>
                    </div>
                </div>

                <!-- Card UID Field -->
                <div class="form-group">
                    <label class="form-label">NFC Card UID</label>
                    <input 
                        type="text" 
                        name="card_uid" 
                        value="{{ old('card_uid', $member->card_uid) }}"
                        placeholder="Tap NFC card or enter UID"
                        class="form-input">
                    <p class="help-text">Unique identifier from member's NFC card for check-in</p>
                </div>

                <!-- Membership Selection -->
                <div class="form-group">
                    <label for="membership_id" class="form-label">Membership Type</label>
                    <select id="membership_id" name="membership_id" class="form-select" required>
                        <option value="">Select membership type...</option>
                        @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}" {{ old('membership_id', $member->membership_id) == $membership->id ? 'selected' : '' }}>
                                {{ $membership->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="help-text">The membership tier for this member</p>
                </div>

                <!-- Date Fields -->
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Membership Start Date</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ old('start_date', $member->start_date) }}"
                            class="form-input"
                            required>
                        <p class="help-text">When membership begins</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Membership End Date</label>
                        <input 
                            type="date" 
                            name="expiry_date" 
                            value="{{ old('expiry_date', $member->expiry_date?->format('Y-m-d')) }}"
                            class="form-input"
                            required>
                        <p class="help-text">When membership expires</p>
                    </div>
                </div>

                <!-- Active Checkbox -->
                <div class="checkbox-wrapper">
                    <input 
                        type="checkbox" 
                        name="active" 
                        id="active"
                        {{ old('active', $member->active) ? 'checked' : '' }}>
                    <label for="active">Mark this member as ACTIVE</label>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('members.index') }}" class="btn-outline">Cancel</a>
                    <div class="form-actions-right">
                        <button type="submit" class="btn-gold">Update Member</button>
                    </div>
                </div>

            </form>

        </div>

        <!-- MEMBER INFO CARD -->
        <div class="card-base rounded-none p-8 mt-8">
            <p class="text-xs uppercase tracking-widest font-bold mb-6" style="color: #d4af37;">Member Overview</p>
            
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-2">
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Member ID</p>
                    <p class="text-gray-200 font-mono text-sm">#{{ $member->id }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Status</p>
                    <p style="color: {{ $member->active ? '#10b981' : '#ef4444' }}; font-weight: 700; text-transform: uppercase; font-size: 0.875rem;">{{ $member->active ? 'Active' : 'Inactive' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Member Since</p>
                    <p class="text-gray-200 text-sm">{{ $member->created_at->format('F j, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 tracking-widest font-bold uppercase mb-2">Last Updated</p>
                    <p class="text-gray-200 text-sm">{{ $member->updated_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- DELETE SECTION -->
        <div class="mt-12 pt-8 border-t header-divider">
            <p class="text-xs uppercase tracking-widest font-bold mb-4" style="color: #ef4444;">Danger Zone</p>
            <p style="color: #d1d5db; font-size: 0.875rem; margin-bottom: 1.5rem;">Once you delete a member, all associated activity logs and membership data will be permanently removed.</p>
            <form action="{{ route('members.destroy', $member->card_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this member? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete Member</button>
            </form>
        </div>

    </div>
</div>
@endsection
