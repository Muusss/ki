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

    <!-- Produk Grid Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="custom-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Produk Sunscreen Terdaftar
                    </h6>
                    <a href="{{ route('alternatif') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Kelola Produk
                    </a>
                </div>
                
                <!-- Product Grid -->
                <div class="row product-showcase">
                    @php
                        $products = \App\Models\Alternatif::orderBy('kode_produk')->limit(8)->get();
                    @endphp
                    
                    @forelse($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card-mini h-100">
                            <div class="product-image-mini">
                                @if($product->gambar && file_exists(public_path('img/produk/'.$product->gambar)))
                                    <img src="{{ asset('img/produk/'.$product->gambar) }}" 
                                         alt="{{ $product->nama_produk }}"
                                         class="img-fluid">
                                @else
                                    <div class="no-image-mini">
                                        <i class="bi bi-image"></i>
                                        <small>No Image</small>
                                    </div>
                                @endif
                                <span class="badge-skin badge bg-{{ 
                                    $product->jenis_kulit == 'normal' ? 'success' : 
                                    ($product->jenis_kulit == 'berminyak' ? 'warning' : 
                                    ($product->jenis_kulit == 'kering' ? 'info' : 'secondary')) 
                                }}">
                                    {{ ucfirst($product->jenis_kulit) }}
                                </span>
                            </div>
                            <div class="product-info-mini">
                                <h6 class="product-name">{{ $product->nama_produk }}</h6>
                                <small class="text-muted">{{ $product->kode_produk }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Belum ada produk terdaftar</p>
                            <a href="{{ route('alternatif') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Produk
                            </a>
                        </div>
                    </div>
                    @endforelse
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

        <!-- Top 5 dengan Gambar -->
        <div class="col-xl-4 col-lg-5">
            <div class="custom-table">
                <h6 class="m-0 font-weight-bold text-primary mb-3">
                    Top 5 Produk Teratas
                </h6>
                @forelse(($top5 ?? []) as $index => $item)
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded top-product-item">
                        <div class="me-3">
                            @if($item->alternatif->gambar && file_exists(public_path('img/produk/'.$item->alternatif->gambar)))
                                <img src="{{ asset('img/produk/'.$item->alternatif->gambar) }}" 
                                     alt="{{ $item->alternatif->nama_produk ?? '-' }}"
                                     class="rounded product-thumb">
                            @else
                                <div class="product-thumb-placeholder rounded">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
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
                                    <th>Gambar</th>
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
                                        <td>
                                            @if($row->alternatif->gambar && file_exists(public_path('img/produk/'.$row->alternatif->gambar)))
                                                <img src="{{ asset('img/produk/'.$row->alternatif->gambar) }}" 
                                                     alt="{{ $row->alternatif->nama_produk ?? '-' }}"
                                                     class="table-product-img">
                                            @else
                                                <div class="table-product-img-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
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

@section('css')
<style>
/* Product Showcase Styles */
.product-showcase {
    margin: 0 -5px;
}

.product-showcase > div {
    padding: 0 5px;
}

.product-card-mini {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-card-mini:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.product-image-mini {
    position: relative;
    height: 150px;
    background: #f8f9fa;
    overflow: hidden;
}

.product-image-mini img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image-mini {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #dee2e6;
}

.no-image-mini i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.badge-skin {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.product-info-mini {
    padding: 0.75rem;
}

.product-name {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Top Product List Styles */
.top-product-item {
    transition: all 0.3s ease;
}

.top-product-item:hover {
    background-color: #e9ecef !important;
    transform: translateX(5px);
}

.product-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
}

.product-thumb-placeholder {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dee2e6;
}

/* Table Product Images */
.table-product-img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 8px;
}

.table-product-img-placeholder {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dee2e6;
    font-size: 0.875rem;
}

/* Stat Cards Enhancement */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

/* Custom Table Enhancement */
.custom-table {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    margin-bottom: 1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-image-mini {
        height: 120px;
    }
    
    .product-card-mini {
        margin-bottom: 1rem;
    }
    
    .table-product-img,
    .table-product-img-placeholder {
        width: 30px;
        height: 30px;
    }
}
</style>
@endsection

@section('js')
<script>
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

    // Product card click handler
    $('.product-card-mini').click(function() {
        window.location.href = '{{ route("alternatif") }}';
    });
});
</script>
@endsection