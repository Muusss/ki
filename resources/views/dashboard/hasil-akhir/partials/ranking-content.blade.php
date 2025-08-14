{{-- resources/views/dashboard/hasil-akhir/partials/ranking-content.blade.php --}}
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
            
            <!-- Skin Type Badge -->
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
            <span class="badge bg-{{ $skinColor }} skin-badge">
                <i class="bi bi-droplet-fill"></i> {{ ucfirst($jenis) }}
            </span>
            
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
                        <i class="bi bi-star-fill"></i> Produk Terbaik {{ $jenisKulit == 'all' ? '' : 'Kulit ' . ucfirst($jenisKulit) }}
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
            <table class="table table-striped table-hover ranking-table">
                <thead>
                    <tr>
                        <th width="80" class="text-center">Peringkat</th>
                        <th width="100">Gambar</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Jenis Kulit</th>
                        <th class="text-center">Total Nilai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                    @php
                        $rank = $loop->iteration;
                        $jenis = $row->alternatif->jenis_kulit ?? '';
                        $skinColor = match($jenis) {
                            'normal' => 'success',
                            'berminyak' => 'warning',
                            'kering' => 'info', 
                            'kombinasi' => 'secondary',
                            default => 'secondary'
                        };
                    @endphp
                    <tr class="{{ $rank <= 3 ? 'table-success' : '' }}">
                        <td class="text-center">
                            @if($rank == 1)
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-trophy-fill"></i> 1
                                </span>
                            @elseif($rank == 2)
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-award-fill"></i> 2
                                </span>
                            @elseif($rank == 3)
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-award-fill"></i> 3
                                </span>
                            @else
                                <span class="badge bg-info">{{ $rank }}</span>
                            @endif
                        </td>
                        <td>
                            @if($row->alternatif->gambar && file_exists(public_path('img/produk/'.$row->alternatif->gambar)))
                                <img src="{{ asset('img/produk/'.$row->alternatif->gambar) }}" 
                                     alt="{{ $row->alternatif->nama_produk ?? '-' }}"
                                     class="img-thumbnail"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="text-center" style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $row->alternatif->kode_produk ?? '-' }}</td>
                        <td>
                            <strong>{{ $row->alternatif->nama_produk ?? '-' }}</strong>
                            @if($rank == 1)
                                <i class="bi bi-star-fill text-warning ms-2"></i>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $skinColor }}">
                                <i class="bi bi-droplet-fill"></i> {{ ucfirst($jenis) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary fs-6">
                                {{ number_format($row->total ?? 0, 4) }}
                            </span>
                        </td>
                        <td>
                            @if($rank == 1)
                                <span class="badge bg-success">Produk Terbaik</span>
                            @elseif($rank <= 3)
                                <span class="badge bg-info">Nominasi</span>
                            @elseif($rank <= 10)
                                <span class="badge bg-secondary">10 Besar</span>
                            @else
                                <span class="badge bg-light text-dark">Partisipan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Comparison View (Optional) -->
@if($data->count() > 3)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-grid-3x3-gap"></i> Perbandingan Visual Produk
        </h5>
    </div>
    <div class="card-body">
        <div class="comparison-grid">
            @foreach($remaining as $row)
            <div class="card h-100">
                <div class="position-relative">
                    <!-- Rank Badge -->
                    <span class="position-absolute top-0 start-0 m-2 badge bg-info">
                        #{{ $loop->iteration + 3 }}
                    </span>
                    
                    <!-- Product Image -->
                    <div style="height: 150px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        @if($row->alternatif->gambar && file_exists(public_path('img/produk/'.$row->alternatif->gambar)))
                            <img src="{{ asset('img/produk/'.$row->alternatif->gambar) }}" 
                                 alt="{{ $row->alternatif->nama_produk ?? '-' }}"
                                 class="img-fluid"
                                 style="max-height: 150px; object-fit: cover;">
                        @else
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="card-title text-truncate">{{ $row->alternatif->nama_produk ?? '-' }}</h6>
                    <p class="small text-muted mb-1">{{ $row->alternatif->kode_produk ?? '-' }}</p>
                    <p class="mb-0">
                        <strong>{{ number_format($row->total ?? 0, 4) }}</strong>
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@else
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Tidak ada produk untuk jenis kulit {{ ucfirst($jenisKulit) }}
</div>
@endif