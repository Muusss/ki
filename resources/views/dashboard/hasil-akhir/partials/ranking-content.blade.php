@php
    $top3 = $data->take(3);
    $remaining = $data->skip(3);
@endphp

<!-- Top 3 Winners dengan Gambar -->
@if($top3->count() > 0)
<div class="row mb-4">
    @foreach($top3 as $index => $produk)
    <div class="col-md-4 mb-3">
        <div class="card product-rank-card {{ $index == 0 ? 'border-warning border-3' : ($index == 1 ? 'border-secondary border-2' : 'border-danger border-2') }}">
            <!-- Rank Badge -->
            <div class="rank-badge rank-{{ $index + 1 }}">
                @if($index == 0)
                    <i class="bi bi-trophy-fill"></i>
                @else
                    {{ $index + 1 }}
                @endif
            </div>
            
            <!-- Additional Info Badges -->
            <div class="position-absolute top-0 end-0 m-2">
                @php
                    // Ambil info harga dan SPF dari penilaian yang sudah di-eager load
                    $hargaInfo = '';
                    $spfInfo = '';
                    
                    if ($produk->alternatif && $produk->alternatif->penilaians) {
                        foreach ($produk->alternatif->penilaians as $penilaian) {
                            if ($penilaian->kriteria && $penilaian->kriteria->kode == 'C3' && $penilaian->subKriteria) {
                                $hargaInfo = $penilaian->subKriteria->label;
                            }
                            if ($penilaian->kriteria && $penilaian->kriteria->kode == 'C2' && $penilaian->subKriteria) {
                                $spfInfo = $penilaian->subKriteria->label;
                            }
                        }
                    }
                @endphp
                
                @if($hargaInfo)
                    <span class="badge bg-info mb-1 d-block">
                        <i class="bi bi-currency-dollar"></i> {{ $hargaInfo }}
                    </span>
                @endif
                @if($spfInfo)
                    <span class="badge bg-warning d-block">
                        <i class="bi bi-sun"></i> SPF {{ $spfInfo }}
                    </span>
                @endif
            </div>
            
            <!-- Product Image -->
            <div class="product-image-container">
                @if($produk->alternatif->gambar && file_exists(public_path('img/produk/'.$produk->alternatif->gambar)))
                    <img src="{{ asset('img/produk/'.$produk->alternatif->gambar) }}" 
                         alt="{{ $produk->alternatif->nama_produk ?? '-' }}"
                         class="img-fluid">
                @else
                    <div class="no-image">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                        <small>No Image</small>
                    </div>
                @endif
            </div>
            
            <!-- Product Info -->
            <div class="card-body text-center">
                <h5 class="card-title mb-1">{{ $produk->alternatif->nama_produk ?? '-' }}</h5>
                <p class="text-muted small mb-2">{{ $produk->alternatif->kode_produk ?? '-' }}</p>
                
                <!-- Jenis Kulit Badge -->
                @php
                    $jenis = $produk->alternatif->jenis_kulit ?? '';
                    $skinColor = match($jenis) {
                        'normal' => 'success',
                        'berminyak' => 'warning', 
                        'kering' => 'info',
                        'kombinasi' => 'secondary',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge bg-{{ $skinColor }} mb-2">
                    <i class="bi bi-droplet-fill"></i> {{ ucfirst($jenis) }}
                </span>
                
                <!-- Score Display -->
                <div class="mb-3">
                    <h3 class="text-primary mb-0">{{ number_format($produk->total ?? 0, 4) }}</h3>
                    <small class="text-muted">Nilai Total</small>
                </div>
                
                <!-- Score Bar Visualization -->
                <div class="score-bar">
                    <div class="score-fill" data-score="{{ ($produk->total ?? 0) * 100 }}" style="width: 0%;"></div>
                </div>
                
                <!-- Status Badge -->
                @if($index == 0)
                    <span class="badge bg-success">
                        <i class="bi bi-star-fill"></i> Produk Terbaik 
                        @if($jenisKulit != 'all')
                            Kulit {{ ucfirst($jenisKulit) }}
                        @endif
                    </span>
                @elseif($index == 1)
                    <span class="badge bg-info">Peringkat 2</span>
                @else
                    <span class="badge bg-warning">Peringkat 3</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Tabel Lengkap Perankingan -->
@if($data->count() > 0)
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-table"></i> {{ $title }}
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <!-- Dalam bagian tabel -->
            <table class="table table-striped table-hover ranking-table">
                <thead>
                    <tr>
                        <th width="80" class="text-center">Peringkat</th>
                        <th width="100">Gambar</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Jenis Kulit</th>
                        <th class="text-center">Harga</th> {{-- Tambah --}}
                        <th class="text-center">SPF</th>   {{-- Tambah --}}
                        <th class="text-center">Total Nilai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    <tr class="{{ $rank <= 3 ? 'table-success' : '' }}">
                        <!-- Existing columns... -->
                        
                        {{-- Kolom Harga --}}
                        <td class="text-center">
                            @if($row->alternatif->harga)
                                <span class="badge bg-success">
                                    Rp {{ number_format($row->alternatif->harga, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        {{-- Kolom SPF --}}
                        <td class="text-center">
                            @if($row->alternatif->spf)
                                <span class="badge bg-warning text-dark">
                                    SPF {{ $row->alternatif->spf }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        <!-- Rest of columns... -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Tidak ada produk untuk 
    @if($jenisKulit != 'all')
        jenis kulit {{ ucfirst($jenisKulit) }}
    @else
        filter ini
    @endif
</div>
@endif