@extends('layouts.flexy')

@section('title','Edit Soal')
@section('page_title','Edit Soal')
@section('breadcrumb') Guru / Ujian / {{ $test->title }} / Edit Soal @endsection

@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm">
      <div class="card-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Periksa kembali isian:</div>
            <ul class="mb-0 ps-3">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('teacher.questions.update', [$test, $question]) }}" novalidate>
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Tipe Soal</label>
            <select name="type" id="type" class="form-select" required>
              <option value="mcq"  {{ old('type',$question->type)==='mcq' ? 'selected' : '' }}>Pilihan Ganda (MCQ)</option>
              <option value="essay"{{ old('type',$question->type)==='essay' ? 'selected' : '' }}>Esai</option>
            </select>
            <div class="form-text">Ubah tipe hanya jika diperlukan.</div>
          </div>

          <div class="mb-3">
            <label class="form-label">Teks Soal</label>
            <textarea name="text" rows="4" class="form-control quill-editor" required>{{ old('text',$question->text) }}</textarea>
          </div>

          <div class="row g-3">
            <div class="col-sm-4">
              <label class="form-label">Skor</label>
              <input type="number" name="score" class="form-control"
                     value="{{ old('score',$question->score) }}" min="1" required>
            </div>
            <div class="col-sm-4">
              <label class="form-label">Urutan</label>
              <input type="number" name="sort_order" class="form-control"
                     value="{{ old('sort_order',$question->sort_order) }}" min="1">
            </div>
          </div>

          {{-- Bagian MCQ --}}
          <div id="mcqBox" class="mt-4">
            <label class="form-label d-block">Pilihan (A–E)</label>
            @foreach ($letters as $i => $L)
              <div class="input-group mb-2">
                <span class="input-group-text">{{ $L }}</span>
                <input type="text" name="choices[{{ $i }}]" class="form-control"
                       value="{{ old('choices.'.$i, $choices[$i] ?? '') }}">
              </div>
            @endforeach

            <div class="mt-2">
              <label class="form-label">Kunci Jawaban</label>
              <select name="answer_key" class="form-select">
                @foreach ($letters as $L)
                  <option value="{{ $L }}" {{ old('answer_key',$question->answer_key) === $L ? 'selected':'' }}>
                    {{ $L }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <a href="{{ route('teacher.tests.show', $test) }}" class="btn btn-outline-secondary">Batal</a>
            <button class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Panel info --}}
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header"><h6 class="mb-0">Info Ujian</h6></div>
      <div class="card-body small text-secondary">
        <div class="mb-1"><b>Judul:</b> {{ $test->title }}</div>
        <div class="mb-1"><b>Durasi:</b> {{ $test->duration_minutes }} menit</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const type = document.getElementById('type');
  const mcq  = document.getElementById('mcqBox');
  const sync = () => { mcq.style.display = (type.value === 'mcq') ? '' : 'none'; }
  type.addEventListener('change', sync); sync();
  if (window.initQuill) {
    window.initQuill('{{ route("teacher.editor.upload") }}'); // initialize Quill with upload URL
  }
});
</script>
@endpush

@push('styles')
<style>
  .quill-container { margin-bottom: 1rem; }
  .quill-container .ql-container { min-height: 160px; }
  textarea.quill-editor { display: none !important; }
</style>
@endpush
