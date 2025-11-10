<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Penilaian Esai — {{ $test->title }}</h2></x-slot>
<div class="p-6 space-y-4">
@if(session('success'))<div class="p-3 bg-green-50 border rounded">{{ session('success') }}</div>@endif
@forelse($answers as $a)
<div class="bg-white p-4 rounded-xl shadow">
<p class="text-sm text-gray-500">Siswa: {{ $a->user->name }} • Soal #{{ $a->question->order }} ({{ $a->question->score }} poin maks)</p>
<p class="mt-2 font-medium">{!! nl2br(e($a->question->text)) !!}</p>
<div class="mt-3 p-3 bg-gray-50 rounded">{!! nl2br(e($a->answer_text)) !!}</div>
<form method="POST" action="{{ route('teacher.grading.grade',$a) }}" class="mt-3 flex items-center gap-2">
@csrf
<input type="number" name="score" class="border rounded p-2 w-28" placeholder="Skor" min="0" max="{{ $a->question->score }}" required>
<button class="px-3 py-2 bg-blue-600 text-white rounded">Simpan Skor</button>
</form>
</div>
@empty
<p>Tidak ada jawaban esai menunggu penilaian.</p>
@endforelse


{{ $answers->links() }}
</div>
</x-app-layout>