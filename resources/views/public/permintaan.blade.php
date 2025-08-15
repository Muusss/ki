@extends('layouts.public')

@section('title', 'Permintaan Produk')

@section('content')
<div class="min-h-screen pt-20 pb-12 px-6">
    <div class="container mx-auto max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="gradient-text">Permintaan Produk Sunscreen</span>
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Tidak menemukan produk yang Anda cari? Ajukan permintaan produk sunscreen dan kami akan menambahkannya ke dalam sistem
            </p>
        </div>

        @if(session('success'))
        <div class="glass bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 flex items-center" data-aos="fade-down">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="glass rounded-3xl p-8 sticky top-24">
                    <h2 class="text-2xl font-bold mb-6 flex items-center">
                        <svg class="w-6 h-6 text-pink-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajukan Permintaan
                    </h2>

                    <form action="{{ route('public.permintaan.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Nama Produk</label>
                            <input type="text" name="nama_produk" required maxlength="100" 
                                   class="w-full px-4 py-3 rounded-2xl border border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all"
                                   placeholder="Contoh: Azarine Hydrasoothe">
                            @error('nama_produk')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Komposisi -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Komposisi/Ingredients</label>
                            <textarea name="komposisi" rows="3" required 
                                      class="w-full px-4 py-3 rounded-2xl border border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all"
                                      placeholder="Tuliskan kandungan utama produk"></textarea>
                            @error('komposisi')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Range Harga</label>
                            <select name="harga" required 
                                    class="w-full px-4 py-3 rounded-2xl border border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all">
                                <option value="">Pilih range harga</option>
                                <option value="<50k">< Rp 50.000</option>
                                <option value="50-100k">Rp 50.000 - 100.000</option>
                                <option value=">100k">> Rp 100.000</option>
                            </select>
                            @error('harga')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- SPF -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">SPF</label>
                            <select name="spf" required 
                                    class="w-full px-4 py-3 rounded-2xl border border-pink-200 focus:border-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-200 transition-all">
                                <option value="">Pilih SPF</option>
                                <option value="30">SPF 30</option>
                                <option value="35">SPF 35</option>
                                <option value="40">SPF 40</option>
                                <option value="50+">SPF 50+</option>
                            </select>
                            @error('spf')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-pink-400 text-white py-3 rounded-2xl hover:shadow-lg transform hover:scale-105 transition-all duration-300 font-semibold">
                            Kirim Permintaan
                        </button>
                    </form>

                    <!-- Info Box -->
                    <div class="mt-6 p-4 bg-pink-50 rounded-2xl">
                        <p class="text-sm text-gray-600">
                            <strong class="text-pink-500">Catatan:</strong> 
                            Permintaan Anda akan diverifikasi oleh admin sebelum produk ditambahkan ke sistem.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="glass rounded-3xl p-8">
                    <h2 class="text-2xl font-bold mb-6 flex items-center">
                        <svg class="w-6 h-6 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Daftar Permintaan Produk
                    </h2>

                    @if($permintaan->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-pink-200">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">#</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Nama Produk</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Komposisi</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">Harga</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">SPF</th>
                                    <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permintaan as $item)
                                <tr class="border-b border-pink-100 hover:bg-pink-50 transition-colors">
                                    <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4 font-medium">{{ $item->nama_produk }}</td>
                                    <td class="py-3 px-4">
                                        <div class="max-w-xs truncate text-sm text-gray-600">
                                            {{ $item->komposisi }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                            @if($item->harga == '<50k')
                                                < 50k
                                            @elseif($item->harga == '50-100k')
                                                50-100k
                                            @else
                                                > 100k
                                            @endif
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                            SPF {{ $item->spf }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if(isset($item->status) && $item->status == 'approved')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                                Disetujui
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm">
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500">Belum ada permintaan produk</p>
                        <p class="text-sm text-gray-400 mt-2">Jadilah yang pertama mengajukan permintaan!</p>
                    </div>
                    @endif
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="glass rounded-2xl p-4 text-center" data-aos="zoom-in" data-aos-delay="100">
                        <h3 class="text-2xl font-bold text-pink-500">{{ $permintaan->count() }}</h3>
                        <p class="text-sm text-gray-600">Total Permintaan</p>
                    </div>
                    <div class="glass rounded-2xl p-4 text-center" data-aos="zoom-in" data-aos-delay="200">
                        <h3 class="text-2xl font-bold text-green-500">
                            {{ $permintaan->where('status', 'approved')->count() ?? 0 }}
                        </h3>
                        <p class="text-sm text-gray-600">Disetujui</p>
                    </div>
                    <div class="glass rounded-2xl p-4 text-center" data-aos="zoom-in" data-aos-delay="300">
                        <h3 class="text-2xl font-bold text-orange-500">
                            {{ $permintaan->where('status', '!=', 'approved')->count() ?? $permintaan->count() }}
                        </h3>
                        <p class="text-sm text-gray-600">Menunggu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection