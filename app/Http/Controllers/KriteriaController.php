<?php
// app/Http/Controllers/KriteriaController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class KriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() 
    {
        $title = 'Data Kriteria';
        $kriteria = Kriteria::orderBy('kode')->get();
        $sumBobotKriteria = (float)$kriteria->sum('bobot_roc');
        return view('dashboard.kriteria.index', compact('title','kriteria','sumBobotKriteria'));
    }

    public function create()
    {
        $title = 'Tambah Kriteria';
        return view('dashboard.kriteria.create', compact('title'));
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

        return redirect()->route('kriteria')->with(
            $ok ? 'success' : 'error', 
            $ok ? 'Kriteria berhasil disimpan' : 'Kriteria gagal disimpan'
        );
    }

    public function edit(Request $request, $id = null)
    {
        // Handle both AJAX and regular request
        if ($request->ajax() && $request->has('kriteria_id')) {
            $row = Kriteria::findOrFail($request->kriteria_id);
            return response()->json($row);
        }
        
        // Regular edit page
        $kriteria = Kriteria::findOrFail($id ?: $request->id);
        $title = 'Edit Kriteria';
        return view('dashboard.kriteria.edit', compact('title', 'kriteria'));
    }

    public function update(Request $request, $id = null)
    {
        $row = Kriteria::findOrFail($id ?: $request->id);

        $data = $request->validate([
            'kode'              => ['required','string','max:10', Rule::unique('kriterias','kode')->ignore($row->id)],
            'kriteria'          => ['required','string','max:100'],
            'atribut'           => ['required', Rule::in(['benefit','cost'])],
            'urutan_prioritas'  => ['required','integer','min:1'],
        ]);

        $ok = $row->update($data);

        return redirect()->route('kriteria')->with(
            $ok ? 'success' : 'error', 
            $ok ? 'Kriteria berhasil diperbarui' : 'Kriteria gagal diperbarui'
        );
    }

    public function delete(Request $request, $id = null)
    {
        $row = Kriteria::findOrFail($id ?: $request->id);
        $ok  = $row->delete();

        return redirect()->route('kriteria')->with(
            $ok ? 'success' : 'error', 
            $ok ? 'Kriteria berhasil dihapus' : 'Kriteria gagal dihapus'
        );
    }

    public function destroy($id)
    {
        return $this->delete(request(), $id);
    }

    /** Tombol hijau "Proses ROC + SMART" */
    public function proses()
    {
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART();
        NilaiAkhir::hitungTotal();

        return redirect()->route('kriteria')->with('success','Perhitungan ROC + SMART selesai');
    }
}