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
        
        // Tidak perlu filter kelas lagi
        $jumlahProduk = Alternatif::count();
        $jumlahKriteria = Kriteria::count();
        $jumlahPenilaian = Penilaian::count();

        // Ranking produk
        $nilaiAkhir = NilaiAkhir::with(['alternatif:id,kode_produk,nama_produk,jenis_kulit'])
            ->orderByDesc('total')
            ->get(['id','alternatif_id','total','peringkat']);

        // Top 5 produk
        $top5 = $nilaiAkhir->take(5);

        // Data untuk chart
        $chartLabels = [];
        $chartSeries = [];
        foreach ($nilaiAkhir->take(10) as $row) {
            $chartLabels[] = $row->alternatif->nama_produk ?? ('Produk '.$row->alternatif_id);
            $chartSeries[] = round((float) $row->total, 3);
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
    }

    public function hasilAkhir(Request $request)
    {
        $title = 'Hasil Akhir';
        
        $nilaiAkhir = NilaiAkhir::with('alternatif')
            ->orderByDesc('total')
            ->get();

        return view('dashboard.hasil-akhir.index', compact('title', 'nilaiAkhir'));
    }
}