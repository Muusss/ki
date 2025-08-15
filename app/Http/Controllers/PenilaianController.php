<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianStoreRequest;
use App\Services\SubKriteriaMatcher;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Tampilkan tabel penilaian.
     * Mendukung filter server-side via query ?skin=normal|berminyak|kering|kombinasi|all
     */
    public function index(Request $request)
    {
        $allowed = ['normal', 'berminyak', 'kering', 'kombinasi', 'all', null, ''];
        $skin = $request->query('skin');

        // Sanitasi nilai query skin
        if (!in_array($skin, $allowed, true)) {
            $skin = null;
        }

        // Ambil alternatif; jika ada filter skin selain "all", terapkan where
        $alternatif = Alternatif::when($skin && $skin !== 'all', function ($q) use ($skin) {
                $q->where('jenis_kulit', $skin);
            })
            ->orderBy('kode_produk') // ganti dari 'nis' -> 'kode_produk'
            ->get();

        $kriteria = Kriteria::orderBy('kode')->get();

        // Ambil semua penilaian lalu indeks per alternatif dan kriteria: [altId][kritId] => Collection
        $penilaian = Penilaian::get()->groupBy(['alternatif_id', 'kriteria_id']);

        // Kirim current filter ke view (biar bisa ditampilkan/diingatkan di UI kalau perlu)
        return view('dashboard.penilaian.index', compact('alternatif', 'kriteria', 'penilaian') + [
            'skin' => $skin,
        ]);
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

    public function inputPage($id, Request $request)
    {
        $alternatif = Alternatif::findOrFail($id);
        $kriteria   = Kriteria::orderBy('kode')->get();
        $rows = Penilaian::where('alternatif_id', $alternatif->id)->get()->groupBy('kriteria_id');

        // cari prev/next berdasarkan urutan kode_produk
        $ordered = Alternatif::orderBy('kode_produk')->pluck('id')->all();
        $idx = array_search((int)$alternatif->id, $ordered, true);
        $prevId = $idx !== false && $idx > 0 ? $ordered[$idx - 1] : null;
        $nextId = $idx !== false && $idx < count($ordered)-1 ? $ordered[$idx + 1] : null;

        // agar tombol "Kembali" tetap ingat ?skin=...
        $skin = $request->query('skin');

        return view('dashboard.penilaian.input', compact(
            'alternatif','kriteria','rows','prevId','nextId','skin'
        ));
    }


    public function store(PenilaianStoreRequest $request)
    {
        [$subId, $skor] = $this->resolveSubAndSkor(
            $request->input('kriteria_id'),
            $request->input('nilai_angka'),
            $request->input('sub_kriteria_id'),
            $request->input('label')
        );

        // Perbaikan validasi
        if ($subId === null) { // Cek null explicitly
            return back()->with('error', 'Tidak menemukan sub kriteria yang cocok.');
        }
        
        // Skor 0 adalah valid
        if (!is_numeric($skor)) {
            return back()->with('error', 'Skor tidak valid.');
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

        return back()->with($row ? 'success' : 'error', 
                            $row ? 'Nilai tersimpan.' : 'Gagal menyimpan nilai.');
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

        // Kembali ke halaman index penilaian (opsional: ikutkan query skin jika ada di referer)
        $redirectTo = url()->previous() ?: route('penilaian');
        return redirect($redirectTo)->with('success', 'Penilaian disimpan.');
    }

    private function resolveSubAndSkor(int $kriteriaId, ?float $nilaiAngka, ?int $subId, ?string $label): array
    {
        if ($subId) {
            $sub = SubKriteria::where('kriteria_id', $kriteriaId)
                            ->where('id', $subId)
                            ->first();
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
