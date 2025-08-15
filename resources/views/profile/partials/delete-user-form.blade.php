<section>
  <div class="alert alert-danger d-flex align-items-start" role="alert" style="gap:.75rem">
    <i class="bi bi-shield-exclamation fs-4"></i>
    <div>
      <div class="fw-semibold">Hapus Akun</div>
      <small class="text-muted">
        Setelah akun dihapus, semua data terkait juga akan dihapus secara permanen. Pastikan
        untuk mengunduh data yang ingin kamu simpan.
      </small>
    </div>
  </div>

  <!-- Trigger -->
  <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
    <i class="bi bi-trash3 me-1"></i> Hapus Akun
  </button>

  <!-- Modal -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
        @csrf
        @method('delete')

        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Yakin ingin menghapus akun?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <p class="mb-3">
            Setelah akun dihapus, semua data akan hilang secara permanen. Masukkan kata sandi untuk konfirmasi.
          </p>

          <label for="password" class="form-label fw-semibold">Kata Sandi</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Password">
          @if($errors->userDeletion?->has('password'))
            <div class="text-danger small mt-1">{{ $errors->userDeletion->first('password') }}</div>
          @endif
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash3 me-1"></i> Hapus Akun
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
