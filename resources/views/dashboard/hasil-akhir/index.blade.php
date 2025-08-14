@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Hasil Akhir Perankingan Produk Sunscreen</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hasil Akhir</li>
                        </ol>
                    </nav>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('perhitungan') }}" class="btn btn-secondary">
                        <i class="bi bi-calculator"></i> Lihat Perhitungan
                    </a>
                    <a href="{{ route('pdf.hasilAkhir') }}" target="_blank" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
        
        <!-- Tab Navigation untuk Filter Jenis Kulit -->
        <ul class="nav nav-tabs mb-4" id="jenisKulitTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                    <i class="bi bi-grid-3x3-gap"></i> Semua Jenis
                    <span class="badge bg-secondary ms-1">{{ $nilaiAkhir->count() }}</span>
                </button>
            </li>
            @foreach($jenisKulitList as $jenis)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="{{ $jenis }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $jenis }}" type="button">
                    <i class="bi bi-droplet-fill"></i> {{ ucfirst($jenis) }}
                    <span class="badge bg-secondary ms-1">{{ $hasilPerJenis[$jenis]->count() }}</span>
                </button>
            </li>
            @endforeach
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="jenisKulitTabContent">
            
            <!-- Tab Semua Jenis -->
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                @include('dashboard.hasil-akhir.partials.ranking-content', [
                    'data' => $nilaiAkhir,
                    'jenisKulit' => 'all',
                    'title' => 'Perankingan Semua Produk'
                ])
            </div>

            <!-- Tab Per Jenis Kulit -->
            @foreach($jenisKulitList as $jenis)
            <div class="tab-pane fade" id="{{ $jenis }}" role="tabpanel">
                @include('dashboard.hasil-akhir.partials.ranking-content', [
                    'data' => $hasilPerJenis[$jenis],
                    'jenisKulit' => $jenis,
                    'title' => 'Perankingan Kulit ' . ucfirst($jenis)
                ])
            </div>
            @endforeach
        </div>

        <!-- Statistik Keseluruhan -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $nilaiAkhir->count() }}</h3>
                        <p class="text-muted mb-0">Total Produk</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ number_format($nilaiAkhir->max('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Tertinggi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ number_format($nilaiAkhir->avg('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Rata-rata</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-danger">{{ number_format($nilaiAkhir->min('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Terendah</p>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Tampilan jika belum ada data -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Belum Ada Data Hasil Perhitungan</h4>
                        <p class="text-muted">Silakan lakukan perhitungan ROC + SMART terlebih dahulu.</p>
                        <a href="{{ route('perhitungan') }}" class="btn btn-primary">
                            <i class="bi bi-calculator"></i> Ke Halaman Perhitungan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('css')
<style>
/* Product Card Styles */
.product-rank-card {
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.product-rank-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.rank-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    z-index: 10;
}

.rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: white; }
.rank-2 { background: linear-gradient(135deg, #C0C0C0, #808080); color: white; }
.rank-3 { background: linear-gradient(135deg, #CD7F32, #8B4513); color: white; }
.rank-default { background: #f8f9fa; color: #6c757d; }

.product-image-container {
    height: 200px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #dee2e6;
}

.skin-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

/* Tab Styles */
.nav-tabs .nav-link {
    color: #6c757d;
    border-radius: 10px 10px 0 0;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    background-color: #f8f9fa;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.nav-tabs .nav-link .badge {
    transition: all 0.3s ease;
}

.nav-tabs .nav-link.active .badge {
    background-color: rgba(255,255,255,0.3) !important;
}

/* Comparison Grid */
.comparison-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

/* Score Bar */
.score-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    margin: 10px 0;
}

.score-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #ffc107, #dc3545);
    border-radius: 4px;
    transition: width 0.5s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .comparison-grid {
        grid-template-columns: 1fr;
    }
    
    .product-rank-card {
        margin-bottom: 1rem;
    }
}
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Animate score bars on tab change
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetPane = $($(e.target).data('bs-target'));
        targetPane.find('.score-fill').each(function() {
            const width = $(this).data('score');
            $(this).css('width', '0%');
            setTimeout(() => {
                $(this).css('width', width + '%');
            }, 100);
        });
    });
    
    // Initialize first tab animations
    $('.score-fill').each(function() {
        const width = $(this).data('score');
        $(this).css('width', width + '%');
    });
    
    // DataTable for detailed view (if exists)
    if ($('.ranking-table').length) {
        $('.ranking-table').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    responsive: true,
                    pageLength: 10,
                    order: [[0, 'asc']],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });
            }
        });
    }
});
</script>
@endsection