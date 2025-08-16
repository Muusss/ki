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
     * Parse filter harga dari dropdown (sama dengan DashboardController & PublicController)
     */
    private function parseHargaFilter($hargaFilter)
    {
        switch ($hargaFilter) {
            case '<=40000':
                return ['min' => null, 'max' => 40000];
            case '40001-60000':
                return ['min' => 40001, 'max' => 60000];
            case '60001-80000':
                return ['min' => 60001, 'max' => 80000];
            case '>80000':
                return ['min' => 80001, 'max' => null];
            default:
                return ['min' => null, 'max' => null];
        }
    }

    /**
     * Parse filter SPF dari dropdown
     */
    private function parseSpfFilter($spfFilter)
    {
        if ($spfFilter === 'all' || empty($spfFilter)) {
            return ['min' => null, 'max' => null];
        }
        
        // Jika nilai spesifik (30, 35, 40, 50)
        if (is_numeric($spfFilter)) {
            return ['min' => (int)$spfFilter, 'max' => (int)$spfFilter];
        }
        
        return ['min' => null, 'max' => null];
    }

    public function pdf_hasil(Request $request)
    {
        $judul = 'Laporan Hasil Akhir Rekomendasi Sunscreen';
        $user = Auth::user();

        // Ambil filter dari request dengan support untuk kedua format (admin & public)
        $jenisKulit = $request->get('jenis_kulit', 'all');
        
        // Handle filter harga - cek apakah format dropdown atau range
        $filterHarga = $request->get('harga');
        $hargaMin = null;
        $hargaMax = null;
        
        if ($filterHarga && $filterHarga !== 'all') {
            // Format dropdown dari public
            $hargaRange = $this->parseHargaFilter($filterHarga);
            $hargaMin = $hargaRange['min'];
            $hargaMax = $hargaRange['max'];
        } else {
            // Format range dari admin
            $hargaMin = $request->get('harga_min');
            $hargaMax = $request->get('harga_max');
        }
        
        // Handle filter SPF - cek apakah format dropdown atau range
        $filterSpf = $request->get('spf');
        $spfMin = null;
        $spfMax = null;
        
        if ($filterSpf && $filterSpf !== 'all') {
            // Format dropdown dari public
            $spfRange = $this->parseSpfFilter($filterSpf);
            $spfMin = $spfRange['min'];
            $spfMax = $spfRange['max'];
        } else {
            // Format range dari admin
            $spfMin = $request->get('spf_min');
            $spfMax = $request->get('spf_max');
        }

        // Build judul dengan info filter
        $filterText = [];
        if ($jenisKulit !== 'all') {
            $filterText[] = 'Jenis Kulit: ' . ucfirst($jenisKulit);
        }
        
        // Format text untuk harga
        if ($hargaMin !== null || $hargaMax !== null) {
            if ($hargaMin !== null && $hargaMax !== null) {
                $filterText[] = 'Harga: Rp ' . number_format($hargaMin, 0, ',', '.') . 
                            ' - Rp ' . number_format($hargaMax, 0, ',', '.');
            } elseif ($hargaMin !== null) {
                $filterText[] = 'Harga: ≥ Rp ' . number_format($hargaMin, 0, ',', '.');
            } elseif ($hargaMax !== null) {
                $filterText[] = 'Harga: ≤ Rp ' . number_format($hargaMax, 0, ',', '.');
            }
        }
        
        // Format text untuk SPF
        if ($spfMin !== null || $spfMax !== null) {
            if ($spfMin === $spfMax && $spfMin !== null) {
                $filterText[] = 'SPF: ' . $spfMin;
            } elseif ($spfMin !== null && $spfMax !== null) {
                $filterText[] = 'SPF: ' . $spfMin . ' - ' . $spfMax;
            } elseif ($spfMin !== null) {
                $filterText[] = 'SPF: ≥ ' . $spfMin;
            } elseif ($spfMax !== null) {
                $filterText[] = 'SPF: ≤ ' . $spfMax;
            }
        }
        
        if (count($filterText) > 0) {
            $judul .= ' (' . implode(', ', $filterText) . ')';
        }

        // Data kriteria
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        
        // Query dengan filter untuk hasil akhir
        $query = NilaiAkhir::with('alternatif');
        
        // Filter jenis kulit
        if ($jenisKulit && $jenisKulit !== 'all') {
            $query->whereHas('alternatif', function($q) use ($jenisKulit) {
                $q->where('jenis_kulit', $jenisKulit);
            });
        }
        
        // Filter harga
        if ($hargaMin !== null && $hargaMin !== '') {
            $query->whereHas('alternatif', function($q) use ($hargaMin) {
                $q->where('harga', '>=', $hargaMin);
            });
        }
        if ($hargaMax !== null && $hargaMax !== '') {
            $query->whereHas('alternatif', function($q) use ($hargaMax) {
                $q->where('harga', '<=', $hargaMax);
            });
        }
        
        // Filter SPF
        if ($spfMin !== null && $spfMin !== '') {
            $query->whereHas('alternatif', function($q) use ($spfMin) {
                $q->where('spf', '>=', $spfMin);
            });
        }
        if ($spfMax !== null && $spfMax !== '') {
            $query->whereHas('alternatif', function($q) use ($spfMax) {
                $q->where('spf', '<=', $spfMax);
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
                $imagePath = public_path('img/produk/' . $alt->gambar);
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
        $filter_info = $jenisKulit !== 'all' ? ucfirst($jenisKulit) : 'Semua Jenis';
        
        // Format display untuk filter yang digunakan
        $display_harga = '';
        if ($hargaMin !== null || $hargaMax !== null) {
            if ($hargaMin !== null && $hargaMax !== null) {
                $display_harga = 'Rp ' . number_format($hargaMin, 0, ',', '.') . ' - Rp ' . number_format($hargaMax, 0, ',', '.');
            } elseif ($hargaMin !== null) {
                $display_harga = '≥ Rp ' . number_format($hargaMin, 0, ',', '.');
            } elseif ($hargaMax !== null) {
                $display_harga = '≤ Rp ' . number_format($hargaMax, 0, ',', '.');
            }
        }
        
        $display_spf = '';
        if ($spfMin !== null || $spfMax !== null) {
            if ($spfMin === $spfMax && $spfMin !== null) {
                $display_spf = 'SPF ' . $spfMin;
            } elseif ($spfMin !== null && $spfMax !== null) {
                $display_spf = 'SPF ' . $spfMin . ' - ' . $spfMax;
            } elseif ($spfMin !== null) {
                $display_spf = 'SPF ≥ ' . $spfMin;
            } elseif ($spfMax !== null) {
                $display_spf = 'SPF ≤ ' . $spfMax;
            }
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
            'jenisKulit',
            'hargaMin',
            'hargaMax',
            'spfMin',
            'spfMax',
            'filterHarga',
            'filterSpf',
            'display_harga',
            'display_spf'
        ));

        $pdf->setPaper('A4', 'portrait');
        
        // Nama file dengan filter info
        $filename = 'Laporan_Sunscreen_';
        if ($jenisKulit !== 'all') {
            $filename .= ucfirst($jenisKulit) . '_';
        }
        
        // Tambahkan info harga ke filename
        if ($filterHarga && $filterHarga !== 'all') {
            $filename .= 'Harga_' . str_replace(['<', '>', '='], '', $filterHarga) . '_';
        } elseif ($hargaMin || $hargaMax) {
            $filename .= 'Harga_' . ($hargaMin ?: '0') . '-' . ($hargaMax ?: 'max') . '_';
        }
        
        // Tambahkan info SPF ke filename
        if ($filterSpf && $filterSpf !== 'all') {
            $filename .= 'SPF_' . $filterSpf . '_';
        } elseif ($spfMin || $spfMax) {
            $filename .= 'SPF_' . ($spfMin ?: 'min') . '-' . ($spfMax ?: 'max') . '_';
        }
        
        $filename .= date('Y-m-d') . '.pdf';
        
        return $pdf->stream($filename);
    }
}