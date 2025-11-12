{{-- resources/views/teacher/questions/create.blade.php --}}
<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Tambah Soal — {{ $test->title }}</h2></x-slot>
  <div class="p-6 max-w-3xl">
    <form method="POST" action="{{ route('teacher.questions.store', $test) }}" class="space-y-4">
      @csrf

      <div class="flex gap-4">
        <label class="flex items-center gap-2">
          <input type="radio" name="type" value="mcq" checked/> <span>MCQ (A–E)</span>
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" name="type" value="essay"/> <span>Esai</span>
        </label>
      </div>

      <div>
        <label class="block text-sm font-medium">Teks Soal</label>
        <textarea name="text" class="w-full border rounded-lg p-2 tinymce-editor" required></textarea>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Poin</label>
          <input type="number" name="score" value="10" min="1" class="w-full border rounded-lg p-2"/>
        </div>
        <div>
          <label class="block text-sm font-medium">Urutan</label>
          <input type="number" name="order" class="w-full border rounded-lg p-2" />
        </div>
      </div>

      <div id="mcq-fields" class="space-y-3">
        <div class="grid sm:grid-cols-2 gap-3">
          @foreach(['A','B','C','D','E'] as $L)
            <div>
              <label class="block text-sm font-medium">Pilihan {{ $L }}</label>
              <input name="options[{{ $L }}]" class="w-full border rounded-lg p-2"/>
            </div>
          @endforeach
        </div>
        <div>
          <label class="block text-sm font-medium">Kunci Jawaban</label>
          <select name="answer_key" class="border rounded p-2">
            @foreach(['A','B','C','D','E'] as $L)
              <option value="{{ $L }}">{{ $L }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="flex gap-2">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        <a href="{{ route('teacher.tests.show', $test) }}" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</a>
      </div>
    </form>

    <script>
      const radios = document.querySelectorAll('input[name="type"]');
      const mcq = document.getElementById('mcq-fields');
      radios.forEach(r => r.addEventListener('change', e => {
        mcq.style.display = (e.target.value === 'mcq') ? 'block' : 'none';
      }));
    </script>
  </div>
</x-app-layout>
