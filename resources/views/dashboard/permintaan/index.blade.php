{{-- resources/views/dashboard/permintaan/index.blade.php --}}
@extends('dashboard.layouts.main')

@section('content')
@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif



{{-- Filter Status --}}
<div class="card mb-3">
  <div class="card-body">
    <div class="row align-items-center">
      <div class="col-md-3">
        <label class="form-label">Filter Status:</label>
        <select id="filterStatus" class="form-select">
          <option value="">Semua Status</option>
          <option value="pending">Menunggu Verifikasi</option>
          <option value="approved">Disetujui</option>
          <option value="rejected">Ditolak</option>
        </select>
      </div>
      <div class="col-md-9">
        <div class="d-flex gap-2 justify-content-end">
          <span class="badge bg-warning text-dark">Pending: <span id="countPending">0</span></span>
          <span class="badge bg-success">Approved: <span id="countApproved">0</span></span>
          <span class="badge bg-danger">Rejected: <span id="countRejected">0</span></span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="tblPermintaan" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th width="5%">#</th>
            <th width="20%">Nama Produk</th>
            <th width="25%">Komposisi</th>
            <th width="10%">Harga</th>
            <th width="8%">SPF</th>
            <th width="12%">Status</th>
            <th width="15%">Catatan Admin</th>
            <th width="15%">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @php $permintaan = $permintaan ?? collect([]); @endphp
        @forelse ($permintaan as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $row->nama_produk }}</strong></td>
            <td class="text-break">
              <small>{{ Str::limit($row->komposisi, 100) }}</small>
              @if(strlen($row->komposisi) > 100)
                <a href="#" onclick="showDetail('{{ addslashes($row->komposisi) }}')" class="text-primary">
                  <small>[Lihat Selengkapnya]</small>
                </a>
              @endif
            </td>
            <td>
              @php
                $hargaLabel = $row->harga == '<50k' || $row->harga == '< 50k' ? '< 50k' :
                              ($row->harga == '50-100k' ? '50-100k' :
                              ($row->harga == '>100k' || $row->harga == '> 100k' ? '> 100k' : $row->harga));
              @endphp
              <span class="badge bg-info">{{ $hargaLabel }}</span>
            </td>
            <td>
              <span class="badge bg-warning text-dark">SPF {{ $row->spf }}</span>
            </td>
            <td>
              @if($row->status == 'pending')
                <span class="badge bg-warning text-dark">
                  <i class="bi bi-clock"></i> Menunggu
                </span>
              @elseif($row->status == 'approved')
                <span class="badge bg-success">
                  <i class="bi bi-check-circle"></i> Disetujui
                </span>
              @elseif($row->status == 'rejected')
                <span class="badge bg-danger">
                  <i class="bi bi-x-circle"></i> Ditolak
                </span>
              @endif
            </td>
            <td>
              <small class="text-muted">{{ $row->admin_notes ?? '-' }}</small>
            </td>
            <td>
              <div class="btn-group btn-group-sm" role="group">
                @if($row->status == 'pending')
                  {{-- APPROVE ⇒ buka form input produk --}}
                  <button type="button" class="btn btn-success"
                          onclick="openAddProduct({{ $row->id }})"
                          title="Setujui & Input Produk">
                    <i class="bi bi-check-lg"></i>
                  </button>
                  {{-- REJECT --}}
                  <button type="button" class="btn btn-danger"
                          onclick="quickReject({{ $row->id }})"
                          title="Tolak">
                    <i class="bi bi-x-lg"></i>
                  </button>
                @endif
                {{-- HAPUS --}}
                <button type="button" class="btn btn-danger"
                        onclick="deleteData({{ $row->id }})"
                        title="Hapus">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data permintaan.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Modal REJECT --}}
<div class="modal fade" id="modalStatus" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formStatus" method="POST">
        @csrf
        <input type="hidden" id="statusPermintaanId">
        <div class="modal-header">
          <h5 class="modal-title">Tolak Permintaan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea class="form-control" name="admin_notes" rows="3" required
                      placeholder="Tuliskan alasan penolakan"></textarea>
          </div>
          <div class="alert alert-warning">
            Permintaan akan <b>ditolak</b>.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Tolak</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal INPUT PRODUK saat Approve --}}
<div class="modal fade" id="modalAddProduct" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formAddProduct" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="permintaan_id" name="permintaan_id" />

        <div class="modal-header">
          <h5 class="modal-title">Setujui & Tambah ke Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Kode Produk</label>
            <input type="text" class="form-control" name="kode_produk" id="add_kode_produk" placeholder="(Opsional) PRD001">
            <small class="text-muted">Kosongkan untuk auto-generate.</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nama_produk" id="add_nama_produk" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Gambar Produk (opsional)</label>
            <input type="file" class="form-control" name="gambar" id="add_gambar" accept="image/*">
            <small class="text-muted d-block mt-1">Format: JPG/PNG/WebP, maks 2 MB.</small>
            <img id="preview_gambar" class="img-thumbnail mt-2 d-none" style="max-height: 160px;" alt="Preview">
          </div>

          <div class="mb-3">
            <label class="form-label">Jenis Kulit <span class="text-danger">*</span></label>
            <select class="form-select" name="jenis_kulit" id="add_jenis_kulit" required>
              <option value="">Pilih...</option>
              <option value="normal">Normal</option>
              <option value="berminyak">Berminyak</option>
              <option value="kering">Kering</option>
              <option value="kombinasi">Kombinasi</option>
            </select>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="harga" id="add_harga" min="0" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">SPF <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="spf" id="add_spf" min="15" max="100" required>
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label">Catatan Admin (opsional)</label>
            <input type="text" class="form-control" name="admin_notes" id="add_admin_notes" placeholder="Opsional">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Simpan & Setujui
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
  const table = $('#tblPermintaan').DataTable({
    responsive: true,
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" },
      emptyTable: "Tidak ada data"
    }
  });

  $('#filterStatus').on('change', function () {
    const map = { '': '', 'pending': 'Menunggu', 'approved': 'Disetujui', 'rejected': 'Ditolak' };
    table.column(5).search(map[this.value] ?? '').draw();
  });

  table.on('draw', updateStatusCount);
  updateStatusCount();

  // Preview gambar
  $(document).on('change', '#add_gambar', function(){
    const file = this.files?.[0];
    if (!file) { $('#preview_gambar').addClass('d-none').attr('src',''); return; }
    if (file.size > 2 * 1024 * 1024) {
      alert('Ukuran file melebihi 2MB.');
      this.value = '';
      return;
    }
    const reader = new FileReader();
    reader.onload = e => $('#preview_gambar').attr('src', e.target.result).removeClass('d-none');
    reader.readAsDataURL(file);
  });

  // Submit REJECT (pakai url('/permintaan') agar pasti match)
  $('#formStatus').on('submit', function(e){
    e.preventDefault();
    const id = $('#statusPermintaanId').val();
    const notes = $(this).find('[name="admin_notes"]').val();

    const rejectUrl = '{{ url("/permintaan") }}' + '/' + id + '/reject';

    $.ajax({
      url: rejectUrl,
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept':'application/json' },
      data: { admin_notes: notes },
      success: function(res){
        $('#modalStatus').modal('hide');
        alert(res.message || 'Permintaan ditolak.');
        location.reload();
      },
      error: function(xhr){
        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menolak permintaan.';
        alert(msg);
      }
    });
  });

  // Submit APPROVE + input produk (FormData multipart)
  $('#formAddProduct').on('submit', function(e){
    e.preventDefault();
    const id = $('#permintaan_id').val();

    const fd = new FormData(this);
    fd.append('_token', '{{ csrf_token() }}');

    const approveUrl = '{{ url("/permintaan") }}' + '/' + id + '/approve';

    $.ajax({
      url: approveUrl,
      method: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      headers: { 'Accept':'application/json' },
      success: function(res){
        $('#modalAddProduct').modal('hide');
        alert(res.message || 'Produk berhasil dibuat dan permintaan disetujui.');
        location.reload();
      },
      error: function(xhr){
        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyetujui & menambah produk.';
        alert(msg);
      }
    });
  });
});

function updateStatusCount() {
  let pending = 0, approved = 0, rejected = 0;
  $('#tblPermintaan tbody tr').each(function () {
    const statusText = $(this).find('td:eq(5) .badge').text().trim();
    if (statusText.includes('Menunggu')) pending++;
    else if (statusText.includes('Disetujui')) approved++;
    else if (statusText.includes('Ditolak')) rejected++;
  });
  $('#countPending').text(pending);
  $('#countApproved').text(approved);
  $('#countRejected').text(rejected);
}

function quickReject(id){
  $('#statusPermintaanId').val(id);
  $('#modalStatus').modal('show');
}

function openAddProduct(id){
  // Prefill dari data permintaan (GET edit -> JSON)
  $.get('{{ url("/permintaan") }}' + '/' + id + '/edit', function(data){
    $('#permintaan_id').val(id);
    $('#add_nama_produk').val(data.nama_produk || '');

    // Default harga dari rentang
    let harga = 0, h = (data.harga || '').toString();
    if (h.includes('<50k') || h.includes('< 50k')) harga = 40000;
    else if (h.includes('50-100k')) harga = 75000;
    else if (h.includes('>100k') || h.includes('> 100k')) harga = 120000;
    $('#add_harga').val(harga || '');

    const spf = (data.spf || '').toString().replace('+','');
    $('#add_spf').val(parseInt(spf) || '');

    // reset gambar & preview
    $('#add_gambar').val('');
    $('#preview_gambar').addClass('d-none').attr('src','');

    $('#add_kode_produk').val('');
    $('#add_jenis_kulit').val('');
    $('#add_admin_notes').val('');

    $('#modalAddProduct').modal('show');
  }).fail(function(xhr){
    alert('Gagal memuat data permintaan: ' + (xhr.status || '') + ' ' + (xhr.statusText || ''));
  });
}

function deleteData(id){
  if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;
  const form = $('<form>', { method: 'POST', action: '{{ url("/permintaan") }}' + '/' + id });
  form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
  form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
  $('body').append(form);
  form.trigger('submit');
}

function showDetail(text){ alert(text); }
</script>
@endsection

