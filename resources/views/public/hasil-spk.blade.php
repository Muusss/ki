<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Menu - Buri Umah Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/buri-umah.jpeg') }}" />
    <link rel="icon" type="image/jpeg" href="{{ asset('img/buri-umah.jpeg') }}" /> 
    <style>
        :root {
            --primary: #8B4513;
            --secondary: #D2691E;
            --accent: #FFD700;
            --dark: #2c1810;
            --line: #e8ecf2;
            --shadow-sm: 0 4px 10px rgba(17,24,39,.08);
            --shadow-md: 0 8px 24px rgba(17,24,39,.12);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 60px 0 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .brand-title {
            font-family: 'Dancing Script', cursive;
            font-size: 3.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 30px;
        }
        
        .btn-filter {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
            color: white;
        }
        
        /* Top 3 Cards dengan Gambar */
        .top-card {
            background: white;
            border-radius: 15px;
            padding: 0;
            margin-bottom: 20px;
            border: 2px solid transparent;
            transition: all 0.3s;
            overflow: hidden;
            height: 100%;
        }
        
        .top-card.gold {
            border-color: var(--accent);
            background: linear-gradient(white, #fffef5);
        }
        
        .top-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .top-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f6f8fb;
        }
        
        .top-card-no-image {
            width: 100%;
            height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f6f8fb;
            color: #cdd5df;
        }
        
        .top-card-content {
            padding: 20px;
        }
        
        .rank-badge {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .rank-1 { 
            background: linear-gradient(135deg, var(--accent), #FFA500); 
            color: var(--dark);
            box-shadow: 0 3px 10px rgba(255, 215, 0, 0.4);
        }
        .rank-2 { 
            background: linear-gradient(135deg, #C0C0C0, #808080); 
            color: white;
        }
        .rank-3 { 
            background: linear-gradient(135deg, #CD7F32, #8B4513); 
            color: white;
        }
        .rank-other {
            background: #f0f0f0;
            color: #666;
        }
        
        /* Table dengan Gambar */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow-sm);
        }
        
        .menu-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--line);
        }
        
        .menu-no-image {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f6f8fb;
            border-radius: 10px;
            border: 1px solid var(--line);
            color: #cdd5df;
        }
        
        .badge-jenis {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            padding-left: 40px;
            border-radius: 25px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
        }
        
        .no-data i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        /* Rank ribbon untuk top 3 cards */
        .rank-ribbon {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            color: #fff;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-size: .95rem;
        }
        
        .rank-ribbon.gold {
            background: linear-gradient(135deg, #facc15, #f59e0b);
        }
        
        .rank-ribbon.silver {
            background: linear-gradient(135deg, #d1d5db, #9ca3af);
        }
        
        .rank-ribbon.bronze {
            background: linear-gradient(135deg, #f59e0b, #b45309);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="brand-title mb-3">Buri Umah</h1>
            <p class="lead mb-0">Sistem Rekomendasi Menu Terbaik</p>
            <p class="opacity-75">Menggunakan Metode ROC + SMART</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="container" style="margin-top: -30px;">
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-cup-hot-fill text-warning" style="font-size: 2rem;"></i>
                    <div class="stats-number">{{ $stats['total_menu'] ?? 0 }}</div>
                    <div class="text-muted">Total Menu</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                    <div class="stats-number">{{ $stats['total_evaluated'] ?? 0 }}</div>
                    <div class="text-muted">Menu Dievaluasi</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-trophy-fill" style="font-size: 2rem; color: var(--accent);"></i>
                    <div class="stats-number">{{ $stats['best_score'] ?? 0 }}</div>
                    <div class="text-muted">Skor Tertinggi</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card text-center">
                    <i class="bi bi-bar-chart-fill text-info" style="font-size: 2rem;"></i>
                    <div class="stats-number">{{ $stats['average_score'] ?? 0 }}</div>
                    <div class="text-muted">Skor Rata-rata</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-card">
            <h5 class="mb-3"><i class="bi bi-funnel"></i> Filter Menu</h5>
            <form method="GET" action="{{ route('hasil-spk') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Jenis Menu</label>
                    <select name="jenis_menu" class="form-select">
                        <option value="all">Semua Jenis</option>
                        @foreach($jenisMenuList ?? [] as $key => $label)
                            <option value="{{ $key }}" {{ request('jenis_menu') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Kategori Harga</label>
                    <select name="harga" class="form-select">
                        <option value="all">Semua Harga</option>
                        @foreach($hargaList ?? [] as $key => $label)
                            <option value="{{ $key }}" {{ request('harga') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Cari Menu</label>
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama atau kode menu..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Top 3 Recommendations dengan Gambar -->
        @if(isset($topRecommendations) && $topRecommendations->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3"><i class="bi bi-star-fill text-warning"></i> Top 3 Rekomendasi</h4>
            </div>
            @foreach($topRecommendations as $top)
                @php 
                    $alt = $top->alternatif;
                    $rank = $loop->iteration;
                @endphp
                <div class="col-md-4 mb-3">
                    <div class="top-card {{ $rank == 1 ? 'gold' : '' }}">
                        <!-- Rank Ribbon -->
                        <div class="rank-ribbon {{ $rank == 1 ? 'gold' : ($rank == 2 ? 'silver' : 'bronze') }}">
                            <i class="bi bi-trophy-fill"></i> #{{ $rank }}
                        </div>
                        
                        <!-- Image Section -->
                        @if($alt->gambar && file_exists(public_path('img/menu/'.$alt->gambar)))
                            <img src="{{ asset('img/menu/'.$alt->gambar) }}" 
                                 alt="{{ $alt->nama_menu }}" 
                                 class="top-card-image">
                        @else
                            <div class="top-card-no-image">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                                <small>No Image</small>
                            </div>
                        @endif
                        
                        <!-- Content Section -->
                        <div class="top-card-content">
                            <h5 class="mb-2">{{ $alt->nama_menu }}</h5>
                            <p class="text-muted small mb-2">{{ $alt->kode_menu }}</p>
                            
                            <div class="d-flex gap-2 flex-wrap mb-3">
                                @php
                                    $jenisColor = match($alt->jenis_menu ?? '') {
                                        'makanan' => 'success',
                                        'cemilan' => 'warning',
                                        'coffee' => 'dark',
                                        'milkshake' => 'info',
                                        'mojito' => 'danger',
                                        'yakult' => 'primary',
                                        'tea' => 'secondary',
                                        default => 'light'
                                    };
                                @endphp
                                <span class="badge bg-{{ $jenisColor }} badge-jenis">
                                    <i class="bi bi-tag"></i> {{ ucfirst($alt->jenis_menu ?? '-') }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-cash"></i> {{ $alt->harga_label ?? $alt->harga }}
                                </span>
                            </div>
                            
                            <div class="text-center">
                                <h4 class="text-primary mb-0">{{ number_format($top->total ?? 0, 4) }}</h4>
                                <small class="text-muted">Nilai Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Results Table dengan Gambar -->
        <div class="table-container">
            <h5 class="mb-3">
                <i class="bi bi-list-ol"></i> Ranking Lengkap Menu
                @if(request('jenis_menu') && request('jenis_menu') != 'all')
                    <span class="badge bg-primary">{{ $jenisMenuList[request('jenis_menu')] ?? request('jenis_menu') }}</span>
                @endif
            </h5>
            
            @if(isset($error))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i> {{ $error }}
                </div>
            @elseif($nilaiAkhir->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th width="80">Rank</th>
                                <th width="80">Gambar</th>
                                <th width="100">Kode</th>
                                <th>Nama Menu</th>
                                <th>Jenis</th>
                                <th>Harga</th>
                                <th>Nilai</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilaiAkhir as $item)
                                @php
                                    $alt = $item->alternatif;
                                    $rank = $item->peringkat_filter ?? $loop->iteration;
                                    $jenisColor = match($alt->jenis_menu ?? '') {
                                        'makanan' => 'success',
                                        'cemilan' => 'warning',
                                        'coffee' => 'dark',
                                        'milkshake' => 'info',
                                        'mojito' => 'danger',
                                        'yakult' => 'primary',
                                        'tea' => 'secondary',
                                        default => 'light'
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <span class="rank-badge {{ $rank <= 3 ? 'rank-'.$rank : 'rank-other' }}">
                                            {{ $rank }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($alt->gambar && file_exists(public_path('img/menu/'.$alt->gambar)))
                                            <img src="{{ asset('img/menu/'.$alt->gambar) }}" 
                                                 alt="{{ $alt->nama_menu }}" 
                                                 class="menu-image">
                                        @else
                                            <div class="menu-no-image">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $alt->kode_menu ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $alt->nama_menu ?? '-' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $jenisColor }} badge-jenis">
                                            {{ ucfirst($alt->jenis_menu ?? '-') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-success fw-semibold">
                                            {{ $alt->harga_label ?? $alt->harga ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ number_format($item->total ?? 0, 4) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($rank == 1)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-trophy-fill"></i> Terbaik
                                            </span>
                                        @elseif($rank <= 3)
                                            <span class="badge bg-info">
                                                <i class="bi bi-star"></i> Rekomendasi
                                            </span>
                                        @elseif($rank <= 10)
                                            <span class="badge bg-light text-dark">Alternatif Baik</span>
                                        @else
                                            <span class="badge bg-light text-muted">Alternatif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="no-data">
                    <i class="bi bi-inbox"></i>
                    <h5>Tidak ada data hasil perhitungan</h5>
                    <p class="text-muted">Silakan tunggu admin melakukan perhitungan atau ubah filter pencarian</p>
                </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4 mb-5">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            {{-- @if($nilaiAkhir->count() > 0)
                <a href="{{ route('pdf.hasilAkhir') }}?jenis_menu={{ request('jenis_menu') }}&harga={{ request('harga') }}" 
                   class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-pdf"></i> Download PDF
                </a>
            @endif --}}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>