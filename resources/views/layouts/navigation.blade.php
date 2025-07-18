<nav x-data="{ open: false }" class="bg-orange-500 border-b border-orange-600">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('bread.png') }}" alt="Toko Roti Dinar Logo" class="h-8 w-32 object-contain">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="url('/')" :active="request()->is('/')" class="text-white hover:text-orange-100">
                        {{ __('Home') }}
                    </x-nav-link>

                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-orange-100">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.product.index')" :active="request()->routeIs('admin.product.*')" class="text-white hover:text-orange-100">
                        {{ __('Product') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.discounts.index')" :active="request()->routeIs('admin.discounts.*')" class="text-white hover:text-orange-100">
                        {{ __('Discount') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.order.index')" :active="request()->routeIs('admin.order.*')" class="text-white hover:text-orange-100">
                        {{ __('Order') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.contact.index')" :active="request()->routeIs('admin.contact.*')" class="text-white hover:text-orange-100">
                        {{ __('Pesan Kontak') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.banners.index')" :active="request()->routeIs('admin.banners.*')" class="text-white hover:text-orange-100">
                        {{ __('Banner') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('admin.notifications.index')" :active="request()->routeIs('admin.notifications.*')" class="text-white hover:text-orange-100">
                        {{ __('Notification') }}
                        @if (Auth::user()->unreadNotifications->count() > 0)
                            <span class="bg-red-600 text-white rounded-full px-2 py-1 text-xs">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-orange-500 hover:text-orange-100 hover:bg-orange-600 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-orange-700 hover:bg-orange-100">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                class="text-orange-700 hover:bg-orange-100">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-orange-100 hover:bg-orange-600 focus:outline-none focus:bg-orange-600 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-orange-500">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-orange-600">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/')" :active="request()->is('/')" class="text-white hover:bg-orange-600">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.product.index')" :active="request()->routeIs('admin.product.*')" class="text-white hover:bg-orange-600">
                {{ __('Product') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.order.index')" :active="request()->routeIs('admin.order.*')" class="text-white hover:bg-orange-600">
                {{ __('Order') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.contact.index')" :active="request()->routeIs('admin.contact.*')" class="text-white hover:bg-orange-600">
                {{ __('Pesan Kontak') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-orange-600">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-orange-100">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-orange-600">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();"
                        class="text-white hover:bg-orange-600">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
