<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class AlternatifController extends Controller
{
    /** LIST */
    public function index(Request $request): View
    {
        $title = 'Data Produk Sunscreen';
        
        $alternatif = Alternatif::orderBy('kode_produk', 'asc')->get();

        return view('dashboard.alternatif.index', compact('title', 'alternatif'));
    }

    /** STORE */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_produk' => ['required','string','max:50','unique:alternatifs,kode_produk'],
            'nama_produk' => ['required','string','max:100'],
            'jenis_kulit' => ['required', Rule::in(['normal','berminyak','kering','kombinasi','sensitif'])],
        ]);

        $ok = Alternatif::create($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Produk berhasil disimpan' : 'Produk gagal disimpan'
        );
    }

    /** EDIT (AJAX) */
    public function edit(Request $request)
    {
        $row = Alternatif::findOrFail($request->alternatif_id);
        return response()->json($row);
    }

    /** UPDATE */
    public function update(Request $request): RedirectResponse
    {
        $row = Alternatif::findOrFail($request->id);

        $data = $request->validate([
            'kode_produk' => ['required','string','max:50', Rule::unique('alternatifs','kode_produk')->ignore($row->id)],
            'nama_produk' => ['required','string','max:100'],
            'jenis_kulit' => ['required', Rule::in(['normal','berminyak','kering','kombinasi','sensitif'])],
        ]);

        $ok = $row->update($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Produk berhasil diperbarui' : 'Produk gagal diperbarui'
        );
    }

    /** DELETE */
    public function delete(Request $request): RedirectResponse
    {
        $row = Alternatif::findOrFail($request->id);
        $ok = $row->delete();

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Produk berhasil dihapus' : 'Produk gagal dihapus'
        );
    }

    /** PERHITUNGAN ROC + SMART */
    public function perhitunganNilaiAkhir(): RedirectResponse
    {
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART();
        NilaiAkhir::hitungTotal();

        return to_route('alternatif')->with('success', 'Perhitungan ROC + SMART selesai');
    }

    public function indexPerhitungan()
    {
        $title = "Hasil Perhitungan ROC + SMART";

        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobotKriteria = (float) $kriteria->sum('bobot_roc');

        $hasil = NilaiAkhir::with('alternatif')
            ->orderByDesc('total')
            ->get();

        $alternatif = Alternatif::orderBy('kode_produk','asc')->get();
            
        $penilaian = Penilaian::with(['alternatif', 'kriteria'])->get();

        return view('dashboard.perhitungan.index', compact(
            'title','kriteria','sumBobotKriteria','hasil','alternatif','penilaian'
        ));
    }

    public function perhitunganMetode(): RedirectResponse
    {
        return $this->perhitunganNilaiAkhir();
    }
}