@extends('layouts.flexy')

@section('title','Dashboard')
@section('page_title','Dashboard')
@section('breadcrumb') Home / Dashboard @endsection

@section('content')
  <div class="row g-3">
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-secondary small">Total Ujian</div>
          <div class="fs-3 fw-semibold">{{ $stats['tests'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-secondary small">Soal (MCQ)</div>
          <div class="fs-3 fw-semibold">{{ $stats['mcq'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-secondary small">Soal (Esai)</div>
          <div class="fs-3 fw-semibold">{{ $stats['essay'] ?? 0 }}</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="text-secondary small">Ujian Aktif</div>
          <div class="fs-3 fw-semibold">{{ $stats['active'] ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mt-4">
    <div class="card-header d-flex align-items-center justify-content-between">
      <span class="fw-semibold">Ujian Terbaru</span>
      <a href="{{ route('teacher.tests.index') }}" class="small text-decoration-underline">Lihat semua</a>
    </div>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Judul</th>
            <th>Durasi</th>
            <th>MCQ</th>
            <th>Esai</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse(($latestTests ?? []) as $t)
            <tr>
              <td>{{ $t->title }}</td>
              <td>{{ $t->duration_minutes }} m</td>
              <td>{{ $t->mcq_count }}</td>
              <td>{{ $t->essay_count }}</td>
              <td class="text-end">
                <a href="{{ route('teacher.tests.show',$t) }}" class="btn btn-sm btn-primary">Kelola</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-secondary">Belum ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
