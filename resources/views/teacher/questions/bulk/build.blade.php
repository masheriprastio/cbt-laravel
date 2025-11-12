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
          <div class="col-12">
            <div class="card shadow-sm">
              <div class="card-body">
                {{-- Baris Header --}}
                <div class="row">
                  <div class="col">
                    <h5 class="card-title">Soal #{{ $num }}</h5>
                  </div>
                  <div class="col-12 col-md-2">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="questions[{{ $idx }}][sort]" class="form-control"
                      value="{{ $num }}">
                  </div>
                  <div class="col-12 col-md-2">
                    <label class="form-label">Skor</label>
                    <input type="number" name="questions[{{ $idx }}][score]" class="form-control"
                      value="{{ $score }}" required>
                  </div>
                </div>

                {{-- Baris Isi Soal --}}
                <div class="row mt-3">
                  <div class="col-12">
                    <label class="form-label">Isi Soal</label>
                    <textarea name="questions[{{ $idx }}][text]" class="form-control tinymce-editor" rows="3" required></textarea>
                  </div>
                </div>

                @if ($type === 'mcq')
                  {{-- Baris Pilihan Ganda --}}
                  <div class="row mt-3">
                    <div class="col-12 col-md">
                      <label class="form-label">Pilihan A</label>
                      <input type="text" name="questions[{{ $idx }}][choices][0]" class="form-control" required>
                    </div>
                    <div class="col-12 col-md">
                      <label class="form-label">Pilihan B</label>
                      <input type="text" name="questions[{{ $idx }}][choices][1]" class="form-control" required>
                    </div>
                    <div class="col-12 col-md">
                      <label class="form-label">Pilihan C</label>
                      <input type="text" name="questions[{{ $idx }}][choices][2]" class="form-control" required>
                    </div>
                    <div class="col-12 col-md">
                      <label class="form-label">Pilihan D</label>
                      <input type="text" name="questions[{{ $idx }}][choices][3]" class="form-control" required>
                    </div>
                    <div class="col-12 col-md">
                      <label class="form-label">Pilihan E</label>
                      <input type="text" name="questions[{{ $idx }}][choices][4]" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-2">
                      <label class="form-label">Kunci</label>
                      <select name="questions[{{ $idx }}][answer_key]" class="form-select" required>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                      </select>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endforeach

        <div class="col-12 d-flex gap-2">
          <a href="{{ route('teacher.tests.show', $test) }}" class="btn btn-outline-secondary">Batal</a>
          <button class="btn btn-primary">Simpan Soal</button>
        </div>
      </form>
    </div>
  </div>
@endsection
