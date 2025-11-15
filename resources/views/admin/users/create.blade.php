@extends('layouts.spike')

@section('title','Tambah Pengguna')
@section('page_title','Tambah Pengguna')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl">
  <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
      <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('name') }}">
      @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
      <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
      <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="updateFieldsForRole(this.value)">
        <option value="">Pilih Role</option>
        <option value="siswa" {{ old('role')=='siswa' ? 'selected':'' }}>Siswa</option>
        <option value="guru" {{ old('role')=='guru' ? 'selected':'' }}>Guru</option>
        <option value="admin" {{ old('role')=='admin' ? 'selected':'' }}>Admin</option>
      </select>
      @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
      <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
      <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Kosongkan untuk auto-generate (siswa)" value="{{ old('username') }}">
      <p class="text-xs text-gray-500 mt-1">Opsional. Untuk siswa, akan digenerate otomatis jika kosong.</p>
      @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
      <input type="email" name="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Opsional" value="{{ old('email') }}">
      @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4" id="password-field" style="display:none;">
      <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500" id="password-required">*</span></label>
      <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 6 karakter">
      @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="flex gap-2">
      <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium">Batal</a>
      <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-700 text-white font-medium">Simpan</button>
    </div>
  </form>
</div>

@push('scripts')
<script>
  function updateFieldsForRole(role) {
    const passwordField = document.getElementById('password-field');
    const passwordRequired = document.getElementById('password-required');
    const passwordInput = document.getElementById('password');
    
    if (role === 'siswa') {
      passwordField.style.display = 'none';
      passwordRequired.textContent = '';
      passwordInput.removeAttribute('required');
    } else if (role === 'guru' || role === 'admin') {
      passwordField.style.display = 'block';
      passwordRequired.textContent = '*';
      passwordInput.setAttribute('required', 'required');
    }
  }
  
  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    if (roleSelect.value) {
      updateFieldsForRole(roleSelect.value);
    }
  });
</script>
@endpush
@endsection
