<section>
  <form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
  </form>

  <form method="post" action="{{ route('profile.update') }}" class="row g-3">
    @csrf
    @method('patch')

    <div class="col-12">
      <label for="name" class="form-label fw-semibold">Nama</label>
      <input type="text" id="name" name="name" class="form-control"
             value="{{ old('name', $user->name) }}" required autocomplete="name">
      @error('name')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div class="col-12">
      <label for="email" class="form-label fw-semibold">Email</label>
      <input type="email" id="email" name="email" class="form-control"
             value="{{ old('email', $user->email) }}" required autocomplete="username">
      @error('email')
        <div class="text-danger small mt-1">{{ $message }}</div>
      @enderror

      @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center" role="alert"
             style="gap:.5rem">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <div class="flex-fill">
            Email kamu belum terverifikasi.
            <button form="send-verification" class="btn btn-sm btn-outline-primary ms-2">
              Kirim ulang tautan verifikasi
            </button>
          </div>
        </div>

        @if (session('status') === 'verification-link-sent')
          <div class="text-success small mt-2">
            Tautan verifikasi baru telah dikirim ke email kamu.
          </div>
        @endif
      @endif
    </div>

    <div class="col-12 d-flex align-items-center gap-2">
      <button class="btn btn-primary">
        <i class="bi bi-check2-circle me-1"></i> Simpan
      </button>

      @if (session('status') === 'profile-updated')
        <span class="text-muted small" id="savedProfileText">Tersimpan.</span>
        <script>
          setTimeout(()=>{document.getElementById('savedProfileText')?.remove()},2000);
        </script>
      @endif
    </div>
  </form>
</section>
