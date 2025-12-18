<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporan = new Laporan();
        return $laporan->HalamanUtama($request);
    }

    public function exportPdf(Request $request)
    {
        $laporan = new Laporan();
        return $laporan->pdf($request);
    }

    public function exportExcel(Request $request)
    {
        $laporan = new Laporan();
        return $laporan->excel($request);
    }
}
