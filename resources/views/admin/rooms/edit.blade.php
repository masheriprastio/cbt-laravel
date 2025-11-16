@extends('layouts.spike')

@section('title', 'Edit Ruangan')
@section('page_title', 'Edit Ruangan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
  <form method="POST" action="{{ route('admin.rooms.update', $room->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Ruangan <span class="text-red-500">*</span></label>
      <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('name', $room->name) }}">
      @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa</label>
      <div class="border border-gray-300 rounded-lg p-4 max-h-72 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          @forelse($students as $student)
            <label class="flex items-center space-x-3">
              <input type="checkbox" name="students[]" value="{{ $student->id }}" class="form-checkbox h-5 w-5 text-blue-600"
                {{ in_array($student->id, old('students', $roomStudentIds)) ? 'checked' : '' }}>
              <span class="text-gray-900">{{ $student->name }}</span>
            </label>
          @empty
            <p class="text-gray-500 col-span-full">Tidak ada siswa yang tersedia.</p>
          @endforelse
        </div>
      </div>
      @error('students') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="flex gap-2">
      <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium">Batal</a>
      <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-700 text-white font-medium">Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection
