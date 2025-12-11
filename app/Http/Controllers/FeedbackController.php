<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Menampilkan halaman feedback untuk ruangan atau proyektor
     */
    public function index(Request $request)
    {
        return Feedback::HalamanUtama($request);
    }

    /**
     * Menyimpan feedback baru
     */
    public function store(Request $request)
    {
        return Feedback::submit($request);
    }

    /**
     * Menghapus feedback
     */
    public function destroy(Feedback $feedback)
    {
        return Feedback::deleteFeedback($feedback);
    }
}
