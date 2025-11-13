<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function beranda()
    {
        return view('profile.beranda');
    }

    public function tentangKami()
    {
        return view('profile.tentang_kami'); // jika sudah ada view tentang_kami
    }
}
