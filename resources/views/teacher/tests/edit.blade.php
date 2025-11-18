{{-- resources/views/teacher/tests/edit.blade.php --}}
@extends('layouts.flexy')

@section('title','Edit Ujian')
@section('page_title','Edit Ujian')
@section('breadcrumb') Guru / Ujian / Edit — {{ $test->title }} @endsection

@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <div class="fw-semibold mb-2">Periksa kembali isian:</div>
            <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('teacher.tests.update',$test) }}">
          @csrf @method('PUT')

          <div class="mb-3">
            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
            <input type="text" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror"
                   value="{{ old('subject', $test->subject ?: $test->title) }}" maxlength="100" required>
            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi (opsional)</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description',$test->description) }}</textarea>
          </div>

          <div class="row g-3">
            <div class="col-sm-4">
              <label class="form-label">Durasi (menit)</label>
              <input type="number" name="duration_minutes" class="form-control" min="1"
                     value="{{ old('duration_minutes',$test->duration_minutes) }}" required>
            </div>
            <div class="col-sm-4">
              <label class="form-label">Mulai</label>
              <input type="datetime-local" name="starts_at" class="form-control"
                     value="{{ old('starts_at', optional($test->starts_at)->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="col-sm-4">
              <label class="form-label">Selesai</label>
              <input type="datetime-local" name="ends_at" class="form-control"
                     value="{{ old('ends_at', optional($test->ends_at)->format('Y-m-d\TH:i')) }}">
            </div>
          </div>

          <div class="form-check form-switch mt-3">
            <input class="form-check-input" type="checkbox" role="switch"
                   id="shuffle" name="shuffle_questions"
                   {{ old('shuffle_questions',$test->shuffle_questions) ? 'checked' : '' }}>
            <label class="form-check-label" for="shuffle">Acak urutan soal</label>
          </div>

          <hr class="my-4">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label for="mcq_count" class="form-label">Jumlah Pilihan Ganda (MCQ) <span class="text-danger">*</span></label>
              <input type="number" min="0" max="500" id="mcq_count" name="mcq_count"
                class="form-control @error('mcq_count') is-invalid @enderror"
                value="{{ old('mcq_count', $test->mcq_count ?? 0) }}" required>
              @error('mcq_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-6">
              <label for="essay_count" class="form-label">Jumlah Esai <span class="text-danger">*</span></label>
              <input type="number" min="0" max="500" id="essay_count" name="essay_count"
                     class="form-control @error('essay_count') is-invalid @enderror"
                     value="{{ old('essay_count', $test->essay_count ?? 0) }}" required>
              @error('essay_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <a href="{{ route('teacher.tests.show',$test) }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header"><h6 class="mb-0">Info</h6></div>
      <div class="card-body small text-secondary">
        Perbarui metadata ujian. Untuk menambah/ubah soal, gunakan menu <b>Tambah Soal</b> pada halaman detail ujian.
      </div>
    </div>
  </div>
</div>
@endsection
