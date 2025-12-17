<nav x-data="{ mobileMenuOpen: false, profileOpen: false }" class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-200 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="<?php echo e(route('public.beranda.index')); ?>" class="flex items-center gap-2.5">
                    <img src="<?php echo e(asset('storage/images/TI.png')); ?>" alt="Logo TI" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                    <img src="<?php echo e(asset('storage/images/politala.png')); ?>" alt="Logo Politala" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                    <span class="text-lg sm:text-xl font-bold text-gray-600">SIMPERSITE.</span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="<?php echo e(route('public.beranda.index')); ?>"
                   class="text-xs font-normal transition-colors duration-200 <?php echo e(request()->routeIs('public.beranda.*') ? 'text-[#179ACE]' : 'text-gray-600 hover:text-[#179ACE]'); ?>">
                    Beranda
                </a>
                <a href="<?php echo e(route('public.peminjaman.daftarpeminjaman')); ?>"
                   class="text-xs font-normal transition-colors duration-200 <?php echo e(request()->routeIs('public.peminjaman.*') ? 'text-[#179ACE]' : 'text-gray-600 hover:text-[#179ACE]'); ?>">
                    Peminjaman
                </a>
                <a href="<?php echo e(route('public.sarana_perasarana.halamansarpras')); ?>"
                   class="text-xs font-normal transition-colors duration-200 <?php echo e(request()->routeIs('public.sarana_perasarana.*') ? 'text-[#179ACE]' : 'text-gray-600 hover:text-[#179ACE]'); ?>">
                    Sarana & Prasarana
                </a>
                <a href="<?php echo e(route('public.tentang_kami.index')); ?>"
                   class="text-xs font-normal transition-colors duration-200 <?php echo e(request()->routeIs('public.tentang_kami.*') ? 'text-[#179ACE]' : 'text-gray-600 hover:text-[#179ACE]'); ?>">
                    Tentang Kami
                </a>

                <div class="h-6 w-px bg-gray-300"></div>

                <div class="flex items-center">
                    <?php if(auth()->guard()->guest()): ?>
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('login')); ?>" class="text-xs font-normal text-gray-700 hover:text-[#179ACE] px-3 py-2 transition-colors">
                                Log in
                            </a>
                            <?php if(Route::has('register')): ?>
                                <a href="<?php echo e(route('register')); ?>" class="text-xs px-4 py-2 rounded-full font-medium text-white bg-[#179ACE] hover:bg-[#127ba5] shadow-sm transition-all transform hover:-translate-y-0.5">
                                    Register
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="relative">
                            <button @click="profileOpen = !profileOpen" @keydown.escape.window="profileOpen = false"
                                    class="flex items-center gap-1 pl-2 pr-1 py-1 hover:bg-gray-100 transition">

                                <div class="text-right items-center hidden lg:block mr-2">
                                    <p class="text-sm font-medium text-gray-800 leading-none"><?php echo e(Str::limit(Auth::user()->nama, 15)); ?></p>
                                    <p class="text-[10px] text-gray-500 uppercase mt-0.5"><?php echo e(Auth::user()->userRole->nama_role ?? 'User'); ?></p>
                                </div>

                                <?php if(Auth::user()->avatar): ?>
                                    <img src="<?php echo e(str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar)); ?>"
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->nama)); ?>&background=random';"
                                         alt="Profile"
                                         class="h-8 w-8 rounded-full object-cover border border-gray-200 shadow-sm">
                                <?php else: ?>
                                    <div class="h-8 w-8 rounded-full bg-[#179ACE] text-white flex items-center justify-center font-medium text-sm shadow-sm">
                                        <?php echo e(strtoupper(substr(Auth::user()->nama, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                            </button>

                            <div x-show="profileOpen"
                                 @click.away="profileOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 my-6 w-72 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50 origin-top-right">

                                <div class="px-4 py-3 lg:hidden">
                                    <p class="text-sm font-semibold text-gray-900"><?php echo e(Auth::user()->nama); ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?php echo e(Auth::user()->email); ?></p>
                                </div>

                                <div class="py-1">
                                    <?php if(Auth::user()->userRole && Auth::user()->userRole->nama_role == 'Admin'): ?>
                                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="group flex rounded-lg items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]">
                                            <i class="fa-solid fa-gauge-high w-5 mr-2 text-gray-400 group-hover:text-[#179ACE]"></i>
                                            Dashboard Admin
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('public.peminjaman.riwayat_peminjaman')); ?>" class="group flex rounded-lg items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]">
                                            <i class="fa-solid fa-clock-rotate-left w-5 mr-2 text-gray-400 group-hover:text-[#179ACE]"></i>
                                            Riwayat Peminjaman
                                        </a>
                                        <a href="<?php echo e(route('public.profile.index')); ?>" class="group flex items-center rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]">
                                            <i class="fa-solid fa-user w-5 mr-2 text-gray-400 group-hover:text-[#179ACE]"></i>
                                            Profil Saya
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="py-1">
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50">
                                            <i class="fa-solid fa-arrow-right-from-bracket w-5 mr-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-[#179ACE] hover:bg-gray-100 focus:outline-none">
                    <span class="sr-only">Open main menu</span>
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen"
         style="display: none;"
         class="md:hidden bg-white border-t border-gray-100 shadow-lg">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="<?php echo e(route('public.beranda.index')); ?>"
               class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('public.beranda.*') ? 'bg-blue-50 text-[#179ACE]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]'); ?>">
                Beranda
            </a>
            <a href="<?php echo e(route('public.peminjaman.daftarpeminjaman')); ?>"
               class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('public.peminjaman.*') ? 'bg-blue-50 text-[#179ACE]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]'); ?>">
                Peminjaman
            </a>
            <a href="<?php echo e(route('public.sarana_perasarana.halamansarpras')); ?>"
               class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('public.sarana_perasarana.*') ? 'bg-blue-50 text-[#179ACE]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]'); ?>">
                Sarana & Prasarana
            </a>
        </div>

        <?php if(auth()->guard()->guest()): ?>
            <div class="pt-4 pb-4 border-t border-gray-200">
                <div class="flex items-center px-4 gap-3">
                    <a href="<?php echo e(route('login')); ?>" class="block w-full text-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-normal rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Log in
                    </a>
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-normal rounded-md text-white bg-[#179ACE] hover:bg-[#127ba5] shadow-sm">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <?php if(Auth::user()->avatar): ?>
                             <img src="<?php echo e(str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar)); ?>" class="h-10 w-10 rounded-full object-cover">
                        <?php else: ?>
                             <div class="h-10 w-10 rounded-full bg-[#179ACE] text-white flex items-center justify-center font-bold text-lg">
                                <?php echo e(strtoupper(substr(Auth::user()->nama, 0, 1))); ?>

                             </div>
                        <?php endif; ?>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?php echo e(Auth::user()->nama); ?></div>
                        <div class="text-sm font-normal text-gray-500"><?php echo e(Auth::user()->email); ?></div>
                    </div>
                </div>
                <div class="mt-3 space-y-1 px-2">
                    <?php if(Auth::user()->userRole && Auth::user()->userRole->nama_role == 'Admin'): ?>
                         <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]">Dashboard Admin</a>
                    <?php else: ?>
                         <a href="<?php echo e(route('public.peminjaman.riwayat_peminjaman')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]">Riwayat Peminjaman</a>
                         <a href="<?php echo e(route('public.profile.index')); ?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-[#179ACE]">Profil Saya</a>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/layouts/partials/NavbarPublic.blade.php ENDPATH**/ ?>