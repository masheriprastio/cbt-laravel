@extends('layouts.flexy')

@section('title','Cetak Kartu Ujian')
@section('page_title','Cetak Kartu Ujian')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <h5>Data Peserta</h5>
    <p><strong>Nama:</strong> {{ $user->name }}</p>
    <p><strong>Username:</strong> {{ $user->username }}</p>

    @if(isset($passwordPlain) && $passwordPlain)
      <p><strong>Password:</strong> {{ $passwordPlain }}</p>
    @else
      <form method="POST" action="{{ route('admin.users.print.confirm', $user) }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Set Password untuk Cetak (kosongkan jika tidak ingin mengubah)</label>
          <input type="text" name="password" class="form-control" placeholder="Masukkan password">
        </div>
        <div>
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">Set & Tampilkan Kartu</button>
        </div>
      </form>
    @endif

    @if(isset($passwordPlain) && $passwordPlain)
      <div class="mt-4">
        <button class="btn btn-secondary" onclick="window.print()">Print</button>
      </div>
    @endif
  </div>
</div>
@endsection
