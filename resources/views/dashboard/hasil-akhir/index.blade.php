@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Hasil Akhir Perankingan Menu</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hasil Akhir</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('perhitungan') }}" class="btn btn-secondary">
                        <i class="bi bi-calculator"></i> Perhitungan
                    </a>
                    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
                        @php
                            $pdfParams = [
                                'jenis_menu' => $jenisMenu ?? 'all',
                                'harga' => $filterHarga ?? 'all'
                            ];
                            $pdfUrl = route('pdf.hasilAkhir') . '?' . http_build_query(array_filter($pdfParams, fn($v) => $v !== 'all'));
                        @endphp
                        <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-danger">
                            <i class="bi bi-file-pdf"></i> Cetak PDF
                        </a>
                    @endif
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
                    <form method="GET" action="{{ route('hasil-akhir') }}">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Jenis Menu</label>
                                <select name="jenis_menu" class="form-select">
                                    <option value="all">Semua Jenis Menu</option>
                                    @php
                                        $jenisMenuList = [
                                            'makanan' => 'Makanan',
                                            'cemilan' => 'Cemilan',
                                            'coffee' => 'Coffee',
                                            'milkshake' => 'Milkshake',
                                            'mojito' => 'Mojito',
                                            'yakult' => 'Yakult',
                                            'tea' => 'Tea'
                                        ];
                                    @endphp
                                    @foreach($jenisMenuList as $key => $label)
                                    <option value="{{ $key }}" {{ ($jenisMenu ?? 'all') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Range Harga</label>
                                <select name="harga" class="form-select">
                                    <option value="all">Semua Harga</option>
                                    <option value="<=20000" {{ ($filterHarga ?? 'all') == '<=20000' ? 'selected' : '' }}>
                                        ≤ Rp 20.000
                                    </option>
                                    <option value=">20000-<=25000" {{ ($filterHarga ?? 'all') == '>20000-<=25000' ? 'selected' : '' }}>
                                        Rp 20.001 - Rp 25.000
                                    </option>
                                    <option value=">25000-<=30000" {{ ($filterHarga ?? 'all') == '>25000-<=30000' ? 'selected' : '' }}>
                                        Rp 25.001 - Rp 30.000
                                    </option>
                                    <option value=">30000" {{ ($filterHarga ?? 'all') == '>30000' ? 'selected' : '' }}>
                                        > Rp 30.000
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label d-none d-md-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                            <a href="{{ route('hasil-akhir') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset Filter
                            </a>
                            @if(isset($nilaiAkhir))
                                <span id="resultCount" class="result-count ms-auto">{{ $nilaiAkhir->count() }} menu ditampilkan</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
        {{-- Toolbar kecil: toggle grid/list --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Perankingan Menu Cafe Buri Umah</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary active" id="rankGridBtn">
                    <i class="bi bi-grid-3x3-gap"></i> Grid
                </button>
                <button type="button" class="btn btn-outline-secondary" id="rankListBtn">
                    <i class="bi bi-list-ul"></i> List
                </button>
            </div>
        </div>

        {{-- GRID VIEW (default) --}}
        <div id="rankGrid" class="row g-3">
            @foreach($nilaiAkhir as $index => $row)
                @php
                    $rank = $index + 1;
                    $alt = $row->alternatif ?? null;
                    $jenis = $alt->jenis_menu ?? '';
                    $menuColor = match($jenis) {
                        'makanan' => 'success',
                        'cemilan' => 'warning',
                        'coffee' => 'dark',
                        'milkshake' => 'info',
                        'mojito' => 'danger',
                        'yakult' => 'primary',
                        'tea' => 'secondary',
                        default => 'secondary'
                    };
                    $menuIcon = match($jenis) {
                        'makanan' => 'bi-egg-fried',
                        'cemilan' => 'bi-cookie',
                        'coffee' => 'bi-cup-hot',
                        'milkshake' => 'bi-cup-straw',
                        'mojito' => 'bi-tropical-storm',
                        'yakult' => 'bi-droplet',
                        'tea' => 'bi-cup',
                        default => 'bi-bag'
                    };
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-card h-100 position-relative">
                        {{-- Rank ribbon --}}
                        <div class="rank-ribbon {{ $rank == 1 ? 'gold' : ($rank == 2 ? 'silver' : ($rank == 3 ? 'bronze' : 'info')) }}">
                            <i class="bi bi-trophy-fill me-1"></i>{{ $rank }}
                        </div>

                        {{-- Gambar --}}
                        <div class="product-image">
                            @if($alt && $alt->has_gambar)
                                <img src="{{ $alt->gambar_url }}" alt="{{ $alt->nama_menu }}" loading="lazy">
                            @elseif($alt && $alt->gambar)
                                <img src="{{ asset('img/menu/' . $alt->gambar) }}" alt="{{ $alt->nama_menu }}" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-image\'><i class=\'bi bi-{{ $menuIcon }}\'></i><span>No Image</span></div>';">
                            @else
                                <div class="no-image">
                                    <i class="bi {{ $menuIcon }}"></i>
                                    <span>No Image</span>
                                </div>
                            @endif
                            <span class="badge-code">{{ $alt->kode_menu ?? '-' }}</span>
                        </div>

                        {{-- Konten --}}
                        <div class="product-content">
                            <h6 class="product-title mb-1" title="{{ $alt->nama_menu ?? '-' }}">
                                {{ $alt->nama_menu ?? '-' }}
                            </h6>

                            <div class="product-meta">
                                <span class="badge bg-{{ $menuColor }}">
                                    <i class="bi {{ $menuIcon }}"></i> {{ \App\Models\Alternatif::JENIS_MENU[$jenis] ?? ucfirst($jenis ?: '-') }}
                                </span>

                                @if($alt && $alt->harga)
                                    <span class="badge bg-light text-success border">
                                        <i class="bi bi-cash"></i> {{ \App\Models\Alternatif::KATEGORI_HARGA[$alt->harga] ?? $alt->harga }}
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                <span class="badge bg-primary px-3 py-2 fs-6">
                                    Score: {{ number_format($row->total ?? 0, 4) }}
                                </span>
                                @if($rank == 1)
                                    <span class="badge bg-success">Terbaik</span>
                                @elseif($rank <= 3)
                                    <span class="badge bg-info">Rekomendasi</span>
                                @else
                                    <span class="badge bg-light text-dark">Alternatif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- LIST VIEW (opsional) --}}
        <div id="rankList" class="table-responsive" style="display:none;">
            <table class="table table-hover modern-table align-middle">
                <thead>
                    <tr>
                        <th width="70" class="text-center">Rank</th>
                        <th width="90">Gambar</th>
                        <th>Kode</th>
                        <th>Nama Menu</th>
                        <th>Jenis Menu</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Nilai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilaiAkhir as $index => $row)
                        @php
                            $rank = $index + 1;
                            $alt = $row->alternatif ?? null;
                            $jenis = $alt->jenis_menu ?? '';
                            $menuColor = match($jenis) {
                                'makanan' => 'success',
                                'cemilan' => 'warning',
                                'coffee' => 'dark',
                                'milkshake' => 'info',
                                'mojito' => 'danger',
                                'yakult' => 'primary',
                                'tea' => 'secondary',
                                default => 'secondary'
                            };
                            $menuIcon = match($jenis) {
                                'makanan' => 'bi-egg-fried',
                                'cemilan' => 'bi-cookie',
                                'coffee' => 'bi-cup-hot',
                                'milkshake' => 'bi-cup-straw',
                                'mojito' => 'bi-tropical-storm',
                                'yakult' => 'bi-droplet',
                                'tea' => 'bi-cup',
                                default => 'bi-bag'
                            };
                        @endphp
                        <tr>
                            <td class="text-center">
                                <span class="badge {{ $rank==1?'bg-warning text-dark':($rank==2?'bg-secondary':($rank==3?'bg-danger':'bg-info')) }} fs-6">{{ $rank }}</span>
                            </td>
                            <td>
                                @if($alt && $alt->has_gambar)
                                    <img src="{{ $alt->gambar_url }}" class="table-image" alt="{{ $alt->nama_menu }}">
                                @elseif($alt && $alt->gambar)
                                    <img src="{{ asset('img/menu/' . $alt->gambar) }}" class="table-image" alt="{{ $alt->nama_menu }}" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'table-no-image\'><i class=\'bi bi-image\'></i></div>';">
                                @else
                                    <div class="table-no-image"><i class="bi bi-{{ $menuIcon }}"></i></div>
                                @endif
                            </td>
                            <td><span class="badge bg-primary">{{ $alt->kode_menu ?? '-' }}</span></td>
                            <td><strong>{{ $alt->nama_menu ?? '-' }}</strong></td>
                            <td><span class="badge bg-{{ $menuColor }}">{{ \App\Models\Alternatif::JENIS_MENU[$jenis] ?? ucfirst($jenis ?: '-') }}</span></td>
                            <td class="text-center">
                                @if($alt && $alt->harga)
                                    <span class="text-success">{{ \App\Models\Alternatif::KATEGORI_HARGA[$alt->harga] ?? $alt->harga }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ number_format($row->total ?? 0, 4) }}</span>
                            </td>
                            <td>
                                @if($rank == 1)
                                    <span class="badge bg-success">Terbaik</span>
                                @elseif($rank <= 3)
                                    <span class="badge bg-info">Rekomendasi</span>
                                @else
                                    <span class="badge bg-light text-dark">Alternatif</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- Empty State --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Tidak Ada Data</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['jenis_menu', 'harga']) && 
                                (request('jenis_menu') != 'all' || request('harga') != 'all'))
                                Tidak ada menu yang sesuai dengan filter.
                                <a href="{{ route('hasil-akhir') }}">Reset filter</a>
                            @else
                                Belum ada data hasil perhitungan. Silakan lakukan perhitungan terlebih dahulu.
                            @endif
                        </p>
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
:root{
  --line:#e8ecf2; --shadow-xs:0 1px 2px rgba(17,24,39,.06);
  --shadow-sm:0 4px 10px rgba(17,24,39,.08);
  --shadow-md:0 8px 24px rgba(17,24,39,.12);
  --transition:all .25s cubic-bezier(.22,.61,.36,1);
}
.result-count{
  font-size:.9rem; color:#6b7380;
  background:#f7f9fc; border:1px solid var(--line);
  padding:6px 10px; border-radius:999px;
}

/* Card gaya "Data Menu" */
.product-card{background:#fff; border:1px solid var(--line); border-radius:16px; overflow:hidden; display:flex; flex-direction:column; box-shadow:var(--shadow-sm); transition:var(--transition)}
.product-card:hover{transform:translateY(-6px); box-shadow:var(--shadow-md)}
.product-image{position:relative; height:200px; background:#f6f8fb}
.product-image img{width:100%; height:100%; object-fit:cover; transition:transform .5s}
.product-card:hover .product-image img{transform:scale(1.05)}
.no-image{height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#cdd5df}
.no-image i{font-size:3rem}
.badge-code{position:absolute; top:10px; left:10px; background:#ffffffcc; color:#394150; border:1px solid #e6eaef; padding:4px 10px; border-radius:999px; font-size:.8rem; backdrop-filter:blur(4px)}
.product-content{padding:14px; display:flex; flex-direction:column; gap:8px; flex:1}
.product-title{font-weight:700; color:#273142; white-space:nowrap; overflow:hidden; text-overflow:ellipsis}
.product-meta{display:flex; flex-wrap:wrap; gap:6px}

/* Rank ribbon */
.rank-ribbon{
  position:absolute; top:10px; right:-12px; z-index:2;
  color:#fff; font-weight:700; padding:6px 16px; transform:skew(-12deg);
  border-radius:6px; box-shadow:var(--shadow-xs); font-size:.95rem
}
.rank-ribbon i{transform:skew(12deg)}
.rank-ribbon.gold{background:linear-gradient(135deg,#facc15,#f59e0b)}
.rank-ribbon.silver{background:linear-gradient(135deg,#d1d5db,#9ca3af)}
.rank-ribbon.bronze{background:linear-gradient(135deg,#f59e0b,#b45309)}
.rank-ribbon.info{background:linear-gradient(135deg,#60a5fa,#38bdf8)}

/* List view images & table header */
.table-image,.table-no-image{width:58px;height:58px;border-radius:10px;object-fit:cover}
.table-no-image{display:flex;align-items:center;justify-content:center;background:#f0f3f8;color:#c7cfdb}
.modern-table thead th{
  background:#f7f9fc; border-bottom:1px solid var(--line);
  text-transform:uppercase; font-size:.78rem; letter-spacing:.6px; color:#667085
}

/* Animasi masuk */
@keyframes fadeInUp{from{opacity:0; transform:translateY(8px)} to{opacity:1; transform:translateY(0)}}
#rankGrid .col-xl-3,#rankGrid .col-lg-4,#rankGrid .col-md-6{animation:fadeInUp .35s ease both}
</style>
@endsection

@section('js')
<script>
$(function(){
  // Toggle grid/list
  $('#rankGridBtn').on('click', function(){
    $('#rankGrid').show(); $('#rankList').hide();
    $(this).addClass('active'); $('#rankListBtn').removeClass('active');
  });
  $('#rankListBtn').on('click', function(){
    $('#rankList').show(); $('#rankGrid').hide();
    $(this).addClass('active'); $('#rankGridBtn').removeClass('active');
  });

  // Update counter jika ada
  const gridCount = $('#rankGrid .product-card').length;
  if($('#resultCount').length){ $('#resultCount').text(gridCount + ' menu ditampilkan'); }
});
</script>
@endsection