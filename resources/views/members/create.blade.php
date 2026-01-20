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
        margin: 0 0 0.25rem 0;
    }
    
    .page-header-subtext {
        color: #9ca3af;
        font-size: 0.875rem;
    }
    
    /* CHECKBOX STYLING */
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
    
    /* GRID LAYOUT */
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
    
    /* ERROR MESSAGE */
    .alert-error {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid #ef4444;
        color: #ef4444;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }
    
    .alert-error ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .alert-error li {
        margin-bottom: 0.25rem;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-4xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1>Add New Member</h1>
            <p class="page-header-subtext">Register a new member to the ASHA Resort community</p>
        </div>

        <!-- ERROR MESSAGES -->
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- CREATE FORM CARD -->
        <div class="card-base p-8" style="border-radius: 0;">
            <form action="{{ route('members.store') }}" method="POST">
                @csrf
                
                <!-- Auto-Generated Card ID (Display Only) -->
                <div class="form-group">
                    <label class="form-label">Card ID</label>
                    <div class="form-input" style="background: rgba(212, 175, 55, 0.1); border-style: dashed; display: flex; align-items: center; padding: 0.875rem 1rem; font-weight: 600; letter-spacing: 0.05em; color: #d4af37;">
                        {{ \App\Models\Member::generateCardId() }}
                    </div>
                    <p class="help-text">Automatically generated unique card identifier</p>
                </div>
                
                <!-- NFC Card UID Field -->
                <div class="form-group">
                    <label class="form-label">NFC Card UID</label>
                    <input 
                        type="text" 
                        name="card_uid"
                        placeholder="Tap card here or enter UID"
                        class="form-input"
                        value="{{ old('card_uid') }}"
                        autofocus
                        required>
                    <p class="help-text">Scan or manually enter the NFC card identifier</p>
                </div>

                <!-- Name Field -->
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Member's full name"
                        class="form-input"
                        value="{{ old('name') }}"
                        required>
                    <p class="help-text">The member's legal name for official records</p>
                </div>

                <!-- Phone Field -->
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input 
                        type="tel" 
                        name="phone" 
                        placeholder="Phone number with area code"
                        class="form-input"
                        value="{{ old('phone') }}"
                        required>
                    <p class="help-text">Primary contact number</p>
                </div>

                <!-- Membership Selection -->
                <div class="form-group">
                    <label for="membership" class="form-label">Membership Type</label>
                    <select id="membership" name="membership_id" class="form-select" required>
                        <option value="">Select membership type...</option>
                        @foreach ($memberships as $membership)
                            <option value="{{ $membership->id }}" {{ old('membership_id') == $membership->id ? 'selected' : '' }}>
                                {{ $membership->name }} ({{ $membership->duration_days }} days)
                            </option>
                        @endforeach
                    </select>
                    <p class="help-text">Choose the membership tier for this member</p>
                </div>

                <!-- Active Checkbox -->
                <div class="checkbox-wrapper">
                    <input 
                        type="checkbox" 
                        name="active" 
                        id="active"
                        value="1"
                        {{ old('active', 1) ? 'checked' : '' }}>
                    <label for="active">Activate member immediately</label>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('members.index') }}" class="btn-outline">Cancel</a>
                    <div class="form-actions-right">
                        <button type="submit" class="btn-gold">Add Member</button>
                    </div>
                </div>

            </form>

        </div>

        <!-- INFO SECTION -->
        <div class="mt-12 pt-8" style="border-top: 1px solid rgba(212, 175, 55, 0.2);">
            <p class="text-xs uppercase tracking-widest font-bold mb-6" style="color: #d4af37;">How It Works</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                
                <!-- Step 1 -->
                <div style="background: rgba(212, 175, 55, 0.05); border-left: 2px solid #d4af37; padding: 1.5rem;">
                    <p style="color: #d4af37; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">1. Scan Card</p>
                    <p style="color: #9ca3af; font-size: 0.875rem; line-height: 1.6;">Have the member tap their NFC card, or manually enter the card UID.</p>
                </div>

                <!-- Step 2 -->
                <div style="background: rgba(212, 175, 55, 0.05); border-left: 2px solid #d4af37; padding: 1.5rem;">
                    <p style="color: #d4af37; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">2. Enter Details</p>
                    <p style="color: #9ca3af; font-size: 0.875rem; line-height: 1.6;">Provide the member's name, contact information, and membership preference.</p>
                </div>

                <!-- Step 3 -->
                <div style="background: rgba(212, 175, 55, 0.05); border-left: 2px solid #d4af37; padding: 1.5rem;">
                    <p style="color: #d4af37; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">3. Confirm & Save</p>
                    <p style="color: #9ca3af; font-size: 0.875rem; line-height: 1.6;">Review the information and click Add Member to complete registration.</p>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
