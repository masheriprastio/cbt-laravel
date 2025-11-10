<x-app-layout>
<div class="p-6 max-w-3xl">
<form method="POST" action="{{ route('teacher.tests.store') }}" class="space-y-4">
@csrf
<div>
<label class="block text-sm font-medium">Judul</label>
<input name="title" class="w-full border rounded-lg p-2" required />
</div>
<div>
<label class="block text-sm font-medium">Deskripsi</label>
<textarea name="description" class="w-full border rounded-lg p-2"></textarea>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-medium">Durasi (menit)</label>
<input type="number" name="duration_minutes" value="30" class="w-full border rounded-lg p-2" min="5" />
</div>
<div class="flex items-center gap-2 mt-6">
<input type="checkbox" name="shuffle_questions" value="1" id="shuffle" />
<label for="shuffle">Acak urutan soal</label>
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-medium">Jumlah Soal MCQ</label>
<input type="number" name="mcq_count" value="0" class="w-full border rounded-lg p-2" />
</div>
<div>
<label class="block text-sm font-medium">Jumlah Soal Esai</label>
<input type="number" name="essay_count" value="0" class="w-full border rounded-lg p-2" />
</div>
</div>
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-sm font-medium">Mulai</label>
<input type="datetime-local" name="starts_at" class="w-full border rounded-lg p-2" />
</div>
<div>
<label class="block text-sm font-medium">Selesai</label>
<input type="datetime-local" name="ends_at" class="w-full border rounded-lg p-2" />
</div>
</div>
<div class="flex gap-2">
<button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
<a href="{{ route('teacher.tests.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</a>
</div>
</form>
</div>
</x-app-layout>