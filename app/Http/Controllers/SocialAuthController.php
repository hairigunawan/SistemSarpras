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
    public function redirectToGoogle()
    {
        /** @var GoogleProvider $driver */
        $driver = Socialite::driver('google');

        return $driver->scopes(['openid', 'profile', 'email'])
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
            ])
            ->redirect();
    }
    public function handleGoogleCallback()
    {
        try {
            /** @var GoogleProvider $driver */
            $driver = Socialite::driver('google');

            $googleUser = $driver->stateless()->user();

            $allowedDomain = 'mhs.politala.ac.id';
            $email = $googleUser->getEmail();

            // Cek domain email
            if (!str_ends_with($email, '@' . $allowedDomain)) {
                Log::warning('Google Login Ditolak - Domain Tidak Valid', [
                    'email' => $email,
                ]);

                return redirect('/login')->with(
                    'error',
                    'Login Google hanya diperbolehkan menggunakan email mahasiswa (@' . $allowedDomain . ')'
                );
            }

            $hd = $googleUser->user['hd'] ?? null;
            if ($hd && $hd !== $allowedDomain) {
                Log::warning('Google Login Ditolak - Hosted Domain Tidak Sesuai', [
                    'email' => $email,
                    'hd' => $hd,
                ]);

                return redirect('/login')->with(
                    'error',
                    'Akun Google Anda bukan dari domain mahasiswa resmi.'
                );
            }

            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                if (
                    $existingUser->userRole &&
                    $existingUser->userRole->nama_role === 'Admin'
                ) {
                    return redirect('/login')->with(
                        'error',
                        'Admin harus login menggunakan email dan password.'
                    );
                }
                if ($existingUser->provider === 'google') {
                    $existingUser->update([
                        'nama' => $googleUser->getName(),
                        'avatar' => $googleUser->getAvatar(),
                        'token' => $googleUser->token ?? null,
                        'refresh_token' => $googleUser->refreshToken ?? null,
                    ]);

                    $userToLogin = $existingUser;
                } else {
                    return redirect('/login')->with(
                        'error',
                        'Email ini sudah terdaftar. Silakan login menggunakan password.'
                    );
                }
            } else {
                $defaultRole = Role::where('nama_role', 'Mahasiswa')->first();  
                $userToLogin = User::create([
                    'nama' => $googleUser->getName(),
                    'email' => $email,
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'role_id' => $defaultRole?->id_role ?? 3,
                    'token' => $googleUser->token ?? null,
                    'refresh_token' => $googleUser->refreshToken ?? null,
                    'password' => Hash::make(uniqid()),
                ]);
            }
            Auth::login($userToLogin);

            return redirect()->route('public.beranda.index');
        } catch (\Exception $e) {

            Log::error('Google Login Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect('/login')->with(
                'error',
                'Gagal login menggunakan Google. Silakan coba lagi.'
            );
        }
    }
}
