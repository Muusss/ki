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
            // Statistik dasar
            $jumlahProduk = Alternatif::count();
            $jumlahKriteria = Kriteria::count();
            $jumlahPenilaian = Penilaian::count();
            $jumlahSubKriteria = SubKriteria::count();
            $jumlahNilaiAkhir = NilaiAkhir::count();

            // Produk showcase
            $products = Alternatif::orderBy('created_at', 'desc')
                                ->limit(8)
                                ->get();

            // Ambil filter dari request (default = all)
            $jenisMenu   = $request->get('jenis_menu', 'all');
            $filterHarga = $request->get('harga', 'all');

            $nilaiAkhir = collect();
            $top5       = collect();
            $chartLabels = [];
            $chartSeries = [];
            $hasilPerJenis = [];
            $jenisMenuList = ['makanan', 'cemilan', 'coffee', 'milkshake', 'mojito', 'yakult', 'tea'];

            if ($jumlahNilaiAkhir > 0) {
                // Query nilai akhir dengan filter
                $query = NilaiAkhir::with('alternatif');

                if ($jenisMenu !== 'all') {
                    $query->whereHas('alternatif', function($q) use ($jenisMenu) {
                        $q->where('jenis_menu', $jenisMenu);
                    });
                }

                if ($filterHarga !== 'all') {
                    $query->whereHas('alternatif', function($q) use ($filterHarga) {
                        $q->where('harga', $filterHarga);
                    });
                }

                $nilaiAkhir = $query->orderByDesc('total')->get();

                // Tambahkan ranking berdasarkan filter
                $nilaiAkhir = $nilaiAkhir->map(function ($item, $index) {
                    $item->peringkat_filter = $index + 1;
                    return $item;
                });

                // Ambil top5
                $top5 = $nilaiAkhir->take(5);

                // Data untuk chart top 10
                foreach ($nilaiAkhir->take(10) as $row) {
                    $chartLabels[] = $row->alternatif->nama_produk ?? ('Produk '.$row->alternatif_id);
                    $chartSeries[] = round((float) ($row->total ?? 0), 3);
                }

                // Data per jenis menu
                foreach ($jenisMenuList as $jenis) {
                    $hasil = NilaiAkhir::with('alternatif')
                        ->whereHas('alternatif', function($q) use ($jenis) {
                            $q->where('jenis_menu', $jenis);
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

            // Info sistem
            $systemInfo = [
                'has_products' => $jumlahProduk > 0,
                'has_criteria' => $jumlahKriteria > 0,
                'has_sub_criteria' => $jumlahSubKriteria > 0,
                'has_assessments' => $jumlahPenilaian > 0,
                'has_final_scores' => $jumlahNilaiAkhir > 0,
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
                'jenisMenu',
                'filterHarga',
                'hasilPerJenis',
                'jenisMenuList'
            ));

        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

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
                'systemInfo' => [],
                'jenisMenu' => 'all',
                'filterHarga' => 'all',
                'hasilPerJenis' => [],
                'jenisMenuList' => ['makanan','cemilan','coffee','milkshake','mojito','yakult','tea']
            ]);
        }
    }


    /**
     * Parse filter harga dari dropdown
     */
    private function parseHargaFilter($hargaFilter)
    {
        switch ($hargaFilter) {
            case '<=20000':
                return ['min' => 0, 'max' => 20000];
            case '>20000-<=25000':
                return ['min' => 20001, 'max' => 25000];
            case '>25000-<=30000':
                return ['min' => 25001, 'max' => 30000];
            case '>30000':
                return ['min' => 30001, 'max' => 999999999];
            default:
                return ['min' => null, 'max' => null];
        }
    }

    public function hasilAkhir(Request $request)
    {
        $title = 'Hasil Akhir';
        
        try {
            // Debug: Cek apakah method dipanggil
            Log::info('Hasil Akhir method called', ['request' => $request->all()]);
            
            // Ambil filter dari request dengan default values
            $jenisMenu = $request->get('jenis_menu', 'all');
            $filterHarga = $request->get('harga', 'all');
            
            // Initialize variables dengan default kosong
            $nilaiAkhir = collect();
            $hasilPerJenis = [];
            $jenisMenuList = ['makanan', 'cemilan', 'coffee', 'milkshake', 'mojito', 'yakult', 'tea'];
            
            // Cek apakah ada data nilai akhir
            if (NilaiAkhir::count() > 0) {
                // Query dengan filter
                $query = NilaiAkhir::with(['alternatif']);
                
                // Filter jenis menu
                if ($jenisMenu !== 'all') {
                    $query->whereHas('alternatif', function($q) use ($jenisMenu) {
                        $q->where('jenis_menu', $jenisMenu);
                    });
                }
                
                // Filter harga berdasarkan dropdown kategori
                if ($filterHarga !== 'all') {
                    $query->whereHas('alternatif', function($q) use ($filterHarga) {
                        $q->where('harga', $filterHarga);
                    });
                }
                
                $nilaiAkhir = $query->orderByDesc('total')->get();
                
                // Re-rank setelah filter
                $nilaiAkhir = $nilaiAkhir->map(function ($item, $index) {
                    $item->peringkat_filter = $index + 1;
                    return $item;
                });
                
                // Data untuk setiap jenis menu (optional, untuk analisis)
                foreach ($jenisMenuList as $jenis) {
                    $hasil = NilaiAkhir::with('alternatif')
                        ->whereHas('alternatif', function($q) use ($jenis) {
                            $q->where('jenis_menu', $jenis);
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
                'jenisMenu' => $jenisMenu,
                'filterHarga' => $filterHarga
            ]);

            return view('dashboard.hasil-akhir.index', compact(
                'title', 
                'nilaiAkhir',
                'jenisMenu',
                'filterHarga',
                'hasilPerJenis',
                'jenisMenuList'
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
                'jenisMenu' => 'all',
                'filterHarga' => 'all',
                'hasilPerJenis' => [],
                'jenisMenuList' => ['makanan', 'cemilan', 'coffee', 'milkshake', 'mojito', 'yakult', 'tea']
            ]);
        }
    }
}