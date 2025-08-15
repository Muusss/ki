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
                <i class="bi bi-list-ul"></i> Hasil Akhir
            </a>
            <a href="{{ route('perhitungan') }}" class="btn btn-primary">
                <i class="bi bi-calculator"></i> Perhitungan
            </a>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-gradient-primary">
                <div class="stats-icon"><i class="bi bi-box-seam"></i></div>
                <div class="stats-content">
                    <p>Total Produk</p>
                    <h4>{{ $jumlahProduk ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-gradient-success">
                <div class="stats-icon"><i class="bi bi-list-check"></i></div>
                <div class="stats-content">
                    <p>Kriteria Penilaian</p>
                    <h4>{{ $jumlahKriteria ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-gradient-warning">
                <div class="stats-icon"><i class="bi bi-clipboard-data"></i></div>
                <div class="stats-content">
                    <p>Data Penilaian</p>
                    <h4>{{ $jumlahPenilaian ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card bg-gradient-info">
                <div class="stats-icon"><i class="bi bi-trophy-fill"></i></div>
                <div class="stats-content">
                    <p>Produk Teratas</p>
                    @php
                        $first = isset($nilaiAkhir) && $nilaiAkhir->count() > 0 ? $nilaiAkhir->first() : null;
                        $topName = $first ? $first->alternatif->nama_produk : '-';
                    @endphp
                    <h6 class="mb-0">{{ $topName }}</h6>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER (langsung redirect ke Hasil Akhir) --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="soft-card filter-section">
                <form action="{{ route('hasil-akhir') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kulit</label>
                        <select name="jenis_kulit" id="f_skin" class="form-select">
                            @php $jenisReq = request('jenis_kulit','all'); @endphp
                            <option value="all" {{ $jenisReq==='all' ? 'selected' : '' }}>Semua Jenis Kulit</option>
                            @foreach(($jenisKulitList ?? ['normal','berminyak','kering','kombinasi']) as $jenis)
                                <option value="{{ $jenis }}" {{ $jenisReq===$jenis ? 'selected' : '' }}>
                                    {{ ucfirst($jenis) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Range Harga</label>
                        <select name="harga" id="f_harga" class="form-select">
                            @php $hargaReq = request('harga','all'); @endphp
                            <option value="all" {{ $hargaReq==='all' ? 'selected' : '' }}>Semua Harga</option>
                            <option value="<=40000" {{ $hargaReq==='<=40000' ? 'selected' : '' }}>≤ Rp 40.000</option>
                            <option value="40001-60000" {{ $hargaReq==='40001-60000' ? 'selected' : '' }}>Rp 40.001 - Rp 60.000</option>
                            <option value="60001-80000" {{ $hargaReq==='60001-80000' ? 'selected' : '' }}>Rp 60.001 - Rp 80.000</option>
                            <option value=">80000" {{ $hargaReq==='>80000' ? 'selected' : '' }}>> Rp 80.000</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SPF</label>
                        <select name="spf" id="f_spf" class="form-select">
                            @php $spfReq = request('spf','all'); @endphp
                            <option value="all" {{ $spfReq==='all' ? 'selected' : '' }}>Semua SPF</option>
                            <option value="30" {{ $spfReq==='30' ? 'selected' : '' }}>SPF 30</option>
                            <option value="35" {{ $spfReq==='35' ? 'selected' : '' }}>SPF 35</option>
                            <option value="40" {{ $spfReq==='40' ? 'selected' : '' }}>SPF 40</option>
                            <option value="50" {{ $spfReq==='50' ? 'selected' : '' }}>SPF 50</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Chart + Top 5 --}}
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="soft-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0">Grafik Nilai Produk</h6>
                    <div class="text-muted small">ROC + SMART</div>
                </div>
                <div id="chart_peringkat" style="min-height: 350px;"></div>
            </div>
        </div>

        {{-- <div class="col-xl-4 col-lg-5">
            <div class="soft-card mb-4">
                <h6 class="m-0 mb-3">Top 5 Produk Teratas</h6>
                @forelse(($top5 ?? []) as $index => $item)
                    @php $alt = $item->alternatif ?? null; @endphp
                    <div class="top5-item product-card-mini h-100 mb-3">
                        <div class="product-image-mini">
                            @if($alt && $alt->gambar && file_exists(public_path('img/produk/'.$alt->gambar)))
                                <img src="{{ asset('img/produk/'.$alt->gambar) }}" alt="{{ $alt->nama_produk ?? '-' }}" class="img-fluid">
                            @elseif($alt && method_exists($alt,'getAttribute') && $alt->has_gambar)
                                <img src="{{ $alt->gambar_url }}" alt="{{ $alt->nama_produk ?? '-' }}" class="img-fluid">
                            @else
                                <div class="no-image-mini">
                                    <i class="bi bi-image"></i>
                                    <small>No Image</small>
                                </div>
                            @endif
                            <div class="rank-ribbon {{ $loop->iteration==1?'gold':($loop->iteration==2?'silver':($loop->iteration==3?'bronze':'info')) }}">
                                <i class="bi bi-trophy-fill me-1"></i>{{ $loop->iteration }}
                            </div>
                        </div>
                        <div class="product-info-mini d-flex align-items-center justify-content-between">
                            <div class="me-2">
                                <h6 class="product-name mb-0" title="{{ $alt->nama_produk ?? '-' }}">{{ $alt->nama_produk ?? '-' }}</h6>
                                <small class="text-muted">{{ $alt->kode_produk ?? '-' }}</small>
                            </div>
                            <span class="badge bg-primary">{{ number_format((float) ($item->total ?? 0), 3) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted mb-0">Belum ada data</p>
                @endforelse
            </div>
        </div> --}}
    </div>

    {{-- Tabel Hasil Perankingan (tampilan cepat, tanpa filter/dashboard JS) --}}
    <div class="row">
        <div class="col-12">
            <div class="soft-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0">Hasil Perankingan Produk</h6>
                    <a href="{{ route('pdf.hasilAkhir') }}" target="_blank" class="btn btn-sm btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    </a>
                </div>
                <div class="table-responsive">
                    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
                        <table class="table modern-table align-middle" id="rankingTable">
                            <thead>
                                <tr>
                                    <th width="70">Rank</th>
                                    <th width="90">Gambar</th>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Jenis Kulit</th>
                                    <th>SPF</th>
                                    <th>Harga</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilaiAkhir as $row)
                                    @php
                                        $alt = $row->alternatif ?? null;
                                        $jenis = $alt->jenis_kulit ?? '';
                                        $skinColor = match($jenis) {
                                            'normal' => 'success',
                                            'berminyak' => 'warning',
                                            'kering' => 'info',
                                            'kombinasi' => 'secondary',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <tr class="rank-row">
                                        <td>
                                            <span class="badge {{ $loop->iteration==1?'bg-warning text-dark':($loop->iteration==2?'bg-secondary':($loop->iteration==3?'bg-danger':'bg-info')) }} fs-6">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            @if($alt && $alt->gambar && file_exists(public_path('img/produk/'.$alt->gambar)))
                                                <img src="{{ asset('img/produk/'.$alt->gambar) }}" class="table-image" alt="{{ $alt->nama_produk ?? '-' }}">
                                            @elseif($alt && method_exists($alt,'getAttribute') && $alt->has_gambar)
                                                <img src="{{ $alt->gambar_url }}" class="table-image" alt="{{ $alt->nama_produk ?? '-' }}">
                                            @else
                                                <div class="table-no-image"><i class="bi bi-image"></i></div>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-primary">{{ $alt->kode_produk ?? '-' }}</span></td>
                                        <td><strong>{{ $alt->nama_produk ?? '-' }}</strong></td>
                                        <td><span class="badge bg-{{ $skinColor }}">{{ ucfirst($jenis ?: '-') }}</span></td>
                                        <td>
                                            @if(!is_null($alt?->spf) && $alt->spf!=='')
                                                <span class="badge bg-warning text-dark">SPF {{ $alt->spf }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!is_null($alt?->harga))
                                                <span class="text-success">Rp {{ number_format($alt->harga,0,',','.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-success">{{ number_format((float) ($row->total ?? 0), 4) }}</span></td>
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
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #cfd6e3;"></i>
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

    {{-- Produk Grid Showcase --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="soft-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0">Produk Sunscreen Terdaftar</h6>
                    <a href="{{ route('alternatif') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Kelola Produk
                    </a>
                </div>
                <div class="row g-3" id="productShowcase">
                    @php
                        $products = \App\Models\Alternatif::orderBy('kode_produk')->limit(12)->get();
                    @endphp
                    @forelse($products as $p)
                        @php
                            $skinColor = match($p->jenis_kulit) {
                                'normal' => 'success',
                                'berminyak' => 'warning',
                                'kering' => 'info',
                                'kombinasi' => 'secondary',
                                default => 'secondary'
                            };
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 product-item">
                            <div class="product-card h-100">
                                <div class="product-image">
                                    @if($p->gambar && file_exists(public_path('img/produk/'.$p->gambar)))
                                        <img src="{{ asset('img/produk/'.$p->gambar) }}" alt="{{ $p->nama_produk }}">
                                    @elseif(method_exists($p,'getAttribute') && $p->has_gambar)
                                        <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_produk }}">
                                    @else
                                        <div class="no-image">
                                            <i class="bi bi-image"></i>
                                            <span>No Image</span>
                                        </div>
                                    @endif
                                    <span class="badge-code">{{ $p->kode_produk }}</span>
                                </div>
                                <div class="product-content">
                                    <h6 class="product-title" title="{{ $p->nama_produk }}">{{ $p->nama_produk }}</h6>
                                    <div class="product-meta">
                                        <span class="badge bg-{{ $skinColor }}"><i class="bi bi-droplet-fill"></i> {{ ucfirst($p->jenis_kulit) }}</span>
                                        @if(!is_null($p->harga))
                                            <span class="badge bg-light text-success border"><i class="bi bi-cash"></i> Rp {{ number_format($p->harga,0,',','.') }}</span>
                                        @endif
                                        @if(!is_null($p->spf) && $p->spf!=='')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-sun"></i> SPF {{ $p->spf }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #cfd6e3;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada produk terdaftar</p>
                                <a href="{{ route('alternatif') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
:root{
  --line:#e8ecf2; --shadow-xs:0 1px 2px rgba(17,24,39,.06);
  --shadow-sm:0 4px 10px rgba(17,24,39,.08);
  --shadow-md:0 8px 24px rgba(17,24,39,.12);
  --transition:all .25s cubic-bezier(.22,.61,.36,1);
  --grad-primary:linear-gradient(135deg,#7069f4 0%,#9c68f5 100%);
  --grad-success:linear-gradient(135deg,#34d399 0%,#22c55e 100%);
  --grad-warning:linear-gradient(135deg,#f59e0b 0%,#fbbf24 100%);
  --grad-info:linear-gradient(135deg,#60a5fa 0%,#38bdf8 100%);
}

/* Cards */
.soft-card{background:#fff; border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow-sm); padding:1rem 1.25rem}
.stats-card{position:relative; overflow:hidden; border-radius:16px; padding:16px; color:#fff; box-shadow:var(--shadow-xs); transition:var(--transition)}
.stats-card:hover{transform:translateY(-4px); box-shadow:var(--shadow-md)}
.stats-card::after{content:""; position:absolute; inset:0; background:radial-gradient(140px 140px at 110% -10%, rgba(255,255,255,.35), transparent 60%)}
.stats-icon{position:absolute; right:14px; top:50%; transform:translateY(-50%); font-size:2.25rem; opacity:.28}
.stats-content h4{margin:0; font-weight:800}
.stats-content p{margin:0; opacity:.95}
.bg-gradient-primary{background:var(--grad-primary)}
.bg-gradient-success{background:var(--grad-success)}
.bg-gradient-warning{background:var(--grad-warning)}
.bg-gradient-info{background:var(--grad-info)}

/* Filter bar */
.filter-section{position:sticky; top:0; z-index:20; backdrop-filter:saturate(1.05) blur(6px)}

/* Product cards */
.product-card{background:#fff; border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow-sm); overflow:hidden; display:flex; flex-direction:column; transition:var(--transition)}
.product-card:hover{transform:translateY(-6px); box-shadow:var(--shadow-md)}
.product-image{position:relative; height:200px; background:#f6f8fb; overflow:hidden}
.product-image img{width:100%; height:100%; object-fit:cover; transition:transform .5s}
.product-card:hover .product-image img{transform:scale(1.05)}
.no-image{height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#cdd5df}
.no-image i{font-size:2rem}
.badge-code{position:absolute; top:10px; left:10px; background:#ffffffcc; color:#394150; border:1px solid #e6eaef; padding:4px 10px; border-radius:999px; font-size:.8rem; backdrop-filter:blur(4px)}
.product-content{padding:14px; display:flex; flex-direction:column; gap:8px; flex:1}
.product-title{font-weight:700; color:#273142; white-space:nowrap; overflow:hidden; text-overflow:ellipsis}
.product-meta{display:flex; flex-wrap:wrap; gap:6px}

/* Top-5 mini */
.product-card-mini{background:#fff; border:1px solid var(--line); border-radius:14px; overflow:hidden; box-shadow:var(--shadow-xs); transition:var(--transition)}
.product-card-mini:hover{transform:translateY(-4px); box-shadow:var(--shadow-md)}
.product-image-mini{position:relative; height:130px; background:#f6f8fb; overflow:hidden}
.product-image-mini img{width:100%; height:100%; object-fit:cover}
.no-image-mini{height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#cdd5df}
.product-info-mini{padding:.75rem}
.rank-ribbon{position:absolute; top:10px; right:-10px; z-index:2; color:#fff; font-weight:700; padding:6px 14px; transform:skew(-12deg); border-radius:6px; box-shadow:var(--shadow-xs); font-size:.9rem}
.rank-ribbon i{transform:skew(12deg)}
.rank-ribbon.gold{background:linear-gradient(135deg,#facc15,#f59e0b)}
.rank-ribbon.silver{background:linear-gradient(135deg,#d1d5db,#9ca3af)}
.rank-ribbon.bronze{background:linear-gradient(135deg,#f59e0b,#b45309)}
.rank-ribbon.info{background:linear-gradient(135deg,#60a5fa,#38bdf8)}

/* Table */
.modern-table thead th{background:#f7f9fc; border-bottom:1px solid var(--line); text-transform:uppercase; font-size:.78rem; letter-spacing:.6px; color:#667085}
.table-image,.table-no-image{width:58px;height:58px;border-radius:10px;object-fit:cover}
.table-no-image{display:flex;align-items:center;justify-content:center;background:#f0f3f8;color:#c7cfdb}

/* Animasi sederhana */
@keyframes fadeInUp{from{opacity:0; transform:translateY(8px)} to{opacity:1; transform:translateY(0)}}
.product-card, .product-card-mini, .rank-row, .top5-item{animation:fadeInUp .35s ease both}
</style>
@endsection

@section('js')
<script>
// Dashboard: filter sudah dipindahkan ke halaman hasil-akhir via GET.
// Tidak ada in-page filtering di dashboard untuk mencegah loading error.
(function(){
  const chartData   = @json($chartSeries ?? []);
  const chartLabels = @json($chartLabels ?? []);
  const el = document.querySelector("#chart_peringkat");
  if(Array.isArray(chartData) && chartData.length && window.ApexCharts){
    const options = {
      series:[{ name:'Nilai Total', data:chartData }],
      chart:{ type:'bar', height:350, toolbar:{ show:true }},
      plotOptions:{ bar:{ borderRadius:8, columnWidth:'60%', dataLabels:{ position:'top' } } },
      dataLabels:{ enabled:true, formatter:(v)=>Number(v).toFixed(3), offsetY:-18, style:{ fontSize:'10px', colors:["#304758"] }},
      xaxis:{ categories:chartLabels, labels:{ rotate:-45, style:{ fontSize:'11px' }}},
      yaxis:{ title:{ text:'Nilai Total' }},
      grid:{ borderColor:'#e3e6f0' }
    };
    new ApexCharts(el, options).render();
  } else if (el){
    el.innerHTML =
      '<div class="text-center py-5"><i class="bi bi-bar-chart" style="font-size:3rem; color:#cfd6e3;"></i><h5 class="mt-3">Belum ada data untuk grafik</h5><p class="text-muted mb-0">Data akan muncul setelah perhitungan ROC + SMART</p></div>';
  }
})();
</script>
@endsection