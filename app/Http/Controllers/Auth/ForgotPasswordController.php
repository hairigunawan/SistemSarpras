<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // FORM EMAIL
    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    // KIRIM OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(100000, 999999);

        // Simpan OTP ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // KIRIM EMAIL OTP
        Mail::raw("Kode OTP Reset Password Anda: $otp", function($message) use ($request) {
            $message->to($request->email)
                    ->subject('Kode OTP Reset Password');
        });

        session(['reset_email' => $request->email]);

        return redirect()->route('password.otpForm')->with('status', 'Kode OTP telah dikirim ke email');
    }

    // FORM OTP
    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    // VERIFIKASI OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        $email = session('reset_email');

        $data = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$data || $data->token != $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau kadaluarsa']);
        }

        return redirect()->route('password.resetForm');
    }

    // FORM RESET PASSWORD
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    // PROSES RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $email = session('reset_email');

        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        session()->forget('reset_email');

        return redirect()->route('login')->with('status', 'Password berhasil direset');
    }
}
