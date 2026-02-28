<!-- Mobile Header -->
<div x-data="{ mobileOpen: false }" class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-dark-card border-b border-gold">
    <div class="flex items-center justify-between px-4 h-16">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/ASHA_LOGO-1.png') }}" alt="Asha stables" class="h-8 w-auto">
            <span class="text-lg font-semibold text-gold">Asha Stables</span>
        </a>
        <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-md text-gold hover:bg-gold-dim">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="absolute top-16 left-0 right-0 shadow-lg bg-dark-card border-b border-gold">
        <div class="px-4 py-3 space-y-1">
            @if(auth()->user() && auth()->user()->role === 'admin')
                @foreach([
                    'dashboard' => 'Dashboard',
                    'staff.scan' => 'Member Scan',
                    'members.index' => 'Members',
                    'memberships.index' => 'Memberships',
                    'activities.index' => 'Activities',
                    'membership-activity-limits.index' => 'Activity Limits',
                    'reports.index' => 'Reports',
                    'memberships.renewal.index' => 'Renewal',
                    'staff.printer.config' => 'Printer',
                    'users.index' => 'Users'
                ] as $route => $label)
                    <a href="{{ route(str_contains($route, '.index') ? $route : $route) }}" 
                       class="block px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route)) ? 'text-gold bg-gold-dim' : 'text-gray-200 hover:text-gold hover:bg-gold-dim' }}">
                        {{ $label }}
                    </a>
                @endforeach
            @endif
            
            @if(auth()->user() && auth()->user()->role === 'staff')
                @foreach([
                    'staff.scan' => 'Member Scan',
                    'staff.members.index' => 'Members',
                    'staff.printer.config' => 'Printer'
                ] as $route => $label)
                    <a href="{{ route($route) }}" 
                       class="block px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route)) ? 'text-gold bg-gold-dim' : 'text-gray-200 hover:text-gold hover:bg-gold-dim' }}">
                        {{ $label }}
                    </a>
                @endforeach
            @endif
        </div>
        <div class="px-4 py-3 border-t border-gold">
            <div class="text-sm font-medium text-gold">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:text-gold hover:bg-gold-dim">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-sm text-gray-200 hover:text-gold hover:bg-gold-dim">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Sidebar -->
<aside x-data="{ expanded: localStorage.getItem('sidebarExpanded') === 'true' }" 
       x-init="$watch('expanded', val => { localStorage.setItem('sidebarExpanded', val); $dispatch('sidebar-toggled', val); })"
       :class="expanded ? 'w-64' : 'w-20'"
       class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 overflow-y-auto transition-all duration-300 bg-dark-card border-r border-gold">
    
    <!-- Logo & Toggle -->
    <div class="flex items-center h-20 px-4 border-b border-gold/30">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 flex-1 min-w-0">
            <img src="{{ asset('images/ASHA_LOGO-1.png') }}" alt="Asha stables" class="h-10 w-10 flex-shrink-0 object-contain">
            <span x-show="expanded" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-xl font-semibold whitespace-nowrap text-gold">Asha Stables</span>
        </a>
        <button @click="expanded = !expanded" class="p-2 rounded-lg hover:bg-gold-dim transition-colors flex-shrink-0 text-gold" title="Toggle sidebar">
            <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
            <svg x-show="expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-6 space-y-2">
        @if(auth()->user() && auth()->user()->role === 'admin')
            @php
                $links = [
                    ['route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => __('messages.dashboard')],
                    ['route' => 'staff.scan', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'label' => __('messages.staff_scan')],
                    ['route' => 'members.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => __('messages.members')],
                    ['route' => 'memberships.index', 'icon' => 'M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z', 'label' => __('messages.memberships')],
                    ['route' => 'activities.index', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => __('messages.activities')],
                    ['route' => 'membership-activity-limits.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => __('messages.activity_limits')],
                    ['route' => 'reports.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => __('messages.reports')],
                    ['route' => 'memberships.renewal.index', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'label' => __('messages.renewal')],
                    ['route' => 'staff.printer.config', 'icon' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z', 'label' => __('messages.printer')],
                    ['route' => 'users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m9 5.197v-1a6 6 0 00-3-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0zm8-2a3 3 0 11-6 0 3 3 0 016 0zm-2 8a3 3 0 100 6 3 3 0 000-6z', 'label' => __('messages.users')],
                ];
            @endphp
            
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 group {{ request()->routeIs($link['route']) || request()->routeIs(str_replace('.index', '.*', $link['route'])) ? 'bg-gold-dim text-gold border-l-4 border-gold' : 'text-gray-200 hover:text-gold hover:bg-gold-dim' }}" 
                   title="{{ $link['label'] }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/></svg>
                    <span x-show="expanded" x-transition class="whitespace-nowrap">{{ $link['label'] }}</span>
                </a>
            @endforeach
        @endif

        @if(auth()->user() && auth()->user()->role === 'staff')
             @php
                $staffLinks = [
                   ['route' => 'staff.scan', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'label' => __('messages.staff_scan')],
                   ['route' => 'staff.members.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => __('messages.members')],
                   ['route' => 'staff.printer.config', 'icon' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z', 'label' => __('messages.printer')],
                ];
            @endphp
            @foreach($staffLinks as $link)
                <a href="{{ route($link['route']) }}" 
                   class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium transition-all duration-200 group {{ request()->routeIs($link['route']) || request()->routeIs(str_replace('.index', '.*', $link['route'])) ? 'bg-gold-dim text-gold border-l-4 border-gold' : 'text-gray-200 hover:text-gold hover:bg-gold-dim' }}" 
                   title="{{ $link['label'] }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/></svg>
                    <span x-show="expanded" x-transition class="whitespace-nowrap">{{ $link['label'] }}</span>
                </a>
            @endforeach
        @endif
    </nav>

    <!-- User Section -->
    <div class="px-3 py-4 border-t border-gold/30">
        <div class="flex items-center gap-3 px-3 py-2 mb-2">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 bg-gold-dim text-gold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="expanded" x-transition class="flex-1 min-w-0">
                <div class="text-sm font-medium truncate text-gold">{{ Auth::user()->name }}</div>
                <div class="text-xs truncate text-gray-400">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <!-- Language Switcher -->
        <div x-data="{ langOpen: false }" class="relative mb-2">
            <button @click="langOpen = !langOpen" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm transition-all duration-200 text-gray-200 hover:text-gold hover:bg-gold-dim" title="{{ __('messages.language') }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                <span x-show="expanded" x-transition class="whitespace-nowrap flex-1 text-left">{{ app()->getLocale() == 'lo' ? 'ລາວ' : 'English' }}</span>
            </button>
            <div x-show="langOpen" @click.away="langOpen = false" x-transition class="absolute bottom-full left-0 mb-1 w-full rounded-lg shadow-lg overflow-hidden bg-dark border border-gold">
                <a href="{{ route('language.switch', 'en') }}" class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-gold-dim transition-colors {{ app()->getLocale() == 'en' ? 'font-semibold text-gold' : 'text-gray-200' }}">
                    <span class="w-5 text-center">🇺🇸</span>
                    <span>English</span>
                </a>
                <a href="{{ route('language.switch', 'lo') }}" class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-gold-dim transition-colors {{ app()->getLocale() == 'lo' ? 'font-semibold text-gold' : 'text-gray-200' }}">
                    <span class="w-5 text-center">🇱🇦</span>
                    <span>ລາວ</span>
                </a>
            </div>
        </div>
        
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all duration-200 text-gray-200 hover:text-gold hover:bg-gold-dim" title="{{ __('messages.profile') }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span x-show="expanded" x-transition class="whitespace-nowrap">{{ __('messages.profile') }}</span>
        </a>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm transition-all duration-200 text-gray-200 hover:text-gold hover:bg-gold-dim" title="{{ __('messages.logout') }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span x-show="expanded" x-transition class="whitespace-nowrap">{{ __('messages.logout') }}</span>
            </button>
        </form>
    </div>
</aside>
