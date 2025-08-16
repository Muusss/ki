<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        /* Style existing */
        @page { margin: 2cm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #333; }
        
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 15px; }
        .header h1 { font-size: 18pt; font-weight: bold; margin-bottom: 5px; color: #2c3e50; }
        .header h2 { font-size: 14pt; font-weight: bold; margin-bottom: 5px; }
        .header p { font-size: 11pt; margin: 2px 0; }
        
        .title-section { text-align: center; margin: 20px 0; }
        .title-section h3 { font-size: 14pt; font-weight: bold; text-decoration: underline; margin-bottom: 5px; }
        
        .info-box { margin: 15px 0; padding: 10px; background-color: #f5f5f5; border: 1px solid #ddd; border-radius: 5px; }
        .info-row { display: table; width: 100%; margin: 3px 0; }
        .info-label { display: table-cell; width: 150px; font-weight: bold; }
        .info-value { display: table-cell; }
        
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table thead { background-color: #3498db; color: white; }
        table th { padding: 8px; text-align: center; font-size: 11pt; font-weight: bold; border: 1px solid #333; }
        table td { padding: 6px 8px; font-size: 10pt; border: 1px solid #ddd; vertical-align: middle; }
        table tbody tr:nth-child(even) { background-color: #f9f9f9; }
        
        .ranking-1 { background-color: #ffd700 !important; font-weight: bold; }
        .ranking-2 { background-color: #c0c0c0 !important; }
        .ranking-3 { background-color: #cd7f32 !important; }
        
        /* Style untuk gambar produk */
        .product-image { 
            width: 60px; 
            height: 60px; 
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .no-image-box {
            width: 60px;
            height: 60px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            font-size: 9pt;
            color: #999;
            text-align: center;
        }
        
        /* Top 3 Products dengan gambar */
        .top-products { margin: 20px 0; }
        .top-product-card {
            page-break-inside: avoid;
            margin-bottom: 15px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .top-product-card.rank-1 { border-color: #ffd700; background-color: #fffdf0; }
        .top-product-card.rank-2 { border-color: #c0c0c0; background-color: #fafafa; }
        .top-product-card.rank-3 { border-color: #cd7f32; background-color: #fff9f5; }
        
        .product-detail { display: table; width: 100%; }
        .product-img-container { display: table-cell; width: 100px; vertical-align: top; }
        .product-info { display: table-cell; padding-left: 15px; vertical-align: top; }
        .product-large-image { 
            width: 80px; 
            height: 80px; 
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 6px;
        }
        
        .summary-box { margin-top: 30px; padding: 15px; background-color: #e8f4f8; border: 2px solid #3498db; border-radius: 5px; }
        .summary-box h4 { font-size: 12pt; font-weight: bold; margin-bottom: 10px; color: #2c5282; }
        
        .footer { margin-top: 40px; page-break-inside: avoid; }
        .signature-section { display: table; width: 100%; margin-top: 30px; }
        .signature-box { display: table-cell; width: 50%; text-align: center; vertical-align: top; padding: 0 10px; }
        .signature-line { margin-top: 60px; border-bottom: 1px solid #000; width: 80%; margin-left: auto; margin-right: auto; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>SISTEM REKOMENDASI SUNSCREEN</h1>
        <p>Metode ROC (Rank Order Centroid) & SMART</p>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

    {{-- Title --}}
    <div class="title-section">
        <h3>{{ strtoupper($judul) }}</h3>
    </div>

    {{-- Info Box dengan Filter Detail --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Filter Jenis Kulit</span>
            <span class="info-value">: {{ $filter_info }}</span>
        </div>
        
        @if(!empty($display_harga))
        <div class="info-row">
            <span class="info-label">Filter Harga</span>
            <span class="info-value">: {{ $display_harga }}</span>
        </div>
        @endif
        
        @if(!empty($display_spf))
        <div class="info-row">
            <span class="info-label">Filter SPF</span>
            <span class="info-value">: {{ $display_spf }}</span>
        </div>
        @endif
        
        <div class="info-row">
            <span class="info-label">Jumlah Produk</span>
            <span class="info-value">: {{ $tabelPerankingan->count() }} Produk
            @if($jenisKulit !== 'all' || !empty($display_harga) || !empty($display_spf))
                (hasil filter)
            @else
                (semua produk)
            @endif
            </span>
        </div>
        
        @if(isset($user) && $user)
        <div class="info-row">
            <span class="info-label">Dicetak Oleh</span>
            <span class="info-value">: {{ $user->name ?? 'Guest' }}</span>
        </div>
        @else
        <div class="info-row">
            <span class="info-label">Sumber</span>
            <span class="info-value">: Sistem Publik</span>
        </div>
        @endif
    </div>

    {{-- TOP 3 PRODUK DENGAN GAMBAR --}}
    @if($tabelPerankingan->count() >= 3)
    <h4 style="margin: 20px 0 10px;">TOP 3 PRODUK TERBAIK</h4>
    <div class="top-products">
        @foreach($tabelPerankingan->take(3) as $item)
        @php
            $alt = $item->alternatif;
        @endphp
        <div class="top-product-card rank-{{ $item->peringkat }}">
            <div class="product-detail">
                <div class="product-img-container">
                    @if($item->image_base64)
                        <img src="{{ $item->image_base64 }}" class="product-large-image" />
                    @else
                        <div style="width: 80px; height: 80px; border: 2px solid #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; font-size: 10pt; color: #999;">
                            No Image
                        </div>
                    @endif
                </div>
                <div class="product-info">
                    <h4 style="margin: 0 0 5px; color: {{ $item->peringkat == 1 ? '#d4af37' : ($item->peringkat == 2 ? '#999' : '#cd7f32') }};">
                        Peringkat {{ $item->peringkat }}
                    </h4>
                    <strong>{{ $alt->nama_produk ?? '-' }}</strong> ({{ $alt->kode_produk ?? '-' }})<br>
                    Jenis Kulit: {{ ucfirst($alt->jenis_kulit ?? '-') }}<br>
                    Harga: @if($alt && $alt->harga) Rp {{ number_format($alt->harga, 0, ',', '.') }} @else - @endif<br>
                    SPF: {{ $alt->spf ?? '-' }}<br>
                    <strong>Nilai Total: {{ number_format($item->total, 4) }}</strong>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabel Hasil Perankingan Lengkap --}}
    <h4 style="margin: 20px 0 10px;">TABEL PERANKINGAN LENGKAP</h4>
    @if($tabelPerankingan->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th width="15%">Gambar</th>
                <th width="10%">Kode</th>
                <th width="22%">Nama Produk</th>
                <th width="10%">Jenis Kulit</th>
                <th width="10%">Harga</th>
                <th width="8%">SPF</th>
                <th width="10%">Nilai</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tabelPerankingan as $item)
            @php
                $alt = $item->alternatif;
            @endphp
            <tr class="{{ $item->peringkat == 1 ? 'ranking-1' : ($item->peringkat == 2 ? 'ranking-2' : ($item->peringkat == 3 ? 'ranking-3' : '')) }}">
                <td class="text-center font-bold">{{ $item->peringkat }}</td>
                <td class="text-center">
                    @if($item->image_base64)
                        <img src="{{ $item->image_base64 }}" class="product-image" />
                    @else
                        <div style="width: 60px; height: 60px; margin: 0 auto; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; font-size: 9pt; color: #999;">
                            No Image
                        </div>
                    @endif
                </td>
                <td class="text-center">{{ $alt->kode_produk ?? '-' }}</td>
                <td>{{ $alt->nama_produk ?? '-' }}</td>
                <td class="text-center">{{ ucfirst($alt->jenis_kulit ?? '-') }}</td>
                <td class="text-center">
                    @if($alt && $alt->harga)
                        Rp {{ number_format($alt->harga, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $alt->spf ?? '-' }}</td>
                <td class="text-center font-bold">{{ number_format($item->total, 4) }}</td>
                <td class="text-center">
                    @if($item->peringkat == 1)
                        <strong>TERBAIK</strong>
                    @elseif($item->peringkat <= 3)
                        Nominasi
                    @else
                        Partisipan
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary Box dengan Gambar Produk Terbaik --}}
    @if($tabelPerankingan->first())
    @php
        $topProduct = $tabelPerankingan->first();
        $altTop = $topProduct->alternatif;
    @endphp
    <div class="summary-box">
        <h4>KESIMPULAN & REKOMENDASI</h4>
        <p>Berdasarkan hasil perhitungan dengan metode ROC + SMART
        @if($jenisKulit !== 'all' || !empty($display_harga) || !empty($display_spf))
            dan filter yang diterapkan,
        @endif
        produk sunscreen terbaik yang direkomendasikan adalah:</p>
        
        <div style="margin: 15px 0; padding: 10px; background: white; border-left: 4px solid #3498db;">
            <div class="product-detail">
                @if($topProduct->image_base64)
                <div class="product-img-container">
                    <img src="{{ $topProduct->image_base64 }}" class="product-large-image" />
                </div>
                @endif
                <div class="product-info">
                    <table style="border: none; margin: 0;">
                        <tr>
                            <td style="border: none; width: 120px;"><strong>Nama Produk</strong></td>
                            <td style="border: none;">: {{ $altTop->nama_produk ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;"><strong>Kode</strong></td>
                            <td style="border: none;">: {{ $altTop->kode_produk ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;"><strong>Jenis Kulit</strong></td>
                            <td style="border: none;">: {{ ucfirst($altTop->jenis_kulit ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;"><strong>Harga</strong></td>
                            <td style="border: none;">: 
                                @if($altTop && $altTop->harga)
                                    Rp {{ number_format($altTop->harga, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none;"><strong>SPF</strong></td>
                            <td style="border: none;">: {{ $altTop->spf ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;"><strong>Nilai Total</strong></td>
                            <td style="border: none;">: {{ number_format($topProduct->total, 4) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <p style="margin-top: 10px; font-style: italic; font-size: 10pt;">
            @if($jenisKulit !== 'all')
                * Rekomendasi ini khusus untuk jenis kulit {{ strtolower($filter_info) }}.
            @endif
            @if(!empty($display_harga) || !empty($display_spf))
                Hasil berdasarkan filter yang diterapkan.
            @endif
        </p>
    </div>
    @endif
    @else
    <div style="text-align: center; padding: 20px; border: 1px solid #ddd; background: #f9f9f9;">
        <p><strong>Tidak ada produk yang sesuai dengan filter yang diterapkan.</strong></p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p style="text-align: center; font-style: italic; font-size: 10pt; margin-top: 20px;">
            Dokumen ini dicetak dari Sistem Rekomendasi Sunscreen
            @if(isset($user) && $user)
                - {{ config('app.name', 'SPK Sunscreen') }}
            @else
                - Versi Publik
            @endif
        </p>
    </div>
</body>
</html>