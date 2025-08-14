<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Models\User;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class AlternatifController extends Controller
{
    /** LIST */
    public function index(Request $request)
    {
        $title = 'Data Produk Sunscreen';
        $alternatif = Alternatif::orderBy('kode_produk', 'asc')->get();
        return view('dashboard.alternatif.index', compact('title', 'alternatif'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_produk' => ['required','string','max:50','unique:alternatifs,kode_produk'],
            'nama_produk' => ['required','string','max:100'],
            'jenis_kulit' => ['required', Rule::in(['normal','berminyak','kering','kombinasi'])],
            'gambar' => ['nullable','image','mimes:jpeg,png,jpg','max:2048']
        ]);

        // Pastikan folder ada
        $uploadPath = public_path('img/produk');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            
            // Generate nama file unik
            $nama_file = time() . '_' . Str::slug($data['nama_produk']) . '.' . $gambar->getClientOriginalExtension();
            
            // Pindahkan file ke folder public/img/produk
            $gambar->move($uploadPath, $nama_file);
            $data['gambar'] = $nama_file;
        }

        $ok = Alternatif::create($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Produk berhasil disimpan' : 'Produk gagal disimpan'
        );
    }

    public function edit(Request $request)
    {
        $row = Alternatif::findOrFail($request->alternatif_id);
        
        // Return dengan data gambar
        return response()->json([
            'id' => $row->id,
            'kode_produk' => $row->kode_produk,
            'nama_produk' => $row->nama_produk,
            'jenis_kulit' => $row->jenis_kulit,
            'gambar' => $row->gambar
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $row = Alternatif::findOrFail($request->id);

        $data = $request->validate([
            'kode_produk' => ['required','string','max:50', Rule::unique('alternatifs','kode_produk')->ignore($row->id)],
            'nama_produk' => ['required','string','max:100'],
            'jenis_kulit' => ['required', Rule::in(['normal','berminyak','kering','kombinasi'])],
            'gambar' => ['nullable','image','mimes:jpeg,png,jpg','max:2048']
        ]);

        // Pastikan folder ada
        $uploadPath = public_path('img/produk');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($row->gambar && File::exists(public_path('img/produk/'.$row->gambar))) {
                File::delete(public_path('img/produk/'.$row->gambar));
            }
            
            $gambar = $request->file('gambar');
            // Generate nama file unik
            $nama_file = time() . '_' . Str::slug($data['nama_produk']) . '.' . $gambar->getClientOriginalExtension();
            
            // Pindahkan file ke folder public/img/produk
            $gambar->move($uploadPath, $nama_file);
            $data['gambar'] = $nama_file;
        }

        $ok = $row->update($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Produk berhasil diperbarui' : 'Produk gagal diperbarui'
        );
    }

    public function delete(Request $request): RedirectResponse
    {
        $row = Alternatif::findOrFail($request->id);
        
        // Hapus file gambar jika ada
        if ($row->gambar && File::exists(public_path('img/produk/'.$row->gambar))) {
            File::delete(public_path('img/produk/'.$row->gambar));
        }
        
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