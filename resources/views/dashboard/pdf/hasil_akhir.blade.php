{{-- resources/views/dashboard/pdf/hasil_produk.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
        }
        
        /* Header Styles */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11pt;
            margin: 2px 0;
        }
        
        /* Title Section */
        .title-section {
            text-align: center;
            margin: 20px 0;
        }
        
        .title-section h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        /* Info Box */
        .info-box {
            margin: 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin: 3px 0;
        }
        
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
        }
        
        .info-value {
            display: table-cell;
        }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        table thead {
            background-color: #3498db;
            color: white;
        }
        
        table th {
            padding: 8px;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            border: 1px solid #333;
        }
        
        table td {
            padding: 6px 8px;
            font-size: 10pt;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* Special Rows */
        .ranking-1 {
            background-color: #ffd700 !important;
            font-weight: bold;
        }
        
        .ranking-2 {
            background-color: #c0c0c0 !important;
        }
        
        .ranking-3 {
            background-color: #cd7f32 !important;
        }
        
        /* Summary Box */
        .summary-box {
            margin-top: 30px;
            padding: 15px;
            background-color: #e8f4f8;
            border: 2px solid #3498db;
            border-radius: 5px;
        }
        
        .summary-box h4 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c5282;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
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

    {{-- Info Box --}}
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Filter Jenis Kulit</span>
            <span class="info-value">: {{ $filter_info }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Produk</span>
            <span class="info-value">: {{ $alternatif->count() }} Produk</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Kriteria</span>
            <span class="info-value">: {{ $kriteria->count() }} Kriteria</span>
        </div>
        <div class="info-row">
            <span class="info-label">Petugas</span>
            <span class="info-value">: {{ $user->name ?? 'Administrator' }}</span>
        </div>
    </div>

    {{-- Tabel Kriteria & Bobot --}}
    <h4 class="mt-20 mb-10">1. KRITERIA PENILAIAN DAN BOBOT ROC</h4>
    <table>
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="12%">Kode</th>
                <th width="35%">Kriteria</th>
                <th width="15%">Prioritas</th>
                <th width="15%">Bobot ROC</th>
                <th width="15%">Atribut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kriteria as $index => $k)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $k->kode }}</td>
                <td>{{ $k->kriteria }}</td>
                <td class="text-center">{{ $k->urutan_prioritas }}</td>
                <td class="text-center">{{ number_format($k->bobot_roc, 4) }}</td>
                <td class="text-center">{{ ucfirst($k->atribut) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right font-bold">Total Bobot:</td>
                <td class="text-center font-bold">{{ number_format($kriteria->sum('bobot_roc'), 4) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- Tabel Hasil Perankingan --}}
    <h4 class="mt-20 mb-10">2. HASIL PERANKINGAN PRODUK SUNSCREEN</h4>
    <table>
        <thead>
            <tr>
                <th width="8%">Rank</th>
                <th width="15%">Kode</th>
                <th width="35%">Nama Produk</th>
                <th width="17%">Jenis Kulit</th>
                <th width="12%">Nilai Total</th>
                <th width="13%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tabelPerankingan as $item)
            <tr class="{{ $item->peringkat == 1 ? 'ranking-1' : ($item->peringkat == 2 ? 'ranking-2' : ($item->peringkat == 3 ? 'ranking-3' : '')) }}">
                <td class="text-center font-bold">{{ $item->peringkat }}</td>
                <td class="text-center">{{ $item->kode_produk }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td class="text-center">{{ ucfirst($item->jenis_kulit) }}</td>
                <td class="text-center font-bold">{{ number_format($item->nilai, 4) }}</td>
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

    {{-- Summary Box --}}
    @if($tabelPerankingan->first())
    <div class="summary-box">
        <h4>KESIMPULAN</h4>
        <p>Berdasarkan hasil perhitungan menggunakan metode ROC (Rank Order Centroid) untuk pembobotan kriteria 
        dan metode SMART untuk normalisasi nilai, maka produk sunscreen yang direkomendasikan 
        @if($filter_info !== 'Semua Jenis')
        untuk jenis kulit <strong>{{ $filter_info }}</strong>
        @endif
        adalah:</p>
        
        <div style="margin: 15px 0; padding: 10px; background: white; border-left: 4px solid #3498db;">
            <table style="border: none; margin: 0;">
                <tr>
                    <td style="border: none; width: 120px;"><strong>Nama Produk</strong></td>
                    <td style="border: none;">: {{ $tabelPerankingan->first()->nama_produk }}</td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>Kode</strong></td>
                    <td style="border: none;">: {{ $tabelPerankingan->first()->kode_produk }}</td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>Jenis Kulit</strong></td>
                    <td style="border: none;">: {{ ucfirst($tabelPerankingan->first()->jenis_kulit) }}</td>
                </tr>
                <tr>
                    <td style="border: none;"><strong>Nilai Total</strong></td>
                    <td style="border: none;">: {{ number_format($tabelPerankingan->first()->nilai, 4) }}</td>
                </tr>
            </table>
        </div>
    </div>
    @endif

    {{-- Footer / Tanda Tangan --}}
    <div class="footer">
        <p class="text-center mb-20">{{ now()->isoFormat('D MMMM Y') }}</p>
        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p>Administrator</p>
                <div class="signature-line"></div>
                <p class="font-bold">{{ $user->name ?? '(.................................)' }}</p>
            </div>
        </div>
    </div>
</body>
</html>