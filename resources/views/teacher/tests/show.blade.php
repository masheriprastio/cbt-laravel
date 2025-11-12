@extends('layouts.flexy')

{{-- resources/views/teacher/tests/show.blade.php --}}

<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">{{ $test->title }}</h2></x-slot>
  <div class="p-6 space-y-6">
    @if(session('success'))<div class="p-3 bg-green-50 border rounded">{{ session('success') }}</div>@endif

    <div class="bg-white p-4 rounded-xl shadow">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-700">Durasi: {{ $test->duration_minutes }} m • MCQ: {{ $test->mcq_count }} • Esai: {{ $test->essay_count }}</p>
          @if($test->starts_at)
            <p class="text-sm text-gray-500">Jadwal: {{ $test->starts_at }} — {{ $test->ends_at ?? 'tanpa batas' }}</p>
          @endif
        </div>
        <a href="{{ route('teacher.questions.bulk.setup', $test) }}" class="px-3 py-2 bg-blue-600 text-white rounded">
          Tambah Soal
        </a>

      </div>
    </div>


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
      @foreach ($questions as $i => $question)
        <tr>
          <td>{{ $question->sort_order ?? ($i + 1) }}</td>
          <td>
            <div class="fw-semibold mb-1">{{ Str::limit($question->text, 120) }}</div>

            @if($question->type === 'mcq')
              @php $letters = ['A','B','C','D','E']; @endphp
              <ol type="A" class="small mb-0 ps-3">
                @foreach(($question->choices ?? []) as $idx => $txt)
                  <li class="{{ ($letters[$idx] ?? '') === $question->answer_key ? 'fw-semibold text-success' : '' }}">
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

            <form method="POST"
                  action="{{ route('questions.destroy', $question) }}"
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

  </div>
</x-app-layout>
