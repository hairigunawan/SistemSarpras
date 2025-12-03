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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriterias = Kriteria::orderBy('created_at', 'desc')->get();

        // Jika tidak ada kriteria, buatkan kriteria default
        if ($kriterias->isEmpty()) {
            $defaultKriterias = [
                ['nama_kriteria' => 'Tanggal', 'tipe' => 'benefit', 'bobot' => 0.25],
                ['nama_kriteria' => 'Jumlah Peserta', 'tipe' => 'benefit', 'bobot' => 0.25],
                ['nama_kriteria' => 'Durasi', 'tipe' => 'benefit', 'bobot' => 0.25],
                ['nama_kriteria' => 'Proyektor', 'tipe' => 'benefit', 'bobot' => 0.25],
            ];

            foreach ($defaultKriterias as $kriteriaData) {
                Kriteria::create($kriteriaData);
            }

            // Reload kriteria setelah dibuat
            $kriterias = Kriteria::orderBy('created_at', 'desc')->get();
        }

        // Ambil data peminjaman yang perlu diprioritaskan
        $peminjamans = Peminjaman::with(['proyektor', 'user'])
            ->whereIn('status_peminjaman', ['Menunggu', 'Disetujui'])
            ->orderBy('tanggal_pinjam', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        // Inisialisasi variabel untuk perhitungan
        $pairwiseMatrix = [];
        $normalizedMatrix = [];
        $bobotAkhir = [];
        $cr = 0;
        $hasil = [];
        $alternatif = [];

        // Jika ada kriteria, lakukan perhitungan AHP dan SAW
        if ($kriterias->isNotEmpty()) {
            // AHP Calculation
            $ahpService = new SimpleAHPService();
            $ahpResult = $ahpService->calculateAHP($kriterias);

            // SAW Calculation
            $sawService = new SimpleSAWService();
            $sawResult = $sawService->calculateSAW($peminjamans, $kriterias, $ahpResult['bobot']);

            // Assign hasil perhitungan ke variabel
            $pairwiseMatrix = $ahpResult['pairwiseMatrix'];
            $normalizedMatrix = $ahpResult['normalizedMatrix'];
            $bobotAkhir = $ahpResult['bobotAkhir'];
            $cr = $ahpResult['cr'];
            $hasil = $sawResult['hasil'];
            $alternatif = $sawResult['alternatif'];

            // Update bobot kriteria dari hasil AHP
            foreach ($kriterias as $kriteria) {
                $kriteria->bobot = $ahpResult['bobot'][$kriteria->id] ?? $kriteria->bobot;
                $kriteria->save();
            }
        }

        return view('admin.kriteria.index', compact(
            'kriterias',
            'peminjamans',
            'pairwiseMatrix',
            'normalizedMatrix',
            'bobotAkhir',
            'cr',
            'hasil',
            'alternatif'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:100|unique:kriteria,nama_kriteria',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);

        Kriteria::create($validated);

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kriteria $kriteria)
    {
        return view('admin.kriteria.show', compact('kriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria)
    {
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:100|unique:kriteria,nama_kriteria,' . $kriteria->id,
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);

        $kriteria->update($validated);

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}
