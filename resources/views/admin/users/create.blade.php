@extends('layouts.spike')

@section('title','Tambah Pengguna')
@section('page_title','Tambah Pengguna')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
  <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
      <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
    </div>
    <div class="mb-4">
      <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
      <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <option value="siswa">Siswa</option>
        <option value="guru">Guru</option>
        <option value="admin">Admin</option>
      </select>
    </div>
    <div class="mb-4">
      <label for="username" class="block text-sm font-medium text-gray-700">Username (opsional, akan digenerate untuk siswa jika dikosongkan)</label>
      <input type="text" name="username" id="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700">Email (opsional untuk siswa)</label>
      <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div class="mb-4">
      <label for="password" class="block text-sm font-medium text-gray-700">Password (wajib untuk guru/admin)</label>
      <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    {{-- removed class and room fields per admin request --}}
    <div>
      <a href="{{ route('admin.users.index') }}" class="text-sm py-2 px-4 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300">Batal</a>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
    </div>
  </form>
</div>
@endsection
