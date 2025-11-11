@extends('layouts.flexy')

@section('title','Buat Ujian')
@section('page_title','Buat Ujian')
@section('breadcrumb') Guru / Ujian / Buat @endsection

@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="mb-0">Informasi Ujian</h5>
      </div>
      <div class="card-body">
        {{-- Error global --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Periksa kembali isian Anda:</div>
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('teacher.tests.store') }}" id="createTestForm" novalidate>
          @csrf

          <div class="mb-3">
            <label for="title" class="form-label">Judul Ujian <span class="text-danger">*</span></label>
            <input type="text" id="title" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" maxlength="200" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description" rows="3"
                      class="form-control @error('description') is-invalid @enderror"
                      placeholder="(Opsional) catatan untuk siswa / pengawas">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="row g-3">
            <div class="col-12 col-sm-6">
              <label for="duration_minutes" class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
              <input type="number" min="5" max="300" step="5" id="duration_minutes" name="duration_minutes"
                     class="form-control @error('duration_minutes') is-invalid @enderror"
                     value="{{ old('duration_minutes', 60) }}" required>
              @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-sm-6">
              <div class="form-check mt-4 pt-1">
                <input class="form-check-input" type="checkbox" value="1" id="shuffle_questions" name="shuffle_questions"
                  {{ old('shuffle_questions') ? 'checked' : '' }}>
                <label class="form-check-label" for="shuffle_questions">
                  Acak urutan soal
                </label>
              </div>
            </div>
          </div>

          <hr class="my-4">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label for="starts_at" class="form-label">Mulai (opsional)</label>
              <input type="datetime-local" id="starts_at" name="starts_at"
                     class="form-control @error('starts_at') is-invalid @enderror"
                     value="{{ old('starts_at') }}">
              @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-6">
              <label for="ends_at" class="form-label">Selesai (opsional)</label>
              <input type="datetime-local" id="ends_at" name="ends_at"
                     class="form-control @error('ends_at') is-invalid @enderror"
                     value="{{ old('ends_at') }}">
              @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">Jika diisi, waktu selesai harus setelah waktu mulai.</div>
            </div>
          </div>

          <hr class="my-4">

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label for="mcq_count" class="form-label">Jumlah Pilihan Ganda (MCQ) <span class="text-danger">*</span></label>
              <input type="number" min="0" max="500" id="mcq_count" name="mcq_count"
                     class="form-control @error('mcq_count') is-invalid @enderror"
                     value="{{ old('mcq_count', 10) }}" required>
              @error('mcq_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-6">
              <label for="essay_count" class="form-label">Jumlah Esai <span class="text-danger">*</span></label>
              <input type="number" min="0" max="500" id="essay_count" name="essay_count"
                     class="form-control @error('essay_count') is-invalid @enderror"
                     value="{{ old('essay_count', 0) }}" required>
              @error('essay_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <a href="{{ route('teacher.tests.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan & Lanjut Tambah Soal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Panel bantuan/preview ringkas --}}
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">Tips</h6>
      </div>
      <div class="card-body small text-secondary">
        <ul class="mb-0 ps-3">
          <li>Anda dapat mengosongkan jadwal mulai/selesai jika ujian dibuka manual.</li>
          <li>Aktifkan <em>acak urutan soal</em> untuk mengurangi kecurangan.</li>
          <li>Setelah tersimpan, lanjutkan menambahkan butir soal MCQ/Esai.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Validasi ringan: ends_at >= starts_at (jika keduanya diisi)
document.addEventListener('DOMContentLoaded', function () {
  const s = document.getElementById('starts_at');
  const e = document.getElementById('ends_at');
  function syncMin() { if (s && e && s.value) e.min = s.value; }
  s?.addEventListener('change', syncMin);
  syncMin();
});
</script>
@endpush
