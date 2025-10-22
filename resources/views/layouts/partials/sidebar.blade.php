<aside class="flex flex-col justify-between w-64 p-6 bg-white">
    <div>
        <div class="flex gap-1 mb-10">
            <p class="rotate-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m21 14l-9 6l-9-6m18-4l-9 6l-9-6l9-6z" />
                </svg>
            </p>
            <h1 class="flex items-center justify-between gap-2 text-xl font-bold text-gray-800">SIMPERSITE.</h1>
        </div>
        <nav>
            <ul>
                <li class="mb-2">
                    <a href="{{ Route('admin.dashboard.index') }}"
                        class="flex items-center p-3 text-sm rounded-lg gap-2 font-medium {{ request()->is('dashboard*') ? 'pl-7 bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M10 13H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1m-1 6H5v-4h4ZM20 3h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1m-1 6h-4V5h4Zm1 7h-2v-2a1 1 0 0 0-2 0v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0-2M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1M9 9H5V5h4Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ Route('admin.peminjaman.index') }}"
                        class="flex items-center p-3 text-sm rounded-lg gap-2 font-medium {{ request()->is('peminjaman*') ? 'pl-7 bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z" />
                        </svg>
                        Peminjam
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ Route('admin.sarpras.index') }}"
                        class="flex items-center text-sm p-3 gap-2 rounded-lg font-medium {{ request()->is('sarpras*') ? 'pl-7 bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M20.47 7.37v-.08l-.06-.15a1 1 0 0 0-.07-.09a1 1 0 0 0-.09-.12l-.09-.07l-.16-.08l-7.5-4.63a1 1 0 0 0-1.06 0L4 6.78l-.09.08l-.09.07a1 1 0 0 0-.09.12a1 1 0 0 0-.07.09l-.06.15v.08a1.2 1.2 0 0 0 0 .26v8.74a1 1 0 0 0 .47.85l7.5 4.63a.5.5 0 0 0 .15.06s.05 0 .08 0a.86.86 0 0 0 .52 0h.08a.5.5 0 0 0 .15-.06L20 17.22a1 1 0 0 0 .47-.85V7.63a1.2 1.2 0 0 0 0-.26M11 19.21l-5.5-3.4V9.43l5.5 3.39Zm1-8.12L6.4 7.63L12 4.18l5.6 3.45Zm6.5 4.72l-5.5 3.4v-6.39l5.5-3.39Z" />
                        </svg>
                        Inventory
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ Route('laporan.index') }}"
                        class="flex items-center gap-2 p-3 rounded-lg font-medium text-sm {{ request()->is('laporan*') ? 'pl-7 bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M7 14h2a1 1 0 0 0 0-2H7a1 1 0 0 0 0 2m6 2H7a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2m6-14H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3m-5 2v3.29l-1.51-.84a1 1 0 0 0-1 0L10 7.29V4Zm6 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3v5a1 1 0 0 0 .5.86a1 1 0 0 0 1 0L12 8.47l2.51 1.4A1 1 0 0 0 15 10a1 1 0 0 0 1-1V4h3a1 1 0 0 1 1 1Z" />
                        </svg>
                        Laporan
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ Route('jadwal.index') }}"
                        class="flex items-center p-3 gap-2 rounded-lg font-medium text-sm {{ request()->is('jadwal*') ? 'pl-7 bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12 14a1 1 0 1 0-1-1a1 1 0 0 0 1 1m5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1m-5 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1m5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1M7 14a1 1 0 1 0-1-1a1 1 0 0 0 1 1M19 4h-1V3a1 1 0 0 0-2 0v1H8V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3m1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V7a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1ZM7 18a1 1 0 1 0-1-1a1 1 0 0 0 1 1" />
                        </svg>
                        Jadwal
                    </a>
                </li>

                <li class="mb-2">
                    <details class="group" {{ request()->is('prioritas*') ? 'open' : '' }}>
                        <summary
                            class="flex items-center justify-between p-3 rounded-lg cursor-pointer text-sm font-medium {{ request()->is('prioritas*') ? 'pl-7 bg-blue-100 text-blue-800' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="flex items-center gap-2">
                                <!-- 🔹 ICON PRIORITAS -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M12 2a1 1 0 0 1 .9.55l2.12 4.29l4.74.69a1 1 0 0 1 .55 1.7l-3.43 3.34l.81 4.73a1 1 0 0 1-1.45 1.05L12 17.77l-4.24 2.23a1 1 0 0 1-1.45-1.05l.81-4.73l-3.43-3.34a1 1 0 0 1 .55-1.7l4.74-.69l2.12-4.29A1 1 0 0 1 12 2" />
                                </svg>
                                Prioritas
                            </span>

                            <!-- 🔻 Panah dropdown -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24"
                                class="transition-transform duration-200 group-open:rotate-180">
                                <path fill="currentColor" d="M7 10l5 5l5-5z" />
                            </svg>
                        </summary>

                        <!-- Submenu -->
                        <ul class="ml-8 mt-2 space-y-1">
                            <li>
                                <a href="{{ Route('admin.prioritas.ruangan') }}"
                                    class="{{ request()->is('prioritas/ruangan*') ? 'block text-sm pl-7 bg-blue-100 text-blue-800 rounded-lg' : 'block text-sm text-gray-600 hover:text-blue-700' }}">
                                    Ruangan
                                </a>
                            </li>
                            <li>
                                <a href="{{ Route('admin.prioritas.proyektor') }}"
                                    class="{{ request()->is('prioritas/proyektor*') ? 'block text-sm pl-7 bg-blue-100 text-blue-800 rounded-lg' : 'block text-sm text-gray-600 hover:text-blue-700' }}">
                                    Proyektor
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
        </nav>
    </div>

    <div class="text-xs text-gray-400">
        <p>© 2025 SIMPERSITE</p>
    </div>
</aside>
