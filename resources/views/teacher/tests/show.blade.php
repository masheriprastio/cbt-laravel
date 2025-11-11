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
        @forelse($test->questions as $q)
          <li class="border rounded p-3">
            <div class="flex justify-between">
              <div>
                <span class="px-2 py-0.5 text-xs rounded {{ $q->type==='mcq' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                  {{ strtoupper($q->type) }}
                </span>
                <span class="ml-2 font-semibold">({{ $q->score }} poin)</span>
                <p class="mt-2">{!! nl2br(e($q->text)) !!}</p>

                @if($q->type==='mcq')
                  <ul class="mt-2 grid sm:grid-cols-2 gap-2 text-sm">
                    @foreach($q->options as $op)
                      <li class="border rounded p-2">
                        <strong>{{ $op->label }}.</strong> {{ $op->text }}
                        @if($q->answer_key===$op->label)
                          <span class="ml-2 text-green-700 text-xs">(kunci)</span>
                        @endif
                      </li>
                    @endforeach
                  </ul>
                @endif
              </div>

              <form method="POST" action="{{ route('teacher.questions.destroy',$q) }}" onsubmit="return confirm('Hapus soal ini?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1.5 bg-red-600 text-white rounded">Hapus</button>
              </form>
            </div>
          </li>
        @empty
          <li>Belum ada soal.</li>
        @endforelse
      </ol>
    </div>
  </div>
</x-app-layout>
