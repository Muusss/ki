<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\NilaiAkhir;
use App\Models\Permintaan;

class PublicController extends Controller
{
    /**
     * Halaman Jenis Kulit
     */
    public function jenisKulit()
    {
        $jenisKulit = [
            'normal' => [
                'title' => 'Kulit Normal',
                'image' => 'https://images.unsplash.com/photo-1520721448897-116030851143?w=500',
                'ciri' => [
                    'Tekstur kulit halus dan lembut',
                    'Pori-pori hampir tidak terlihat',
                    'Tidak terlalu berminyak atau kering',
                    'Jarang mengalami jerawat',
                    'Warna kulit merata',
                    'Elastisitas kulit baik'
                ],
                'rekomendasi' => [
                    'Gunakan sunscreen dengan tekstur ringan',
                    'Pilih SPF 30-50 untuk perlindungan optimal',
                    'Sunscreen dengan moisturizer ringan',
                    'Formula yang tidak lengket',
                    'Bisa menggunakan chemical atau physical sunscreen'
                ],
                'produk_cocok' => 'Gel, Lotion ringan, Cream ringan'
            ],
            'berminyak' => [
                'title' => 'Kulit Berminyak',
                'image' => 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?w=500',
                'ciri' => [
                    'Kulit terlihat mengkilap terutama di T-zone',
                    'Pori-pori besar dan terlihat jelas',
                    'Mudah berjerawat dan komedo',
                    'Makeup mudah luntur',
                    'Tekstur kulit tebal',
                    'Produksi sebum berlebih'
                ],
                'rekomendasi' => [
                    'Pilih sunscreen oil-free atau non-comedogenic',
                    'Tekstur gel atau water-based lebih cocok',
                    'Hindari sunscreen dengan kandungan minyak',
                    'Pilih yang mengandung niacinamide',
                    'SPF 30-50 dengan formula mattifying'
                ],
                'produk_cocok' => 'Gel, Water-based, Spray'
            ],
            'kering' => [
                'title' => 'Kulit Kering',
                'image' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=500',
                'ciri' => [
                    'Kulit terasa kencang dan kasar',
                    'Mudah mengelupas dan bersisik',
                    'Pori-pori sangat kecil',
                    'Garis halus lebih terlihat',
                    'Kulit terlihat kusam',
                    'Sering terasa gatal'
                ],
                'rekomendasi' => [
                    'Pilih sunscreen dengan moisturizer tinggi',
                    'Tekstur cream atau balm lebih cocok',
                    'Cari yang mengandung hyaluronic acid',
                    'Hindari sunscreen dengan alkohol tinggi',
                    'Physical sunscreen lebih lembut untuk kulit kering'
                ],
                'produk_cocok' => 'Cream, Balm, Lotion kaya'
            ],
            'kombinasi' => [
                'title' => 'Kulit Kombinasi',
                'image' => 'https://images.unsplash.com/photo-1588392382834-a891154bca4d?w=500',
                'ciri' => [
                    'T-zone berminyak (dahi, hidung, dagu)',
                    'Pipi cenderung normal atau kering',
                    'Pori-pori besar di area T-zone',
                    'Blackhead di area hidung',
                    'Tekstur kulit tidak merata',
                    'Kebutuhan perawatan berbeda di tiap area'
                ],
                'rekomendasi' => [
                    'Pilih sunscreen dengan formula balanced',
                    'Tekstur lotion ringan paling cocok',
                    'Cari yang oil-control tapi tetap melembabkan',
                    'Bisa gunakan sunscreen berbeda untuk area berbeda',
                    'SPF 30-50 dengan formula hybrid'
                ],
                'produk_cocok' => 'Lotion, Gel-cream, Serum sunscreen'
            ]
        ];

        return view('public.jenis-kulit', compact('jenisKulit'));
    }

    /**
     * Halaman Permintaan
     */
    public function permintaan()
    {
        $permintaan = Permintaan::orderBy('created_at', 'desc')->get();
        return view('public.permintaan', compact('permintaan'));
    }

    /**
     * Store Permintaan dari Public
     */
    public function storePermintaan(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'komposisi' => 'required|string',
            'harga' => 'required|in:<50k,50-100k,>100k',
            'spf' => 'required|in:30,35,40,50+',
        ]);

        // Tambahkan flag bahwa ini dari public
        $data['status'] = 'pending'; // Anda perlu menambahkan kolom status di tabel permintaans
        
        Permintaan::create($data);

        return redirect()->route('public.permintaan')
            ->with('success', 'Permintaan Anda telah berhasil dikirim! Admin akan memverifikasi permintaan Anda.');
    }

    /**
     * Halaman Hasil SPK
     */
    public function hasilSPK(Request $request)
    {
        // Filter
        $jenisKulit = $request->get('jenis_kulit', 'all');
        $filterHarga = $request->get('harga', 'all');
        $filterSpf = $request->get('spf', 'all');

        // Query dengan filter
        $query = NilaiAkhir::with(['alternatif']);

        // Filter jenis kulit
        if ($jenisKulit !== 'all') {
            $query->whereHas('alternatif', function($q) use ($jenisKulit) {
                $q->where('jenis_kulit', $jenisKulit);
            });
        }

        // Filter harga
        if ($filterHarga !== 'all') {
            $hargaRange = $this->parseHargaFilter($filterHarga);
            $query->whereHas('alternatif', function($q) use ($hargaRange) {
                if ($hargaRange['min'] !== null) {
                    $q->where('harga', '>=', $hargaRange['min']);
                }
                if ($hargaRange['max'] !== null) {
                    $q->where('harga', '<=', $hargaRange['max']);
                }
            });
        }

        // Filter SPF
        if ($filterSpf !== 'all') {
            $query->whereHas('alternatif', function($q) use ($filterSpf) {
                $q->where('spf', $filterSpf);
            });
        }

        $nilaiAkhir = $query->orderByDesc('total')->get();

        // Re-rank setelah filter
        $nilaiAkhir = $nilaiAkhir->map(function ($item, $index) {
            $item->peringkat_filter = $index + 1;
            return $item;
        });

        $jenisKulitList = ['normal', 'berminyak', 'kering', 'kombinasi'];

        return view('public.hasil-spk', compact(
            'nilaiAkhir',
            'jenisKulit',
            'filterHarga',
            'filterSpf',
            'jenisKulitList'
        ));
    }

    /**
     * Parse filter harga
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
}