@extends('layouts.flexy')

@section('title','Pilih Ujian')
@section('page_title','Tambah Soal — Pilih Ujian')
@section('breadcrumb') Guru / Tambah Soal / Pilih Ujian @endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form class="row g-2 mb-3" method="GET" action="">
      <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari judul ujian…">
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-secondary">Cari</button>
      </div>
    </form>

    @if($tests->count())
      <div class="row g-3">
        @foreach($tests as $t)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1">{{ $t->title }}</div>
              <div class="text-secondary small mb-2">
                Durasi: {{ $t->duration_minutes }} m • MCQ: {{ $t->mcq_count }} • Esai: {{ $t->essay_count }}
              </div>
              <div class="d-flex gap-2 mt-auto">
                <a class="btn btn-primary btn-sm"
                   href="{{ route('teacher.questions.create', $t) }}">Tambah Soal</a>
                <a class="btn btn-outline-secondary btn-sm"
                   href="{{ route('teacher.tests.show', $t) }}">Detail</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-3">{{ $tests->withQueryString()->links() }}</div>
    @else
      <div class="text-secondary">Belum ada ujian. <a href="{{ route('teacher.tests.create') }}">Buat ujian</a>.</div>
    @endif
  </div>
</div>
@endsection
