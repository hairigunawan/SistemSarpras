<aside class="flex flex-col justify-between w-64 p-6 bg-white border border-gray-300">
        <div
            <div>
                <div class="flex items-center gap-2 mb-10">
            <div class="flex items-center gap-2">
                <img src="{{ asset('storage/images/TI.png') }}" alt="Logo TI" class="object-contain w-8 h-8">
                <img src="{{ asset('storage/images/politala.png') }}" alt="Logo Politala" class="object-contain w-8 h-8">
            </div>
            <h1 class="text-xl font-bold text-gray-800">SIMPERSITE.</h1>
        </div>
        <nav>
            <ul>
                <li class="mb-0.5">
                    <a href="{{ Route('admin.dashboard.index') }}"
                        class="flex items-center p-2 text-sm rounded gap-2 font-medium {{ request()->routeIs('admin.dashboard*') ? 'pl-5 bg-[#1180ab] bg-opacity-10 text-[#127ea9]' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M10 13H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1m-1 6H5v-4h4ZM20 3h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1m-1 6h-4V5h4Zm1 7h-2v-2a1 1 0 0 0-2 0v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0-2M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1M9 9H5V5h4Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <li class="mb-0.5">
                    <a href="{{ Route('admin.peminjaman.index') }}"
                        class="flex items-center p-2 text-sm rounded gap-2 font-medium {{ request()->routeIs('admin.peminjaman*') ? 'pl-5 bg-[#1180ab] bg-opacity-10 text-[#127ea9]' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z" />
                        </svg>
                        Peminjam
                    </a>
                </li>

                <li class="mb-0.5">
                    <a href="{{ Route('admin.sarpras.index') }}"
                        class="flex items-center p-2 text-sm rounded gap-2 font-medium {{ request()->routeIs('admin.sarpras.*') || request()->routeIs('sarpras.*') ? 'pl-5 bg-[#1180ab] bg-opacity-10 text-[#127ea9]' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 26 26" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                        </svg>
                        Inventory
                    </a>
                </li>

                <li class="mb-0.5">
                    <a href="{{ Route('laporan.index') }}"
                        class="flex items-center gap-2 p-2 rounded font-medium text-sm {{ request()->routeIs('laporan*') ? 'pl-5 bg-[#1180ab] bg-opacity-10 text-[#127ea9]' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M7 14h2a1 1 0 0 0 0-2H7a1 1 0 0 0 0 2m6 2H7a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2m6-14H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3m-5 2v3.29l-1.51-.84a1 1 0 0 0-1 0L10 7.29V4Zm6 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3v5a1 1 0 0 0 .5.86a1 1 0 0 0 1 0L12 8.47l2.51 1.4A1 1 0 0 0 15 10a1 1 0 0 0 1-1V4h3a1 1 0 0 1 1 1Z" />
                        </svg>
                        Laporan
                    </a>
                </li>

                <li class="mb-0.5">
                    <a href="{{ route('admin.jadwal.index') }}"
                    class="flex items-center p-2 gap-2 rounded font-medium text-sm {{ request()->routeIs('admin.jadwal.*') ? 'pl-5 bg-[#1180ab] bg-opacity-10 text-[#127ea9]' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 14a1 1 0 1 0-1-1a1 1 0 0 0 1 1m5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1m-5 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1m5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1M7 14a1 1 0 1 0-1-1a1 1 0 0 0 1 1M19 4h-1V3a1 1 0 0 0-2 0v1H8V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3m1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V7a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1ZM7 18a1 1 0 1 0-1-1a1 1 0 0 0 1 1" />
                        </svg>
                        Jadwal
                    </a>
                </li>

                <li class="mb-0.5">
                    <a href="{{ route('admin.akun.index') }}"
                    class="flex items-center p-2 gap-2 rounded font-medium text-sm {{ request()->routeIs('admin.akun.*') ? 'pl-5 bg-[#1180ab] bg-opacity-10 text-[#127ea9]' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                        Akun
                    </a>
                </li>

                <li class="mb-0.5" x-data="{ isOpen: {{ request()->is('admin/prioritas*') || request()->routeIs('admin.kriteria*') ? 'true' : 'false' }} }">
                <div 
                    @click="isOpen = !isOpen"
                    class="flex items-center justify-between p-2 rounded cursor-pointer text-sm font-medium transition-all duration-200
                    {{ request()->is('admin/prioritas*') || request()->routeIs('admin.kriteria*') ? 'bg-[#1180ab] bg-opacity-10 text-[#127ea9]' : 'text-gray-600 hover:bg-gray-100' }}">
                    
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2a1 1 0 0 1 .9.55l2.12 4.29l4.74.69a1 1 0 0 1 .55 1.7l-3.43 3.34l.81 4.73a1 1 0 0 1-1.45 1.05L12 17.77l-4.24 2.23a1 1 0 0 1-1.45-1.05l.81-4.73l-3.43-3.34a1 1 0 0 1 .55-1.7l4.74-.69l2.12-4.29A1 1 0 0 1 12 2" />
                        </svg>
                        Prioritas
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        class="transition-transform duration-200"
                        :class="isOpen ? 'rotate-180' : ''">
                        <path fill="currentColor" d="M7 10l5 5l5-5z" />
                    </svg>
                </div>

                <ul 
                    x-show="isOpen" 
                    x-collapse
                    x-cloak
                    class="mt-2 mb-3 ml-8 space-y-1">
                    
                    <li>
                        <a href="{{ route('admin.prioritas.ruangan') }}"
                            class="{{ request()->routeIs('admin.prioritas.ruangan') ? 'block text-sm bg-[#1180ab] bg-opacity-10 p-1.5 text-[#127ea9] rounded' : 'block text-sm text-gray-600 hover:text-[#0f7299] hover:bg-gray-100 p-1.5' }}">
                            Ruangan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.prioritas.proyektor') }}"
                            class="{{ request()->routeIs('admin.prioritas.proyektor') ? 'block p-1.5 text-sm bg-[#1180ab] bg-opacity-10 text-[#127ea9] rounded' : 'block text-sm text-gray-600 hover:text-[#0f7299] hover:bg-gray-100 p-1.5' }}">
                            Proyektor
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kriteria.index') }}"
                            class="{{ request()->routeIs('admin.kriteria*') ? 'block text-sm bg-[#1180ab] bg-opacity-10 p-1.5 text-[#127ea9] rounded' : 'block text-sm text-gray-600 hover:text-[#0f7299] hover:bg-gray-100 p-1.5' }}">
                            Kriteria
                        </a>
                    </li>
                </ul>
            </li>
        </nav>
    </div>
    <div class="grid text-xs text-gray-700 gap-y-3">
        <form method="POST" action="{{ route('logout') }}">
        @csrf
            <button type="submit"
            class="flex gap-2 items-center w-full px-4 py-2 text-sm transition rounded-xl hover:border hover:border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                </svg>
                Logout
            </button>
        </form>
        <p class="text-gray-500">© 2025 SIMPERSITE</p>
    </div>
</aside>
