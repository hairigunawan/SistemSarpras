<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class WhatsappMessageHelper
{
    protected static function getGreeting($name)
    {
        $hour = date('H');
        $greeting = '';

        if ($hour >= 5 && $hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }

        return "Yth.  *" . Str::title($name) . "*,
$greeting.";
    }

    protected static function getFooter()
    {
        return "

_Pesan ini dikirim otomatis oleh Sistem Peminjaman Sarana & Prasarana berbasi website (SIMPERSITE)._
_Mohon tidak membalas pesan ini._";
    }

    public static function approved($peminjaman)
    {
        $name = $peminjaman->user->nama ?? $peminjaman->nama_peminjam;
        $greeting = self::getGreeting($name);
        $footer = self::getFooter();

        $tanggal = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('l, d F Y');
        $jam = "{$peminjaman->jam_mulai} - {$peminjaman->jam_selesai}";
        $kegiatan = $peminjaman->jenis_kegiatan;

        // Cek jika peminjaman melibatkan Ruangan DAN Proyektor
        if ($peminjaman->ruangan && $peminjaman->proyektor) {
            $fasilitas = $peminjaman->ruangan->nama_ruangan . " dan " . $peminjaman->proyektor->nama_proyektor;
        } else {
            // Jika hanya satu (Ruangan saja atau Proyektor saja)
            $fasilitas = $peminjaman->nama_sarpras;
        }

        return "$greeting

" .
            "Kami informasikan bahwa pengajuan peminjaman fasilitas Anda telah *DISETUJUI* ✅.

" .
            "*Detail Peminjaman:*
" .
            "Fasilitas: $fasilitas
" .
            "Tanggal: $tanggal
" .
            "Waktu: $jam WITA
" .
            "Kegiatan: $kegiatan

" .
            "Harap hadir tepat waktu dan menjaga kebersihan fasilitas yang digunakan." .
            $footer;
    }

    public static function rejected($peminjaman, $alasan)
    {
        $name = $peminjaman->user->nama ?? $peminjaman->nama_peminjam;
        $greeting = self::getGreeting($name);
        $footer = self::getFooter();

        $sarpras = $peminjaman->nama_sarpras;
        $tanggal = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('l, d F Y');

        return "$greeting

" .
               "Mohon maaf, pengajuan peminjaman fasilitas Anda *DITOLAK* ❌.

" .
               "*Detail Pengajuan:*
" .
               "Fasilitas: $sarpras
" .
               "Tanggal: $tanggal

" .
               "*Alasan Penolakan:*
" .
               "⚠️ _\"$alasan\"_

" .
               "Silakan hubungi admin atau ajukan peminjaman di waktu/fasilitas lain." .
               $footer;
    }

    public static function completed($peminjaman)
    {
        $name = $peminjaman->user->nama ?? $peminjaman->nama_peminjam;
        $greeting = self::getGreeting($name);
        $footer = self::getFooter();

        $sarpras = $peminjaman->nama_sarpras;

        return "$greeting

" .
               "Peminjaman fasilitas *$sarpras* telah selesai ✅.

" .
               "Terima kasih telah menggunakan fasilitas kami dan menjaga kebersihan serta ketertiban selama kegiatan berlangsung.

" .
               "Sampai jumpa di kegiatan selanjutnya! 👋" .
               $footer;
    }

    public static function verification($user, $code)
    {
        $greeting = self::getGreeting($user->nama);
        $footer = self::getFooter();

        return "$greeting

" .
               "Berikut adalah kode verifikasi (OTP) untuk akun Anda:

" .
               "*$code*

" .
               "Kode ini bersifat rahasia. Jangan berikan kepada siapa pun, termasuk pihak admin." .
               $footer;
    }
}
