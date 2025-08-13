@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Statistik Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Total Produk
                        </div>
                        <div class="h4 mb-0 font-weight-bold">{{ $jumlahProduk ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Kriteria Penilaian
                        </div>
                        <div class="h4 mb-0 font-weight-bold">{{ $jumlahKriteria ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Data Penilaian
                        </div>
                        <div class="h4 mb-0 font-weight-bold">{{ $jumlahPenilaian ?? 0 }}</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Produk Teratas
                        </div>
                        @php
                            $first = isset($nilaiAkhir) && $nilaiAkhir->count() > 0 ? $nilaiAkhir->first() : null;
                            $topName = $first ? $first->alternatif->nama_produk : '-';
                        @endphp
                        <div class="h5 mb-0 font-weight-bold">{{ $topName }}</div>
                    </div>
                    <div class="icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="row">
        <!-- Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="custom-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Grafik Nilai Produk 
                    </h6>
                </div>
                <div id="chart_peringkat" style="min-height: 350px;"></div>
            </div>
        </div>

        {{-- Tambahkan di resources/views/dashboard/index.blade.php --}}
        <!-- Top 5 dengan Gambar -->
        <div class="col-xl-4 col-lg-5">
            <div class="custom-table">
                <h6 class="m-0 font-weight-bold text-primary mb-3">
                    Top 5 Produk Teratas
                </h6>
                @forelse(($top5 ?? []) as $index => $item)
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <div class="me-3">
                            <img src="{{ $item->alternatif->gambar_url ?? asset('img/no-image.png') }}" 
                                alt="{{ $item->alternatif->nama_produk ?? '-' }}"
                                class="rounded"
                                style="width: 50px; height: 50px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $item->alternatif->nama_produk ?? '-' }}</h6>
                            <small class="text-muted">{{ $item->alternatif->kode_produk ?? '-' }}</small>
                        </div>
                        <div>
                            <span class="badge bg-primary">
                                {{ number_format((float) ($item->total ?? 0), 3) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Full Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="custom-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Hasil Perankingan Produk 
                    </h6>
                    <div>
                        <button class="btn btn-sm btn-info" onclick="exportToExcel()">
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </button>
                        <a href="{{ route('pdf.hasilAkhir') }}" target="_blank" class="btn btn-sm btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
                        <table class="table" id="rankingTable">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Jenis Kulit</th>
                                    <th>Nilai Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilaiAkhir as $row)
                                    <tr class="{{ $loop->iteration <= 3 ? 'table-success' : '' }}">
                                        <td>
                                            <span class="badge bg-{{ $loop->iteration == 1 ? 'warning text-dark' : ($loop->iteration <= 3 ? 'info' : 'secondary') }}">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>
                                        <td>{{ $row->alternatif->kode_produk ?? '-' }}</td>
                                        <td><strong>{{ $row->alternatif->nama_produk ?? '-' }}</strong></td>
                                        <td>
                                            <span class="badge bg-primary">{{ $row->alternatif->jenis_kulit ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ number_format((float) ($row->total ?? 0), 4) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($loop->iteration == 1)
                                                <span class="badge bg-success">Produk Teratas</span>
                                            @elseif($loop->iteration <= 3)
                                                <span class="badge bg-info">Nominasi</span>
                                            @else
                                                <span class="badge bg-light text-dark">Partisipan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <h5 class="mt-3">Belum ada data hasil perhitungan</h5>
                            <p class="text-muted">Silakan lakukan perhitungan ROC + SMART terlebih dahulu</p>
                            <a href="{{ route('perhitungan') }}" class="btn btn-primary">
                                <i class="bi bi-calculator"></i> Mulai Perhitungan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Export functions
function exportToExcel() {
    alert('Fitur export Excel akan segera tersedia');
}

$(document).ready(function() {
    // Initialize DataTables only if there's data
    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
        $('#rankingTable').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir", 
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data tersedia"
            }
        });
    @endif

    // Chart
    const chartData = @json($chartSeries ?? []);
    const chartLabels = @json($chartLabels ?? []);

    if(Array.isArray(chartData) && chartData.length > 0) {
        const options = {
            series: [{
                name: 'Nilai Total',
                data: chartData
            }],
            chart: { 
                type: 'bar', 
                height: 350, 
                toolbar: { show: true } 
            },
            colors: ['#4e73df'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: false,
                    columnWidth: '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(3);
                },
                offsetY: -20,
                style: {
                    fontSize: '10px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: chartLabels,
                labels: { 
                    rotate: -45,
                    style: { fontSize: '11px' } 
                }
            },
            yaxis: {
                title: { text: 'Nilai Total' }
            },
            grid: {
                borderColor: '#e3e6f0'
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart_peringkat"), options);
        chart.render();
    } else {
        // Show no data message for chart
        document.querySelector("#chart_peringkat").innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-bar-chart" style="font-size: 3rem; color: #ccc;"></i>
                <h5 class="mt-3">Belum ada data untuk grafik</h5>
                <p class="text-muted">Data akan muncul setelah perhitungan ROC + SMART</p>
            </div>
        `;
    }
});
</script>
@endsection