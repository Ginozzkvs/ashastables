@extends('layouts.app')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Inter', sans-serif; }
    h1, h2, h3, h4 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; }
    
    body {
        background: #0f1419 !important;
        color: #fff;
        line-height: 1.6;
    }

    /* Override white background from x-app-layout */
    .bg-white {
        background: #0f1419 !important;
    }
    
    .container { max-width: 90rem; margin: 0 auto; padding: 2rem 1rem; }
    @media (min-width: 640px) { .container { padding: 2rem; } }
        
        .header-divider { border-color: #d4af37; }
        
        .card-base {
            background: #1a1f2e;
            border: 1px solid #d4af37;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .card-base:hover { box-shadow: 0 8px 24px rgba(212, 175, 55, 0.15); }
        
        .stat-box {
            background: #1a1f2e;
            border: 1px solid #d4af37;
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #d4af37;
            margin: 0.5rem 0;
        }
        
        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #9ca3af;
            text-transform: uppercase;
        }
        
        .btn-gold {
            background: #d4af37;
            color: #0f1419;
            font-weight: 600;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-block;
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
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-outline:hover {
            background: rgba(212, 175, 55, 0.1);
            transform: translateY(-2px);
        }
        
        .table-row {
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            transition: background 0.3s;
        }
        
        .table-row:hover { background: rgba(212, 175, 55, 0.05); }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            border: 1px solid;
            text-transform: uppercase;
        }
        
        .badge-active { border-color: #10b981; color: #10b981; background: rgba(16, 185, 129, 0.1); }
        .badge-inactive { border-color: #ef4444; color: #ef4444; background: rgba(239, 68, 68, 0.1); }
        
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
        
        .mb-8 { margin-bottom: 2rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mt-6 { margin-top: 1.5rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-1 { margin-top: 0.25rem; }
        .p-8 { padding: 2rem; }
        .p-6 { padding: 1.5rem; }
        .p-4 { padding: 1rem; }
        .w-full { width: 100%; }
        
        .text-xs { font-size: 0.75rem; }
        .text-sm { font-size: 0.875rem; }
        .text-lg { font-size: 1.125rem; }
        .text-2xl { font-size: 1.5rem; }
        .text-4xl { font-size: 2.25rem; }
        .text-6xl { font-size: 3.75rem; }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        
        .text-white { color: #fff; }
        .text-gray-200 { color: #e5e7eb; }
        .text-gray-300 { color: #d1d5db; }
        .text-gray-400 { color: #9ca3af; }
        .text-gray-500 { color: #6b7280; }
        
        .border-b { border-bottom: 1px solid #d4af37; }
        .border-l-2 { border-left: 2px solid; }
        .border-green-500 { border-color: #10b981; }
        .border-red-500 { border-color: #ef4444; }
        
        .overflow-x-auto { overflow-x: auto; }
        .space-y-4 > * + * { margin-top: 1rem; }
        
        /* Respect Tailwind responsive classes */
        .sm\:hidden { display: none !important; }
        @media (min-width: 640px) { .sm\:hidden { display: none !important; } }
    </style>

    <div class="dashboard">
        <div class="container mb-8">
        <div class="text-center mb-8">
            <!-- GEOMETRIC TENT LOGO -->
            <svg class="w-20 h-20 mx-auto mb-4" style="width: 80px; height: 80px; margin: 0 auto 1rem;" viewBox="0 0 100 100" fill="none">
                <path d="M50 10 L10 90 L90 90 Z" stroke="#d4af37" stroke-width="2.5" fill="none"/>
                <path d="M50 30 L25 80 L75 80 Z" stroke="#d4af37" stroke-width="2.5" fill="none"/>
                <circle cx="50" cy="50" r="8" fill="#d4af37"/>
            </svg>
            <h1 class="text-6xl font-bold text-white mb-2">ASHA Resort</h1>
            <p class="text-gray-400 text-lg">Admin Dashboard</p>
        </div>

        <!-- STATISTICS GRID -->
        <div class="grid-4 mb-12">
            <!-- Active Members -->
            <div class="stat-box">
                <p class="stat-label">Active Members</p>
                <p class="stat-number">{{ $memberCount ?? 0 }}</p>
                <p class="text-xs text-gray-500">Registered accounts</p>
            </div>

            <!-- Sessions Used -->
            <div class="stat-box">
                <p class="stat-label">Sessions Used</p>
                <p class="stat-number">{{ $sessionCount ?? 0 }}</p>
                <p class="text-xs text-gray-500">This month</p>
            </div>

            <!-- Total Reservations -->
            <div class="stat-box">
                <p class="stat-label">Reservations</p>
                <p class="stat-number">{{ $reservationCount ?? 0 }}</p>
                <p class="text-xs text-gray-500">Total bookings</p>
            </div>

            <!-- Available Activities -->
            <div class="stat-box">
                <p class="stat-label">Available</p>
                <p class="stat-number">{{ $activityCount ?? 0 }}</p>
                <p class="text-xs text-gray-500">Activities</p>
            </div>
        </div>

        <!-- RECENT MEMBERS SECTION -->
        <div class="card-base p-8 mb-12">
            <div class="flex-between mb-6">
                <h2 class="text-4xl font-bold text-white">Recent Members</h2>
                <a href="{{ route('members.create') }}" class="btn-gold">+ Add Member</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="p-4 font-semibold text-gray-300">Name</th>
                            <th class="p-4 font-semibold text-gray-300">Member ID</th>
                            <th class="p-4 font-semibold text-gray-300">Status</th>
                            <th class="p-4 font-semibold text-gray-300">Membership</th>
                            <th class="p-4 font-semibold text-gray-300">Card UID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members ?? [] as $member)
                        <tr class="table-row">
                            <td class="p-4 text-gray-200">{{ $member->name }}</td>
                            <td class="p-4 text-gray-400 font-mono text-xs">{{ $member->card_id }}</td>
                            <td class="p-4"><span class="badge badge-active">ACTIVE</span></td>
                            <td class="p-4 text-gray-300">{{ $member->membership->name ?? 'N/A' }}</td>
                            <td class="p-4 text-gray-400 font-mono text-xs">{{ $member->card_uid ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr class="table-row">
                            <td colspan="5" class="p-4 text-center text-gray-400">No members found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('members.index') }}" class="btn-outline w-full p-4 text-center text-sm font-bold mt-6">View All Members</a>
        </div>

        <!-- ACTIVITIES GRID -->
        <div class="mb-12">
            <div class="flex-between mb-6">
                <h2 class="text-4xl font-bold text-white">Available Activities</h2>
                <a href="{{ route('activities.create') }}" class="btn-gold">+ New Activity</a>
            </div>

            <div class="grid-2">
                @forelse($activities ?? [] as $activity)
                <div class="card-base p-6">
                    <h3 class="text-lg font-bold text-white mb-2">{{ $activity->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4">{{ $activity->description ?? 'No description' }}</p>
                    <div class="flex-between text-sm">
                        <span class="text-gray-500">Unit: <span class="text-gray-200 font-semibold">{{ $activity->unit ?? 'Session' }}</span></span>
                        <span class="text-gray-500">Available <span class="text-green-500">✓</span></span>
                    </div>
                </div>
                @empty
                <div class="card-base p-6 col-span-full">
                    <p class="text-gray-400">No activities found. <a href="{{ route('activities.create') }}" class="text-yellow-500 underline">Create one</a></p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="grid-2 mb-12">
            <a href="{{ route('members.create') }}" class="btn-gold w-full p-4 text-center text-sm font-bold">Add New Member</a>
            <a href="{{ route('activities.create') }}" class="btn-outline w-full p-4 text-center text-sm font-bold">Create Activity</a>
        </div>

    </div>

@endsection
