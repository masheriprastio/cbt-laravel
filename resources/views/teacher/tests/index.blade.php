@extends('layouts.spike')

@section('title','Daftar Ujian')
@section('page_title','Daftar Ujian')

@section('content')
  <div class="mb-4">
    <a href="{{ route('teacher.tests.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white">
      Buat Ujian
    </a>
  </div>

  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($tests as $t)
      <div class="p-4 bg-white rounded-xl shadow">
        <h4 class="font-semibold">{{ $t->title }}</h4>
        <p class="text-sm text-gray-600">Durasi: {{ $t->duration_minutes }} menit</p>
        <div class="mt-3 flex gap-2">
          <a href="{{ route('teacher.tests.show',$t) }}" class="px-3 py-1.5 bg-gray-800 text-white rounded">Kelola</a>
          <a href="{{ route('teacher.tests.edit',$t) }}" class="px-3 py-1.5 bg-amber-600 text-white rounded">Edit</a>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-4">{{ $tests->links() }}</div>
@endsection
