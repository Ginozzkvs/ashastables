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
        margin: 0 0 0.5rem 0;
    }
    
    .page-header-subtext {
        color: #9ca3af;
        font-size: 0.875rem;
    }
    
    .form-error {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }
    
    .toggle-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 26px;
        background: #374151;
        border-radius: 13px;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .toggle-switch.active {
        background: #d4af37;
    }
    
    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        transition: transform 0.3s;
    }
    
    .toggle-switch.active::after {
        transform: translateX(24px);
    }
    
    .toggle-label {
        color: #d1d5db;
        font-size: 0.875rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    
    .user-info {
        background: rgba(212, 175, 55, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.2);
        padding: 1rem;
        margin-bottom: 2rem;
    }
    
    .user-info-label {
        color: #9ca3af;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .user-info-value {
        color: #d4af37;
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 0.25rem;
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<div class="min-h-screen p-4 sm:p-8" style="background: #0f1419;">
    <div class="max-w-3xl mx-auto">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1>{{ __('messages.edit_user') }}</h1>
            <p class="page-header-subtext">{{ __('messages.edit_user_subtitle') }}</p>
        </div>

        <!-- FORM CARD -->
        <div class="card-base p-8">
            
            <!-- User Info -->
            <div class="user-info" style="display: flex; gap: 2rem;">
                <div>
                    <p class="user-info-label">{{ __('messages.user_id') }}</p>
                    <p class="user-info-value">#{{ $user->id }}</p>
                </div>
                <div>
                    <p class="user-info-label">{{ __('messages.created_at') }}</p>
                    <p class="user-info-value">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="user-info-label">{{ __('messages.last_updated') }}</p>
                    <p class="user-info-value">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label">{{ __('messages.name') }} *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required placeholder="{{ __('messages.enter_name') }}">
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">{{ __('messages.email') }} *</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required placeholder="{{ __('messages.enter_email') }}">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.new_password') }}</label>
                        <input type="password" name="password" class="form-input" placeholder="{{ __('messages.leave_blank_password') }}">
                        <p class="help-text">{{ __('messages.password_change_help') }}</p>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('messages.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="{{ __('messages.confirm_password') }}">
                    </div>
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label class="form-label">{{ __('messages.role') }} *</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>{{ __('messages.staff') }}</option>
                    </select>
                    <p class="help-text">{{ __('messages.role_help_text') }}</p>
                    @error('role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="form-group" x-data="{ active: {{ old('active', $user->active) ? 'true' : 'false' }} }">
                    <label class="form-label">{{ __('messages.status') }}</label>
                    <div class="toggle-container">
                        <input type="hidden" name="active" :value="active ? '1' : '0'">
                        <div class="toggle-switch" :class="{ 'active': active }" @click="active = !active"></div>
                        <span class="toggle-label" x-text="active ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}'"></span>
                    </div>
                    @if($user->id === auth()->id())
                        <p class="help-text" style="color: #f59e0b;">{{ __('messages.cannot_deactivate_self_warning') }}</p>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('users.index') }}" class="btn-outline">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="btn-gold">{{ __('messages.update_user') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
