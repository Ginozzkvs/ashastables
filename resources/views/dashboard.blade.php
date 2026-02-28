@extends('layouts.app')

@section('content')
    <div class="dashboard">
        <div class="container mx-auto px-4 sm:px-8 mb-8">
            <div class="text-center mb-12">
                <!-- GEOMETRIC TENT LOGO -->
                <svg class="w-20 h-20 mx-auto mb-4 animate-fade-in-up" viewBox="0 0 100 100" fill="none">
                    <path d="M50 10 L10 90 L90 90 Z" class="stroke-gold" stroke-width="2.5" fill="none"/>
                    <path d="M50 30 L25 80 L75 80 Z" class="stroke-gold" stroke-width="2.5" fill="none"/>
                    <circle cx="50" cy="50" r="8" class="fill-gold"/>
                </svg>
                <h1 class="text-6xl font-serif font-bold text-white mb-2 tracking-tight">ASHA Resort</h1>
                <p class="text-gray-400 text-lg uppercase tracking-widest">Admin Dashboard</p>
            </div>

            <!-- STATISTICS GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Active Members -->
                <div class="bg-dark-card border border-gold p-8 text-center rounded-lg shadow-lg hover:shadow-gold/20 transition-all duration-300 hover:-translate-y-1">
                    <p class="text-xs font-bold tracking-widest text-gray-400 uppercase">Active Members</p>
                    <p class="text-5xl font-bold text-gold my-2">{{ $memberCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Registered accounts</p>
                </div>

                <!-- Sessions Used -->
                <div class="bg-dark-card border border-gold p-8 text-center rounded-lg shadow-lg hover:shadow-gold/20 transition-all duration-300 hover:-translate-y-1">
                    <p class="text-xs font-bold tracking-widest text-gray-400 uppercase">Sessions Used</p>
                    <p class="text-5xl font-bold text-gold my-2">{{ $sessionCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">This month</p>
                </div>

                <!-- Total Reservations -->
                <div class="bg-dark-card border border-gold p-8 text-center rounded-lg shadow-lg hover:shadow-gold/20 transition-all duration-300 hover:-translate-y-1">
                    <p class="text-xs font-bold tracking-widest text-gray-400 uppercase">Reservations</p>
                    <p class="text-5xl font-bold text-gold my-2">{{ $reservationCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Total bookings</p>
                </div>

                <!-- Available Activities -->
                <div class="bg-dark-card border border-gold p-8 text-center rounded-lg shadow-lg hover:shadow-gold/20 transition-all duration-300 hover:-translate-y-1">
                    <p class="text-xs font-bold tracking-widest text-gray-400 uppercase">Available</p>
                    <p class="text-5xl font-bold text-gold my-2">{{ $activityCount ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Activities</p>
                </div>
            </div>

            <!-- RECENT MEMBERS SECTION -->
            <div class="bg-dark-card border border-gold shadow-xl rounded-lg overflow-hidden mb-12 p-8 hover:shadow-gold/10 transition-shadow duration-500">
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                    <h2 class="text-4xl font-serif font-bold text-white">Recent Members</h2>
                    <a href="{{ route('members.create') }}" class="bg-gold text-dark font-semibold uppercase tracking-wider py-3 px-6 rounded hover:bg-gold-light hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300 text-sm">
                        + Add Member
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gold/30">
                                <th class="p-4 font-semibold text-gray-300">Name</th>
                                <th class="p-4 font-semibold text-gray-300">Member ID</th>
                                <th class="p-4 font-semibold text-gray-300">Status</th>
                                <th class="p-4 font-semibold text-gray-300">Membership</th>
                                <th class="p-4 font-semibold text-gray-300">Card UID</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gold/10">
                            @forelse($members ?? [] as $member)
                            <tr class="hover:bg-gold-dim/50 transition-colors duration-200">
                                <td class="p-4 text-gray-200 font-medium">{{ $member->name }}</td>
                                <td class="p-4 text-gray-400 font-mono text-xs">{{ $member->card_id }}</td>
                                <td class="p-4">
                                    <span class="inline-block px-3 py-1 text-xs font-bold tracking-wider border border-green-500 text-green-500 bg-green-500/10 rounded uppercase">
                                        ACTIVE
                                    </span>
                                </td>
                                <td class="p-4 text-gray-300">{{ $member->membership->name ?? 'N/A' }}</td>
                                <td class="p-4 text-gray-400 font-mono text-xs">{{ $member->card_uid ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">No members found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    <a href="{{ route('members.index') }}" class="block w-full text-center border border-gold text-gold font-bold uppercase tracking-wider py-4 rounded hover:bg-gold-dim hover:-translate-y-0.5 transition-all duration-300 text-sm">
                        View All Members
                    </a>
                </div>
            </div>

            <!-- ACTIVITIES GRID -->
            <div class="mb-12">
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                    <h2 class="text-4xl font-serif font-bold text-white">Available Activities</h2>
                    <a href="{{ route('activities.create') }}" class="bg-gold text-dark font-semibold uppercase tracking-wider py-3 px-6 rounded hover:bg-gold-light hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300 text-sm">
                        + New Activity
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($activities ?? [] as $activity)
                    <div class="bg-dark-card border border-gold p-8 rounded-lg shadow-lg hover:shadow-gold/20 transition-all duration-300 hover:-translate-y-1">
                        <h3 class="text-xl font-bold text-white mb-2">{{ $activity->name }}</h3>
                        <p class="text-gray-400 text-sm mb-6 line-clamp-2">{{ $activity->description ?? 'No description available for this activity.' }}</p>
                        <div class="flex justify-between items-center text-sm border-t border-gold/20 pt-4">
                            <span class="text-gray-500">Unit: <span class="text-gray-200 font-semibold border-b border-dotted border-gray-500">{{ $activity->unit ?? 'Session' }}</span></span>
                            <span class="flex items-center gap-2 text-green-500 font-medium bg-green-500/10 px-3 py-1 rounded-full text-xs uppercase tracking-wide">
                                Available <span>✓</span>
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full bg-dark-card border border-gold/30 border-dashed p-12 text-center rounded-lg">
                        <p class="text-gray-400 mb-4">No activities found.</p>
                        <a href="{{ route('activities.create') }}" class="text-gold underline hover:text-gold-light">Create your first activity</a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <a href="{{ route('members.create') }}" class="bg-gold text-dark font-bold uppercase tracking-wider p-6 text-center rounded text-sm hover:bg-gold-light hover:-translate-y-1 hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                    <span class="text-xl">+</span> Add New Member
                </a>
                <a href="{{ route('activities.create') }}" class="border border-gold text-gold font-bold uppercase tracking-wider p-6 text-center rounded text-sm hover:bg-gold-dim hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    Create Activity
                </a>
            </div>

        </div>
    </div>
@endsection
