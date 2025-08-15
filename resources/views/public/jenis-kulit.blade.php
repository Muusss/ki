@extends('layouts.public')

@section('title', 'Informasi Jenis Kulit')

@section('content')
<div class="min-h-screen pt-20 pb-12 px-6">
    <div class="container mx-auto">
        <!-- Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="gradient-text">Kenali Jenis Kulitmu</span>
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Pahami karakteristik setiap jenis kulit dan temukan produk sunscreen yang tepat berdasarkan rekomendasi para ahli dermatologi
            </p>
        </div>

        <!-- Skin Types Grid -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            @foreach($jenisKulit as $key => $jenis)
            <div class="glass rounded-3xl overflow-hidden hover:shadow-2xl transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <!-- Image -->
                <div class="h-64 overflow-hidden relative">
                    <img src="{{ $jenis['image'] }}" alt="{{ $jenis['title'] }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <h2 class="absolute bottom-4 left-6 text-3xl font-bold text-white">{{ $jenis['title'] }}</h2>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Characteristics -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-pink-500 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ciri-ciri
                        </h3>
                        <ul class="space-y-2">
                            @foreach($jenis['ciri'] as $ciri)
                            <li class="flex items-start">
                                <span class="text-pink-400 mr-2">•</span>
                                <span class="text-gray-600">{{ $ciri }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Recommendations -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-purple-500 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Rekomendasi Sunscreen
                        </h3>
                        <ul class="space-y-2">
                            @foreach($jenis['rekomendasi'] as $rekomendasi)
                            <li class="flex items-start">
                                <span class="text-purple-400 mr-2">✓</span>
                                <span class="text-gray-600">{{ $rekomendasi }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Product Types -->
                    <div class="bg-gradient-to-r from-pink-50 to-purple-50 rounded-2xl p-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Tekstur yang Cocok:</h4>
                        <p class="text-gray-600">{{ $jenis['produk_cocok'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Expert Tips Section -->
        <div class="glass rounded-3xl p-8 mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-center mb-8">
                <span class="gradient-text">Tips dari Ahli Dermatologi</span>
            </h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="bg-gradient-to-br from-pink-400 to-pink-500 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">☀️</span>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Aplikasi Ulang</h3>
                    <p class="text-gray-600 text-sm">Aplikasikan ulang sunscreen setiap 2-3 jam untuk perlindungan maksimal</p>
                </div>

                <div class="text-center">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-500 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">💧</span>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Jumlah Tepat</h3>
                    <p class="text-gray-600 text-sm">Gunakan 2 ruas jari sunscreen untuk wajah dan leher</p>
                </div>

                <div class="text-center">
                    <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⏰</span>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Waktu Tunggu</h3>
                    <p class="text-gray-600 text-sm">Aplikasikan 15-30 menit sebelum keluar rumah</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center" data-aos="zoom-in">
            <h3 class="text-2xl font-bold mb-4">Sudah tahu jenis kulitmu?</h3>
            <p class="text-gray-600 mb-6">Lihat rekomendasi sunscreen terbaik untuk jenis kulitmu</p>
            <a href="{{ route('public.hasil-spk') }}" class="bg-gradient-to-r from-pink-500 to-pink-400 text-white px-8 py-4 rounded-full hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-semibold inline-flex items-center">
                Lihat Rekomendasi
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection