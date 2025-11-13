@extends('layouts.flexy-blank')

@section('title','Hasil Demo Ujian')

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Hasil Ujian: {{ $test->title }}</h4>
          <p class="text-muted small">Skor: <strong>{{ $totalScore }}</strong> / {{ $totalPossible }} ({{ $percent }}%)</p>

          <div class="list-group mb-3">
            @foreach($test->questions as $q)
              @php $info = $perQuestion[$q->id] ?? null; @endphp
              <div class="list-group-item">
                <div class="d-flex justify-content-between">
                  <div>
                    <strong>Soal {{ $q->id }}</strong>
                    <div class="small text-muted">{{ $q->text }}</div>
                  </div>
                  <div class="text-end">
                    @if($info && $info['is_correct'])
                      <span class="badge bg-success">Benar (+{{ $info['score'] }})</span>
                    @else
                      <span class="badge bg-danger">Salah</span>
                    @endif
                  </div>
                </div>

                <div class="mt-2">
                  <div class="small">Jawaban kamu: 
                    @if($info && $info['given'])
                      @php $givenOpt = $q->options->firstWhere('id', $info['given']); @endphp
                      {{ $givenOpt ? ($givenOpt->label . '. ' . $givenOpt->text) : '(tidak ditemukan)'}}
                    @else
                      (tidak memilih)
                    @endif
                  </div>
                  <div class="small">Kunci jawaban:
                    @php $correctOpt = $q->options->firstWhere('is_correct', 1); @endphp
                    @if($correctOpt)
                      {{ $correctOpt->label }}. {{ $correctOpt->text }}
                    @else
                      (tidak diset)
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <a href="{{ route('exam.demo', ['test' => $test->id]) }}" class="btn btn-secondary">Ulangi Demo</a>
          <a href="/" class="btn btn-light">Kembali</a>
        </div>
      </div>
    </div>
  </div>
@endsection
