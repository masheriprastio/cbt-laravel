@extends('layouts.spike')

@section('title','Edit Pengguna')
@section('page_title','Edit Pengguna')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl">
  <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
      <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $user->name }}" required>
      @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
      <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
      <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <option value="siswa" {{ $user->role==='siswa' ? 'selected':'' }}>Siswa</option>
        <option value="guru" {{ $user->role==='guru' ? 'selected':'' }}>Guru</option>
        <option value="admin" {{ $user->role==='admin' ? 'selected':'' }}>Admin</option>
      </select>
      @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div id="class-field" class="mb-4" style="display:none;">
      <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
      <input type="text" name="class" id="class" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('class', $user->class) }}">
      @error('class') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
      <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
      <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $user->username }}">
      @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
      <input type="text" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $user->email }}">
      @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="bg-blue-50 p-4 rounded-lg mb-4 border border-blue-200">
      <p class="text-sm text-blue-800">
        <strong>Catatan:</strong> Untuk mengubah password, gunakan fitur "Reset Password" di halaman daftar pengguna.
      </p>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium">Batal</a>
      <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-700 text-white font-medium">Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  function updateFieldsForRole(role) {
    const classField = document.getElementById('class-field');

    if (role === 'siswa') {
      classField.style.display = 'block';
    } else {
      classField.style.display = 'none';
    }
  }

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');

    // Initial check
    updateFieldsForRole(roleSelect.value);

    // Update on change
    roleSelect.addEventListener('change', function() {
      updateFieldsForRole(this.value);
    });
  });
</script>
@endpush
