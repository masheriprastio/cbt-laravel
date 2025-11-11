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

    <div class="bg-white p-4 rounded-xl shadow">
      <h3 class="font-semibold mb-3">Daftar Soal</h3>
      <ol class="space-y-3">
        @if($q->type === 'mcq')
  @php $letters = ['A','B','C','D','E']; @endphp
  <ol type="A" class="mb-2">
    @foreach($q->choices ?? [] as $i => $text)
      <li class="{{ ($letters[$i] ?? '') === $q->answer_key ? 'fw-semibold text-success' : '' }}">
        {{ $text }}
      </li>
    @endforeach
  </ol>
  <div class="small text-secondary">Kunci: {{ $q->answer_key }}</div>
@endif

      </ol>
    </div>
  </div>
</x-app-layout>
