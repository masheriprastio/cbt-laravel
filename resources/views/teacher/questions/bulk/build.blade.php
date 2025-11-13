@extends('layouts.flexy')

@section('title', 'Tambah Soal Massal')
@section('page_title', 'Tambah Soal Massal (Langkah 2)')
@section('breadcrumb')
  Guru / Ujian / {{ $test->title }} / Tambah Soal Massal
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="bulk-questions">
      <form method="POST" action="{{ route('teacher.questions.bulk.store', $test) }}" class="row g-3" novalidate>
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        @foreach ($indexes as $idx)
          @php $num = $start + $idx; @endphp
          <div class="col-12 mb-4">
            <div class="card shadow-sm">
              <div class="card-body">
                {{-- Header dengan nomor jelas di kiri --}}
                <div class="mb-3">
                  <h5 class="card-title mb-0"><span class="badge bg-primary me-2">{{ $num }}</span>Soal Nomor {{ $num }}</h5>
                </div>

                {{-- Baris Isi Soal --}}
                {{-- Wrap editor + choices in an explicit wrapper to guarantee DOM flow and separation --}}
                <div class="editor-wrapper">
                  <div class="row mt-3">
                    <div class="col-12">
                      <label class="form-label">Isi Soal</label>
                      {{-- Rich editor (CKEditor) enabled textarea for rich text input --}}
                      <textarea name="questions[{{ $idx }}][text]" class="form-control ck-editor" rows="4" required>{{ old("questions.$idx.text") }}</textarea>
                    </div>
                  </div>

                  @if ($type === 'mcq')
                  {{-- Pilihan Ganda (vertikal): tampilkan A..E tiap baris --}}
                  @php $letters = ['A','B','C','D','E']; @endphp
                  {{-- Wrap choices in a dedicated block so it always sits outside the editor area --}}
                  <div class="mt-3 choices-block">
                    <label class="form-label d-block mb-2">Pilihan</label>
                    @foreach($letters as $i => $letter)
                      <div class="mb-3">
                        <label class="form-label">Pilihan {{ $letter }}</label>
                        <input type="text" name="questions[{{ $idx }}][choices][{{ $i }}]" class="form-control" required>
                      </div>
                    @endforeach

                    <div class="mb-3">
                      <label class="form-label">Kunci</label>
                      <select name="questions[{{ $idx }}][answer_key]" class="form-select" required>
                        @foreach($letters as $letter)
                          <option value="{{ $letter }}">{{ $letter }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                @endif
                </div>

                {{-- Pengaturan tambahan (Urutan & Skor) ditampilkan vertikal di bawah isi soal dan pilihan --}}
                <div class="mt-3">
                  <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                      <label class="form-label">Urutan</label>
                      <input type="number" name="questions[{{ $idx }}][sort]" class="form-control form-control-sm" value="{{ $num }}">
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                      <label class="form-label">Skor</label>
                      <input type="number" name="questions[{{ $idx }}][score]" class="form-control form-control-sm" value="{{ $score }}" required>
                    </div>
                  </div>
                </div>

                </div>
              </div>
            </div>
          </div>
        @endforeach
        </div>

        <div class="col-12 d-flex gap-2">
          <a href="{{ route('teacher.tests.show', $test) }}" class="btn btn-outline-secondary">Batal</a>
          <button class="btn btn-primary">Simpan Soal</button>
        </div>
      </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<!-- Load CKEditor 5 Classic build from CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Initialize CKEditor instances for each textarea with class .ck-editor
  document.querySelectorAll('textarea.ck-editor').forEach((textarea) => {
    // create a container for the editor and hide the original textarea
    const container = document.createElement('div');
    container.className = 'ck-editor-container mb-2';
    textarea.style.display = 'none';
    textarea.parentNode.insertBefore(container, textarea);

    ClassicEditor.create(container).then((editor) => {
      // load initial data
      editor.setData(textarea.value || '');
      // store reference so we can copy data back on submit
      textarea._ckEditor = editor;
    }).catch((err) => {
      console.error('CKEditor init failed', err);
    });

    // ensure on form submit we copy editor data back into textarea
    const form = textarea.closest('form');
    if (form) {
      form.addEventListener('submit', () => {
        if (textarea._ckEditor) {
          textarea.value = textarea._ckEditor.getData();
        }
      });
    }
  });
});
</script>
@endpush

@push('styles')
<style>
  /* Force each bulk question card to occupy full width and stack vertically */
  .bulk-questions .row > .col-12 {
    -webkit-box-flex: 0 !important;
    -ms-flex: 0 0 100% !important;
    flex: 0 0 100% !important;
    max-width: 100% !important;
  }
  .bulk-questions .card { width: 100%; }
  /* Quill appearance tweaks to keep spacing tidy between editor and choices */
  .quill-container { margin-bottom: 1rem; }
  .quill-container .ql-toolbar { border-radius: 6px 6px 0 0; }
  .quill-container .ql-container { border-radius: 0 0 6px 6px; min-height: 160px; }
  /* Ensure the hidden textarea doesn't take up layout space */
  textarea.quill-editor { display: none !important; }
  /* Ensure the choices block sits clearly below the editor */
  .choices-block { clear: both; margin-top: 1rem !important; padding: 0.75rem; background: #ffffff; border: 1px solid #e9ecef; border-radius: 6px; }
</style>
@endpush
