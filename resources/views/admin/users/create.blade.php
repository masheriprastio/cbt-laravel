@extends('layouts.flexy')

@section('title','Tambah Pengguna')
@section('page_title','Tambah Pengguna')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="siswa">Siswa</option>
          <option value="guru">Guru</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Username (opsional, akan digenerate untuk siswa jika dikosongkan)</label>
        <input type="text" name="username" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Email (opsional untuk siswa)</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Password (wajib untuk guru/admin)</label>
        <input type="password" name="password" class="form-control">
      </div>
      {{-- removed class and room fields per admin request --}}
      <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
