<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Dashboard';
        
        try {
            // Statistik dasar
            $jumlahProduk = Alternatif::count();
            $jumlahKriteria = Kriteria::count();
            $jumlahPenilaian = Penilaian::count();

            // Ambil produk untuk showcase (8 produk terbaru)
            $products = Alternatif::orderBy('created_at', 'desc')
                                  ->limit(8)
                                  ->get();

            // Ranking produk - dengan pengecekan data
            $nilaiAkhir = collect();
            $top5 = collect();
            $chartLabels = [];
            $chartSeries = [];

            // Cek apakah ada data nilai akhir
            if (NilaiAkhir::count() > 0) {
                $nilaiAkhir = NilaiAkhir::with('alternatif')
                    ->orderByDesc('total')
                    ->get();

                // Top 5 produk
                $top5 = $nilaiAkhir->take(5);

                // Data untuk chart
                foreach ($nilaiAkhir->take(10) as $row) {
                    if ($row->alternatif) {
                        $chartLabels[] = $row->alternatif->nama_produk ?? ('Produk '.$row->alternatif_id);
                        $chartSeries[] = round((float) ($row->total ?? 0), 3);
                    }
                }
            }

            return view('dashboard.index', compact(
                'title',
                'jumlahProduk',
                'jumlahKriteria',
                'jumlahPenilaian',
                'nilaiAkhir',
                'top5',
                'chartLabels',
                'chartSeries',
                'products'
            ));

        } catch (\Exception $e) {
            // Jika ada error, return dengan data kosong
            return view('dashboard.index', [
                'title' => 'Dashboard',
                'jumlahProduk' => 0,
                'jumlahKriteria' => 0,
                'jumlahPenilaian' => 0,
                'nilaiAkhir' => collect(),
                'top5' => collect(),
                'chartLabels' => [],
                'chartSeries' => [],
                'products' => collect()
            ]);
        }
    }

    public function hasilAkhir(Request $request)
    {
        $title = 'Hasil Akhir';
        
        try {
            // Ambil filter jenis kulit dari request
            $jenisKulit = $request->get('jenis_kulit', 'all');
            
            // Query dengan filter
            $query = NilaiAkhir::with('alternatif');
            
            if ($jenisKulit !== 'all') {
                $query->whereHas('alternatif', function($q) use ($jenisKulit) {
                    $q->where('jenis_kulit', $jenisKulit);
                });
            }
            
            $nilaiAkhir = $query->orderByDesc('total')->get();
            
            // Re-rank setelah filter
            $nilaiAkhir = $nilaiAkhir->map(function ($item, $index) {
                $item->peringkat_filter = $index + 1;
                return $item;
            });
            
            // Ambil data untuk setiap jenis kulit (untuk tab)
            $hasilPerJenis = [];
            $jenisKulitList = ['normal', 'berminyak', 'kering', 'kombinasi'];
            
            foreach ($jenisKulitList as $jenis) {
                $hasil = NilaiAkhir::with('alternatif')
                    ->whereHas('alternatif', function($q) use ($jenis) {
                        $q->where('jenis_kulit', $jenis);
                    })
                    ->orderByDesc('total')
                    ->get()
                    ->map(function ($item, $index) {
                        $item->peringkat_jenis = $index + 1;
                        return $item;
                    });
                    
                $hasilPerJenis[$jenis] = $hasil;
            }

            return view('dashboard.hasil-akhir.index', compact(
                'title', 
                'nilaiAkhir', 
                'jenisKulit',
                'hasilPerJenis',
                'jenisKulitList'
            ));
            
        } catch (\Exception $e) {
            return view('dashboard.hasil-akhir.index', [
                'title' => 'Hasil Akhir',
                'nilaiAkhir' => collect(),
                'jenisKulit' => 'all',
                'hasilPerJenis' => [],
                'jenisKulitList' => ['normal', 'berminyak', 'kering', 'kombinasi']
            ]);
        }
    }
}