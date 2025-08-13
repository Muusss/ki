<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianStoreRequest;
use App\Services\SubKriteriaMatcher;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index()
    {
        $alternatif = Alternatif::orderBy('kode_produk')->get(); // Ganti dari 'nis' ke 'kode_produk'
        $kriteria   = Kriteria::orderBy('kode')->get();

        // Ambil semua penilaian lalu indeks per alternatif dan kriteria
        $penilaian = Penilaian::get()
            ->groupBy(['alternatif_id','kriteria_id']); // hasil: [altId][kritId] => Collection

        return view('dashboard.penilaian.index', compact('alternatif','kriteria','penilaian'));
    }

    public function edit($id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $kriteria   = Kriteria::orderBy('kode')->get();
        $rows = Penilaian::where('alternatif_id', $alternatif->id)->get()->groupBy('kriteria_id');

        if (request()->ajax()) {
            return view('dashboard.penilaian._form', compact('alternatif','kriteria','rows'));
        }
        return view('dashboard.penilaian.edit', compact('alternatif','kriteria','rows'));
    }

    public function store(PenilaianStoreRequest $request)
    {
        [$subId, $skor] = $this->resolveSubAndSkor(
            $request->input('kriteria_id'),
            $request->input('nilai_angka'),
            $request->input('sub_kriteria_id'),
            $request->input('label')
        );

        if (!$subId || !$skor) {
            return back()->with('error','Tidak menemukan sub kriteria yang cocok. Periksa input.');
        }

        $row = Penilaian::updateOrCreate(
            [
                'alternatif_id' => $request->input('alternatif_id'),
                'kriteria_id'   => $request->input('kriteria_id'),
            ],
            [
                'sub_kriteria_id' => $subId,
                'nilai_asli'      => $skor,
                'nilai_normal'    => null,
            ]
        );

        return back()->with($row ? 'success' : 'error', $row ? 'Nilai tersimpan.' : 'Gagal menyimpan nilai.');
    }

    public function update(Request $request, $id)
    {
        $alternatif = Alternatif::findOrFail($id);

        $data = $request->validate([
            'nilai_asli'   => ['required','array'],
            'nilai_asli.*' => ['nullable','numeric'],
        ]);

        foreach ($data['nilai_asli'] as $kriteriaId => $nilai) {
            Penilaian::updateOrCreate(
                ['alternatif_id' => $alternatif->id, 'kriteria_id' => $kriteriaId],
                ['nilai_asli' => ($nilai === '' ? null : $nilai)]
            );
        }

        // JANGAN pakai back(); supaya balik ke tabel dan render ulang
        return redirect()->route('penilaian')->with('success', 'Penilaian disimpan.');
    }

    private function resolveSubAndSkor(int $kriteriaId, ?float $nilaiAngka, ?int $subId, ?string $label): array
    {
        if ($subId) {
            $sub = SubKriteria::where('kriteria_id',$kriteriaId)->where('id',$subId)->first();
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        if ($label) {
            $sub = SubKriteriaMatcher::matchByLabel($kriteriaId, $label);
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        if ($nilaiAngka !== null) {
            $sub = SubKriteriaMatcher::matchNumeric($kriteriaId, (float)$nilaiAngka);
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        return [null, null];
    }
}