<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Proyektor;

class SarprasController extends Controller
{
    // ...existing code...
    public function destroy($sarpras)
    {
        // Implementasi penghapusan data sarpras
    }

    public function index()
    {
        $ruangans = Ruangan::paginate(9);
        $proyektors = Proyektor::paginate(9);

        return view('admin.sarpras.index', compact('ruangans', 'proyektors'));
    }
    // ...existing code...
}