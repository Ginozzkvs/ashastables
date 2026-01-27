@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold" style="color: #d4af37;">{{ __('messages.profile') }}</h1>
            <p class="mt-1 text-sm" style="color: #9ca3af;">{{ __('messages.manage_profile_settings') }}</p>
        </div>
    </div>

    <!-- Update Profile Information -->
    <div style="background: #1a1f2e; border: 1px solid #d4af37; border-radius: 0;" class="p-6">
        @include('profile.partials.update-profile-information-form')
    </div>

    <!-- Update Password -->
    <div style="background: #1a1f2e; border: 1px solid #d4af37; border-radius: 0;" class="p-6">
        @include('profile.partials.update-password-form')
    </div>

    <!-- Delete Account -->
    <div style="background: #1a1f2e; border: 1px solid #d4af37; border-radius: 0;" class="p-6">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
