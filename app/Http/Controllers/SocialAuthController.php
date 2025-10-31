<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;

class SocialAuthController extends Controller
{
    /**
     * Redirect user ke halaman login Google
     */
    public function redirectToGoogle()
    {
        /** @var \Laravel\Socialite\Contracts\Provider $driver */
        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');
        return $driver->scopes(['openid', 'profile', 'email'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    /**
     * Callback dari Google setelah login
     */
    public function handleGoogleCallback()
    {
        try {
            // Ambil data user dari Google
            /** @var \Laravel\Socialite\Contracts\Provider $socialiteDriver */
            /** @var GoogleProvider $socialiteDriver */
            $socialiteDriver = Socialite::driver('google');

            // Debug: Simpan informasi driver untuk debugging
            Log::info('Google Socialite Driver Info', [
                'driver_class' => get_class($socialiteDriver),
                'stateless' => true,
            ]);

            $googleUser = $socialiteDriver->stateless()->user();

            // Cek apakah user sudah ada berdasarkan email
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Cegah Admin login lewat Google
                if ($existingUser->userRole && $existingUser->userRole->nama_role === 'Admin') {
                    return redirect('/login')->with('error', 'Admin harus login menggunakan email dan password.');
                }

                // Jika user memang dari Google, update data profilnya
                if ($existingUser->provider === 'google') {
                    $existingUser->update([
                        'nama' => $googleUser->getName(),
                        'avatar' => $googleUser->getAvatar(),
                        'token' => $googleUser->token ?? null,
                        'refresh_token' => $googleUser->refreshToken ?? null,
                    ]);

                    $userToLogin = $existingUser;
                } else {
                    // Jika email sudah terdaftar tapi bukan provider Google
                    return redirect('/login')->with('error', 'Email ini sudah terdaftar. Silakan login menggunakan password Anda.');
                }
            } else {
                // Cari role default 'Mahasiswa'
                $defaultRole = Role::where('nama_role', 'Mahasiswa')->first();

                // Buat user baru
                $newUser = User::create([
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'role_id' => $defaultRole ? $defaultRole->id_role : 3, // Menggunakan null jika role tidak ditemukan, agar validasi model bisa menangani.
                    'token' => $googleUser->token ?? null,
                    'refresh_token' => $googleUser->refreshToken ?? null,
                    'password' => Hash::make(uniqid()),
                ]);

                $userToLogin = $newUser;
            }

            // Login user
            Auth::login($userToLogin);

            // Redirect ke halaman utama
            return redirect()->route('public.beranda.index');
        } catch (\Exception $e) { // Variabel $e tetap dideklarasikan untuk potensi debugging di masa depan.
            // Log error detail untuk debugging
            Log::error('Google Login Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'type' => get_class($e),
            ]);

            // Cek apakah error terkait cURL/jaringan
            if (strpos($e->getMessage(), 'Could not resolve host') !== false ||
                strpos($e->getMessage(), 'cURL') !== false) {
                return redirect('/login')->with('error', 'Tidak dapat terhubung ke Google. Periksa koneksi internet Anda atau coba gunakan login biasa.');
            }

            // Untuk debugging, uncomment baris di bawah ini:
            // dd($e->getMessage());

            return redirect('/login')->with('error', 'Gagal login dengan Google. Silakan coba lagi atau gunakan login biasa.');
        }
    }
}
