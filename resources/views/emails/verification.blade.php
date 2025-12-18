@component('mail::message')
# Verifikasi Email - SIMPERSITE

Halo {{ $user->nama }},

Terima kasih telah mendaftar di SIMPERSITE. Untuk melanjutkan proses registrasi, silakan verifikasi email Anda dengan memasukkan kode verifikasi berikut:

**Kode Verifikasi:** {{ $verificationCode }}

Kode verifikasi ini akan berlaku selama 24 jam. Jika Anda tidak melakukan permintaan ini, silakan abaikan email ini.

Jika Anda memiliki pertanyaan, silakan hubungi tim kami.

Salam hangat,
Tim SIMPERSITE

@component('mail::button', ['url' => route('verification.form'), 'color' => 'blue'])
Verifikasi Email
@endcomponent

@component('mail::subcopy')
Jika tombol di atas tidak berfungsi, silakan salin dan tempel URL berikut ke browser Anda: {{ route('verification.form') }}
@endcomponent
@endcomponent
