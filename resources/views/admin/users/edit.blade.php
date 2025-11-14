@extends('layouts.flexy')

@section('title','Edit Pengguna')
@section('page_title','Edit Pengguna')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="siswa" {{ $user->role==='siswa' ? 'selected':'' }}>Siswa</option>
          <option value="guru" {{ $user->role==='guru' ? 'selected':'' }}>Guru</option>
          <option value="admin" {{ $user->role==='admin' ? 'selected':'' }}>Admin</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" value="{{ $user->username }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
      </div>
      <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
