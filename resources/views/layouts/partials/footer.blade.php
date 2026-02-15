<!-- FOOTER -->
<footer class="mt-16 bg-primary text-white">
    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            {{-- Brand --}}
            <div>
                <h3 class="text-lg font-semibold">My Laravel App</h3>
                <p class="text-sm text-gray-300 mt-3 leading-relaxed">
                    Modern marketplace platform built with Laravel.
                    Clean. Minimal. Scalable.
                </p>
            </div>

            {{-- Navigation --}}
            <div>
                <h4 class="font-semibold mb-3">Navigation</h4>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li>
                        <a href="{{ route('products.index') }}" class="hover:text-white transition">
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stores.index') }}" class="hover:text-white transition">
                            Stores
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('stores.mine') }}" class="hover:text-white transition">
                                My Stores
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            {{-- Account --}}
            <div>
                <h4 class="font-semibold mb-3">Account</h4>
                <ul class="space-y-2 text-sm text-gray-300">
                    @guest
                        <li>
                            <a href="{{ route('login') }}" class="hover:text-white transition">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="hover:text-white transition">
                                Register
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li>
                            <a href="{{ route('profile.edit') }}" class="hover:text-white transition">
                                Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard') }}" class="hover:text-white transition">
                                Dashboard
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="font-semibold mb-3">Contact</h4>
                <p class="text-sm text-gray-300">
                    support@myapp.com
                </p>
                <p class="text-sm text-gray-300 mt-2">
                    Belgrade, Serbia
                </p>
            </div>

        </div>

        {{-- Bottom Line --}}
        {{-- Contact --}}
        <div>
            <h4 class="font-semibold mb-3">Project Info</h4>
            <p class="text-sm text-white/70">
                © {{ date('Y') }} Veselin Sevo 23/22
                <a href="{{ route('about.author') }}" class="hover:underline">
                    --- > See About Author
                </a>

            </p>


        </div>
        <div class="border-t border-primary-light mt-10 pt-6 text-sm text-gray-400 text-center">
            © {{ date('Y') }} My Laravel PHP 2 App. All rights reserved.
        </div>

    </div>
</footer>