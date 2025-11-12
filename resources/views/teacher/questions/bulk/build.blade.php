@extends('layouts.flexy')

@section('title', 'Tambah Soal Massal')
@section('page_title', 'Tambah Soal Massal (Langkah 2)')
@section('breadcrumb')
  Guru / Ujian / {{ $test->title }} / Tambah Soal Massal
@endsection

@section('content')
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" action="{{ route('teacher.questions.bulk.store', $test) }}" class="row g-3" novalidate>
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        @foreach ($indexes as $idx)
          @php $num = $start + $idx; @endphp
          <div class="col-12 mb-4">
            <div class="card shadow-sm">
              <div class="card-body">
                {{-- Header dengan nomor jelas di kiri --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="card-title mb-0"><span class="badge bg-primary me-2">{{ $num }}</span>Soal Nomor {{ $num }}</h5>
                  <div class="d-flex gap-2">
                    <div>
                      <div class="small text-muted">Urutan</div>
                      <input type="number" name="questions[{{ $idx }}][sort]" class="form-control form-control-sm text-end" style="width:100px;" value="{{ $num }}">
                    </div>
                    <div>
                      <div class="small text-muted">Skor</div>
                      <input type="number" name="questions[{{ $idx }}][score]" class="form-control form-control-sm text-end" style="width:100px;" value="{{ $score }}" required>
                    </div>
                  </div>
                </div>

                {{-- Baris Isi Soal --}}
                <div class="row mt-3">
                  <div class="col-12">
                    <label class="form-label">Isi Soal</label>
                    {{-- TinyMCE enabled textarea for rich text input --}}
                    <textarea name="questions[{{ $idx }}][text]" class="form-control tinymce" rows="4" required>{{ old("questions.$idx.text") }}</textarea>
                  </div>
                </div>

                @if ($type === 'mcq')
                  {{-- Pilihan Ganda (vertikal): tampilkan A..E tiap baris --}}
                  @php $letters = ['A','B','C','D','E']; @endphp
                  <div class="mt-3">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (window.initTiny) {
    window.initTiny('{{ route("teacher.editor.upload") }}');
  }
});
</script>
@endpush
