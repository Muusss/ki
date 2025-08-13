<?php
// app/Http/Controllers/PDFController.php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function pdf_hasil()
    {
        $judul = 'Laporan Hasil Akhir';
        $user = Auth::user();

        // Ambil filter kelas dari query (?kelas=...); default 'all'
        $kelasFilter = request()->get('kelas', 'all');


        // Update judul bila ada filter kelas
        if ($kelasFilter && $kelasFilter !== 'all') {
            $judul .= ' - Kelas ' . $kelasFilter;
        }

        // Helper closure untuk filter kelas
        $filterKelas = function($q) use ($kelasFilter) {
            if ($kelasFilter && $kelasFilter !== 'all') {
                $q->where('kelas', $kelasFilter);
            }
        };

        // Data kriteria
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        
        // Data alternatif
        $alternatif = Alternatif::query()
            ->when($kelasFilter && $kelasFilter !== 'all', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            })
            ->orderBy('kode_produk')
            ->get();
        
        // Data penilaian dengan fix null handling
        $tabelPenilaian = Penilaian::with(['kriteria', 'subKriteria', 'alternatif'])
            ->whereHas('alternatif', $filterKelas)
            ->get()
            ->map(function($item) {
                return (object)[
                    'alternatif' => $item->alternatif,
                    'kriteria' => $item->kriteria,
                    'sub_kriteria' => $item->subKriteria ? $item->subKriteria->label : 'Nilai: ' . $item->nilai_asli,
                    'nilai_asli' => $item->nilai_asli,
                    'nilai_normal' => $item->nilai_normal
                ];
            });
        
        // Data hasil akhir dengan ranking
        $tabelPerankingan = NilaiAkhir::query()
            ->join('alternatifs as a', 'a.id', '=', 'nilai_akhirs.alternatif_id')
            ->selectRaw("a.kode_produk as kode, a.nama_produk as alternatif, nilai_akhirs.total as nilai")
            ->when($kelasFilter && $kelasFilter !== 'all', function($q) use ($kelasFilter) {
                $q->where('a.kelas', $kelasFilter);
            })
            ->orderBy('nilai', 'desc')
            ->get();
        
        // Info tambahan
        $tanggal_cetak = now()->format('d F Y');

        // Generate PDF
        $pdf = PDF::setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadview('dashboard.pdf.hasil_akhir', compact(
            'judul',
            'tanggal_cetak',
            'tabelPenilaian', 
            'tabelPerankingan',
            'kriteria',
            'alternatif'
        ));

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Laporan_Produk_' . date('Y-m-d') . '.pdf');
    }
}