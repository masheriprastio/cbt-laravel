{{-- resources/views/teacher/questions/bulk/build.blade.php --}}
@extends('layouts.flexy')

@section('title','Input Massal Soal')
@section('page_title','Input Massal Soal')

@section('content')
  {{-- ... kode form kamu ... --}}

  @foreach ($items as $i => $row)
    <div class="card mb-3">
      <div class="card-body">
        <div class="mb-2">
          <label class="form-label fw-bold">Soal #{{ $start + $i }}</label>
          <textarea name="questions[{{ $i }}][text]" rows="3"
                    class="form-control ckeditor"
                    placeholder="Tulis isi soal di sini...">{{ old("questions.$i.text") }}</textarea>
        </div>

        @if ($type === 'mcq')
          <div class="row mt-3">
            <div class="col-md-7">
              <label class="form-label">Pilihan Jawaban:</label>
              @foreach (['A','B','C','D','E'] as $choiceKey => $choiceLabel)
                <div class="input-group mb-2">
                  <span class="input-group-text">{{ $choiceLabel }}</span>
                  <input type="text"
                         name="questions[{{ $i }}][choices][{{ $choiceKey }}]"
                         class="form-control" value="{{ old("questions.$i.choices.$choiceKey") }}"
                         placeholder="Teks pilihan {{ $choiceLabel }}">
                </div>
              @endforeach
            </div>
            <div class="col-md-5">
              <label class="form-label">Kunci Jawaban:</label>
              <div class="mt-2">
                @foreach (['A','B','C','D','E'] as $choiceLabel)
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                           name="questions[{{ $i }}][answer_key]"
                           id="answer_key_{{ $i }}_{{ $choiceLabel }}"
                           value="{{ $choiceLabel }}"
                           @if (old("questions.$i.answer_key", 'A') == $choiceLabel) checked @endif>
                    <label class="form-check-label"
                           for="answer_key_{{ $i }}_{{ $choiceLabel }}">{{ $choiceLabel }}</label>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <div class="row mt-3">
            <div class="col-md-3">
                <label class="form-label" for="score_{{ $i }}">Skor:</label>
                <input type="number" name="questions[{{ $i }}][score]" id="score_{{ $i }}" class="form-control" value="{{ old("questions.$i.score", $score) }}" min="0">
            </div>
        </div>
      </div>
    </div>
  @endforeach

  {{-- ... tombol submit dsb ... --}}
@endsection

@push('scripts')
  {{-- CKEditor 5 Classic via CDN --}}
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Init semua textarea yang ber-class "ckeditor"
      const editors = document.querySelectorAll('textarea.ckeditor');
      window._ckEditors = [];
      editors.forEach((el) => {
        ClassicEditor
          .create(el, {
            toolbar: {
              items: [
                'undo','redo','|',
                'heading','|',
                'bold','italic','underline','|',
                'bulletedList','numberedList','outdent','indent','|',
                'link','blockQuote','insertTable','codeBlock'
              ]
            },
            placeholder: 'Tulis isi soal di sini...',
          })
          .then(editor => {
            window._ckEditors.push(editor);
            // Sinkron ke textarea saat ketik (penting untuk submit)
            editor.model.document.on('change:data', () => {
              el.value = editor.getData();
            });
          })
          .catch(console.error);
      });
    });
  </script>
@endpush
