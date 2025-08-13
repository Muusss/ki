{{-- resources/views/dashboard/alternatif/index.blade.php --}}
@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid px-4">
    {{-- Header Section --}}
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="page-title mb-0">
                    <i class="bi bi-box-seam text-primary"></i> Data Produk Sunscreen
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 mt-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Produk</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <button class="btn btn-primary btn-rounded shadow-sm" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                </button>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-primary">
                <div class="stats-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stats-content">
                    <h4>{{ $alternatif->count() }}</h4>
                    <p>Total Produk</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-success">
                <div class="stats-icon">
                    <i class="bi bi-droplet"></i>
                </div>
                <div class="stats-content">
                    <h4>{{ $alternatif->where('jenis_kulit', 'normal')->count() }}</h4>
                    <p>Kulit Normal</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-warning">
                <div class="stats-icon">
                    <i class="bi bi-droplet-half"></i>
                </div>
                <div class="stats-content">
                    <h4>{{ $alternatif->where('jenis_kulit', 'berminyak')->count() }}</h4>
                    <p>Kulit Berminyak</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stats-card bg-gradient-info">
                <div class="stats-icon">
                    <i class="bi bi-moisture"></i>
                </div>
                <div class="stats-content">
                    <h4>{{ $alternatif->where('jenis_kulit', 'kering')->count() }}</h4>
                    <p>Kulit Kering</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & View Toggle --}}
    <div class="filter-section mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari produk...">
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary active" id="gridViewBtn">
                        <i class="bi bi-grid-3x3-gap"></i> Grid
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="listViewBtn">
                        <i class="bi bi-list-ul"></i> List
                    </button>
                </div>
                
                <div class="btn-group ms-2" role="group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item filter-skin" href="#" data-filter="all">Semua Jenis</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item filter-skin" href="#" data-filter="normal">Kulit Normal</a></li>
                        <li><a class="dropdown-item filter-skin" href="#" data-filter="berminyak">Kulit Berminyak</a></li>
                        <li><a class="dropdown-item filter-skin" href="#" data-filter="kering">Kulit Kering</a></li>
                        <li><a class="dropdown-item filter-skin" href="#" data-filter="kombinasi">Kulit Kombinasi</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid View --}}
    <div id="gridView" class="row product-grid">
        @forelse ($alternatif as $row)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-item" data-skin="{{ $row->jenis_kulit }}">
            <div class="product-card h-100">
                <div class="product-image">
                    @if($row->gambar && file_exists(public_path('img/produk/'.$row->gambar)))
                        <img src="{{ asset('img/produk/'.$row->gambar) }}" alt="{{ $row->nama_produk }}">
                    @else
                        <div class="no-image">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                    <div class="product-overlay">
                        <button class="btn btn-light btn-sm" onclick="quickView({{ $row->id }})">
                            <i class="bi bi-eye"></i> Quick View
                        </button>
                    </div>
                    <span class="badge-code">{{ $row->kode_produk }}</span>
                </div>
                <div class="product-content">
                    <h5 class="product-title">{{ $row->nama_produk }}</h5>
                    <div class="product-meta">
                        <span class="skin-type badge bg-{{ 
                            $row->jenis_kulit == 'normal' ? 'success' : 
                            ($row->jenis_kulit == 'berminyak' ? 'warning' : 
                            ($row->jenis_kulit == 'kering' ? 'info' : 
                            ($row->jenis_kulit == 'kombinasi' ? 'secondary' : 'danger'))) 
                        }}">
                            <i class="bi bi-droplet-fill"></i> {{ ucfirst($row->jenis_kulit) }}
                        </span>
                    </div>
                    <div class="product-actions">
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
                                    onclick="return confirmDelete('{{ $row->nama_produk }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Belum Ada Data Produk</h4>
                <p>Mulai tambahkan produk sunscreen untuk sistem rekomendasi</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
                    <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                </button>
            </div>
        </div>
        @endforelse
    </div>

    {{-- List View (Hidden by default) --}}
    <div id="listView" class="product-list" style="display: none;">
        <div class="table-responsive">
            <table class="table table-hover modern-table">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th width="80">Gambar</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Jenis Kulit</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alternatif as $row)
                    <tr class="product-item" data-skin="{{ $row->jenis_kulit }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($row->gambar && file_exists(public_path('img/produk/'.$row->gambar)))
                                <img src="{{ asset('img/produk/'.$row->gambar) }}" 
                                     alt="{{ $row->nama_produk }}" 
                                     class="table-image">
                            @else
                                <div class="table-no-image">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $row->kode_produk }}</span></td>
                        <td><strong>{{ $row->nama_produk }}</strong></td>
                        <td>
                            <span class="badge bg-{{ 
                                $row->jenis_kulit == 'normal' ? 'success' : 
                                ($row->jenis_kulit == 'berminyak' ? 'warning' : 
                                ($row->jenis_kulit == 'kering' ? 'info' : 
                                ($row->jenis_kulit == 'kombinasi' ? 'secondary' : 'danger'))) 
                            }}">
                                {{ ucfirst($row->jenis_kulit) }}
                            </span>
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
                                            onclick="return confirmDelete('{{ $row->nama_produk }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h4>Belum Ada Data</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modern Modal --}}
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <form id="formAlternatif" method="POST" action="{{ route('alternatif.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id">
                
                <div class="modal-header border-0 bg-gradient-primary text-white">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{-- Image Upload Section --}}
                    <div class="image-upload-section mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-image text-primary"></i> Gambar Produk
                        </label>
                        <div class="image-upload-container">
                            <input type="file" 
                                   id="imageInput" 
                                   name="gambar" 
                                   class="d-none" 
                                   accept="image/*"
                                   onchange="handleImageSelect(this)">
                            
                            <div class="image-preview" id="imagePreview" onclick="document.getElementById('imageInput').click()">
                                <img id="previewImg" src="" style="display: none;">
                                <div class="upload-placeholder" id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                    <p>Klik untuk upload gambar</p>
                                    <small>JPG, PNG (Max: 2MB)</small>
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
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control" 
                                       id="kode_produk"
                                       name="kode_produk" 
                                       placeholder="Kode"
                                       required>
                                <label for="kode_produk">
                                    <i class="bi bi-upc"></i> Kode Produk
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control" 
                                       id="nama_produk"
                                       name="nama_produk" 
                                       placeholder="Nama"
                                       required>
                                <label for="nama_produk">
                                    <i class="bi bi-box"></i> Nama Produk
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select" 
                                        id="jenis_kulit"
                                        name="jenis_kulit" 
                                        required>
                                    <option value="">Pilih jenis kulit...</option>
                                    <option value="normal">Normal</option>
                                    <option value="berminyak">Berminyak</option>
                                    <option value="kering">Kering</option>
                                    <option value="kombinasi">Kombinasi</option>
                                </select>
                                <label for="jenis_kulit">
                                    <i class="bi bi-droplet"></i> Jenis Kulit
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
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

{{-- Quick View Modal --}}
<div class="modal fade" id="quickViewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-index-1" data-bs-dismiss="modal"></button>
                <div id="quickViewContent">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Floating Action Button --}}
<div class="fab-container">
    <button class="fab btn btn-primary shadow-lg" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
        <i class="bi bi-plus-lg"></i>
    </button>
</div>
@endsection

@section('css')
<style>
/* Modern Variables */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
    --warning-gradient: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
    --info-gradient: linear-gradient(135deg, #2af1ff 0%, #2e86de 100%);
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
    --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Page Header */
.page-header {
    padding: 1.5rem 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: -1.5rem -1.5rem 2rem -1.5rem;
    padding: 2rem 1.5rem;
}

.page-title {
    font-weight: 700;
    color: #2c3e50;
}

/* Statistics Cards */
.stats-card {
    padding: 1.5rem;
    border-radius: 15px;
    color: white;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.bg-gradient-primary { background: var(--primary-gradient); }
.bg-gradient-success { background: var(--success-gradient); }
.bg-gradient-warning { background: var(--warning-gradient); }
.bg-gradient-info { background: var(--info-gradient); }

.stats-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 3rem;
    opacity: 0.3;
}

.stats-content h4 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.stats-content p {
    margin: 0;
    opacity: 0.9;
}

/* Search Box */
.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.search-box input {
    padding-left: 2.5rem;
    border-radius: 50px;
    border: 2px solid #e3e6f0;
    transition: var(--transition);
}

.search-box input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
}

/* Product Grid */
.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    font-size: 3rem;
    color: #dee2e6;
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.badge-code {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(102, 126, 234, 0.9);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.product-content {
    padding: 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.product-meta {
    margin-bottom: 1rem;
}

.product-actions {
    margin-top: auto;
    display: flex;
    gap: 0.5rem;
}

/* Modern Table */
.modern-table {
    border-collapse: separate;
    border-spacing: 0 0.5rem;
}

.modern-table thead th {
    background: #f8f9fa;
    border: none;
    padding: 1rem;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.modern-table tbody tr {
    background: white;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.modern-table tbody tr:hover {
    box-shadow: var(--shadow-md);
    transform: scale(1.01);
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border: none;
}

.table-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 10px;
}

.table-no-image {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 10px;
    color: #dee2e6;
}

/* Image Upload */
.image-upload-container {
    text-align: center;
}

.image-preview {
    width: 100%;
    max-width: 300px;
    height: 200px;
    margin: 0 auto;
    border: 3px dashed #dee2e6;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.image-preview:hover {
    border-color: #667eea;
    background: #f8f9fa;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-placeholder {
    text-align: center;
    color: #6c757d;
}

.upload-placeholder i {
    font-size: 3rem;
    color: #dee2e6;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
}

.empty-state i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

/* Floating Action Button */
.fab-container {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 1000;
}

.fab {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: var(--transition);
    background: var(--primary-gradient);
    border: none;
}

.fab:hover {
    transform: scale(1.1) rotate(90deg);
}

/* Responsive */
@media (max-width: 768px) {
    .product-grid {
        margin: 0 -0.5rem;
    }
    
    .product-card {
        margin: 0 0.5rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .page-header {
        text-align: center;
    }
    
    .filter-section .btn-group {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.product-item {
    animation: fadeIn 0.5s ease-out;
}

/* Button Styles */
.btn-rounded {
    border-radius: 50px;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    color: #495057;
}
</style>
@endsection

@section('js')
<script>
// Initialize
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Animate cards on scroll
    animateOnScroll();
});

// View Toggle
$('#gridViewBtn').click(function() {
    $('#gridView').show();
    $('#listView').hide();
    $(this).addClass('active');
    $('#listViewBtn').removeClass('active');
});

$('#listViewBtn').click(function() {
    $('#listView').show();
    $('#gridView').hide();
    $(this).addClass('active');
    $('#gridViewBtn').removeClass('active');
});

// Search Function
$('#searchInput').on('keyup', function() {
    const value = $(this).val().toLowerCase();
    $('.product-item').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});

// Filter by Skin Type
$('.filter-skin').click(function(e) {
    e.preventDefault();
    const filter = $(this).data('filter');
    
    if (filter === 'all') {
        $('.product-item').show();
    } else {
        $('.product-item').hide();
        $('.product-item[data-skin="' + filter + '"]').show();
    }
    
    // Update active state
    $('.filter-skin').removeClass('active');
    $(this).addClass('active');
});

// Image Upload Handler
function handleImageSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran maksimal 2MB',
                confirmButtonColor: '#667eea'
            });
            input.value = '';
            return;
        }
        
        // Preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#previewImg').attr('src', e.target.result).show();
            $('#uploadPlaceholder').hide();
            $('#removeImageBtn').show();
        }
        reader.readAsDataURL(file);
    }
}

// Remove Image
function removeImage() {
    $('#imageInput').val('');
    $('#previewImg').hide();
    $('#uploadPlaceholder').show();
    $('#removeImageBtn').hide();
}

// Create Product
function create_button() {
    $('#modalTitle').html('<i class="bi bi-plus-circle"></i> Tambah Produk');
    $('#formAlternatif')[0].reset();
    removeImage();
}

// Edit Product
function show_button(id) {
    $('#modalTitle').html('<i class="bi bi-pencil"></i> Edit Produk');
    
    // Show loading
    Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
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
            $('#kode_produk').val(data.kode_produk);
            $('#nama_produk').val(data.nama_produk);
            $('#jenis_kulit').val(data.jenis_kulit);
            
            if (data.gambar) {
                $('#previewImg').attr('src', '/img/produk/' + data.gambar).show();
                $('#uploadPlaceholder').hide();
                $('#removeImageBtn').show();
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

// Quick View
function quickView(id) {
    $('#quickViewModal').modal('show');
    $('#quickViewContent').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>');
    
    // Simulate loading content
    setTimeout(() => {
        // Load content via AJAX
        $('#quickViewContent').html(`
            <div class="quick-view-content">
                <img src="/img/produk/sample.jpg" class="w-100">
                <div class="p-3">
                    <h5>Product Name</h5>
                    <p>Product details here...</p>
                </div>
            </div>
        `);
    }, 500);
}

// Delete Confirmation
function confirmDelete(name) {
    return confirm(`Apakah Anda yakin ingin menghapus produk "${name}"?`);
}

// Animate on Scroll
function animateOnScroll() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    });
    
    document.querySelectorAll('.product-card').forEach(card => {
        observer.observe(card);
    });
}

// Toast Notification
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