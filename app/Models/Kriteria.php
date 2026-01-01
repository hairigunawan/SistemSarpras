<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SimpleAHPService;
use App\Services\SimpleSAWService;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'nama_kriteria',
        'tipe',
        'bobot'
    ];

    protected $casts = [
        'tipe' => 'string',
        'bobot' => 'decimal:4'
    ];

    public function HalamanUtama(){
        $kriterias = Kriteria::orderBy('created_at', 'desc')->get();

        $peminjamans = Peminjaman::with(['proyektor', 'user'])
            ->whereIn('status_peminjaman', ['Menunggu', 'Disetujui'])
            ->orderBy('tanggal_pinjam', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $pairwiseMatrix = [];
        $normalizedMatrix = [];
        $bobotAkhir = [];
        $cr = 0;
        $hasil = [];
        $alternatif = [];

        if ($kriterias->isNotEmpty()) {
            // Ambil data perbandingan yang tersimpan
            $comparisons = DB::table('kriteria_comparisons')->get();

            $ahpService = new SimpleAHPService();
            $ahpResult = $ahpService->calculateAHP($kriterias, $comparisons);

            // SAW tetap menggunakan bobot yang tersimpan di database (yang sudah diupdate oleh proses AHP)
            $manualBobot = $kriterias->pluck('bobot', 'id')->toArray();

            $sawService = new SimpleSAWService();
            $sawResult = $sawService->calculateSAW($peminjamans, $kriterias, $manualBobot);

            $pairwiseMatrix = $ahpResult['pairwiseMatrix'];
            $normalizedMatrix = $ahpResult['normalizedMatrix'];
            $bobotAkhir = $ahpResult['bobotAkhir'];
            $cr = $ahpResult['cr'];
            $hasil = $sawResult['hasil'];
            $alternatif = $sawResult['alternatif'];
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

    public static function Submit(Request $request){
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:100|unique:kriteria,nama_kriteria',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);

        Kriteria::create($validated);

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }
    public static function UpdateKriteria(Request $request, Kriteria $kriteria){
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:100|unique:kriteria,nama_kriteria,' . $kriteria->id,
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);

        $kriteria->update($validated);

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public static function DeleteKriteria(Kriteria $kriteria){
        $kriteria->delete();

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}
