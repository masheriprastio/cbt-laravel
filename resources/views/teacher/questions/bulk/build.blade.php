@extends('layouts.flexy')

@section('title','Tambah Soal Massal')
@section('page_title','Tambah Soal (Langkah 1)')
@section('breadcrumb') Guru / Ujian / {{ $test->title }} / Tambah Soal Massal @endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('teacher.questions.bulk.build', $test) }}" class="row g-3" novalidate>
      @csrf

      <div class="col-12 col-md-3">
        <label class="form-label">Jenis Soal</label>
        <select name="type" class="form-select" required>
          <option value="mcq">Pilihan Ganda (MCQ)</option>
          <option value="essay">Esai</option>
        </select>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Jumlah Soal</label>
        <input type="number" name="count" class="form-control" value="5" min="1" max="100" required>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Mulai Nomor</label>
        <input type="number" name="start_number" class="form-control" value="1" min="1" max="10000">
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Skor Default</label>
        <input type="number" name="default_score" class="form-control" value="10" min="1" max="1000">
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('teacher.tests.show',$test) }}" class="btn btn-outline-secondary">Kembali</a>
        <button class="btn btn-primary">Lanjutkan</button>
      </div>
    </form>
  </div>
</div>
@endsection
