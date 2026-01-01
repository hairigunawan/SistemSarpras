<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

        return view('admin.kriteria.index', compact('kriterias'));
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
