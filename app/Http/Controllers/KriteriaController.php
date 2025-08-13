<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class KriteriaController extends Controller
{
    // app/Http/Controllers/KriteriaController.php
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index() 
    {
        $title = 'Data Kriteria';
        $kriteria = Kriteria::orderBy('kode')->get();
        $sumBobotKriteria = (float)$kriteria->sum('bobot_roc');
        return view('dashboard.kriteria.index', compact('title','kriteria','sumBobotKriteria'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'              => ['required','string','max:10','unique:kriterias,kode'],
            'kriteria'          => ['required','string','max:100'],
            'atribut'           => ['required', Rule::in(['benefit','cost'])],
            'urutan_prioritas'  => ['required','integer','min:1'],
        ]);

        $ok = Kriteria::create($data);

        return to_route('kriteria')->with($ok ? 'success' : 'error', $ok ? 'Kriteria berhasil disimpan' : 'Kriteria gagal disimpan');
    }

    public function edit(Request $request)
    {
        $row = Kriteria::findOrFail($request->kriteria_id);
        return response()->json($row);
    }

    public function update(Request $request)
    {
        $row = Kriteria::findOrFail($request->id);

        $data = $request->validate([
            'kode'              => ['required','string','max:10', Rule::unique('kriterias','kode')->ignore($row->id)],
            'kriteria'          => ['required','string','max:100'],
            'atribut'           => ['required', Rule::in(['benefit','cost'])],
            'urutan_prioritas'  => ['required','integer','min:1'],
        ]);

        $ok = $row->update($data);

        return to_route('kriteria')->with($ok ? 'success' : 'error', $ok ? 'Kriteria berhasil diperbarui' : 'Kriteria gagal diperbarui');
    }

    public function delete(Request $request)
    {
        $row = Kriteria::findOrFail($request->id);
        $ok  = $row->delete();

        return to_route('kriteria')->with($ok ? 'success' : 'error', $ok ? 'Kriteria berhasil dihapus' : 'Kriteria gagal dihapus');
    }

    /** Tombol hijau “Proses ROC + SMART” */
    public function proses()
    {
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART(); // Tanpa parameter User
        NilaiAkhir::hitungTotal();     // Tanpa parameter User

        return to_route('kriteria')->with('success','Perhitungan ROC + SMART selesai');
    }

}
