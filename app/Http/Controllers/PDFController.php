<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\Penilaian;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    /**
     * Parse filter harga dari dropdown menu cafe
     */
    private function parseHargaFilter($hargaFilter)
    {
        switch ($hargaFilter) {
            case '<=20000':
                return ['min' => null, 'max' => 20000];
            case '>20000-<=25000':
                return ['min' => 20001, 'max' => 25000];
            case '>25000-<=30000':
                return ['min' => 25001, 'max' => 30000];
            case '>30000':
                return ['min' => 30001, 'max' => null];
            default:
                return ['min' => null, 'max' => null];
        }
    }

    public function hasilAkhir(Request $request)
    {
        $judul = 'Laporan Hasil Akhir Rekomendasi Menu Cafe Buri Umah';
        $user = Auth::user();

        // Ambil filter dari request
        $jenisMenu = $request->get('jenis_menu', 'all');
        $filterHarga = $request->get('harga', 'all');
        
        // Build judul dengan info filter
        $filterText = [];
        if ($jenisMenu !== 'all') {
            $filterText[] = 'Jenis: ' . ucfirst($jenisMenu);
        }
        
        // Format text untuk harga
        if ($filterHarga !== 'all') {
            $filterText[] = 'Harga: ' . (Alternatif::KATEGORI_HARGA[$filterHarga] ?? $filterHarga);
        }
        
        if (count($filterText) > 0) {
            $judul .= ' (' . implode(', ', $filterText) . ')';
        }

        // Data kriteria
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        
        // Query dengan filter untuk hasil akhir
        $query = NilaiAkhir::with('alternatif');
        
        // Filter jenis menu
        if ($jenisMenu && $jenisMenu !== 'all') {
            $query->whereHas('alternatif', function($q) use ($jenisMenu) {
                $q->where('jenis_menu', $jenisMenu);
            });
        }
        
        // Filter harga (kategori)
        if ($filterHarga && $filterHarga !== 'all') {
            $query->whereHas('alternatif', function($q) use ($filterHarga) {
                $q->where('harga', $filterHarga);
            });
        }
        
        // Get data yang sudah difilter
        $nilaiAkhir = $query->orderByDesc('total')->get();
        
        // Re-rank setelah filter
        $tabelPerankingan = $nilaiAkhir->map(function($item, $index) {
            $item->peringkat = $index + 1;
            
            // Konversi gambar ke base64 untuk PDF
            $alt = $item->alternatif;
            if ($alt && $alt->gambar) {
                $imagePath = public_path('img/menu/' . $alt->gambar);
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $item->image_base64 = 'data:image/' . $imageType . ';base64,' . $imageData;
                } else {
                    $item->image_base64 = null;
                }
            } else {
                $item->image_base64 = null;
            }
            
            return $item;
        });
        
        // Data alternatif yang sesuai filter
        $alternatifIds = $nilaiAkhir->pluck('alternatif_id');
        $alternatif = Alternatif::whereIn('id', $alternatifIds)->get();
        
        // Data penilaian yang sesuai filter
        $tabelPenilaian = Penilaian::with(['kriteria', 'subKriteria', 'alternatif'])
            ->whereIn('alternatif_id', $alternatifIds)
            ->get();
        
        // Info tambahan  
        $tanggal_cetak = now()->format('d F Y');
        $filter_info = $jenisMenu !== 'all' ? ucfirst($jenisMenu) : 'Semua Jenis';
        
        // Format display untuk filter yang digunakan
        $display_harga = '';
        if ($filterHarga !== 'all' && isset(Alternatif::KATEGORI_HARGA[$filterHarga])) {
            $display_harga = Alternatif::KATEGORI_HARGA[$filterHarga];
        }

        // Generate PDF dengan option untuk image
        $pdf = PDF::setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'enable_remote' => true
        ])->loadview('dashboard.pdf.hasil_akhir', compact(
            'judul',
            'tanggal_cetak',
            'tabelPenilaian', 
            'tabelPerankingan',
            'kriteria',
            'alternatif',
            'filter_info',
            'user',
            'jenisMenu',
            'filterHarga',
            'display_harga'
        ));

        $pdf->setPaper('A4', 'portrait');
        
        // Nama file dengan filter info
        $filename = 'Laporan_Menu_';
        if ($jenisMenu !== 'all') {
            $filename .= ucfirst($jenisMenu) . '_';
        }
        
        // Tambahkan info harga ke filename
        if ($filterHarga && $filterHarga !== 'all') {
            $filename .= 'Harga_' . str_replace(['<', '>', '=', '-'], '', $filterHarga) . '_';
        }
        
        $filename .= date('Y-m-d') . '.pdf';
        
        return $pdf->stream($filename);
    }
}