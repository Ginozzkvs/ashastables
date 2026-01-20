<nav x-data="{ open: false }" class="border-b shadow-sm" style="background: #0f1419; border-color: #d4af37;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <img 
                            src="{{ asset('images/ASHA_LOGO-1.png') }}" 
                            alt="Asha sables"
                            class="h-10 w-auto"
                        >
                        <span class="text-xl font-semibold" style="color: #d4af37;">Asha stables</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user() && auth()->user()->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" style="color: #e0e0e0;">
                            Dashboard
                        </x-nav-link>

                        <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')" style="color: #e0e0e0;">
                            Members
                        </x-nav-link>

                        <x-nav-link :href="route('memberships.index')" :active="request()->routeIs('memberships.*')" style="color: #e0e0e0;">
                            Memberships
                        </x-nav-link>

                        <x-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.*')" style="color: #e0e0e0;">
                            Activities
                        </x-nav-link>

                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" style="color: #e0e0e0;">
                            Reports
                        </x-nav-link>

                        <x-nav-link :href="route('memberships.renewal.index')" :active="request()->routeIs('memberships.renewal.*')" style="color: #e0e0e0;">
                            Renewal
                        </x-nav-link>
                    @endif

                    @if(auth()->user() && auth()->user()->role === 'staff')
                        <x-nav-link :href="route('staff.scan')" :active="request()->routeIs('staff.scan')" style="color: #e0e0e0;">
                            {{ __('Staff Scan') }}
                        </x-nav-link>
                    @endif
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md" style="color: #e0e0e0; background: transparent;">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="color: #d4af37;">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out" style="color: #d4af37;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="background: #0f1419;">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user() && auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" style="color: #e0e0e0;">
                    Dashboard
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')" style="color: #e0e0e0;">
                    Members
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('memberships.index')" :active="request()->routeIs('memberships.*')" style="color: #e0e0e0;">
                    Memberships
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.*')" style="color: #e0e0e0;">
                    Activities
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" style="color: #e0e0e0;">
                    Reports
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('memberships.renewal.index')" :active="request()->routeIs('memberships.renewal.*')" style="color: #e0e0e0;">
                    Renewal
                </x-responsive-nav-link>
            @endif

            @if(auth()->user() && auth()->user()->role === 'staff')
                <x-responsive-nav-link :href="route('staff.scan')" :active="request()->routeIs('staff.scan')" style="color: #e0e0e0;">
                    Scan
                </x-responsive-nav-link>
            @endif
        </div>


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1" style="border-top: 1px solid #d4af37;">
            <div class="px-4">
                <div class="font-medium text-base" style="color: #d4af37;">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm" style="color: #9ca3af;">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" style="color: #e0e0e0;">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            style="color: #e0e0e0;">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
