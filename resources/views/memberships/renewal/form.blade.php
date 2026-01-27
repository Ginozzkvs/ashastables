@extends('layouts.app')

@section('content')
<div class="py-12" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('memberships.renewal.index') }}" class="inline-flex items-center gap-2 mb-6 transition-colors" style="color: #d4af37;">
            ← {{ __('messages.back_to_renewals') }}
        </a>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2" style="color: #d4af37;">{{ __('messages.renew_membership') }}</h1>
            <p style="color: #9ca3af;">{{ __('messages.member') }}: <strong style="color: #e0e0e0;">{{ $member->name }}</strong></p>
        </div>

        <!-- Member Info Card -->
        <div class="p-6 rounded-lg mb-8" style="background: #1a1f2e; border: 1px solid #d4af37;">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p style="color: #9ca3af;" class="text-sm mb-1">{{ __('messages.card_id') }}</p>
                    <p class="text-lg font-semibold" style="color: #e0e0e0;">{{ $member->card_id }}</p>
                </div>
                <div>
                    <p style="color: #9ca3af;" class="text-sm mb-1">{{ __('messages.current_membership') }}</p>
                    <p class="text-lg font-semibold" style="color: #e0e0e0;">{{ $member->membership->name }}</p>
                </div>
                <div>
                    <p style="color: #9ca3af;" class="text-sm mb-1">{{ __('messages.phone') }}</p>
                    <p class="text-lg font-semibold" style="color: #e0e0e0;">{{ $member->phone ?? __('messages.na') }}</p>
                </div>
                <div>
                    <p style="color: #9ca3af;" class="text-sm mb-1">{{ __('messages.email') }}</p>
                    <p class="text-lg font-semibold" style="color: #e0e0e0;">{{ $member->email ?? __('messages.na') }}</p>
                </div>
            </div>
            @if ($member->isExpired())
                <div class="mt-6 p-4 rounded-lg" style="background: #7f1d1d; border-left: 4px solid #ef4444;">
                    <p style="color: #fca5a5;" class="font-medium">⚠️ {{ __('messages.membership_expired') }}</p>
                    <p style="color: #9ca3af;" class="text-sm mt-1">{{ __('messages.expired_on') }}: {{ $member->expiry_date->format('M d, Y') }}</p>
                </div>
            @elseif ($member->isExpiring())
                <div class="mt-6 p-4 rounded-lg" style="background: #92400e; border-left: 4px solid #fcd34d;">
                    <p style="color: #fcd34d;" class="font-medium">⏰ {{ __('messages.membership_expiring_soon') }}</p>
                    <p style="color: #9ca3af;" class="text-sm mt-1">{{ __('messages.expires_on') }}: {{ $member->expiry_date->format('M d, Y') }} ({{ $member->daysUntilExpiry() }} {{ __('messages.days') }})</p>
                </div>
            @endif
        </div>

        <!-- Renewal Form -->
        <form action="{{ route('memberships.renewal.renew', $member->card_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 rounded-lg" style="background: #1a1f2e; border: 1px solid #d4af37;">
                <!-- Membership Selection -->
                <div class="mb-6">
                    <label for="membership_id" class="block text-sm font-medium mb-2" style="color: #d4af37;">
                        {{ __('messages.select_membership_type') }} *
                    </label>
                    <select name="membership_id" id="membership_id" required class="w-full px-4 py-3 rounded-lg border" style="background: #0f1419; border-color: #d4af37; color: #e0e0e0;" onchange="updatePrice()">
                        <option value="">-- {{ __('messages.choose_membership') }} --</option>
                        @foreach ($memberships as $membership)
                            <option value="{{ $membership->id }}" data-price="{{ $membership->price }}" {{ $member->membership_id == $membership->id ? 'selected' : '' }}>
                                {{ $membership->name }} - ${{ number_format($membership->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('membership_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Display -->
                <div class="mb-6 p-4 rounded-lg" style="background: #0f1419; border: 1px solid #d4af37;">
                    <p style="color: #9ca3af;" class="text-sm mb-1">{{ __('messages.membership_price') }}</p>
                    <p class="text-2xl font-bold" style="color: #d4af37;">
                        $<span id="priceDisplay">{{ number_format($member->membership->price ?? 0, 2) }}</span>
                    </p>
                </div>

                <!-- Expiry Date with Calendar Picker -->
                <div class="mb-6">
                    <label for="expiry_date" class="block text-sm font-medium mb-2" style="color: #d4af37;">
                        {{ __('messages.new_expiry_date') }} *
                    </label>
                    <div style="position: relative;">
                        <input type="date" name="expiry_date" id="expiry_date" required class="w-full px-4 py-3 rounded-lg border transition-all" style="background: #0f1419; border-color: #d4af37; color: #e0e0e0; cursor: pointer; font-size: 1rem;" min="{{ now()->addDay()->format('Y-m-d') }}" onchange="updateDays()" onfocus="this.style.borderColor='#fcd34d'; this.style.boxShadow='0 0 0 3px rgba(212, 175, 55, 0.2)'" onblur="this.style.borderColor='#d4af37'; this.style.boxShadow='none'">
                        <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: #d4af37; font-size: 1.25rem;"></span>
                    </div>
                    <p style="color: #9ca3af;" class="text-sm mt-2">{{ __('messages.click_to_select_date') }}</p>
                    @error('expiry_date')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Days Valid Display -->
                <div class="mb-6 p-4 rounded-lg" style="background: #0f1419; border: 1px solid #d4af37;">
                    <p style="color: #9ca3af;" class="text-sm mb-1">{{ __('messages.membership_duration') }}</p>
                    <p class="text-lg font-semibold" style="color: #e0e0e0;">
                        <span id="daysDisplay">0</span> {{ __('messages.days') }}
                    </p>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium mb-2" style="color: #d4af37;">
                        {{ __('messages.notes_optional') }}
                    </label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 rounded-lg border" style="background: #0f1419; border-color: #d4af37; color: #e0e0e0;" placeholder="{{ __('messages.add_renewal_notes') }}"></textarea>
                    @error('notes')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium transition-colors" style="background: #d4af37; color: #0f1419;">
                        {{ __('messages.confirm_renewal') }}
                    </button>
                    <a href="{{ route('memberships.renewal.index') }}" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background: #404854; color: #e0e0e0;">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const dateInput = document.getElementById('expiry_date');
    const daysDisplay = document.getElementById('daysDisplay');
    const membershipSelect = document.getElementById('membership_id');
    const priceDisplay = document.getElementById('priceDisplay');

    // Membership duration data
    const membershipDurations = {
        @foreach ($memberships as $membership)
            {{ $membership->id }}: {{ $membership->duration_days ?? 365 }},
        @endforeach
    };

    // Update price and expiry date when membership changes
    function updatePrice() {
        const selected = membershipSelect.options[membershipSelect.selectedIndex];
        const price = selected.getAttribute('data-price') || '0';
        const membershipId = membershipSelect.value;
        priceDisplay.textContent = parseFloat(price).toFixed(2);
        
        // Set default expiry date based on membership duration
        if (membershipId && membershipDurations[membershipId]) {
            const durationDays = membershipDurations[membershipId];
            const expiryDate = new Date();
            expiryDate.setDate(expiryDate.getDate() + durationDays);
            
            const year = expiryDate.getFullYear();
            const month = String(expiryDate.getMonth() + 1).padStart(2, '0');
            const day = String(expiryDate.getDate()).padStart(2, '0');
            
            dateInput.value = `${year}-${month}-${day}`;
        }
        
        updateDays();
    }

    // Update days when date changes
    dateInput.addEventListener('change', updateDays);
    membershipSelect.addEventListener('change', updatePrice);

    function updateDays() {
        if (!dateInput.value) return;
        
        const expiryDate = new Date(dateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        expiryDate.setHours(0, 0, 0, 0);
        
        const diffTime = expiryDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        daysDisplay.textContent = Math.max(0, diffDays);
    }

    // Initialize on page load
    updatePrice();

</script>
@endsection
