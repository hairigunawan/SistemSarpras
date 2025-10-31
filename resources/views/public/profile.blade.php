<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex items-center">
                        @if($user->avatar)
                            @if(str_starts_with($user->avatar, 'http'))
                                <img src="{{ $user->avatar }}" alt="Profile" class="h-16 w-16 rounded-full object-cover mr-4">
                            @else
                                <img src="{{ asset($user->avatar) }}" alt="Profile" class="h-16 w-16 rounded-full object-cover mr-4">
                            @endif
                        @else
                            <div class="h-16 w-16 rounded-full border flex items-center justify-center bg-gray-200 mr-4">
                                <span class="text-2xl font-medium text-gray-700">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                            <p class="text-gray-800">{{ $user->nama }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <p class="text-gray-800">{{ $user->email }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                        <p class="text-gray-800">{{ $user->userRole->nama_role ?? 'N/A' }}</p>
                    </div>
                    <!-- Tambahkan informasi profil lainnya sesuai kebutuhan -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
