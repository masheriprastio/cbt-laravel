{{-- resources/views/teacher/questions/select.blade.php --}}
@extends('layouts.flexy')

@section('title','Pilih Ujian')
@section('page_title','Tambah/Hapus Soal — Pilih Ujian')
@section('breadcrumb') Guru / Tambah Soal / Pilih Ujian @endsection

@section('content')
  {{-- Notifikasi --}}
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-semibold">Periksa kembali:</div>
      <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  {{-- Pencarian --}}
  <div class="card shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('teacher.questions.select') }}" id="searchForm">
        <div class="col-md-6">
          <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Cari judul ujian…">
        </div>
        <div class="col-auto d-flex gap-2">
          <button class="btn btn-outline-secondary" type="submit">Cari</button>
          @if(!empty($q))
            <a href="{{ route('teacher.questions.select') }}" class="btn btn-outline-dark">Reset</a>
          @endif
        </div>
      </form>
      @if(!empty($q))
        <div class="small text-secondary mt-2">Hasil untuk: <b>{{ $q }}</b></div>
      @endif
    </div>
  </div>

  {{-- Daftar ujian --}}
  @if($tests->count())
    <div class="row g-3">
      @foreach($tests as $t)
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
              <div class="fw-semibold mb-1">{{ $t->title }}</div>
              <div class="text-secondary small mb-3">
                Durasi: {{ $t->duration_minutes }} m
                • MCQ: {{ $t->mcq_cnt ?? $t->mcq_count ?? 0 }}
                • Esai: {{ $t->essay_cnt ?? $t->essay_count ?? 0 }}
              </div>

              <div class="mt-auto d-flex gap-2">
                
  <a class="btn btn-primary btn-sm"
     href="{{ route('teacher.questions.bulk.setup', $t) }}">
    Tambah Soal
  </a>

  {{-- Hapus UJIAN (kartu hilang karena record test dihapus) --}}
  <form method="POST"
        action="{{ route('teacher.tests.destroy', ['test' => $t->id]) }}"
        onsubmit="return confirm('Hapus PERMANEN UJIAN \"{{ addslashes($t->title) }}\" beserta semua soalnya?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm">Hapus Ujian</button>
  </form>

  <a class="btn btn-outline-secondary btn-sm"
     href="{{ route('teacher.tests.show', $t) }}">
    Detail
  </a>
</div>

            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">{{ $tests->withQueryString()->links() }}</div>
  @else
    <div class="alert alert-secondary">Tidak ada ujian.</div>
  @endif
@endsection
