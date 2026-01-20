<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASHA Resort Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.15);
        }
        
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
        }
        
        .btn-outline:hover {
            background: rgba(212, 175, 55, 0.1);
            transform: translateY(-2px);
        }
        
        .table-header {
            background: rgba(212, 175, 55, 0.1);
            border-bottom: 1px solid #d4af37;
        }
        
        .table-row {
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            transition: background 0.3s;
        }
        
        .table-row:hover {
            background: rgba(212, 175, 55, 0.05);
        }
        
        .status-active {
            color: #10b981;
            font-weight: 700;
        }
        
        .status-inactive {
            color: #ef4444;
            font-weight: 700;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            border: 1px solid;
            text-transform: uppercase;
        }
        
        .badge-active {
            border-color: #10b981;
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }
        
        .badge-inactive {
            border-color: #ef4444;
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }
    </style>
</head>

<body class="min-h-screen p-4 sm:p-8">

    <!-- HEADER -->
    <div class="max-w-6xl mx-auto mb-12">
        <div class="text-center mb-8">
            <!-- GEOMETRIC TENT LOGO -->
            <svg class="w-20 h-20 mx-auto mb-4" viewBox="0 0 100 100" fill="none">
                <path d="M50 10 L10 90 L90 90 Z" stroke="#d4af37" stroke-width="2.5" fill="none"/>
                <path d="M50 30 L25 80 L75 80 Z" stroke="#d4af37" stroke-width="2.5" fill="none"/>
                <line x1="50" y1="10" x2="50" y2="90" stroke="#d4af37" stroke-width="2"/>
                <path d="M20 70 Q15 75 20 80" stroke="#d4af37" stroke-width="2" fill="none" stroke-linecap="round"/>
                <path d="M80 70 Q85 75 80 80" stroke="#d4af37" stroke-width="2" fill="none" stroke-linecap="round"/>
            </svg>
        </div>
        
        <div class="header-divider border-b pb-8 text-center">
            <h1 class="text-6xl font-bold text-white mb-3" style="letter-spacing: -2px;">ASHA</h1>
            <p class="text-gray-300 text-sm font-semibold tracking-widest mb-1">EQUESTRIAN RESORT</p>
            <p class="text-gray-500 text-xs font-medium tracking-widest">ADMINISTRATION DASHBOARD</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-6xl mx-auto">

        <!-- WELCOME SECTION -->
        <div class="card-base rounded-none p-8 mb-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-widest font-bold mb-2" style="color: #d4af37;">Welcome</p>
                    <h2 class="text-4xl font-bold text-white mb-2" style="letter-spacing: -1px;">Dashboard</h2>
                    <p class="text-gray-400 text-sm">Administrator, {{ auth()->user()->name ?? 'Guest' }}</p>
                </div>
                <svg class="w-12 h-12" viewBox="0 0 100 100" fill="none">
                    <path d="M50 20 L20 80 L80 80 Z" stroke="#d4af37" stroke-width="2.5" fill="none" stroke-linejoin="round"/>
                    <line x1="50" y1="20" x2="50" y2="80" stroke="#d4af37" stroke-width="2"/>
                </svg>
            </div>
            <p class="text-gray-300 text-sm" style="line-height: 1.6;">
                Manage members, activities, sessions, and staff operations. View real-time activity logs and system statistics.
            </p>
        </div>

        <!-- STATISTICS SECTION -->
        <div class="mb-8">
            <p class="text-xs uppercase tracking-widest font-bold mb-6" style="color: #d4af37;">System Overview</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Members -->
                <div class="stat-box">
                    <p class="stat-label">Active Members</p>
                    <p class="stat-number">{{ $memberCount ?? 247 }}</p>
                    <p class="text-gray-400 text-xs">+12 this month</p>
                </div>

                <!-- Active Sessions -->
                <div class="stat-box">
                    <p class="stat-label">Sessions Booked</p>
                    <p class="stat-number">{{ $sessionCount ?? '1,840' }}</p>
                    <p class="text-gray-400 text-xs">+156 this week</p>
                </div>

                <!-- Available Activities -->
                <div class="stat-box">
                    <p class="stat-label">Activities</p>
                    <p class="stat-number">{{ $activityCount ?? 18 }}</p>
                    <p class="text-gray-400 text-xs">All active</p>
                </div>

                <!-- Staff Members -->
                <div class="stat-box">
                    <p class="stat-label">Staff Members</p>
                    <p class="stat-number">{{ $staffCount ?? 24 }}</p>
                    <p class="text-gray-400 text-xs">On duty: 18</p>
                </div>
            </div>
        </div>

        <!-- MEMBERS SECTION -->
        <div class="card-base rounded-none p-8 mb-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-widest font-bold mb-2" style="color: #d4af37;">Member Management</p>
                    <h3 class="text-2xl font-bold text-white" style="letter-spacing: -1px;">Recent Members</h3>
                </div>
                <a href="{{ route('members.index') }}" class="btn-gold px-6 py-2 text-sm rounded-none inline-block">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="table-header">
                            <th class="text-left px-4 py-3 text-xs font-bold tracking-widest uppercase">Name</th>
                            <th class="text-left px-4 py-3 text-xs font-bold tracking-widest uppercase">Member ID</th>
                            <th class="text-left px-4 py-3 text-xs font-bold tracking-widest uppercase">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-bold tracking-widest uppercase">Valid Until</th>
                            <th class="text-left px-4 py-3 text-xs font-bold tracking-widest uppercase">Sessions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members ?? [] as $member)
                        <tr class="table-row">
                            <td class="px-4 py-4 text-gray-200">{{ $member->name ?? '-' }}</td>
                            <td class="px-4 py-4 text-gray-400 font-mono text-xs">{{ $member->id ?? '-' }}</td>
                            <td class="px-4 py-4">
                                <span class="badge {{ $member->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $member->status === 'active' ? 'ACTIVE' : 'EXPIRED' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-gray-300">{{ $member->expiry_date?->format('M d, Y') ?? '-' }}</td>
                            <td class="px-4 py-4 text-gray-300">{{ $member->sessions_used ?? '0' }}/{{ $member->sessions_limit ?? '0' }}</td>
                        </tr>
                        @empty
                        <tr class="table-row">
                            <td colspan="5" class="px-4 py-4 text-center text-gray-400">No members found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ACTIVITIES SECTION -->
        <div class="card-base rounded-none p-8 mb-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <p class="text-xs uppercase tracking-widest font-bold mb-2" style="color: #d4af37;">Activity Management</p>
                    <h3 class="text-2xl font-bold text-white" style="letter-spacing: -1px;">Program Activities</h3>
                </div>
                <a href="{{ route('activities.create') }}" class="btn-gold px-6 py-2 text-sm rounded-none inline-block">Add Activity</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse($activities ?? [] as $activity)
                <div class="border border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-white mb-2">{{ $activity->name ?? 'Activity' }}</h4>
                    <p class="text-gray-400 text-sm mb-4">{{ $activity->description ?? 'Activity description' }}</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 tracking-widest font-bold">CAPACITY</p>
                            <p class="text-lg font-bold text-gray-200">{{ $activity->current_capacity ?? '0' }}/{{ $activity->max_capacity ?? '0' }}</p>
                        </div>
                        <span class="badge badge-active">ACTIVE</span>
                    </div>
                </div>
                @empty
                <div class="border border-gray-700 p-6">
                    <p class="text-gray-400 text-sm text-center col-span-2">No activities available</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- RECENT ACTIVITY LOG -->
        <div class="card-base rounded-none p-8 mb-8">
            <div class="mb-6">
                <p class="text-xs uppercase tracking-widest font-bold mb-2" style="color: #d4af37;">System Logs</p>
                <h3 class="text-2xl font-bold text-white" style="letter-spacing: -1px;">Recent Activity</h3>
            </div>

            <div class="space-y-4">
                @forelse($logs ?? [] as $log)
                <div class="border-l-2 {{ $log->success ? 'border-green-500' : 'border-red-500' }} pl-4 py-2">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-200 font-semibold text-sm">{{ $log->message ?? 'Activity Log' }}</p>
                            <p class="text-gray-400 text-xs mt-1">{{ $log->description ?? '-' }}</p>
                        </div>
                        <p class="text-gray-500 text-xs">{{ $log->created_at->format('Y-m-d H:i') ?? '-' }}</p>
                    </div>
                    <span class="{{ $log->success ? 'status-active' : 'status-inactive' }} text-xs inline-block mt-2">
                        {{ $log->success ? 'SUCCESS' : 'FAILED' }}
                    </span>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center">No recent activity logs</p>
                @endforelse
            </div>

            <a href="{{ route('logs.index') }}" class="btn-outline px-6 py-3 text-sm rounded-none w-full mt-6 inline-block text-center">View Full Logs</a>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
            <a href="{{ route('members.index') }}" class="btn-gold px-8 py-4 text-sm rounded-none font-bold text-center">Manage Members</a>
            <a href="{{ route('reports.index') }}" class="btn-outline px-8 py-4 text-sm rounded-none font-bold text-center">View Reports</a>
        </div>

    </div>

</body>
</html>
