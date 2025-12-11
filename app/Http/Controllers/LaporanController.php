<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index(Request $request, Laporan $l){
        return $l->HalamanUtama($request);
    }

    public function exportPdf(Request $request, Laporan $l){
        return $l->pdf($request);
    }

    public function exportExcel(Request $request, Laporan $l){
        return $l->excel($request);
    }
}

