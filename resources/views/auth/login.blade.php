<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Masuk | SMART ReGlow</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo-ss.jpg') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-100">
  <div class="min-h-full grid lg:grid-cols-2">
    {{-- Kiri: Panel Informasi --}}
    <section class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-pink-600 to-violet-700 p-12 text-white">
      <div class="flex items-center gap-3">
        <img src="{{ asset('img/logo-ss.jpg') }}" class="h-12 w-12 rounded-lg object-cover" alt="logo-ss">
        <div>
          <div class="text-lg font-bold">SMART ReGlow</div>
          <p class="text-sm text-white/80">Beuaty Inside and Outside</p>
        </div>
      </div>

      <div class="max-w-md">
        <h1 class="text-4xl font-bold leading-tight">Rekomendasi Sunscreen Metode <span class="underline decoration-white/40 decoration-4">SMART + ROC</span></h1>
        {{-- <p class="mt-4 text-white/90">
          Aplikasi ini digunakan oleh <b>Admin</b> dan <b>Wali Kelas</b>.
          Wali kelas otomatis hanya dapat melihat dan mengelola siswa di kelasnya (6A–6D).
        </p> --}}
        {{-- <ul class="mt-8 space-y-3 text-white/90">
          <li class="flex items-start gap-3">
            <svg class="h-6 w-6 flex-shrink-0 text-white/80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Manajemen data siswa: NIS, nama, jenis kelamin, dan kelas.</span>
          </li>
          <li class="flex items-start gap-3">
            <svg class="h-6 w-6 flex-shrink-0 text-white/80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Pengelolaan kriteria dan subkriteria untuk penilaian.</span>
          </li>
          <li class="flex items-start gap-3">
            <svg class="h-6 w-6 flex-shrink-0 text-white/80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Perhitungan otomatis bobot (ROC) & nilai utility (SMART).</span>
          </li>
        </ul> --}}
      </div>

      <p class="text-sm text-white/60">© {{ date('Y') }} - Cirebon</p>
    </section>

    {{-- Kanan: Form Login --}}
    <section class="flex flex-col items-center justify-center p-6 lg:p-8">
      <div class="w-full max-w-md">
        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold text-slate-800">Selamat Datang</h2>
          <p class="text-slate-500 mt-1">Silakan masuk untuk melanjutkan.</p>
        </div>

        {{-- Card --}}
        <div class="bg-white shadow-xl rounded-2xl p-8">
          {{-- Notifikasi error validasi --}}
          @if ($errors->any())
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-800">
              <div class="font-medium mb-1">Terjadi kesalahan</div>
              <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }" class="space-y-5">
            @csrf
            <div>
              <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
              <input type="email" id="email" name="email" required autofocus
                     class="mt-1 block w-full rounded-lg border-slate-300 text-black shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500"
                     placeholder="" value="{{ old('email') }}">
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
              <div class="mt-1 relative">
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                       class="block w-full rounded-lg border-slate-300 text-black pr-12 shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="">
                <button type="button" class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 text-sm hover:text-slate-700"
                        @click="showPassword = !showPassword" x-text="showPassword ? 'Sembunyi' : 'Lihat'"></button>
              </div>
            </div>

            <div class="flex items-center justify-between pt-1">
              <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                Ingat saya
              </label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-purple-600 hover:text-indigo-800 hover:underline">Lupa kata sandi?</a>
              @endif
            </div>

            <div class="pt-2">
              <button type="submit"
                      class="w-full rounded-lg bg-pink-600 py-3 text-white font-semibold shadow-sm transition-colors duration-200 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Masuk
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>

  <script>
    function fillDemo(email) {
      const emailInput = document.querySelector('input[name="email"]');
      const passwordInput = document.querySelector('input[name="password"]');
      if (emailInput && passwordInput) {
        emailInput.value = email;
        passwordInput.value = 'password'; // samakan dengan seeder Anda
        emailInput.focus();
      }
    }
  </script>
</body>
</html>