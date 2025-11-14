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

          <div class="row g-3 mb-3">
            <div class="col-12">
                <label for="subject" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                <input type="text" id="subject" name="subject"
                       class="form-control @error('subject') is-invalid @enderror"
                       value="{{ old('subject') }}" maxlength="100" required>
                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
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
                value="{{ old('duration_minutes') }}" required>
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
              <label for="starts_at" class="form-label">Mulai <span class="text-danger">*</span></label>
              <input type="datetime-local" id="starts_at" name="starts_at"
                     class="form-control @error('starts_at') is-invalid @enderror"
                     value="{{ old('starts_at') }}" required>
              @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-6">
              <label for="ends_at" class="form-label">Selesai <span class="text-danger">*</span></label>
              <input type="datetime-local" id="ends_at" name="ends_at"
                     class="form-control @error('ends_at') is-invalid @enderror"
                     value="{{ old('ends_at') }}" required>
              @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <div class="form-text">Waktu selesai harus setelah waktu mulai.</div>
            </div>
          </div>

          <hr class="my-4">

          <div class="row g-3">
            <div class="col-12 col-md-6">
    <label for="mcq_count" class="form-label">Jumlah Pilihan Ganda (MCQ) <span class="text-danger">*</span></label>
    <input type="number" min="0" max="500" id="mcq_count" name="mcq_count"
      class="form-control @error('mcq_count') is-invalid @enderror"
      value="{{ old('mcq_count', 0) }}" data-initial="0" required>
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
            <button type="submit" class="btn btn-primary">Simpan & Lanjut</button>
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
<script>
// Konfirmasi khusus untuk kasus ketika user memulai dari default 0 ke 1 (atau hanya 1 MCQ)
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('createTestForm');
  const mcq = document.getElementById('mcq_count');

  form?.addEventListener('submit', function (ev) {
    try {
      const mcqVal = mcq ? parseInt(mcq.value || '0', 10) : 0;
      const mcqInitial = mcq ? parseInt(mcq.dataset.initial || '0', 10) : 0;

      // Jika awalnya 0 dan sekarang hanya diisi 1, tunjukkan peringatan konfirmasi
      if (mcqInitial === 0 && mcqVal === 1) {
        const ok = confirm('Anda hanya memasukkan 1 soal Pilihan Ganda. Lanjutkan ke proses tambah soal massal?');
        if (!ok) {
          ev.preventDefault();
          return false;
        }
      }

      // Jika jumlah total soal (MCQ+Essay) masih 0, tanyakan apakah tetap ingin membuat ujian kosong
      const essay = document.getElementById('essay_count');
      const essayVal = essay ? parseInt(essay.value || '0', 10) : 0;
      if (mcqVal === 0 && essayVal === 0) {
        const ok2 = confirm('Anda belum menambahkan jumlah soal (MCQ atau Esai). Buat ujian tanpa soal?');
        if (!ok2) {
          ev.preventDefault();
          return false;
        }
      }
    } catch (err) {
      // jangan ganggu submit jika ada error JS
    }
  });
});
</script>
@endpush
