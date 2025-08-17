@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Dashboard</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Overview</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('hasil-akhir') }}" class="btn btn-outline-secondary">
                <i class="bi bi-trophy"></i> Hasil Akhir
            </a>
            <a href="{{ route('perhitungan') }}" class="btn btn-primary">
                <i class="bi bi-calculator"></i> Perhitungan
            </a>
        </div>
    </div>

    {{-- Welcome Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- User Welcome --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="text-white">Selamat Datang, {{ auth()->user()->name }}!</h4>
                            <p class="mb-0 opacity-90">
                                Sistem Pendukung Keputusan Rekomendasi Menu Cafe Buri Umah menggunakan metode ROC + SMART
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex gap-2 justify-content-md-end">
                                <a href="{{ route('alternatif') }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-plus-circle"></i> Tambah Menu
                                </a>
                                <a href="{{ route('penilaian') }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-clipboard-check"></i> Input Penilaian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-primary">
                <div class="stats-icon"><i class="bi bi-cup-hot"></i></div>
                <div class="stats-content">
                    <p>Total Menu</p>
                    <h4>{{ $jumlahProduk ?? 0 }}</h4>
                    <small class="text-white-50">Menu terdaftar</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-success">
                <div class="stats-icon"><i class="bi bi-list-check"></i></div>
                <div class="stats-content">
                    <p>Kriteria Penilaian</p>
                    <h4>{{ $jumlahKriteria ?? 0 }}</h4>
                    <small class="text-white-50">Kriteria aktif</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-warning">
                <div class="stats-icon"><i class="bi bi-clipboard-data"></i></div>
                <div class="stats-content">
                    <p>Data Penilaian</p>
                    <h4>{{ $jumlahPenilaian ?? 0 }}</h4>
                    <small class="text-white-50">Penilaian selesai</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-info">
                <div class="stats-icon"><i class="bi bi-trophy-fill"></i></div>
                <div class="stats-content">
                    <p>Menu Terbaik</p>
                    @php
                        $first = isset($nilaiAkhir) && $nilaiAkhir->count() > 0 ? $nilaiAkhir->first() : null;
                        $topName = $first && $first->alternatif ? $first->alternatif->nama_menu : '-';
                    @endphp
                    <h6 class="mb-0 text-truncate" title="{{ $topName }}">{{ $topName }}</h6>
                </div>
            </div>
        </div>
    </div>


    {{-- Main Content Row --}}
    <div class="row">
        {{-- Chart --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Grafik Nilai Menu (Top 10)</h6>
                        <div class="text-muted small">
                            <i class="bi bi-graph-up"></i> ROC + SMART Score
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart_peringkat" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        {{-- Top 5 Menu --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Top 5 Menu Terbaik</h6>
                </div>
                <div class="card-body">
                    @forelse(($top5 ?? collect())->take(5) as $index => $item)
                        @php 
                            $alt = $item->alternatif ?? null;
                            $rankClass = match($loop->iteration) {
                                1 => 'warning',
                                2 => 'secondary', 
                                3 => 'danger',
                                default => 'info'
                            };
                        @endphp
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <span class="badge bg-{{ $rankClass }} rounded-circle p-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    {{ $loop->iteration }}
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $alt->nama_menu ?? '-' }}</h6>
                                <small class="text-muted">
                                    {{ $alt->kode_menu ?? '-' }} • 
                                    <span class="badge bg-light text-dark">{{ ucfirst($alt->jenis_menu ?? '-') }}</span>
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary">
                                    {{ number_format((float) ($item->total ?? 0), 3) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0 mt-2">Belum ada data hasil perhitungan</p>
                            <a href="{{ route('perhitungan') }}" class="btn btn-sm btn-primary mt-2">
                                Mulai Perhitungan
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Hasil Perankingan --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Hasil Perankingan Menu</h6>
                        <div>
                            <a href="{{ route('hasil-akhir') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Lihat Semua
                            </a>
                            @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
                            <a href="{{ route('pdf.hasilAkhir') }}" target="_blank" class="btn btn-sm btn-danger ms-2">
                                <i class="bi bi-file-pdf"></i> Export PDF
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="60">Rank</th>
                                        <th width="80">Gambar</th>
                                        <th>Kode</th>
                                        <th>Nama Menu</th>
                                        <th>Jenis</th>
                                        <th>Harga</th>
                                        <th class="text-center">Nilai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nilaiAkhir->take(10) as $row)
                                        @php
                                            $alt = $row->alternatif ?? null;
                                            $jenisColor = match($alt->jenis_menu ?? '') {
                                                'makanan' => 'success',
                                                'cemilan' => 'warning',
                                                'coffee' => 'dark',
                                                'milkshake' => 'info',
                                                'mojito' => 'danger',
                                                'yakult' => 'primary',
                                                'tea' => 'secondary',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="badge {{ $loop->iteration == 1 ? 'bg-warning text-dark' : ($loop->iteration == 2 ? 'bg-secondary' : ($loop->iteration == 3 ? 'bg-danger' : 'bg-info')) }}">
                                                    {{ $loop->iteration }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($alt && $alt->gambar && file_exists(public_path('img/menu/'.$alt->gambar)))
                                                    <img src="{{ asset('img/menu/'.$alt->gambar) }}" 
                                                         class="rounded" 
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         alt="{{ $alt->nama_menu ?? '-' }}">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $alt->kode_menu ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $alt->nama_menu ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $jenisColor }}">
                                                    {{ ucfirst($alt->jenis_menu ?? '-') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $hargaLabel = match($alt->harga ?? '') {
                                                        '<=20000' => '≤ 20K',
                                                        '>20000-<=25000' => '20-25K',
                                                        '>25000-<=30000' => '25-30K',
                                                        '>30000' => '> 30K',
                                                        default => $alt->harga ?? '-'
                                                    };
                                                @endphp
                                                <span class="text-success">{{ $hargaLabel }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    {{ number_format((float) ($row->total ?? 0), 4) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($loop->iteration == 1)
                                                    <span class="badge bg-success">Terbaik</span>
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
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #cfd6e3;"></i>
                                <h5 class="mt-3">Belum ada hasil perhitungan</h5>
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

    {{-- Quick Actions
    <div class="row">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Actions</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('alternatif') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle"></i><br>
                                Tambah Menu
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('kriteria') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-list-check"></i><br>
                                Kelola Kriteria
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('penilaian') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-clipboard-data"></i><br>
                                Input Penilaian
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('perhitungan') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-calculator"></i><br>
                                Hitung SPK
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection

@section('css')
<style>
/* Keep existing styles and add: */
.progress-item {
    margin-bottom: 15px;
}

.card {
    transition: transform .2s;
    border: 1px solid #e3e6f0;
}

.card:hover {
    transform: translateY(-2px);
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Data
    const chartData = @json($chartSeries ?? []);
    const chartLabels = @json($chartLabels ?? []);
    
    if (chartData.length > 0 && window.ApexCharts) {
        const options = {
            series: [{
                name: 'Nilai Total',
                data: chartData
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return Number(val).toFixed(3);
                },
                offsetY: -20,
                style: {
                    fontSize: '10px',
                    colors: ["#304758"]
                }
            },
            colors: ['#8B4513'],
            xaxis: {
                categories: chartLabels,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Nilai Total (ROC + SMART)'
                },
                labels: {
                    formatter: function(val) {
                        return Number(val).toFixed(3);
                    }
                }
            },
            grid: {
                borderColor: '#e3e6f0',
                strokeDashArray: 4
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "Score: " + Number(val).toFixed(4);
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart_peringkat"), options);
        chart.render();
    } else {
        // Show empty state
        document.querySelector("#chart_peringkat").innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-bar-chart" style="font-size: 3rem; color: #cfd6e3;"></i>
                <h5 class="mt-3">Belum ada data untuk grafik</h5>
                <p class="text-muted mb-0">Data akan muncul setelah perhitungan ROC + SMART</p>
                <a href="{{ route('perhitungan') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-calculator"></i> Mulai Perhitungan
                </a>
            </div>
        `;
    }
    
    // Auto dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            if (!alert.classList.contains('alert-info')) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
});
</script>
@endsection