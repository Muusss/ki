<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlternatifRequest;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class AlternatifController extends Controller
{
    /**
     * Display listing dengan filter dan search
     */
    public function index(Request $request)
    {
        $title = 'Data Menu';
        
        $query = Alternatif::query();
        
        // Apply filters
        $query->jenis($request->input('jenis_menu'))
              ->hargaKategori($request->input('harga'))
              ->search($request->input('q'));
        
        // Pagination
        $items = $query->orderBy('kode_menu', 'asc')
                      ->paginate($request->input('per_page', 15));
        
        // Filter data untuk view
        $filters = [
            'jenis_menu' => $request->input('jenis_menu'),
            'harga' => $request->input('harga'),
            'q' => $request->input('q')
        ];
        
        // Jika request JSON (untuk API)
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $items,
                'filters' => $filters,
                'message' => 'Data berhasil diambil'
            ]);
        }
        
        return view('dashboard.alternatif.index', compact('title', 'items', 'filters'));
    }

    /**
     * Store new alternatif
     */
    public function store(AlternatifRequest $request)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->saveUploadedImage($request->file('gambar'), $data['nama_menu']);
        }
        
        $alternatif = Alternatif::create($data);
        
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $alternatif,
                'message' => 'Menu berhasil disimpan'
            ], 201);
        }
        
        return redirect()->back()->with('success', 'Menu berhasil disimpan');
    }

    /**
     * Update existing alternatif
     */
    public function update(AlternatifRequest $request, Alternatif $alternatif)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($alternatif->gambar && File::exists(public_path('img/menu/' . $alternatif->gambar))) {
                File::delete(public_path('img/menu/' . $alternatif->gambar));
            }
            
            $data['gambar'] = $this->saveUploadedImage($request->file('gambar'), $data['nama_menu']);
        }
        
        $alternatif->update($data);
        
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $alternatif->fresh(),
                'message' => 'Menu berhasil diperbarui'
            ]);
        }
        
        return redirect()->back()->with('success', 'Menu berhasil diperbarui');
    }

    /**
     * Delete alternatif
     */
    public function destroy(Alternatif $alternatif)
    {
        // Delete image file if exists
        if ($alternatif->gambar && File::exists(public_path('img/menu/' . $alternatif->gambar))) {
            File::delete(public_path('img/menu/' . $alternatif->gambar));
        }
        
        $alternatif->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Menu berhasil dihapus'
            ]);
        }
        
        return redirect()->back()->with('success', 'Menu berhasil dihapus');
    }

    /**
     * Edit alternatif (for AJAX)
     */
    public function edit(Request $request)
    {
        if ($request->has('alternatif_id')) {
            $alternatif = Alternatif::findOrFail($request->alternatif_id);
            return response()->json($alternatif);
        }
        
        abort(404);
    }

    /**
     * Private helper: Save uploaded image
     */
    private function saveUploadedImage(UploadedFile $file, string $menuName): string
    {
        // Create directory if not exists
        $uploadPath = public_path('img/menu');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }
        
        // Generate filename
        $filename = time() . '_' . Str::slug($menuName) . '.' . $file->getClientOriginalExtension();
        
        // Move file to public/img/menu
        $file->move($uploadPath, $filename);
        
        // Return just filename (not full path)
        return $filename;
    }

    /**
     * Perhitungan ROC + SMART
     */
    public function perhitunganNilaiAkhir()
    {
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART();
        NilaiAkhir::hitungTotal();

        return redirect()->route('alternatif')->with('success', 'Perhitungan ROC + SMART selesai');
    }

    /**
     * Index perhitungan
     */
    public function indexPerhitungan()
    {
        $title = "Hasil Perhitungan ROC + SMART";
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobotKriteria = (float) $kriteria->sum('bobot_roc');
        $hasil = NilaiAkhir::with('alternatif')->orderByDesc('total')->get();
        $alternatif = Alternatif::orderBy('kode_menu','asc')->get();
        $penilaian = Penilaian::with(['alternatif', 'kriteria'])->get();

        return view('dashboard.perhitungan.index', compact(
            'title','kriteria','sumBobotKriteria','hasil','alternatif','penilaian'
        ));
    }
}