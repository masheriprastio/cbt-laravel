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
          <label class="form-label">Teks Soal #{{ $i+1 }}</label>
          {{-- Tambahkan class "ckeditor" ke setiap textarea teks soal --}}
          <textarea name="questions[{{ $i }}][text]" rows="6"
                    class="form-control ckeditor"
                    placeholder="Tulis isi soal di sini...">{{ old("questions.$i.text") }}</textarea>
        </div>

        {{-- Contoh input pilihan A–E (biarkan seperti punya kamu) --}}
        {{-- name="questions[i][choices][0..4]" dan "questions[i][answer_key]" --}}
        {{-- ... --}}
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
