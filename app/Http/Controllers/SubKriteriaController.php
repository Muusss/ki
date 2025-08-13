<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SubKriteria;
use App\Models\Kriteria;

class SubKriteriaController extends Controller
{
    public function index()
    {
        $title = 'Data Sub Kriteria';
        $sub = SubKriteria::with('kriteria:id,kode,kriteria')
            ->orderBy('kriteria_id')->orderBy('skor','asc')->get();
        $kriteria = Kriteria::orderBy('kode')->get(['id','kode','kriteria']);

        return view('dashboard.subkriteria.index', compact('title','sub','kriteria'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'kriteria_id' => ['required','integer','exists:kriterias,id'],
            'label'       => ['required','string','max:100'],
            'skor'        => ['required','integer','between:1,4'],
            'min_val'     => ['nullable','numeric'],
            'max_val'     => ['nullable','numeric','gte:min_val'],
        ]);

        $ok = SubKriteria::create($data);

        return to_route('subkriteria')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Sub kriteria disimpan' : 'Gagal menyimpan'
        );
    }

    public function edit(Request $request)
    {
        $row = SubKriteria::findOrFail($request->sub_kriteria_id);
        return response()->json($row);
    }

    public function update(Request $request)
    {
        $row = SubKriteria::findOrFail($request->id);

        $data = $request->validate([
            'kriteria_id' => ['required','integer','exists:kriterias,id'],
            'label'       => ['required','string','max:100'],
            'skor'        => ['required','integer','between:1,4'],
            'min_val'     => ['nullable','numeric'],
            'max_val'     => ['nullable','numeric','gte:min_val'],
        ]);

        $ok = $row->update($data);

        return to_route('subkriteria')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Sub kriteria diperbarui' : 'Gagal memperbarui'
        );
    }

    public function delete(Request $request)
    {
        $row = SubKriteria::findOrFail($request->id);
        $ok  = $row->delete();

        return to_route('subkriteria')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Sub kriteria dihapus' : 'Gagal menghapus'
        );
    }
}
