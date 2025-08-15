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

                {{-- Filter Harga --}}
                <div class="btn-group ms-2" role="group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-cash"></i> Harga
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item filter-price" href="#" data-min="0" data-max="">Semua</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item filter-price" href="#" data-min="0" data-max="50000">< Rp 50.000</a></li>
                        <li><a class="dropdown-item filter-price" href="#" data-min="50000" data-max="100000">Rp 50.000 - 100.000</a></li>
                        <li><a class="dropdown-item filter-price" href="#" data-min="100000" data-max="">> Rp 100.000</a></li>
                    </ul>
                </div>

                {{-- Filter SPF --}}
                <div class="btn-group ms-2" role="group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-sun"></i> SPF
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item filter-spf" href="#" data-spf="all">Semua</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item filter-spf" href="#" data-spf="30">SPF 30</a></li>
                        <li><a class="dropdown-item filter-spf" href="#" data-spf="35">SPF 35</a></li>
                        <li><a class="dropdown-item filter-spf" href="#" data-spf="40">SPF 40</a></li>
                        <li><a class="dropdown-item filter-spf" href="#" data-spf="50">SPF 50</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
        <div class="row mt-2">
    <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="chips">
        <span id="chip-search" class="chip" aria-hidden="true">
            <i class="bi bi-search"></i><span class="chip-text"></span>
            <button class="btn btn-link btn-sm p-0 ms-2" onclick="clearChip('#chip-search',()=>{ currentFilters.search=''; $('#searchInput').val(''); })" aria-label="Hapus filter pencarian">
            <i class="bi bi-x-lg"></i>
            </button>
        </span>
        <span id="chip-skin" class="chip" aria-hidden="true">
            <i class="bi bi-droplet"></i><span class="chip-text"></span>
            <button class="btn btn-link btn-sm p-0 ms-2" onclick="clearChip('#chip-skin',()=> currentFilters.skin='all')" aria-label="Hapus filter kulit">
            <i class="bi bi-x-lg"></i>
            </button>
        </span>
        <span id="chip-price" class="chip" aria-hidden="true">
            <i class="bi bi-cash"></i><span class="chip-text"></span>
            <button class="btn btn-link btn-sm p-0 ms-2" onclick="clearChip('#chip-price',()=>{ currentFilters.priceMin=0; currentFilters.priceMax=null; })" aria-label="Hapus filter harga">
            <i class="bi bi-x-lg"></i>
            </button>
        </span>
        <span id="chip-spf" class="chip" aria-hidden="true">
            <i class="bi bi-sun"></i><span class="chip-text"></span>
            <button class="btn btn-link btn-sm p-0 ms-2" onclick="clearChip('#chip-spf',()=> currentFilters.spf='all')" aria-label="Hapus filter SPF">
            <i class="bi bi-x-lg"></i>
            </button>
        </span>
        </div>
        <div id="resultCount" class="result-count">0 produk ditampilkan</div>
    </div>
    </div>

    {{-- Grid View --}}
    <div id="gridView" class="row product-grid">
        @forelse ($alternatif as $row)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-item" 
             data-skin="{{ $row->jenis_kulit }}"
             data-price="{{ $row->harga ?? 0 }}"
             data-spf="{{ $row->spf ?? 0 }}">
            <div class="product-card h-100">
                <div class="product-image">
                    @if($row->has_gambar)
                        <img src="{{ $row->gambar_url }}" alt="{{ $row->nama_produk }}" loading="lazy">
                    @else
                        <div class="no-image">
                            <i class="bi bi-image"></i>
                            <span>No Image</span>
                        </div>
                    @endif
                    <span class="badge-code">{{ $row->kode_produk }}</span>
                </div>
                <div class="product-content">
                    <h5 class="product-title">{{ $row->nama_produk }}</h5>
                    <div class="product-meta mb-2">
                        {{-- Jenis Kulit --}}
                        <span class="skin-type badge bg-{{ 
                            $row->jenis_kulit == 'normal' ? 'success' : 
                            ($row->jenis_kulit == 'berminyak' ? 'warning' : 
                            ($row->jenis_kulit == 'kering' ? 'info' : 'secondary')) 
                        }}">
                            <i class="bi bi-droplet-fill"></i> {{ ucfirst($row->jenis_kulit) }}
                        </span>

                        {{-- Harga (tampilkan jika ada) --}}
                        @if(!is_null($row->harga))
                            <span class="badge bg-light text-success border ms-1">
                                <i class="bi bi-cash"></i> Rp {{ number_format($row->harga, 0, ',', '.') }}
                            </span>
                        @endif

                        {{-- SPF (tampilkan jika ada) --}}
                        @if(!is_null($row->spf) && $row->spf !== '')
                            <span class="badge bg-warning text-dark ms-1">
                                <i class="bi bi-sun"></i> SPF {{ $row->spf }}
                            </span>
                        @endif
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
                        <th width="100">Gambar</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Jenis Kulit</th>
                        <th>Harga</th>
                        <th>SPF</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alternatif as $row)
                    <tr class="product-item" 
                        data-skin="{{ $row->jenis_kulit }}"
                        data-price="{{ $row->harga ?? 0 }}"
                        data-spf="{{ $row->spf ?? 0 }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($row->has_gambar)
                                <img src="{{ $row->gambar_url }}" 
                                     alt="{{ $row->nama_produk }}" 
                                     class="table-image"
                                     loading="lazy">
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
                                ($row->jenis_kulit == 'kering' ? 'info' : 'secondary')) 
                            }}">
                                {{ ucfirst($row->jenis_kulit) }}
                            </span>
                        </td>
                        <td>
                            @if(!is_null($row->harga))
                                <span class="text-success">Rp {{ number_format($row->harga, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if(!is_null($row->spf) && $row->spf !== '')
                                <span class="badge bg-warning text-dark">SPF {{ $row->spf }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
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
                        <td colspan="8" class="text-center py-5">
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
                                   accept="image/jpeg,image/jpg,image/png"
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

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="harga"
                                       name="harga" placeholder="Harga" min="0">
                                <label for="harga">
                                    <i class="bi bi-cash"></i> Harga (Rp)
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="spf" name="spf">
                                    <option value="">Pilih SPF...</option>
                                    <option value="30">SPF 30</option>
                                    <option value="35">SPF 35</option>
                                    <option value="40">SPF 40</option>
                                    <option value="50">SPF 50</option>
                                </select>
                                <label for="spf">
                                    <i class="bi bi-sun"></i> SPF Protection
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

{{-- Floating Action Button --}}
<div class="fab-container">
    <button class="fab btn btn-primary shadow-lg" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
        <i class="bi bi-plus-lg"></i>
    </button>
</div>
@endsection

@section('css')
<style>
/* =============== Design Tokens =============== */
:root{
  --radius:16px;
  --radius-sm:12px;
  --surface:#ffffff;
  --surface-soft:#f7f9fc;
  --text:#273142;
  --muted:#6b7380;
  --line:#e8ecf2;
  --shadow-xs:0 1px 2px rgba(17,24,39,.06);
  --shadow-sm:0 4px 10px rgba(17,24,39,.08);
  --shadow-md:0 8px 24px rgba(17,24,39,.12);
  --transition:all .25s cubic-bezier(.22,.61,.36,1);
  --grad-primary:linear-gradient(135deg,#7069f4 0%,#9c68f5 100%);
  --grad-success:linear-gradient(135deg,#34d399 0%,#22c55e 100%);
  --grad-warning:linear-gradient(135deg,#f59e0b 0%,#fbbf24 100%);
  --grad-info:linear-gradient(135deg,#60a5fa 0%,#38bdf8 100%);
}

/* =============== Page Header =============== */
.page-header{
  background:linear-gradient(180deg,#f1f5ff 0%,#eef2ff 100%);
  border-radius:0 0 24px 24px;
  padding:28px 16px;
  margin:-1.5rem -1.5rem 24px;
  border-bottom:1px solid #e6eaff;
}
.page-title{color:var(--text);font-weight:800;letter-spacing:.2px}
.breadcrumb{--bs-breadcrumb-divider: "›"; font-size:.925rem}
.breadcrumb a{color:#6670f0}
.breadcrumb .active{color:var(--muted)}

/* =============== Stats =============== */
.stats-card{
  border-radius:var(--radius);
  padding:18px 16px;
  color:#fff;
  position:relative;
  overflow:hidden;
  box-shadow:var(--shadow-xs);
  transition:var(--transition);
  isolation:isolate;
}
.stats-card::after{
  content:"";
  position:absolute;
  inset:0;
  background:radial-gradient(120px 120px at 110% -10%, rgba(255,255,255,.35), transparent 60%);
  z-index:0;
}
.stats-card:hover{transform:translateY(-4px); box-shadow:var(--shadow-md)}
.bg-gradient-primary{background:var(--grad-primary)}
.bg-gradient-success{background:var(--grad-success)}
.bg-gradient-warning{background:var(--grad-warning)}
.bg-gradient-info{background:var(--grad-info)}
.stats-icon{position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:2.75rem; opacity:.25; z-index:0}
.stats-content{position:relative; z-index:1}
.stats-content h4{margin:0; font-weight:800; letter-spacing:.3px}
.stats-content p{margin:2px 0 0; opacity:.95}

/* =============== Sticky Toolbar (filters) =============== */
.filter-section{
  position:sticky; top:0; z-index:20;
  backdrop-filter:saturate(1.1) blur(6px);
  background:rgba(255,255,255,.7);
  border:1px solid var(--line);
  border-radius:var(--radius);
  padding:12px;
  box-shadow:var(--shadow-xs);
}
.filter-meta{display:flex; align-items:center; gap:10px; justify-content:flex-end}
.result-count{
  font-size:.9rem; color:var(--muted);
  background:var(--surface-soft); border:1px solid var(--line);
  padding:6px 10px; border-radius:999px;
}
.chips{display:flex; gap:8px; flex-wrap:wrap}
.chip{
  border:1px solid var(--line); background:#fff; color:var(--text);
  padding:6px 10px; border-radius:999px; font-size:.86rem; display:none;
}
.chip i{margin-right:6px}
.chip.active{display:inline-flex}

/* search */
.search-box{position:relative}
.search-icon{position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#98a2b3}
.search-box input{
  padding-left:40px; border-radius:999px; border:1.5px solid var(--line); height:42px;
  transition:var(--transition); background:#fff
}
.search-box input:focus{border-color:#8d8df6; box-shadow:0 0 0 6px rgba(141,141,246,.08)}

/* =============== Product Card =============== */
.product-card{
  background:var(--surface); border-radius:var(--radius);
  box-shadow:var(--shadow-sm); overflow:hidden; display:flex; flex-direction:column;
  transition:var(--transition); border:1px solid var(--line);
}
.product-card:hover{transform:translateY(-6px); box-shadow:var(--shadow-md)}
.product-card::before{
  content:""; position:absolute; inset:0; pointer-events:none;
  background:linear-gradient(180deg,rgba(112,105,244,.08),transparent 35%);
  opacity:0; transition:var(--transition);
}
.product-card:hover::before{opacity:1}

.product-image{position:relative; height:210px; background:#f6f8fb}
.product-image img{width:100%; height:100%; object-fit:cover; transition:transform .5s}
.product-card:hover .product-image img{transform:scale(1.05)}
.no-image{height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#cdd5df}
.no-image i{font-size:2.2rem}
.badge-code{
  position:absolute; top:10px; left:10px;
  background:#ffffffcc; color:#394150; border:1px solid #e6eaef;
  padding:5px 10px; font-size:.8rem; border-radius:999px; backdrop-filter:blur(4px)
}

.product-content{padding:14px 14px 12px; display:flex; flex-direction:column; gap:8px; flex:1}
.product-title{font-weight:700; color:var(--text); margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis}
.product-meta{display:flex; flex-wrap:wrap; gap:6px}
.product-actions{margin-top:auto; display:flex; gap:8px}

/* Badge palette refinement */
.badge.bg-light{background:#f9fafb!important; border:1px solid #e9eef5!important}

/* =============== Table Modern =============== */
.table-responsive{border-radius:var(--radius); border:1px solid var(--line); overflow:hidden; background:#fff; box-shadow:var(--shadow-xs)}
.modern-table{margin:0}
.modern-table thead th{
  background:#f7f9fc; border-bottom:1px solid var(--line);
  text-transform:uppercase; font-size:.78rem; letter-spacing:.6px; color:#667085
}
.modern-table tbody tr{transition:var(--transition)}
.modern-table tbody tr:hover{background:#fbfcff}
.modern-table tbody td{vertical-align:middle}
.table-image,.table-no-image{width:58px;height:58px;border-radius:10px;object-fit:cover}
.table-no-image{display:flex;align-items:center;justify-content:center;background:#f0f3f8;color:#c7cfdb}

/* =============== Modal Upload =============== */
.image-upload-container{text-align:center}
.image-preview{
  width:100%; max-width:420px; height:260px; margin:0 auto;
  border:2px dashed #dfe6f0; border-radius:var(--radius);
  display:flex; align-items:center; justify-content:center; background:#fafbff; transition:var(--transition)
}
.image-preview:hover{border-color:#8d8df6; background:#f4f6ff}
.image-preview img{width:100%; height:100%; object-fit:contain; border-radius:12px}
.upload-placeholder{color:#8a94a6}
.upload-placeholder i{font-size:2.6rem; color:#d3daea}

/* =============== Empty State =============== */
.empty-state{text-align:center; padding:42px 16px}
.empty-state i{font-size:3rem; color:#cfd6e3; margin-bottom:10px}

/* =============== FAB =============== */
.fab-container{position:fixed; right:20px; bottom:20px; z-index:1000}
.fab{
  width:58px; height:58px; border:none; border-radius:999px;
  background:var(--grad-primary); box-shadow:var(--shadow-sm);
  transition:var(--transition)
}
.fab:hover{transform:scale(1.06) rotate(8deg); box-shadow:var(--shadow-md)}

/* =============== Buttons =============== */
.btn-rounded{border-radius:999px}
.btn-outline-secondary.active, .btn-outline-secondary[aria-pressed="true"]{
  background:#eef1ff; border-color:#cfd5ff; color:#4f58f7;
}

/* =============== Animations =============== */
@keyframes fadeInUp{from{opacity:0; transform:translateY(10px)} to{opacity:1; transform:translateY(0)}}
.product-item{animation:fadeInUp .35s ease both}

/* Responsive tweak */
@media (max-width: 768px){
  .filter-meta{justify-content:flex-start; margin-top:10px}
}
</style>
@endsection


@section('js')
<script>
// =============== Utils ===============
const debounce = (fn, wait=250) => {
  let t; return (...args) => { clearTimeout(t); t=setTimeout(()=>fn(...args), wait); };
};

// =============== State ===============
let currentFilters = {
  skin: 'all',
  priceMin: 0,
  priceMax: null,
  spf: 'all',
  search: ''
};

// =============== Init ===============
$(function(){
  $('[data-bs-toggle="tooltip"]').tooltip();
  animateOnScroll();

  // Restore view mode
  const savedView = localStorage.getItem('product_view') || 'grid';
  toggleView(savedView);

  // Buttons view
  $('#gridViewBtn').on('click', ()=>toggleView('grid'));
  $('#listViewBtn').on('click', ()=>toggleView('list'));

  // Skin
  $('.filter-skin').on('click', function(e){
    e.preventDefault();
    currentFilters.skin = $(this).data('filter');
    applyFilters();
    setChip('#chip-skin', currentFilters.skin==='all' ? null : `Kulit: ${capitalize(currentFilters.skin)}`);
  });

  // Price
  $('.filter-price').on('click', function(e){
    e.preventDefault();
    currentFilters.priceMin = parseInt($(this).data('min')) || 0;
    currentFilters.priceMax = $(this).data('max') ? parseInt($(this).data('max')) : null;
    applyFilters();

    let label = 'Harga: Semua';
    if(currentFilters.priceMax===null && currentFilters.priceMin>0) label = `Harga ≥ Rp ${formatIDR(currentFilters.priceMin)}`;
    if(currentFilters.priceMax!==null && currentFilters.priceMin===0) label = `Harga ≤ Rp ${formatIDR(currentFilters.priceMax)}`;
    if(currentFilters.priceMax!==null && currentFilters.priceMin>0) label = `Harga Rp ${formatIDR(currentFilters.priceMin)}–${formatIDR(currentFilters.priceMax)}`;
    setChip('#chip-price', (currentFilters.priceMin===0 && currentFilters.priceMax===null) ? null : label);
  });

  // SPF
  $('.filter-spf').on('click', function(e){
    e.preventDefault();
    currentFilters.spf = $(this).data('spf');
    applyFilters();
    setChip('#chip-spf', currentFilters.spf==='all' ? null : `SPF ${currentFilters.spf}`);
  });

  // Search
  $('#searchInput').on('input', debounce(function(){
    currentFilters.search = $(this).val().toLowerCase();
    applyFilters();
    setChip('#chip-search', currentFilters.search ? `Cari: "${currentFilters.search}"` : null);
  }, 200));

  // First run
  applyFilters();
});

// =============== View Toggle ===============
function toggleView(mode){
  const isGrid = mode==='grid';
  $('#gridView').toggle(isGrid);
  $('#listView').toggle(!isGrid);
  $('#gridViewBtn').toggleClass('active', isGrid).attr('aria-pressed', isGrid);
  $('#listViewBtn').toggleClass('active', !isGrid).attr('aria-pressed', !isGrid);
  localStorage.setItem('product_view', mode);
}

// =============== Filters Apply ===============
function applyFilters(){
  let visible = 0;
  $('.product-item').each(function(){
    let show = true;
    const $item = $(this);

    // Skin
    if(currentFilters.skin!=='all' && ($item.data('skin') !== currentFilters.skin)) show = false;

    // Price
    const price = parseInt($item.data('price')) || 0;
    if(currentFilters.priceMin && price < currentFilters.priceMin) show = false;
    if(currentFilters.priceMax!==null && price > currentFilters.priceMax) show = false;

    // SPF
    if(currentFilters.spf!=='all'){
      const itemSpf = String($item.data('spf')||'');
      if(itemSpf != String(currentFilters.spf)) show = false;
    }

    // Search
    if(currentFilters.search){
      const text = $item.text().toLowerCase();
      if(!text.includes(currentFilters.search)) show = false;
    }

    $item.toggle(show);
    if(show) visible++;
  });

  // Update counter
  $('#resultCount').text(`${visible} produk ditampilkan`);
}

// =============== Chips helper ===============
function setChip(selector, text){
  const $chip = $(selector);
  if(!text){ $chip.removeClass('active').attr('aria-hidden','true'); return; }
  $chip.addClass('active').attr('aria-hidden','false').find('.chip-text').text(text);
}
function clearChip(selector, clearFn){
  $(selector).removeClass('active').attr('aria-hidden','true');
  if(typeof clearFn==='function') clearFn();
  applyFilters();
}

// =============== Image Upload ===============
function handleImageSelect(input){
  if(input.files && input.files[0]){
    const file = input.files[0];
    const validTypes = ['image/jpeg','image/jpg','image/png'];
    if(!validTypes.includes(file.type)){
      Swal.fire({icon:'error',title:'Format Tidak Valid',text:'Hanya JPG & PNG',confirmButtonColor:'#667eea'});
      input.value = ''; return;
    }
    if(file.size > 2*1024*1024){
      Swal.fire({icon:'error',title:'File Terlalu Besar',text:'Maksimal 2MB',confirmButtonColor:'#667eea'});
      input.value=''; return;
    }
    const reader = new FileReader();
    reader.onload = e=>{
      $('#previewImg').attr('src', e.target.result).show();
      $('#uploadPlaceholder').hide();
      $('#removeImageBtn').show();
    };
    reader.readAsDataURL(file);
  }
}
function removeImage(){
  $('#imageInput').val('');
  $('#previewImg').hide();
  $('#uploadPlaceholder').show();
  $('#removeImageBtn').hide();
}

// =============== Create / Edit ===============
function create_button(){
  $('#modalTitle').html('<i class="bi bi-plus-circle"></i> Tambah Produk');
  $('#formAlternatif')[0].reset();
  $('#formAlternatif').attr('action','{{ route("alternatif.store") }}');
  removeImage();
}
function show_button(id){
  $('#modalTitle').html('<i class="bi bi-pencil"></i> Edit Produk');
  $('#formAlternatif').attr('action','{{ route("alternatif.update") }}');

  Swal.fire({title:'Memuat data...', allowOutsideClick:false, didOpen:()=>Swal.showLoading()});
  $.ajax({
    url:'{{ route("alternatif.edit") }}', type:'GET',
    data:{ _token:'{{ csrf_token() }}', alternatif_id:id },
    success:function(data){
      $('#formAlternatif input[name=id]').val(data.id);
      $('#kode_produk').val(data.kode_produk);
      $('#nama_produk').val(data.nama_produk);
      $('#jenis_kulit').val(data.jenis_kulit);
      $('#harga').val(data.harga);
      $('#spf').val(data.spf);

      if(data.gambar){
        $('#previewImg').attr('src','/img/produk/'+data.gambar).show();
        $('#uploadPlaceholder').hide();
        $('#removeImageBtn').show();
      }else{ removeImage(); }
      Swal.close();
    },
    error:function(){
      Swal.fire({icon:'error',title:'Error',text:'Gagal memuat data'});
    }
  });
}

// =============== Delete Confirm (SweetAlert) ===============
function confirmDelete(name){
  event.preventDefault(); // stop default submit
  const form = event.target.closest('form');
  Swal.fire({
    title:'Hapus Produk?',
    html:`Produk <b>${name}</b> akan dihapus.`,
    icon:'warning', showCancelButton:true,
    confirmButtonText:'Ya, hapus', cancelButtonText:'Batal',
    confirmButtonColor:'#ef4444'
  }).then((r)=>{ if(r.isConfirmed) form.submit(); });
  return false;
}

// =============== A11y + Visual Enhancements ===============
function animateOnScroll(){
  const observer = new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
      if(entry.isIntersecting){ entry.target.classList.add('animate-in'); observer.unobserve(entry.target); }
    });
  },{threshold:.08});
  document.querySelectorAll('.product-card').forEach(c=>observer.observe(c));
}

// =============== Format helpers ===============
const formatIDR = (n)=> n.toLocaleString('id-ID');
const capitalize = (s)=> s.charAt(0).toUpperCase()+s.slice(1);

// =============== Toasts ===============
@if(session('success'))
  Swal.fire({toast:true, position:'top-end', icon:'success', title:'{{ session("success") }}', showConfirmButton:false, timer:3000, timerProgressBar:true});
@endif
@if(session('error'))
  Swal.fire({toast:true, position:'top-end', icon:'error', title:'{{ session("error") }}', showConfirmButton:false, timer:3000, timerProgressBar:true});
@endif
</script>
@endsection
