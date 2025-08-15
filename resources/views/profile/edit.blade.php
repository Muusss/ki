@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid page-transition">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0" style="color:var(--text)">Profil</h1>
  </div>

  <div class="row g-3">
    <div class="col-12 col-lg-6">
      <div class="card">
        <div class="card-header">Informasi Profil</div>
        <div class="card-body">
          @include('profile.partials.update-profile-information-form')
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="card">
        <div class="card-header">Ubah Kata Sandi</div>
        <div class="card-body">
          @include('profile.partials.update-password-form')
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-header">Hapus Akun</div>
        <div class="card-body">
          @include('profile.partials.delete-user-form')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
