{{-- resources/views/dashboard/alternatif/index.blade.php --}}
@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Data Menu Cafe</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Menu</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
                        <i class="bi bi-plus-circle"></i> Tambah Menu
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Total Menu</h6>
                            <h3 class="mb-0">{{ $items->count() ?? 0 }}</h3>
                        </div>
                        <div class="stats-icon opacity-50">
                            <i class="bi bi-cup-hot" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Makanan</h6>
                            <h3 class="mb-0">{{ $items->where('jenis_menu', 'makanan')->count() ?? 0 }}</h3>
                        </div>
                        <div class="stats-icon opacity-50">
                            <i class="bi bi-egg-fried" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Minuman</h6>
                            <h3 class="mb-0">{{ $items->whereIn('jenis_menu', ['coffee', 'milkshake', 'mojito', 'yakult', 'tea'])->count() ?? 0 }}</h3>
                        </div>
                        <div class="stats-icon opacity-50">
                            <i class="bi bi-cup-straw" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 text-white-50">Cemilan</h6>
                            <h3 class="mb-0">{{ $items->where('jenis_menu', 'cemilan')->count() ?? 0 }}</h3>
                        </div>
                        <div class="stats-icon opacity-50">
                            <i class="bi bi-cookie" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-funnel"></i> Filter Menu</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Cari Menu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama menu...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Menu</label>
                            <select id="filterJenis" class="form-select">
                                <option value="all">Semua Jenis Menu</option>
                                <option value="makanan">Makanan</option>
                                <option value="cemilan">Cemilan</option>
                                <option value="coffee">Coffee</option>
                                <option value="milkshake">Milkshake</option>
                                <option value="mojito">Mojito</option>
                                <option value="yakult">Yakult</option>
                                <option value="tea">Tea</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Range Harga</label>
                            <select id="filterHarga" class="form-select">
                                <option value="all">Semua Harga</option>
                                <option value="<=20000">≤ Rp 20.000</option>
                                <option value=">20000-<=25000">Rp 20.001 - Rp 25.000</option>
                                <option value=">25000-<=30000">Rp 25.001 - Rp 30.000</option>
                                <option value=">30000">> Rp 30.000</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="resetFilters()">
                            <i class="bi bi-arrow-clockwise"></i> Reset Filter
                        </button>
                        <span id="resultCount" class="result-count ms-auto">{{ $items->count() }} menu ditampilkan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toolbar View Toggle --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Daftar Menu Cafe Buri Umah</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary active" id="gridViewBtn">
                <i class="bi bi-grid-3x3-gap"></i> Grid
            </button>
            <button type="button" class="btn btn-outline-secondary" id="listViewBtn">
                <i class="bi bi-list-ul"></i> List
            </button>
        </div>
    </div>

    {{-- Grid View --}}
    <div id="gridView" class="row g-3">
        @forelse ($items as $row)
        <div class="col-xl-3 col-lg-4 col-md-6 product-item" 
             data-jenis="{{ $row->jenis_menu }}"
             data-price="{{ $row->harga }}"
             data-name="{{ strtolower($row->nama_menu) }}">
            <div class="product-card h-100 position-relative">
                {{-- Action buttons di pojok kanan atas --}}
                <div class="action-buttons">
                    <button class="btn btn-sm btn-warning" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalForm"
                            onclick="show_button({{ $row->id }})"
                            title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('alternatif.delete') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="id" value="{{ $row->id }}">
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirmDelete('{{ $row->nama_menu }}')"
                                title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

                {{-- Product Image --}}
                <div class="product-image">
                    @if($row->gambar && file_exists(public_path('img/menu/'.$row->gambar)))
                        <img src="{{ asset('img/menu/' . $row->gambar) }}" alt="{{ $row->nama_menu }}" loading="lazy">
                    @else
                        <div class="no-image">
                            <i class="bi bi-image"></i>
                            <span>No Image</span>
                        </div>
                    @endif
                    <span class="badge-code">{{ $row->kode_menu }}</span>
                </div>

                {{-- Product Content --}}
                <div class="product-content">
                    <h6 class="product-title mb-1" title="{{ $row->nama_menu }}">
                        {{ $row->nama_menu }}
                    </h6>
                    
                    <div class="product-meta">
                        {{-- Jenis Menu Badge --}}
                        @php
                            $menuColor = match($row->jenis_menu) {
                                'makanan' => 'success',
                                'cemilan' => 'warning',
                                'coffee' => 'dark',
                                'milkshake' => 'info',
                                'mojito' => 'danger',
                                'yakult' => 'primary',
                                'tea' => 'secondary',
                                default => 'secondary'
                            };
                            $menuIcon = match($row->jenis_menu) {
                                'makanan' => 'bi-egg-fried',
                                'cemilan' => 'bi-cookie',
                                'coffee' => 'bi-cup-hot-fill',
                                'milkshake' => 'bi-cup-straw',
                                'mojito' => 'bi-tropical-storm',
                                'yakult' => 'bi-cup',
                                'tea' => 'bi-cup-fill',
                                default => 'bi-bag'
                            };
                            $menuLabel = match($row->jenis_menu) {
                                'makanan' => 'Makanan',
                                'cemilan' => 'Cemilan',
                                'coffee' => 'Coffee',
                                'milkshake' => 'Milkshake',
                                'mojito' => 'Mojito',
                                'yakult' => 'Yakult',
                                'tea' => 'Tea',
                                default => ucfirst($row->jenis_menu)
                            };
                            $hargaLabel = match($row->harga) {
                                '<=20000' => '≤ Rp 20.000',
                                '>20000-<=25000' => 'Rp 20.001-25.000',
                                '>25000-<=30000' => 'Rp 25.001-30.000',
                                '>30000' => '> Rp 30.000',
                                default => $row->harga
                            };
                        @endphp
                        <span class="badge bg-{{ $menuColor }}">
                            <i class="bi {{ $menuIcon }}"></i> {{ $menuLabel }}
                        </span>

                        {{-- Harga Badge --}}
                        <span class="badge bg-light text-success border">
                            <i class="bi bi-cash"></i> {{ $hargaLabel }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Belum Ada Data Menu</h4>
                    <p class="text-muted">Mulai tambahkan menu untuk sistem rekomendasi</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
                        <i class="bi bi-plus-circle"></i> Tambah Menu Pertama
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- List View --}}
    <div id="listView" class="table-responsive" style="display: none;">
        <table class="table table-hover modern-table align-middle">
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th width="90">Gambar</th>
                    <th>Kode</th>
                    <th>Nama Menu</th>
                    <th>Jenis Menu</th>
                    <th class="text-center">Harga</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $row)
                <tr class="product-item" 
                    data-jenis="{{ $row->jenis_menu }}"
                    data-price="{{ $row->harga }}"
                    data-name="{{ strtolower($row->nama_menu) }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($row->gambar && file_exists(public_path('img/menu/'.$row->gambar)))
                            <img src="{{ asset('img/menu/' . $row->gambar) }}" 
                                 alt="{{ $row->nama_menu }}" 
                                 class="table-image"
                                 loading="lazy">
                        @else
                            <div class="table-no-image">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td><span class="badge bg-primary">{{ $row->kode_menu }}</span></td>
                    <td><strong>{{ $row->nama_menu }}</strong></td>
                    <td>
                        @php
                            $menuColor = match($row->jenis_menu) {
                                'makanan' => 'success',
                                'cemilan' => 'warning',
                                'coffee' => 'dark',
                                'milkshake' => 'info',
                                'mojito' => 'danger',
                                'yakult' => 'primary',
                                'tea' => 'secondary',
                                default => 'secondary'
                            };
                            $menuLabel = match($row->jenis_menu) {
                                'makanan' => 'Makanan',
                                'cemilan' => 'Cemilan',
                                'coffee' => 'Coffee',
                                'milkshake' => 'Milkshake',
                                'mojito' => 'Mojito',
                                'yakult' => 'Yakult',
                                'tea' => 'Tea',
                                default => ucfirst($row->jenis_menu)
                            };
                        @endphp
                        <span class="badge bg-{{ $menuColor }}">
                            {{ $menuLabel }}
                        </span>
                    </td>
                    <td class="text-center">
                        @php
                            $hargaLabel = match($row->harga) {
                                '<=20000' => '≤ Rp 20.000',
                                '>20000-<=25000' => 'Rp 20.001-25.000',
                                '>25000-<=30000' => 'Rp 25.001-30.000',
                                '>30000' => '> Rp 30.000',
                                default => $row->harga
                            };
                        @endphp
                        <span class="text-success">{{ $hargaLabel }}</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalForm"
                                    onclick="show_button({{ $row->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('alternatif.delete') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $row->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirmDelete('{{ $row->nama_menu }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <h4>Belum Ada Data</h4>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Form --}}
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <form id="formAlternatif" method="POST" action="{{ route('alternatif.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="bi bi-plus-circle"></i> Tambah Menu
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{-- Image Upload Section --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-image text-primary"></i> Gambar Menu
                        </label>
                        <div class="image-upload-container">
                            <input type="file" 
                                   id="imageInput" 
                                   name="gambar" 
                                   class="d-none" 
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   onchange="handleImageSelect(this)">
                            
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('imageInput').click()">
                                <img id="previewImg" src="" style="display: none;">
                                <div class="upload-placeholder" id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <small>JPG, PNG, WebP (Max: 2MB)</small>
                                </div>
                            </div>
                            
                            <button type="button" 
                                    class="btn btn-sm btn-danger mt-2" 
                                    id="removeImageBtn" 
                                    style="display: none;"
                                    onclick="removeImage()">
                                <i class="bi bi-trash"></i> Hapus Gambar
                            </button>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kode_menu" class="form-label">
                                <i class="bi bi-upc"></i> Kode Menu
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="kode_menu"
                                   name="kode_menu" 
                                   required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="nama_menu" class="form-label">
                                <i class="bi bi-cup-hot"></i> Nama Menu
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nama_menu"
                                   name="nama_menu" 
                                   required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="jenis_menu" class="form-label">
                                <i class="bi bi-tags"></i> Jenis Menu
                            </label>
                            <select class="form-select" 
                                    id="jenis_menu"
                                    name="jenis_menu" 
                                    required>
                                <option value="">Pilih jenis menu...</option>
                                <option value="makanan">Makanan</option>
                                <option value="cemilan">Cemilan</option>
                                <option value="coffee">Coffee</option>
                                <option value="milkshake">Milkshake</option>
                                <option value="mojito">Mojito</option>
                                <option value="yakult">Yakult</option>
                                <option value="tea">Tea</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="harga" class="form-label">
                                <i class="bi bi-cash"></i> Kategori Harga
                            </label>
                            <select class="form-select" id="harga" name="harga" required>
                                <option value="">Pilih kategori harga...</option>
                                <option value="<=20000">≤ Rp 20.000</option>
                                <option value=">20000-<=25000">Rp 20.001 - Rp 25.000</option>
                                <option value=">25000-<=30000">Rp 25.001 - Rp 30.000</option>
                                <option value=">30000">> Rp 30.000</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
/* Variables */
:root {
    --line: #e8ecf2;
    --shadow-xs: 0 1px 2px rgba(17,24,39,.06);
    --shadow-sm: 0 4px 10px rgba(17,24,39,.08);
    --shadow-md: 0 8px 24px rgba(17,24,39,.12);
    --transition: all .25s cubic-bezier(.22,.61,.36,1);
}

/* Result Count Badge */
.result-count {
    font-size: .9rem;
    color: #6b7380;
    background: #f7f9fc;
    border: 1px solid var(--line);
    padding: 6px 10px;
    border-radius: 999px;
}

/* Product Card - Matching hasil-akhir style */
.product-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.product-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-md);
}

/* Action Buttons (Edit/Delete) */
.action-buttons {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .action-buttons {
    opacity: 1;
}

.action-buttons .btn {
    padding: 4px 8px;
    font-size: 0.875rem;
}

/* Product Image */
.product-image {
    position: relative;
    height: 200px;
    background: #f6f8fb;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.no-image {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #cdd5df;
}

.no-image i {
    font-size: 3rem;
}

.badge-code {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #ffffffcc;
    color: #394150;
    border: 1px solid #e6eaef;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: .8rem;
    backdrop-filter: blur(4px);
}

/* Product Content */
.product-content {
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

.product-title {
    font-weight: 700;
    color: #273142;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
}

.product-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

/* Table Styles */
.table-image,
.table-no-image {
    width: 58px;
    height: 58px;
    border-radius: 10px;
    object-fit: cover;
}

.table-no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f3f8;
    color: #c7cfdb;
}

.modern-table thead th {
    background: #f7f9fc;
    border-bottom: 1px solid var(--line);
    text-transform: uppercase;
    font-size: .78rem;
    letter-spacing: .6px;
    color: #667085;
}

.modern-table tbody tr:hover {
    background: #f8f9fa;
}

/* Modal Styles */
.image-upload-container {
    text-align: center;
}

.image-preview {
    width: 100%;
    max-width: 420px;
    height: 260px;
    margin: 0 auto;
    border: 2px dashed #dfe6f0;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fafbff;
    transition: var(--transition);
    cursor: pointer;
}

.image-preview:hover {
    border-color: #0d6efd;
    background: #f0f6ff;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 12px;
}

.upload-placeholder {
    color: #8a94a6;
}

.upload-placeholder i {
    font-size: 2.6rem;
    color: #d3daea;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-card,
.product-item {
    animation: fadeInUp .35s ease both;
}

/* Stats Card */
.stats-icon {
    opacity: 0.3;
}

/* Responsive */
@media (max-width: 768px) {
    .action-buttons {
        opacity: 1;
    }
    
    .product-image {
        height: 180px;
    }
}
</style>
@endsection

@section('js')
<script>
// State
let currentFilters = {
    jenis: 'all',
    price: 'all',
    search: ''
};

// Initialize
$(function() {
    // View toggle
    $('#gridViewBtn').on('click', function() {
        $('#gridView').show();
        $('#listView').hide();
        $(this).addClass('active');
        $('#listViewBtn').removeClass('active');
    });
    
    $('#listViewBtn').on('click', function() {
        $('#listView').show();
        $('#gridView').hide();
        $(this).addClass('active');
        $('#gridViewBtn').removeClass('active');
    });

    // Search filter
    $('#searchInput').on('input', function() {
        currentFilters.search = $(this).val().toLowerCase();
        applyFilters();
    });

    // Jenis filter
    $('#filterJenis').on('change', function() {
        currentFilters.jenis = $(this).val();
        applyFilters();
    });

    // Harga filter
    $('#filterHarga').on('change', function() {
        currentFilters.price = $(this).val();
        applyFilters();
    });
});

// Apply Filters
function applyFilters() {
    let visible = 0;
    
    $('.product-item').each(function() {
        let show = true;
        const $item = $(this);
        
        // Jenis filter
        if (currentFilters.jenis !== 'all' && $item.data('jenis') !== currentFilters.jenis) {
            show = false;
        }
        
        // Price filter
        if (currentFilters.price !== 'all' && $item.data('price') !== currentFilters.price) {
            show = false;
        }
        
        // Search filter
        if (currentFilters.search && !$item.data('name').includes(currentFilters.search)) {
            show = false;
        }
        
        $item.toggle(show);
        if (show) visible++;
    });
    
    $('#resultCount').text(visible + ' menu ditampilkan');
}

// Reset Filters
function resetFilters() {
    $('#searchInput').val('');
    $('#filterJenis').val('all');
    $('#filterHarga').val('all');
    currentFilters = {
        jenis: 'all',
        price: 'all',
        search: ''
    };
    applyFilters();
}

// Image Upload
function handleImageSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        
        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format Tidak Valid',
                text: 'Hanya JPG, PNG & WebP yang diperbolehkan'
            });
            input.value = '';
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Maksimal ukuran file 2MB'
            });
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImg').attr('src', e.target.result).show();
            $('#uploadPlaceholder').hide();
            $('#removeImageBtn').show();
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    $('#imageInput').val('');
    $('#previewImg').hide();
    $('#uploadPlaceholder').show();
    $('#removeImageBtn').hide();
}

// CRUD Functions
function create_button() {
    $('#modalTitle').html('<i class="bi bi-plus-circle"></i> Tambah Menu');
    $('#formAlternatif')[0].reset();
    $('#formAlternatif').attr('action', '{{ route("alternatif.store") }}');
    removeImage();
}

function show_button(id) {
    $('#modalTitle').html('<i class="bi bi-pencil"></i> Edit Menu');
    $('#formAlternatif').attr('action', '{{ route("alternatif.update") }}');
    
    Swal.fire({
        title: 'Memuat data...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    $.ajax({
        url: '{{ route("alternatif.edit") }}',
        type: 'GET',
        data: {
            _token: '{{ csrf_token() }}',
            alternatif_id: id
        },
        success: function(data) {
            $('#formAlternatif input[name=id]').val(data.id);
            $('#kode_menu').val(data.kode_menu);
            $('#nama_menu').val(data.nama_menu);
            $('#jenis_menu').val(data.jenis_menu);
            $('#harga').val(data.harga);
            
            if (data.gambar) {
                $('#previewImg').attr('src', '/img/menu/' + data.gambar).show();
                $('#uploadPlaceholder').hide();
                $('#removeImageBtn').show();
            } else {
                removeImage();
            }
            
            Swal.close();
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat data'
            });
        }
    });
}

function confirmDelete(name) {
    event.preventDefault();
    const form = event.target.closest('form');
    
    Swal.fire({
        title: 'Hapus Menu?',
        html: `Menu <b>${name}</b> akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    
    return false;
}

// Toast notifications
@if(session('success'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
@endif

@if(session('error'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: '{{ session("error") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
@endif
</script>
@endsection