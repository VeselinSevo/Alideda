<nav x-data="{ open: false }" class="bg-surface border-b border-border">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left side -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('stores.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-primary" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('stores.index')" :active="request()->routeIs('stores.*')">
                        Stores
                    </x-nav-link>

                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        Products
                    </x-nav-link>

                    {{-- Cart: visible in top nav (best UX) --}}
                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                        <span class="inline-flex items-center gap-2">
                            Cart

                            {{-- Optional badge (only if $cartCount exists and > 0) --}}
                            @isset($cartCount)
                                @if($cartCount > 0)
                                    <span
                                        class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-accent text-white text-xs leading-none">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            @endisset
                        </span>
                    </x-nav-link>
                    <x-nav-link :href="route('contact.create')" :active="request()->routeIs('contact.*')">
                        Contact
                    </x-nav-link>
                </div>
            </div>


            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                @auth
                    {{-- Admin link: keep it visible if admin --}}
                    @if(auth()->user()->is_admin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                            Admin
                        </x-nav-link>
                    @endif

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-muted bg-surface hover:text-text focus:outline-none transition">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('stores.mine')">
                                My Stores
                            </x-dropdown-link>

                            @if(auth()->user()->stores()->exists())
                                <x-dropdown-link :href="route('owner.orders.index')">
                                    Orders From My Stores
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link :href="route('orders.index')">
                                My Orders
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('cart.index')">
                                Cart
                                @isset($cartCount)
                                    @if($cartCount > 0)
                                        <span class="ml-2 text-xs text-muted">
                                            ({{ $cartCount }})
                                        </span>
                                    @endif
                                @endisset
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="w-full text-left px-4 py-2 text-sm font-medium rounded-md
                               bg-danger text-white
                               hover:bg-danger/90
                               transition">
                                    Log Out
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm text-secondary underline">
                        Log in
                    </a>

                    <a href="{{ route('register') }}" class="ml-4 text-sm text-secondary underline">
                        Register
                    </a>
                @endguest

            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-muted hover:text-text hover:bg-background focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('stores.index')" :active="request()->routeIs('stores.*')">
                Stores
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                Products
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                Cart
                @isset($cartCount)
                    @if($cartCount > 0)
                        <span
                            class="ml-2 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-accent text-white text-xs leading-none">
                            {{ $cartCount }}
                        </span>
                    @endif
                @endisset
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('contact.create')" :active="request()->routeIs('contact.*')">
                Contact
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->is_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                        Admin
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-border">

            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-text">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="font-medium text-sm text-muted">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profile
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('stores.mine')">
                        My Stores
                    </x-responsive-nav-link>

                    @if(auth()->user()->stores()->exists())
                        <x-responsive-nav-link :href="route('owner.orders.index')">
                            Orders From My Stores
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('orders.index')">
                        My Orders
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('cart.index')">
                        Cart
                        @isset($cartCount)
                            @if($cartCount > 0)
                                <span class="ml-2 text-xs text-muted">
                                    ({{ $cartCount }})
                                </span>
                            @endif
                        @endisset
                    </x-responsive-nav-link>


                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth

            @guest
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        Log in
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register')">
                        Register
                    </x-responsive-nav-link>
                </div>
            @endguest

        </div>
    </div>
</nav>
