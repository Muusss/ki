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
        
        // Filter query
        $query = Alternatif::query();
        
        // Filter jenis kulit
        if ($request->has('jenis_kulit') && $request->jenis_kulit != 'all') {
            $query->where('jenis_kulit', $request->jenis_kulit);
        }
        
        // Filter harga
        if ($request->has('harga_min') && $request->harga_min) {
            $query->where('harga', '>=', $request->harga_min);
        }
        if ($request->has('harga_max') && $request->harga_max) {
            $query->where('harga', '<=', $request->harga_max);
        }
        
        // Filter SPF
        if ($request->has('spf') && $request->spf != 'all') {
            if ($request->spf == '50+') {
                $query->where('spf', '>=', 50);
            } else {
                $query->where('spf', $request->spf);
            }
        }
        
        $alternatif = $query->orderBy('kode_produk', 'asc')->get();
        
        return view('dashboard.alternatif.index', compact('title', 'alternatif'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_produk' => ['required', 'string', 'max:50', 'unique:alternatifs,kode_produk'],
            'nama_produk' => ['required', 'string', 'max:100'],
            'jenis_kulit' => ['required', Rule::in(['normal', 'berminyak', 'kering', 'kombinasi'])],
            'harga' => ['nullable', 'integer', 'min:0'],
            'spf' => ['nullable', 'integer', 'min:15', 'max:100'],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
                function ($attribute, $value, $fail) {
                    // Validasi MIME type
                    $mimeType = $value->getMimeType();
                    if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
                        $fail('File harus berupa gambar JPG atau PNG.');
                    }
                    // Validasi konten gambar
                    $imgInfo = @getimagesize($value->getRealPath());
                    if (!$imgInfo) {
                        $fail('File bukan gambar yang valid.');
                    }
                }
            ]
        ]);

        // Penyimpanan gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $nama_file = time() . '_' . Str::slug($data['nama_produk']) . '.' . $gambar->getClientOriginalExtension();
            $path = $gambar->move(public_path('img/produk'), $nama_file); // Simpan di public/img/produk
            $data['gambar'] = $nama_file;
        }

        // Simpan data produk ke database
        $ok = Alternatif::create($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Produk berhasil disimpan' : 'Produk gagal disimpan'
        );
    }

    public function update(Request $request)
    {
        $row = Alternatif::findOrFail($request->id);

        $data = $request->validate([
            'kode_produk' => ['required', 'string', 'max:50', Rule::unique('alternatifs', 'kode_produk')->ignore($row->id)],
            'nama_produk' => ['required', 'string', 'max:100'],
            'jenis_kulit' => ['required', Rule::in(['normal', 'berminyak', 'kering', 'kombinasi'])],
            'harga' => ['nullable', 'integer', 'min:0'],
            'spf' => ['nullable', 'integer', 'min:15', 'max:100'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ]);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $uploadPath = public_path('img/produk');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Hapus gambar lama jika ada
            if ($row->gambar && File::exists(public_path('img/produk/' . $row->gambar))) {
                File::delete(public_path('img/produk/' . $row->gambar));
            }

            // Simpan gambar yang baru
            $gambar = $request->file('gambar');
            $nama_file = time() . '_' . Str::slug($data['nama_produk']) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move($uploadPath, $nama_file);
            $data['gambar'] = $nama_file;
        }

        $ok = $row->update($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Produk berhasil diperbarui' : 'Produk gagal diperbarui'
        );
    }



    public function edit(Request $request)
    {
        $row = Alternatif::findOrFail($request->alternatif_id);
        
        return response()->json([
            'id' => $row->id,
            'kode_produk' => $row->kode_produk,
            'nama_produk' => $row->nama_produk,
            'jenis_kulit' => $row->jenis_kulit,
            'harga' => $row->harga,
            'spf' => $row->spf,
            'gambar' => $row->gambar
        ]);
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