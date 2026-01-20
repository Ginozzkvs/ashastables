@extends('layouts.app')

@section('content')
<div class="py-12" style="background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2" style="color: #d4af37;">Membership Renewal Management</h1>
            <p style="color: #9ca3af;">Manage expiring and expired member memberships</p>
        </div>

        <!-- Messages -->
        @if ($message = Session::get('success'))
            <div class="mb-6 p-4 rounded-lg" style="background: #1a1f2e; border-left: 4px solid #10b981; color: #10b981;">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-6 p-4 rounded-lg" style="background: #1a1f2e; border-left: 4px solid #ef4444; color: #ef4444;">
                {{ $message }}
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="mb-6 flex gap-4 border-b" style="border-color: #d4af37;">
            <button onclick="showTab('expiring')" id="expiring-tab" class="px-4 py-3 font-medium transition-colors" style="color: #d4af37; border-bottom: 3px solid #d4af37;">
                🔔 Expiring Soon ({{ $expiringMembers->total() }})
            </button>
            <button onclick="showTab('expired')" id="expired-tab" class="px-4 py-3 font-medium transition-colors" style="color: #9ca3af;">
                ❌ Expired ({{ $expiredMembers->total() }})
            </button>
            <button onclick="showTab('stats')" id="stats-tab" class="px-4 py-3 font-medium transition-colors" style="color: #9ca3af;">
                📊 Statistics
            </button>
        </div>

        <!-- Expiring Tab -->
        <div id="expiring" class="tab-content">
            @if ($expiringMembers->count() > 0)
                <div class="grid gap-4 mb-6">
                    @foreach ($expiringMembers as $member)
                        <div class="p-6 rounded-lg transition-transform hover:scale-102" style="background: #1a1f2e; border: 1px solid #d4af37;">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold" style="color: #d4af37;">{{ $member->name }}</h3>
                                    <p style="color: #9ca3af;" class="text-sm">Card: {{ $member->card_id }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium" style="background: #92400e; color: #fcd34d;">
                                    {{ $member->daysUntilExpiry() }} days
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p style="color: #9ca3af;" class="text-sm">Membership</p>
                                    <p class="font-semibold" style="color: #e0e0e0;">{{ $member->membership->name }}</p>
                                </div>
                                <div>
                                    <p style="color: #9ca3af;" class="text-sm">Expires</p>
                                    <p class="font-semibold" style="color: #fcd34d;">{{ $member->expiry_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p style="color: #9ca3af;" class="text-sm">Price</p>
                                    <p class="font-semibold" style="color: #e0e0e0;">${{ number_format($member->membership->price, 2) }}</p>
                                </div>
                            </div>

                            <a href="{{ route('memberships.renewal.form', $member->card_id) }}" class="inline-block px-4 py-2 rounded-lg font-medium transition-colors" style="background: #d4af37; color: #0f1419;">
                                Renew Now
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $expiringMembers->links() }}
                </div>
            @else
                <div class="p-8 rounded-lg text-center" style="background: #1a1f2e;">
                    <p class="text-lg" style="color: #9ca3af;">✓ No members with expiring memberships</p>
                </div>
            @endif
        </div>

        <!-- Expired Tab -->
        <div id="expired" class="tab-content hidden">
            @if ($expiredMembers->count() > 0)
                <div class="grid gap-4 mb-6">
                    @foreach ($expiredMembers as $member)
                        <div class="p-6 rounded-lg transition-transform hover:scale-102" style="background: #1a1f2e; border: 1px solid #ef4444;">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold" style="color: #ef4444;">{{ $member->name }}</h3>
                                    <p style="color: #9ca3af;" class="text-sm">Card: {{ $member->card_id }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium" style="background: #7f1d1d; color: #fca5a5;">
                                    Expired {{ abs($member->daysUntilExpiry()) }} days ago
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p style="color: #9ca3af;" class="text-sm">Membership</p>
                                    <p class="font-semibold" style="color: #e0e0e0;">{{ $member->membership->name }}</p>
                                </div>
                                <div>
                                    <p style="color: #9ca3af;" class="text-sm">Expired</p>
                                    <p class="font-semibold" style="color: #ef4444;">{{ $member->expiry_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p style="color: #9ca3af;" class="text-sm">Price</p>
                                    <p class="font-semibold" style="color: #e0e0e0;">${{ number_format($member->membership->price, 2) }}</p>
                                </div>
                            </div>

                            <a href="{{ route('memberships.renewal.form', $member->card_id) }}" class="inline-block px-4 py-2 rounded-lg font-medium transition-colors" style="background: #ef4444; color: white;">
                                Renew Now
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $expiredMembers->links('pagination::bootstrap-4', ['paginator' => $expiredMembers, 'path' => route('memberships.renewal.index'), 'query' => request()->query(), 'fragment' => 'expired']) }}
                </div>
            @else
                <div class="p-8 rounded-lg text-center" style="background: #1a1f2e;">
                    <p class="text-lg" style="color: #9ca3af;">✓ No members with expired memberships</p>
                </div>
            @endif
        </div>

        <!-- Statistics Tab -->
        <div id="stats" class="tab-content hidden">
            <div class="grid grid-cols-4 gap-6 mb-6">
                <div class="p-6 rounded-lg text-center" style="background: #1a1f2e; border: 1px solid #d4af37;">
                    <p style="color: #9ca3af;" class="text-sm mb-2">Total Members</p>
                    <p class="text-4xl font-bold" style="color: #d4af37;">
                        @php
                            echo \App\Models\Member::where('active', true)->count();
                        @endphp
                    </p>
                </div>

                <div class="p-6 rounded-lg text-center" style="background: #1a1f2e; border: 1px solid #fcd34d;">
                    <p style="color: #9ca3af;" class="text-sm mb-2">Expiring (30 days)</p>
                    <p class="text-4xl font-bold" style="color: #fcd34d;">
                        @php
                            echo \App\Models\Member::where('active', true)
                                ->where('expiry_date', '<=', now()->addDays(30))
                                ->where('expiry_date', '>', now())
                                ->count();
                        @endphp
                    </p>
                </div>

                <div class="p-6 rounded-lg text-center" style="background: #1a1f2e; border: 1px solid #ef4444;">
                    <p style="color: #9ca3af;" class="text-sm mb-2">Expired</p>
                    <p class="text-4xl font-bold" style="color: #ef4444;">
                        @php
                            echo \App\Models\Member::where('active', true)
                                ->where('expiry_date', '<=', now())
                                ->count();
                        @endphp
                    </p>
                </div>

                <div class="p-6 rounded-lg text-center" style="background: #1a1f2e; border: 1px solid #10b981;">
                    <p style="color: #9ca3af;" class="text-sm mb-2">Revenue at Risk</p>
                    <p class="text-4xl font-bold" style="color: #10b981;">
                        $@php
                            $risk = \App\Models\Member::where('active', true)
                                ->where('expiry_date', '<=', now()->addDays(30))
                                ->where('expiry_date', '>', now())
                                ->with('membership')
                                ->get()
                                ->sum(fn($m) => $m->membership->price ?? 0);
                            echo number_format($risk, 0);
                        @endphp
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Remove active styling from all buttons
        document.querySelectorAll('[id$="-tab"]').forEach(btn => {
            btn.style.color = '#9ca3af';
            btn.style.borderBottom = 'none';
        });

        // Show selected tab
        document.getElementById(tabName).classList.remove('hidden');

        // Add active styling to clicked button
        document.getElementById(tabName + '-tab').style.color = '#d4af37';
        document.getElementById(tabName + '-tab').style.borderBottom = '3px solid #d4af37';
    }
</script>
@endsection
