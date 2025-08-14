<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;
use App\Models\SubKriteria;

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
            // Ambil filter dari request
            $jenisKulit = $request->get('jenis_kulit', 'all');
            $filterHarga = $request->get('harga', 'all');
            $filterSpf = $request->get('spf', 'all');
            
            // Query dengan filter jenis kulit
            $query = NilaiAkhir::with(['alternatif', 'alternatif.penilaians.subKriteria']);
            
            if ($jenisKulit !== 'all') {
                $query->whereHas('alternatif', function($q) use ($jenisKulit) {
                    $q->where('jenis_kulit', $jenisKulit);
                });
            }
            
            // Filter berdasarkan harga (dari sub kriteria)
            if ($filterHarga !== 'all') {
                $query->whereHas('alternatif.penilaians', function($q) use ($filterHarga) {
                    $kriteriaHarga = Kriteria::where('kode', 'C3')->first(); // C3 adalah Harga
                    if ($kriteriaHarga) {
                        $q->where('kriteria_id', $kriteriaHarga->id)
                          ->whereHas('subKriteria', function($sq) use ($filterHarga) {
                              $sq->where('label', 'LIKE', '%' . $filterHarga . '%');
                          });
                    }
                });
            }
            
            // Filter berdasarkan SPF
            if ($filterSpf !== 'all') {
                $query->whereHas('alternatif.penilaians', function($q) use ($filterSpf) {
                    $kriteriaSpf = Kriteria::where('kode', 'C2')->first(); // C2 adalah SPF
                    if ($kriteriaSpf) {
                        $q->where('kriteria_id', $kriteriaSpf->id)
                          ->whereHas('subKriteria', function($sq) use ($filterSpf) {
                              $sq->where('label', $filterSpf);
                          });
                    }
                });
            }
            
            $nilaiAkhir = $query->orderByDesc('total')->get();
            
            // Re-rank setelah filter
            $nilaiAkhir = $nilaiAkhir->map(function ($item, $index) {
                $item->peringkat_filter = $index + 1;
                return $item;
            });
            
            // Data untuk setiap jenis kulit
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
            
            // Data untuk filter harga
            $hasilPerHarga = [];
            $hargaList = ['<40k', '40k-60k', '61-80k', '>80k'];
            
            foreach ($hargaList as $harga) {
                $hasil = NilaiAkhir::with(['alternatif', 'alternatif.penilaians.subKriteria'])
                    ->whereHas('alternatif.penilaians', function($q) use ($harga) {
                        $kriteriaHarga = Kriteria::where('kode', 'C3')->first();
                        if ($kriteriaHarga) {
                            $q->where('kriteria_id', $kriteriaHarga->id)
                              ->whereHas('subKriteria', function($sq) use ($harga) {
                                  $sq->where('label', $harga);
                              });
                        }
                    })
                    ->orderByDesc('total')
                    ->get()
                    ->map(function ($item, $index) {
                        $item->peringkat_harga = $index + 1;
                        return $item;
                    });
                    
                $hasilPerHarga[$harga] = $hasil;
            }
            
            // Data untuk filter SPF
            $hasilPerSpf = [];
            $spfList = ['30', '35', '40', '50+'];
            
            foreach ($spfList as $spf) {
                $hasil = NilaiAkhir::with(['alternatif', 'alternatif.penilaians.subKriteria'])
                    ->whereHas('alternatif.penilaians', function($q) use ($spf) {
                        $kriteriaSpf = Kriteria::where('kode', 'C2')->first();
                        if ($kriteriaSpf) {
                            $q->where('kriteria_id', $kriteriaSpf->id)
                              ->whereHas('subKriteria', function($sq) use ($spf) {
                                  $sq->where('label', $spf);
                              });
                        }
                    })
                    ->orderByDesc('total')
                    ->get()
                    ->map(function ($item, $index) {
                        $item->peringkat_spf = $index + 1;
                        return $item;
                    });
                    
                $hasilPerSpf[$spf] = $hasil;
            }

            return view('dashboard.hasil-akhir.index', compact(
                'title', 
                'nilaiAkhir',
                'jenisKulit',
                'filterHarga',
                'filterSpf',
                'hasilPerJenis',
                'hasilPerHarga',
                'hasilPerSpf',
                'jenisKulitList',
                'hargaList',
                'spfList'
            ));
            
        } catch (\Exception $e) {
            return view('dashboard.hasil-akhir.index', [
                'title' => 'Hasil Akhir',
                'nilaiAkhir' => collect(),
                'jenisKulit' => 'all',
                'filterHarga' => 'all',
                'filterSpf' => 'all',
                'hasilPerJenis' => [],
                'hasilPerHarga' => [],
                'hasilPerSpf' => [],
                'jenisKulitList' => ['normal', 'berminyak', 'kering', 'kombinasi'],
                'hargaList' => ['<40k', '40k-60k', '61-80k', '>80k'],
                'spfList' => ['30', '35', '40', '50+']
            ]);
        }
    }
}