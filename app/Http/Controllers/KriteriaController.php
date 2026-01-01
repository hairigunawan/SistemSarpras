<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SimpleAHPService;
use App\Services\SimpleSAWService;

class KriteriaController extends Controller
{

    public function index(Kriteria $k)
    {
        return $k->HalamanUtama();
    }

    public function create()
    {
        return view('admin.kriteria.create');
    }

    public function store(Request $request)
    {
        return Kriteria::Submit($request);
    }

    public function show(Kriteria $kriteria)
    {
        return view('admin.kriteria.show', compact('kriteria'));
    }

    public function edit(Kriteria $kriteria)
    {
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        return Kriteria::UpdateKriteria($request, $kriteria);
    }

    public function destroy(Kriteria $kriteria)
    {
        return Kriteria::DeleteKriteria($kriteria);
    }

    public function perbandingan()
    {
        $kriterias = Kriteria::all();
        $comparisons = DB::table('kriteria_comparisons')->get();
        return view('admin.kriteria.perbandingan', compact('kriterias', 'comparisons'));
    }

    public function simpanPerbandingan(Request $request, SimpleAHPService $ahpService)
    {
        $matrix = $request->input('matrix', []);
        
        DB::beginTransaction();
        try {
            // 1. Simpan Perbandingan
            foreach ($matrix as $id1 => $row) {
                foreach ($row as $id2 => $nilai) {
                    if ($id1 != $id2) {
                        DB::table('kriteria_comparisons')->updateOrInsert(
                            ['kriteria_id_1' => $id1, 'kriteria_id_2' => $id2],
                            ['nilai' => $nilai, 'updated_at' => now()]
                        );
                    }
                }
            }

            // 2. Hitung AHP
            $kriterias = Kriteria::all();
            $comparisons = DB::table('kriteria_comparisons')->get();
            $ahpResult = $ahpService->calculateAHP($kriterias, $comparisons);

            // 3. Update Bobot Kriteria di Database
            foreach ($ahpResult['bobot'] as $id => $bobot) {
                Kriteria::where('id', $id)->update(['bobot' => $bobot]);
            }

            DB::commit();

            $status = $ahpResult['cr'] <= 0.1 ? 'success' : 'warning';
            $msg = 'Perbandingan disimpan. CR: ' . round($ahpResult['cr'], 4);
            
            if ($ahpResult['cr'] > 0.1) {
                $msg .= ' (Tidak Konsisten! Mohon tinjau kembali input Anda)';
            }

            return redirect()->route('admin.kriteria.perbandingan')->with($status, $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
