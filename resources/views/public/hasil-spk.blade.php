@extends('layouts.public')

@section('title', 'Hasil Rekomendasi SPK')

@section('content')
<div class="min-h-screen pt-20 pb-12 px-6">
    <div class="container mx-auto max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="gradient-text">Hasil Rekomendasi Sunscreen</span>
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Rekomendasi produk sunscreen terbaik berdasarkan metode ROC & SMART yang telah dianalisis secara ilmiah
            </p>
        </div>

        <!-- Filter Section -->
        <div class="glass rounded-3xl p-6 mb-8" data-aos="fade-up">
            <form method="GET" action="{{ route('public.hasil-spk') }}" class="grid md:grid-cols-4 gap-4">
                <!-- Jenis Kulit Filter -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2 text-sm">Jenis Kulit</label>
                    <select name="jenis_kulit" class="w-full px-4 py-3 rounded-2xl border border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all">
                        <option value="all">Semua Jenis</option>
                        @foreach($jenisKulitList as $jenis)
                        <option value="{{ $jenis }}" {{ $jenisKulit == $jenis ? 'selected' : '' }}>
                            {{ ucfirst($jenis) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga Filter -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2 text-sm">Range Harga</label>
                    <select name="harga" class="w-full px-4 py-3 rounded-2xl border border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all">
                        <option value="all">Semua Harga</option>
                        <option value="<=40000" {{ $filterHarga == '<=40000' ? 'selected' : '' }}>≤ Rp 40.000</option>
                        <option value="40001-60000" {{ $filterHarga == '40001-60000' ? 'selected' : '' }}>Rp 40.001 - 60.000</option>
                        <option value="60001-80000" {{ $filterHarga == '60001-80000' ? 'selected' : '' }}>Rp 60.001 - 80.000</option>
                        <option value=">80000" {{ $filterHarga == '>80000' ? 'selected' : '' }}>> Rp 80.000</option>
                    </select>
                </div>

                <!-- SPF Filter -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2 text-sm">SPF</label>
                    <select name="spf" class="w-full px-4 py-3 rounded-2xl border border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all">
                        <option value="all">Semua SPF</option>
                        <option value="30" {{ $filterSpf == '30' ? 'selected' : '' }}>SPF 30</option>
                        <option value="35" {{ $filterSpf == '35' ? 'selected' : '' }}>SPF 35</option>
                        <option value="40" {{ $filterSpf == '40' ? 'selected' : '' }}>SPF 40</option>
                        <option value="50" {{ $filterSpf == '50' ? 'selected' : '' }}>SPF 50</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2 items-end">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-pink-400 text-white py-3 rounded-2xl hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold">
                        Filter
                    </button>
                    <a href="{{ route('public.hasil-spk') }}" class="px-4 py-3 bg-gray-200 text-gray-700 rounded-2xl hover:bg-gray-300 transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        @if($nilaiAkhir->count() > 0)
        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">
                Menampilkan <span class="font-semibold text-pink-500">{{ $nilaiAkhir->count() }}</span> produk
            </p>
            
            <!-- Download PDF Button -->
            @php
                $pdfParams = [
                    'jenis_kulit' => $jenisKulit ?? 'all',
                    'harga' => $filterHarga ?? 'all',
                    'spf' => $filterSpf ?? 'all'
                ];
                $pdfUrl = route('pdf.hasilAkhir') . '?' . http_build_query(array_filter($pdfParams, fn($v) => $v !== 'all'));
            @endphp
            <a href="{{ $pdfUrl }}" target="_blank" class="bg-red-500 text-white px-6 py-2 rounded-full hover:bg-red-600 transition-all flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
        </div>

        <!-- Top 3 Winners -->
        @if($nilaiAkhir->count() >= 3)
        <div class="grid md:grid-cols-3 gap-6 mb-12">
            @foreach($nilaiAkhir->take(3) as $index => $item)
            @php
                $rank = $index + 1;
                $alt = $item->alternatif ?? null;
                $medals = ['🥇', '🥈', '🥉'];
                $borderColors = ['border-yellow-400', 'border-gray-400', 'border-orange-400'];
                $bgGradients = [
                    'from-yellow-50 to-yellow-100',
                    'from-gray-50 to-gray-100',
                    'from-orange-50 to-orange-100'
                ];
            @endphp
            <div class="glass rounded-3xl overflow-hidden border-2 {{ $borderColors[$index] }} hover:shadow-2xl transition-all duration-300" data-aos="zoom-in" data-aos-delay="{{ ($index + 1) * 100 }}">
                <!-- Rank Badge -->
                <div class="bg-gradient-to-r {{ $bgGradients[$index] }} p-4 text-center">
                    <span class="text-4xl">{{ $medals[$index] }}</span>
                    <h3 class="text-xl font-bold mt-2">Peringkat {{ $rank }}</h3>
                </div>

                <!-- Product Image -->
                @if($alt && $alt->gambar)
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('img/produk/' . $alt->gambar) }}" alt="{{ $alt->nama_produk }}" class="w-full h-full object-cover">
                </div>
                @else
                <div class="h-48 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                @endif

                <!-- Product Info -->
                <div class="p-6">
                    <h4 class="text-xl font-bold mb-2">{{ $alt->nama_produk ?? '-' }}</h4>
                    <p class="text-gray-600 text-sm mb-4">{{ $alt->kode_produk ?? '-' }}</p>

                    <!-- Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jenis Kulit:</span>
                            <span class="font-semibold">{{ ucfirst($alt->jenis_kulit ?? '-') }}</span>
                        </div>
                        @if($alt && $alt->harga)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Harga:</span>
                            <span class="font-semibold text-green-600">Rp {{ number_format($alt->harga, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($alt && $alt->spf)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">SPF:</span>
                            <span class="font-semibold">SPF {{ $alt->spf }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Score -->
                    <div class="bg-gradient-to-r from-pink-500 to-purple-500 text-white rounded-2xl p-3 text-center">
                        <p class="text-sm">Total Nilai</p>
                        <p class="text-2xl font-bold">{{ number_format($item->total ?? 0, 4) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Complete Results Table -->
        <div class="glass rounded-3xl p-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-6">Tabel Perankingan Lengkap</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-pink-200">
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Rank</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Gambar</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Kode</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Nama Produk</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Jenis Kulit</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Harga</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">SPF</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nilaiAkhir as $index => $item)
                        @php
                            $rank = $index + 1;
                            $alt = $item->alternatif ?? null;
                            $rowClass = $rank <= 3 ? 'bg-gradient-to-r from-pink-50 to-purple-50' : '';
                        @endphp
                        <tr class="border-b border-pink-100 hover:bg-pink-50 transition-colors {{ $rowClass }}">
                            <td class="py-3 px-4 text-center">
                                @if($rank == 1)
                                    <span class="text-2xl">🥇</span>
                                @elseif($rank == 2)
                                    <span class="text-2xl">🥈</span>
                                @elseif($rank == 3)
                                    <span class="text-2xl">🥉</span>
                                @else
                                    <span class="font-bold text-gray-600">{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($alt && $alt->gambar)
                                    <img src="{{ asset('img/produk/' . $alt->gambar) }}" alt="{{ $alt->nama_produk }}" class="w-12 h-12 rounded-lg object-cover mx-auto">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                    {{ $alt->kode_produk ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $alt->nama_produk ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm">
                                    {{ ucfirst($alt->jenis_kulit ?? '-') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($alt && $alt->harga)
                                    <span class="text-green-600 font-semibold">
                                        Rp {{ number_format($alt->harga, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($alt && $alt->spf)
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                        SPF {{ $alt->spf }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-bold text-pink-500">
                                    {{ number_format($item->total ?? 0, 4) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="glass rounded-3xl p-12 text-center" data-aos="fade-up">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Hasil</h3>
            <p class="text-gray-500">Tidak ada produk yang sesuai dengan filter yang dipilih.</p>
            <a href="{{ route('public.hasil-spk') }}" class="inline-block mt-4 text-pink-500 hover:text-pink-600 font-semibold">
                Reset Filter
            </a>
        </div>
        @endif
    </div>
</div>
@endsection