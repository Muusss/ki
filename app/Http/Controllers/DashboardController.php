<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Dashboard';
        
        try {
            // Statistik dasar dengan debugging
            $jumlahProduk = Alternatif::count();
            $jumlahKriteria = Kriteria::count();
            $jumlahPenilaian = Penilaian::count();
            $jumlahSubKriteria = SubKriteria::count();
            $jumlahNilaiAkhir = NilaiAkhir::count();

            // Debug info untuk development
            if (config('app.debug')) {
                Log::info('Dashboard Stats', [
                    'produk' => $jumlahProduk,
                    'kriteria' => $jumlahKriteria,
                    'sub_kriteria' => $jumlahSubKriteria,
                    'penilaian' => $jumlahPenilaian,
                    'nilai_akhir' => $jumlahNilaiAkhir
                ]);
            }

            // Ambil produk untuk showcase (8 produk terbaru)
            $products = Alternatif::orderBy('created_at', 'desc')
                                  ->limit(8)
                                  ->get();

            // Inisialisasi variabel ranking
            $nilaiAkhir = collect();
            $top5 = collect();
            $chartLabels = [];
            $chartSeries = [];
            $needsCalculation = false;

            // Cek apakah ada data nilai akhir
            if ($jumlahNilaiAkhir > 0) {
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
            } else {
                // Cek apakah ada data penilaian tapi belum dihitung
                if ($jumlahPenilaian > 0) {
                    $needsCalculation = true;
                }
            }

            // Info sistem untuk debugging
            $systemInfo = [
                'has_products' => $jumlahProduk > 0,
                'has_criteria' => $jumlahKriteria > 0,
                'has_sub_criteria' => $jumlahSubKriteria > 0,
                'has_assessments' => $jumlahPenilaian > 0,
                'has_final_scores' => $jumlahNilaiAkhir > 0,
                'needs_calculation' => $needsCalculation,
                'calculation_ready' => $jumlahProduk > 0 && $jumlahKriteria > 0 && $jumlahPenilaian > 0
            ];

            return view('dashboard.index', compact(
                'title',
                'jumlahProduk',
                'jumlahKriteria',
                'jumlahPenilaian',
                'jumlahSubKriteria',
                'jumlahNilaiAkhir',
                'nilaiAkhir',
                'top5',
                'chartLabels',
                'chartSeries',
                'products',
                'systemInfo',
                'needsCalculation'
            ));

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Dashboard Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Jika ada error, return dengan data kosong tapi tetap bisa digunakan
            return view('dashboard.index', [
                'title' => 'Dashboard',
                'jumlahProduk' => 0,
                'jumlahKriteria' => 0,
                'jumlahPenilaian' => 0,
                'jumlahSubKriteria' => 0,
                'jumlahNilaiAkhir' => 0,
                'nilaiAkhir' => collect(),
                'top5' => collect(),
                'chartLabels' => [],
                'chartSeries' => [],
                'products' => collect(),
                'systemInfo' => [
                    'has_products' => false,
                    'has_criteria' => false,
                    'has_sub_criteria' => false,
                    'has_assessments' => false,
                    'has_final_scores' => false,
                    'needs_calculation' => false,
                    'calculation_ready' => false,
                    'error' => $e->getMessage()
                ],
                'needsCalculation' => false
            ]);
        }
    }


    public function hasilAkhir(Request $request)
    {
        $title = 'Hasil Akhir';
        
        try {
            // Debug: Cek apakah method dipanggil
            Log::info('Hasil Akhir method called', ['request' => $request->all()]);
            
            // Ambil filter dari request dengan default values
            $jenisKulit = $request->get('jenis_kulit', 'all');
            $filterHargaMin = $request->get('harga_min', null);
            $filterHargaMax = $request->get('harga_max', null);
            $filterSpfMin = $request->get('spf_min', null);
            $filterSpfMax = $request->get('spf_max', null);
            
            // Initialize variables dengan default kosong
            $nilaiAkhir = collect();
            $hasilPerJenis = [];
            $jenisKulitList = ['normal', 'berminyak', 'kering', 'kombinasi'];
            
            // Cek apakah ada data nilai akhir
            if (NilaiAkhir::count() > 0) {
                // Query dengan filter
                $query = NilaiAkhir::with(['alternatif']);
                
                // Filter jenis kulit
                if ($jenisKulit !== 'all') {
                    $query->whereHas('alternatif', function($q) use ($jenisKulit) {
                        $q->where('jenis_kulit', $jenisKulit);
                    });
                }
                
                // Filter harga
                if ($filterHargaMin !== null) {
                    $query->whereHas('alternatif', function($q) use ($filterHargaMin) {
                        $q->where('harga', '>=', $filterHargaMin);
                    });
                }
                if ($filterHargaMax !== null) {
                    $query->whereHas('alternatif', function($q) use ($filterHargaMax) {
                        $q->where('harga', '<=', $filterHargaMax);
                    });
                }
                
                // Filter SPF
                if ($filterSpfMin !== null) {
                    $query->whereHas('alternatif', function($q) use ($filterSpfMin) {
                        $q->where('spf', '>=', $filterSpfMin);
                    });
                }
                if ($filterSpfMax !== null) {
                    $query->whereHas('alternatif', function($q) use ($filterSpfMax) {
                        $q->where('spf', '<=', $filterSpfMax);
                    });
                }
                
                $nilaiAkhir = $query->orderByDesc('total')->get();
                
                // Re-rank setelah filter
                $nilaiAkhir = $nilaiAkhir->map(function ($item, $index) {
                    $item->peringkat_filter = $index + 1;
                    return $item;
                });
                
                // Data untuk setiap jenis kulit
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
            }
            
            // Debug: Log data yang akan dikirim ke view
            Log::info('Data to view', [
                'nilaiAkhir_count' => $nilaiAkhir->count(),
                'jenisKulit' => $jenisKulit
            ]);

            return view('dashboard.hasil-akhir.index', compact(
                'title', 
                'nilaiAkhir',
                'jenisKulit',
                'filterHargaMin',
                'filterHargaMax',
                'filterSpfMin',
                'filterSpfMax',
                'hasilPerJenis',
                'jenisKulitList'
            ));
            
        } catch (\Exception $e) {
            // Log error
            Log::error('Error in hasilAkhir: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return view dengan data kosong
            return view('dashboard.hasil-akhir.index', [
                'title' => 'Hasil Akhir',
                'nilaiAkhir' => collect(),
                'jenisKulit' => 'all',
                'filterHargaMin' => null,
                'filterHargaMax' => null,
                'filterSpfMin' => null,
                'filterSpfMax' => null,
                'hasilPerJenis' => [],
                'jenisKulitList' => ['normal', 'berminyak', 'kering', 'kombinasi']
            ]);
        }
    }
}