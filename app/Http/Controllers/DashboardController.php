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
                'chartSeries'
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
                'chartSeries' => []
            ]);
        }
    }

    public function hasilAkhir(Request $request)
    {
        $title = 'Hasil Akhir';
        
        try {
            $nilaiAkhir = NilaiAkhir::with('alternatif')
                ->orderByDesc('total')
                ->get();

            return view('dashboard.hasil-akhir.index', compact('title', 'nilaiAkhir'));
        } catch (\Exception $e) {
            return view('dashboard.hasil-akhir.index', [
                'title' => 'Hasil Akhir',
                'nilaiAkhir' => collect()
            ]);
        }
    }
}