@extends('layouts.flexy')

@php use Illuminate\Support\Str; @endphp

@section('title','Detail Ujian')
@section('page_title','Ujian ' . $test->title)
@section('breadcrumb') Guru / Ujian / {{ $test->title }} @endsection

@section('page_actions')
  <div class="d-flex gap-2">
    <a href="{{ route('teacher.questions.bulk.setup', $test) }}" class="btn btn-primary btn-sm">Tambah Soal</a>
  </div>
@endsection

@section('content')
  {{-- Ringkasan --}}
  <div class="card shadow-sm mb-4">
    <div class="card-body d-flex align-items-center justify-content-between">
      <div class="text-secondary">
        Durasi: <b>{{ $test->duration_minutes }} m</b>
        • MCQ: <b>{{ $counts['mcq'] }}</b>
        • Esai: <b>{{ $counts['essay'] }}</b>
      </div>
      <div>
        <form method="POST" action="{{ route('teacher.questions.bulk.destroy', $test) }}"
              onsubmit="return confirm('Hapus semua soal pada ujian ini?');" class="d-inline">
          @csrf @method('DELETE')
          <button class="btn btn-outline-danger btn-sm">Hapus Semua Soal</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Daftar Soal --}}
  <div class="card shadow-sm">
    <div class="card-header">
      <h6 class="mb-0">Daftar Soal</h6>
    </div>
    <div class="card-body">
      @if ($questions->isEmpty())
        <div class="alert alert-secondary mb-0">Belum ada soal. Klik <b>Tambah Soal</b> untuk memulai.</div>
      @else
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th style="width:70px">No</th>
                <th>Soal</th>
                <th style="width:120px">Tipe</th>
                <th style="width:100px">Skor</th>
                <th style="width:180px" class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($questions as $question)
                <tr>
                  <td>{{ $question->sort_order ?? $loop->iteration }}</td>
                  <td>
                    @php
                      // Hapus penomoran manual di awal teks soal (mis. "1. ..." atau "1) ...")
                      $raw = strip_tags($question->text);
                      $displayText = preg_replace('/^\s*\d+[\)\.\-:\s]+/u', '', $raw);
                    @endphp
                    <div class="fw-semibold mb-1">
                      {{ Str::limit($displayText, 140) }}
                    </div>

                    @if ($question->type === 'mcq')
                      @php $letters = ['A','B','C','D','E']; @endphp
                      <ol type="A" class="small mb-0 ps-3">
                        @foreach (($question->choices ?? []) as $i => $txt)
                          <li class="{{ ($letters[$i] ?? '') === $question->answer_key ? 'fw-semibold text-success' : '' }}">
                            {{ $txt }}
                          </li>
                        @endforeach
                      </ol>
                      <div class="small text-secondary mt-1">Kunci: <b>{{ $question->answer_key }}</b></div>
                    @endif
                  </td>

                  <td>
                    <span class="badge {{ $question->type === 'mcq' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }}">
                      {{ strtoupper($question->type) }}
                    </span>
                  </td>

                  <td>{{ $question->score }}</td>

                  <td class="text-end">
                    <a href="{{ route('teacher.questions.edit', [$test, $question]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('questions.destroy', $question) }}"
                          class="d-inline" onsubmit="return confirm('Hapus soal ini?');">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection
