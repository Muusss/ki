<section>
  <form method="post" action="{{ route('password.update') }}" class="row g-3">
    @csrf
    @method('put')

    <div class="col-12">
      <label for="update_password_current_password" class="form-label fw-semibold">Kata Sandi Saat Ini</label>
      <input type="password" id="update_password_current_password" name="current_password"
             class="form-control" autocomplete="current-password">
      @if($errors->updatePassword?->has('current_password'))
        <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
      @endif
    </div>

    <div class="col-12 col-md-6">
      <label for="update_password_password" class="form-label fw-semibold">Kata Sandi Baru</label>
      <input type="password" id="update_password_password" name="password"
             class="form-control" autocomplete="new-password">
      @if($errors->updatePassword?->has('password'))
        <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
      @endif
    </div>

    <div class="col-12 col-md-6">
      <label for="update_password_password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
      <input type="password" id="update_password_password_confirmation" name="password_confirmation"
             class="form-control" autocomplete="new-password">
      @if($errors->updatePassword?->has('password_confirmation'))
        <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>
      @endif
    </div>

    <div class="col-12 d-flex align-items-center gap-2">
      <button class="btn btn-primary">
        <i class="bi bi-check2-circle me-1"></i> Simpan
      </button>

      @if (session('status') === 'password-updated')
        <span class="text-muted small" id="savedPwdText">Tersimpan.</span>
        <script>
          setTimeout(()=>{document.getElementById('savedPwdText')?.remove()},2000);
        </script>
      @endif
    </div>
  </form>
</section>
