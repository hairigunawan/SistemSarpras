<nav class="bg-white border-b border-gray-200 py-3">
    <div class="flex mx-5 justify-between items-center">

        <div class="flex gap-2 items-center">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('storage/images/TI.png') }}" alt="Logo TI" class="w-10 h-10 object-contain">
                <img src="{{ asset('storage/images/politala.png') }}" alt="Logo Politala" class="w-10 h-10 object-contain">
            </div>
            <h1 class="text-xl font-semibold text-gray-800">SIMPERSITE.</h1>
        </div>

        <!-- Menu Navigasi -->
        <div class="flex justify-end px-6 py-3 gap-10 items-center">
            <ul class="hidden md:flex space-x-8">
                <li>
                    <a href="{{ route('public.beranda.index') }}"
                       class="text-sm font-normal transition-colors duration-200
                       {{ request()->routeIs('public.beranda.*') ? 'text-[#179ACE]' : 'text-gray-600 hover:text-[#0E7CBA]' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('public.peminjaman.daftarpeminjaman') }}"
                       class="text-sm font-normal transition-colors duration-200
                       {{ request()->routeIs('public.peminjaman.*') ? 'text-[#179ACE]' : 'text-gray-600 hover:text-[#0E7CBA]' }}">
                        Peminjaman
                    </a>
                </li>
                <li>
                    <a href="{{ route('public.sarana_perasarana.halamansarpras') }}"
                       class="text-sm font-normal transition-colors duration-200
                       {{ request()->routeIs('public.sarana_perasarana.*') ? 'text-[#179ACE]' : 'text-gray-600 hover:text-[#0E7CBA]' }}">
                        Sarana & Prasarana
                    </a>
                </li>
            </ul>

            <!-- User Section -->
            <div class="flex items-center">
                @guest
                    <a href="{{ route('login') }}"
                       class="text-sm font-normal text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md transition-colors">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="text-sm ml-2 border border-gray-300 py-1 px-4 rounded-full font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                            Register
                        </a>
                    @endif
                @else
                    <!-- Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center gap-2 bg-white rounded-full pl-2 pr-4 py-1.5 hover:bg-gray-50 transition">
                            @if(Auth::user()->avatar)
                                @if(str_starts_with(Auth::user()->avatar, 'http'))
                                    <img src="{{ Auth::user()->avatar }}"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'32\' height=\'32\' viewBox=\'0 0 32 32\'%3E%3Crect width=\'32\' height=\'32\' fill=\'%23e5e7eb\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%236b7280\' font-size=\'16\' font-family=\'Arial\'%3E{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}%3C/text%3E%3C/svg%3E';"
                                         alt="Profile"
                                         class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <img src="{{ asset(Auth::user()->avatar) }}"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'32\' height=\'32\' viewBox=\'0 0 32 32\'%3E%3Crect width=\'32\' height=\'32\' fill=\'%23e5e7eb\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%236b7280\' font-size=\'16\' font-family=\'Arial\'%3E{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}%3C/text%3E%3C/svg%3E';"
                                         alt="Profile"
                                         class="h-8 w-8 rounded-full object-cover">
                                @endif
                            @else
                                <div class="h-8 w-8 rounded-full border flex items-center justify-center bg-gray-200 text-gray-700">
                                    <span class="text-sm font-semibold">
                                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <span class="text-sm text-gray-800 font-medium">{{ Str::limit(Auth::user()->nama, 18) }}</span>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-gray-100 divide-y divide-gray-100 focus:outline-none z-50">

                            <!-- Info -->
                            <div class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->nama }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            @if (Auth::user()->userRole && Auth::user()->userRole->nama_role == 'Admin')
                                <!-- Menu Links -->
                                <div class="py-1">
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a1 1 0 011 1v1h3a2 2 0 012 2v3h1a1 1 0 110 2h-1v3a2 2 0 01-2 2h-3v1a1 1 0 11-2 0v-1H7a2 2 0 01-2-2v-3H4a1 1 0 110-2h1V6a2 2 0 012-2h3V3a1 1 0 011-1zM8 6v8h4V6H8z"/>
                                        </svg>
                                        Dashboard Admin
                                    </a>
                                </div>
                            @else
                                <div class="py-1">
                                        <a href="{{ route('peminjaman.riwayat') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd"/>
                                            </svg>
                                            Riwayat Peminjaman
                                        </a>

                                        <a href="{{ route('public.profile.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd"/>
                                            </svg>
                                            Profil
                                        </a>
                                    </div>
                            @endif
                            <!-- Logout -->
                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
