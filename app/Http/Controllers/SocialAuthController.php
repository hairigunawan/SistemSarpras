<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                if ($user->userRole->nama_role === 'Admin') {
                    return redirect('/login')->with('error', 'Admin harus login menggunakan email dan password.');
                }

                if (!$user->provider) {
                    $user->update([
                        'provider' => 'google',
                        'provider_id' => $googleUser->getId(),
                    ]);
                }
            } else {
                $defaultRole = Role::where('nama_role', 'Mahasiswa')->first();

                $user = User::create([
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'role_id' => $defaultRole ? $defaultRole->id_role : 3,
                    'password' => Hash::make(uniqid()),
                ]);
            }

            // Login user
            Auth::login($user);

            if ($user->userRole && $user->userRole->nama_role === 'Admin') {
                Auth::logout();
                return redirect('/login')->with('error', 'Admin harus login menggunakan email dan password.');
            }

            return redirect()->route('public.beranda.index');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
