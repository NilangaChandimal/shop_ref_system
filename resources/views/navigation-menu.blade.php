<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-600 to-blue-800 border-b border-blue-6c82b3 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transition duration-300 transform hover:scale-105">
                        <img src="{{ asset('images/mc.png') }}" alt="Logo" class="block h-10 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    @foreach([
                        ['dashboard', 'Dashboard'],
                        ['sales.create', 'POS'],
                        ['products.index', 'Products'],
                        ['products.discounts', 'Discount'],
                        ['customers.index', 'Customer'],
                        ['sales.index', 'Sales'],
                        ['returns.index', 'Returns'],
                        ['products.display', 'Display']
                    ] as [$route, $label])
                        <x-nav-link
                            href="{{ route($route) }}"
                            :active="request()->routeIs($route)"
                            class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:bg-blue-700"
                        >
                            {{ __($label) }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white hover:text-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-150 ease-in-out">
                                        {{ Auth::user()->currentTeam->name }}
                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <button class="flex items-center text-sm font-medium text-white hover:text-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <div class="bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}"
                                        @click.prevent="$root.submit();"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach([
                ['dashboard', 'Dashboard'],
                ['sales.create', 'POS'],
                ['products.index', 'Products'],
                ['products.discounts', 'Discount'],
                ['customers.index', 'Customer'],
                ['sales.index', 'Sales'],
                ['returns.index', 'Returns'],
                ['products.display', 'Display']
            ] as [$route, $label])
                <x-responsive-nav-link
                    href="{{ route($route) }}"
                    :active="request()->routeIs($route)"
                    class="block px-3 py-2 text-base font-medium text-white hover:text-blue-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                >
                    {{ __($label) }}
                </x-responsive-nav-link>
            @endforeach
        </div>
    </div>
</nav>
