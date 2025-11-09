<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>@yield('title', 'Sarpras')</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": {
              DEFAULT: "#38bdf8",
              50: "#f0f9ff",
              100: "#e0f2fe",
              200: "#bae6fd",
              300: "#7dd3fc",
              400: "#38bdf8",
              500: "#0ea5e9",
              600: "#0284c7",
              700: "#0369a1",
              800: "#075985",
              900: "#0c4a6e"
            },
            "background-light": "#ffffff",
            "background-dark": "#0f172a",
            "text-light": "#0f172a",
            "text-dark": "#f8fafc"
          },
          fontFamily: {
            "display": ["Inter", "sans-serif"]
          }
        },
      },
    }
  </script>

  <style>
    body {
      @apply font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark;
    }

    /* Efek transisi navbar */
    .navbar {
      transition: all 0.3s ease;
    }
  </style>

  @stack('head')
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">

  <div class="relative flex min-h-screen flex-col">

    <!-- Header/Navbar -->
    <header id="navbar" class="navbar fixed top-0 left-0 right-0 z-50 bg-transparent text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="bg-white/90 p-1.5 rounded-md">
              <svg class="text-primary-600 size-6" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
              </svg>
            </div>
            <span class="text-2xl font-bold">Sarpras</span>
          </a>

          <!-- Navigasi -->
          <nav class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="nav-link text-sm font-medium hover:text-primary-300 transition-colors {{ request()->routeIs('home') ? 'text-primary-200' : '' }}">Beranda</a>
            <a href="{{ route('tentang.kami') }}" class="nav-link text-sm font-medium hover:text-primary-300 transition-colors {{ request()->routeIs('tentang.kami') ? 'text-primary-200' : '' }}">Tentang Kami</a>
            <a href="#" class="nav-link text-sm font-medium hover:text-primary-300 transition-colors">Layanan</a>
            <a href="#" class="nav-link text-sm font-medium hover:text-primary-300 transition-colors">Kontak</a>
          </nav>

          <!-- Tombol CTA -->
          
          <!-- User Section -->
            <div class="flex items-center">
                @guest
                    <a href="{{ route('login') }}"
                       class="text-sm font-normal text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md transition-colors">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="text-sm ml-2 border bg-blue-600 border-gray-300 py-1 px-4 rounded-full font-medium text-white hover:bg-gray-50 transition-colors">
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

                                        <a href="{{ route('public.profile') }}"
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

          <!-- Tombol Mobile -->
          <button class="md:hidden p-2 rounded-md hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
            <span class="material-symbols-outlined">menu</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Konten Halaman -->
    <main class="flex-grow">
      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          <div class="lg:col-span-4">
            <a href="#" class="flex items-center gap-3 mb-4">
              <div class="bg-white/90 p-1.5 rounded-md">
                <svg class="text-primary-600 size-6" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                  <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                </svg>
              </div>
              <span class="text-2xl font-bold text-white">Sarpras</span>
            </a>
            <p class="text-slate-400 text-sm">Solusi terdepan untuk manajemen sarana dan prasarana yang profesional.</p>
          </div>
          <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-3 gap-8">
            <div>
              <h3 class="text-sm font-semibold text-white uppercase">Tautan</h3>
              <ul class="mt-4 space-y-3">
                <li><a href="{{ route('home') }}" class="text-base text-slate-400 hover:text-white">Beranda</a></li>
                <li><a href="{{ route('tentang.kami') }}" class="text-base text-slate-400 hover:text-white">Tentang</a></li>
                <li><a href="#" class="text-base text-slate-400 hover:text-white">Layanan</a></li>
                <li><a href="#" class="text-base text-slate-400 hover:text-white">Kontak</a></li>
              </ul>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-white uppercase">Legal</h3>
              <ul class="mt-4 space-y-3">
                <li><a href="#" class="text-base text-slate-400 hover:text-white">Kebijakan Privasi</a></li>
                <li><a href="#" class="text-base text-slate-400 hover:text-white">Syarat & Ketentuan</a></li>
              </ul>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-white uppercase">Kontak</h3>
              <ul class="mt-4 space-y-3">
                <li><span class="text-base text-slate-400">info@sarpras.com</span></li>
                <li><span class="text-base text-slate-400">(021) 123-4567</span></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="mt-12 pt-8 border-t border-slate-800 text-center">
          <p class="text-sm text-slate-500">© 2024 Sarpras. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>

  <!-- Script perubahan warna navbar saat scroll -->
  <script>
    window.addEventListener("scroll", () => {
      const navbar = document.getElementById("navbar");
      if (window.scrollY > 50) {
        navbar.classList.remove("bg-transparent", "text-white");
        navbar.classList.add("bg-white/90", "backdrop-blur-md", "shadow-md", "text-gray-900");
      } else {
        navbar.classList.add("bg-transparent", "text-white");
        navbar.classList.remove("bg-white/90", "backdrop-blur-md", "shadow-md", "text-gray-900");
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
