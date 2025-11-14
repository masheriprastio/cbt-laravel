{{-- resources/views/teacher/questions/bulk/build.blade.php --}}
@extends('layouts.flexy')

@section('title','Input Massal Soal')
@section('page_title','Input Massal Soal')

@section('content')
  {{-- Form input massal soal (STEP: build) --}}
  <form method="POST" action="{{ route('teacher.questions.bulk.store', $test) }}" id="bulkBuildForm">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">

    {{-- Number list — tampilkan tombol untuk menampilkan form tiap nomor --}}
    <div class="mb-3">
      <div class="d-flex flex-wrap">
        @foreach ($indexes as $i)
          <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-2 show-question" data-index="{{ $i }}">#{{ $start + $i }}</button>
        @endforeach
      </div>
    </div>

    @foreach ($indexes as $i)
    <div class="card mb-3 question-card" id="question-card-{{ $i }}" style="display:none;">
      <div class="card-body">
        <div class="mb-2 d-flex align-items-center gap-3">
          <label class="form-label fw-bold mb-0">Soal #{{ $start + $i }}</label>
          <div>
            <select name="questions[{{ $i }}][type]" class="form-select form-select-sm question-type" data-index="{{ $i }}">
              <option value="mcq" {{ (isset($type) && $type==='mcq') || old("questions.$i.type")==='mcq' ? 'selected':'' }}>Pilihan Ganda (MCQ)</option>
              <option value="essay" {{ (isset($type) && $type==='essay') || old("questions.$i.type")==='essay' ? 'selected':'' }}>Esai</option>
              <option value="tf" {{ (isset($type) && $type==='tf') || old("questions.$i.type")==='tf' ? 'selected':'' }}>Benar / Salah</option>
            </select>
          </div>
        </div>
        <div class="mb-2">
          <textarea name="questions[{{ $i }}][text]" rows="3"
                    class="form-control ckeditor"
                    placeholder="Tulis isi soal di sini...">{{ old("questions.$i.text") }}</textarea>
        </div>
        <div class="mb-2">
          <label class="form-label fw-bold">Soal #{{ $start + $i }}</label>
          <textarea name="questions[{{ $i }}][text]" rows="3"
                    class="form-control ckeditor"
                    placeholder="Tulis isi soal di sini...">{{ old("questions.$i.text") }}</textarea>
        </div>

        <div class="mcq-fields" style="display:none;">
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
                  <div class="form-check">
                    <input class="form-check-input" type="radio"
                           name="questions[{{ $i }}][answer_key]"
                           id="answer_key_{{ $i }}_{{ $choiceLabel }}"
                           value="{{ $choiceLabel }}"
                           @if (old("questions.$i.answer_key") == $choiceLabel) checked @endif>
                    <label class="form-check-label"
                           for="answer_key_{{ $i }}_{{ $choiceLabel }}">{{ $choiceLabel }}</label>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        <div class="tf-fields" style="display:none;">
          <div class="row mt-3">
            <div class="col-md-8">
              <label class="form-label">Pernyataan (True / False):</label>
              {{-- statement is the same textarea above (questions[].text) --}}
            </div>
            <div class="col-md-4">
              <label class="form-label">Kunci Jawaban:</label>
              <div class="mt-2">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="questions[{{ $i }}][answer_key]" id="tf_true_{{ $i }}" value="true" @if(old("questions.$i.answer_key")=='true') checked @endif>
                  <label class="form-check-label" for="tf_true_{{ $i }}">Benar (True)</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="questions[{{ $i }}][answer_key]" id="tf_false_{{ $i }}" value="false" @if(old("questions.$i.answer_key")=='false') checked @endif>
                  <label class="form-check-label" for="tf_false_{{ $i }}">Salah (False)</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label class="form-label" for="score_{{ $i }}">Skor:</label>
                <input type="number" name="questions[{{ $i }}][score]" id="score_{{ $i }}" class="form-control" value="{{ old("questions.$i.score", $score) }}" min="0">
            </div>
        </div>
      </div>
    </div>
  @endforeach
    <div class="d-flex gap-2 mb-4">
      <a href="{{ route('teacher.tests.show', $test) }}" class="btn btn-outline-secondary">Batal</a>
      <button type="button" class="btn btn-secondary" id="previewBtn">Preview Soal</button>
      <button type="submit" class="btn btn-primary">Simpan & Lanjutkan</button>
    </div>

  </form>

  {{-- Modal preview sederhana --}}
  <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Preview Soal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="previewBody">
          <!-- isi preview diinject lewat JS -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
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
                    'alignment','|',
                    'link','blockQuote','insertTable','imageUpload','codeBlock'
                  ]
                },
                placeholder: 'Tulis isi soal di sini...',
                simpleUpload: {
                  uploadUrl: '{{ url('teacher/editor/upload') }}',
                  headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  }
                }
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
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const previewBtn = document.getElementById('previewBtn');
      const previewBody = document.getElementById('previewBody');
      const modalEl = document.getElementById('previewModal');
      const bsModal = modalEl ? new bootstrap.Modal(modalEl) : null;

      const indexes = @json($indexes);

      function syncTypeFields(i) {
        const sel = document.querySelector('select[name="questions['+i+'][type]"]');
        const card = document.getElementById('question-card-' + i);
        const mcqFields = card ? card.querySelector('.mcq-fields') : null;
        const tfFields = card ? card.querySelector('.tf-fields') : null;
        if (!sel) return;
        const val = sel.value;
        if (mcqFields) mcqFields.style.display = (val === 'mcq') ? '' : 'none';
        if (tfFields) tfFields.style.display = (val === 'tf') ? '' : 'none';
      }

      function hasContent(i) {
        const textEl = document.querySelector('textarea[name="questions['+i+'][text]"]');
        if (textEl && textEl.value.trim().length) return true;
        for (let ci=0;ci<5;ci++){
          const cEl = document.querySelector('input[name="questions['+i+'][choices]['+ci+']"]');
          if (cEl && cEl.value.trim().length) return true;
        }
        const aEl = document.querySelector('input[name="questions['+i+'][answer_key]"]:checked');
        if (aEl) return true;
        const scoreEl = document.querySelector('input[name="questions['+i+'][score]"]');
        if (scoreEl && scoreEl.value) return true;
        return false;
      }

      // show when number button clicked
      document.querySelectorAll('.show-question').forEach(btn => {
        btn.addEventListener('click', function(){
          const i = this.getAttribute('data-index');
          const card = document.getElementById('question-card-' + i);
          if (card) {
            card.style.display = '';
            const ta = card.querySelector('textarea.ckeditor');
            if (ta) ta.focus();
            const sel = card.querySelector('.question-type');
            if (sel) syncTypeFields(i);
            card.scrollIntoView({behavior:'smooth', block:'center'});
          }
        });
      });

      // type change handler
      document.querySelectorAll('.question-type').forEach(sel => {
        sel.addEventListener('change', function(){
          const i = this.getAttribute('data-index');
          syncTypeFields(i);
        });
      });

      // auto-show cards that have existing content (old inputs)
      indexes.forEach(i => {
        try{
          if (hasContent(i)) {
            const card = document.getElementById('question-card-' + i);
            if (card) card.style.display = '';
          }
          syncTypeFields(i);
        }catch(e){console.error(e)}
      });

      function collectQuestions() {
        const items = [];
        indexes.forEach(i => {
          const textEl = document.querySelector('textarea[name="questions['+i+'][text]"]');
          const text = textEl ? textEl.value : '';
          const scoreEl = document.querySelector('input[name="questions['+i+'][score]"]');
          const score = scoreEl ? scoreEl.value : '';
          const typeSel = document.querySelector('select[name="questions['+i+'][type]"]');
          const qtype = typeSel ? typeSel.value : document.querySelector('input[name="type"]')?.value || '';
          const q = { index: i, text: text, score: score, type: qtype, choices: null, answer_key: null };

          if (qtype === 'mcq'){
            const choices = [];
            for (let ci=0;ci<5;ci++){
              const cEl = document.querySelector('input[name="questions['+i+'][choices]['+ci+']"]');
              choices.push(cEl ? cEl.value : '');
            }
            q.choices = choices;
            const aEl = document.querySelector('input[name="questions['+i+'][answer_key]"]:checked');
            q.answer_key = aEl ? aEl.value : null;
          } else if (qtype === 'tf'){
            const tfEl = document.querySelector('input[name="questions['+i+'][answer_key]"]:checked');
            q.answer_key = tfEl ? tfEl.value : null;
          }
          items.push(q);
        });
        return items;
      }

      previewBtn?.addEventListener('click', function () {
        const items = collectQuestions();
        let html = '';
        items.forEach(function (it, idx) {
          html += '<div class="mb-4">';
          html += '<h6>Soal #' + (idx+1) + ' (Skor: ' + (it.score || '-') + ')</h6>';
          html += '<div>' + (it.text || '<em>(kosong)</em>') + '</div>';
          if (it.choices) {
            html += '<ul class="mt-2">';
            it.choices.forEach(function (c, ci) {
              const label = ['A','B','C','D','E'][ci] || (ci+1);
              html += '<li><strong>'+label+':</strong> ' + (c || '<em>(kosong)</em>') + '</li>';
            });
            html += '</ul>';
            if (it.answer_key) html += '<div class="mt-2"><strong>Kunci:</strong> ' + it.answer_key + '</div>';
          } else if (it.type === 'tf') {
            if (it.answer_key) html += '<div class="mt-2"><strong>Kunci:</strong> ' + (it.answer_key === 'true' ? 'Benar' : 'Salah') + '</div>';
          }
          html += '</div>';
        });
        previewBody.innerHTML = html;
        bsModal?.show();
      });
    });
  </script>
@endpush
