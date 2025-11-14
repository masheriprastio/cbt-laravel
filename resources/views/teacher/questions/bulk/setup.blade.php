{{-- resources/views/teacher/questions/bulk/setup.blade.php --}}
@extends('layouts.flexy')

@section('title','Tambah Soal Massal')
@section('page_title','Tambah Soal (Langkah 1)')
@section('breadcrumb') Guru / Ujian / {{ $test->title }} / Tambah Soal Massal @endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-body">

    @if ($errors->any())
      <div class="alert alert-danger">
        <div class="fw-semibold">Periksa kembali isian:</div>
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST"
          action="{{ route('teacher.questions.bulk.build', $test) }}"
          class="row g-3" id="bulkSetup">
      @csrf

      <div class="col-12 col-md-3">
        <label class="form-label">Jenis Soal</label>
        <select name="type" class="form-select" required>
          <option value="mcq"  {{ old('type')==='mcq'  ? 'selected':'' }}>Pilihan Ganda (MCQ)</option>
          <option value="essay"{{ old('type')==='essay'? 'selected':'' }}>Esai</option>
          <option value="tf"{{ old('type')==='tf'? 'selected':'' }}>Benar / Salah (True/False)</option>
        </select>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Jumlah Soal</label>
        <input type="number" name="count" class="form-control"
               value="{{ old('count',5) }}" min="1" max="100" required>
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Mulai Nomor</label>
        <input type="number" name="start_number" class="form-control"
               value="{{ old('start_number',1) }}" min="1" max="10000">
      </div>

      <div class="col-12 col-md-3">
        <label class="form-label">Skor Default</label>
        <input type="number" name="default_score" class="form-control"
               value="{{ old('default_score',10) }}" min="1" max="1000">
      </div>

      <div class="col-12 d-flex gap-2">
        <a href="{{ route('teacher.tests.show',$test) }}" class="btn btn-outline-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Lanjutkan</button>
      </div>
    </form>
  </div>
</div>
@endsection
