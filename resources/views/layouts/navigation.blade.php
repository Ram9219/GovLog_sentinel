<nav x-data="{ open: false }" class="bg-gradient-to-r from-purple-600 to-purple-700 shadow-lg border-b border-purple-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo with Home Link -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <span class="text-2xl">🛡️</span>
                        <span class="text-white font-bold text-lg hidden sm:inline">GovLog Sentinel</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-2 sm:flex">
                    <a href="{{ route('landing') }}" class="text-white hover:bg-purple-500 px-3 py-2 rounded-md text-sm font-medium transition">
                        🏠 Home
                    </a>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'operator')
                        <x-nav-link :href="route('admin.logs.index')" :active="request()->routeIs('admin.logs.*')" class="text-white">
                            📋 Logs
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- User Info Section -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <!-- Role Badge -->
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white bg-opacity-20 text-white">
                    {{ ucfirst(Auth::user()->role) }}
                </span>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-500 hover:bg-purple-400 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <span>👤</span>
                                <div>{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 bg-gray-50">
                            <div class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            @if(Auth::user()->department)
                                <div class="text-xs text-gray-500 mt-1">Dept: {{ Auth::user()->department }}</div>
                            @endif
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            ⚙️ {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                🚪 {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-purple-500 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-purple-600">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('landing') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-purple-500 transition">
                🏠 Home
            </a>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white">
                📊 {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'operator')
                <x-responsive-nav-link :href="route('admin.logs.index')" :active="request()->routeIs('admin.logs.*')" class="text-white">
                    📋 {{ __('Logs') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-purple-500">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-purple-200">{{ Auth::user()->email }}</div>
                <div class="font-medium text-xs text-purple-100 mt-1">Role: {{ ucfirst(Auth::user()->role) }}</div>
                @if(Auth::user()->department)
                    <div class="font-medium text-xs text-purple-100">Dept: {{ Auth::user()->department }}</div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-purple-500 transition">
                    ⚙️ {{ __('Profile Settings') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-white hover:bg-purple-500 transition">
                        🚪 {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
