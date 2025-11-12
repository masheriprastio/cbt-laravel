@extends('layouts.flexy')

@section('title','Detail Ujian')
@section('page_title','Detail Ujian')
@section('breadcrumb') Guru / Ujian / {{ $test->title }} @endsection

@section('page_actions')
  <div class="d-flex gap-2">
    <a href="{{ route('teacher.tests.edit', $test) }}" class="btn btn-outline-primary btn-sm">Edit Ujian</a>
    <a href="{{ route('teacher.questions.bulk.setup', $test) }}" class="btn btn-primary btn-sm">Tambah Soal</a>
    <a href="{{ route('teacher.questions.select', ['hide_empty'=>1]) }}" class="btn btn-outline-secondary btn-sm">Pilih Ujian Lain</a>
  </div>
@endsection

@section('content')
<div class="row g-4">
  {{-- INFO KIRI --}}
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header">
        <h6 class="mb-0">Info Ujian</h6>
      </div>
      <div class="card-body">
        <div class="mb-2"><span class="text-secondary">Judul</span><div class="fw-semibold">{{ $test->title }}</div></div>
        <div class="mb-2"><span class="text-secondary">Deskripsi</span><div class="small">{{ $test->description ?: '—' }}</div></div>
        <div class="mb-2"><span class="text-secondary">Durasi</span><div>{{ $test->duration_minutes }} menit</div></div>
        <div class="mb-2"><span class="text-secondary">Jadwal</span>
          <div class="small">
            Mulai: {{ $test->starts_at ? $test->starts_at->format('d M Y H:i') : '—' }}<br>
            Selesai: {{ $test->ends_at ? $test->ends_at->format('d M Y H:i') : '—' }}
          </div>
        </div>
        <div class="mb-2"><span class="text-secondary">Opsi</span>
          <div class="small">{{ $test->shuffle_questions ? 'Acak urutan soal' : 'Urutan tetap' }}</div>
        </div>
        <hr>
        <div class="d-flex flex-wrap gap-2">
          <span class="badge bg-primary-subtle text-primary">MCQ: {{ $counts['mcq'] }}</span>
          <span class="badge bg-success-subtle text-success">Esai: {{ $counts['essay'] }}</span>
          <span class="badge bg-secondary-subtle text-secondary">Total: {{ $counts['total'] }}</span>
        </div>
      </div>
    </div>
  </div>

<<<<<<< HEAD
    <div class="bg-white p-4 rounded-xl shadow">
      <h3 class="font-semibold mb-3">Daftar Soal</h3>
      <ol class="space-y-3">
        @foreach($test->questions as $question)
          <li class="p-3 border rounded">
            <div>{{ $question->text }}</div>
            @if($question->type === 'mcq')
              <ol class="list-disc pl-5 mt-2">
                @foreach($question->options as $option)
                  <li @if($option->is_correct) class="font-semibold text-green-700" @endif>
                    {{ $option->text }}
                  </li>
                @endforeach
              </ol>
            @endif
          </li>
        @endforeach
      </ol>
=======
  {{-- DAFTAR SOAL --}}
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0">Daftar Soal</h6>
        <div class="d-flex gap-2">
          <a href="{{ route('teacher.questions.bulk.setup', $test) }}" class="btn btn-sm btn-primary">Tambah Soal</a>
          <form method="POST" action="{{ route('teacher.questions.bulk.destroy', $test) }}"
                onsubmit="return confirm('Hapus semua soal pada ujian ini?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus Semua Soal</button>
          </form>
        </div>
      </div>
      <div class="card-body">
        @if ($questions->count() === 0)
          <div class="alert alert-secondary mb-0">Belum ada soal. Klik <b>Tambah Soal</b> untuk memulai.</div>
        @else
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th style="width:70px">No</th>
                  <th>Soal</th>
                  <th style="width:120px">Tipe</th>
                  <th style="width:120px">Skor</th>
                  <th style="width:180px" class="text-end">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($questions as $i => $q)
                  <tr>
                    <td>{{ $q->sort_order ?? ($i+1) }}</td>
                    <td>
                      <div class="fw-semibold mb-1">{{ Str::limit($q->text, 120) }}</div>
                      @if($q->type === 'mcq')
                        @php $letters = ['A','B','C','D','E']; @endphp
                        <ol type="A" class="small mb-0 ps-3">
                          @foreach(($q->choices ?? []) as $idx => $txt)
                            <li class="{{ ($letters[$idx] ?? '') === $q->answer_key ? 'fw-semibold text-success' : '' }}">
                              {{ $txt }}
                            </li>
                          @endforeach
                        </ol>
                        <div class="small text-secondary mt-1">Kunci: <b>{{ $q->answer_key }}</b></div>
                      @endif
                    </td>
                    <td>
                      <span class="badge {{ $q->type === 'mcq' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }}">
                        {{ strtoupper($q->type) }}
                      </span>
                    </td>
                    <td>{{ $q->score }}</td>
                    <td class="text-end">
                      <a href="{{ route('teacher.questions.edit', [$test, $q]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                      <form method="POST" action="{{ route('questions.destroy', $q) }}"
                            class="d-inline"
                            onsubmit="return confirm('Hapus soal ini?');">
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
>>>>>>> 4a128e1 (Commit perubahan lokal sebelum pull)
    </div>
  </div>
</div>
@endsection
