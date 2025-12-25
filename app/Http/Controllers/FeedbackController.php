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
    public function index(Request $request)
    {
        return Feedback::HalamanUtama($request);
    }

    public function store(Request $request)
    {
        return Feedback::submit($request);
    }

    public function destroy(Feedback $feedback)
    {
        return Feedback::deleteFeedback($feedback);
    }
}
