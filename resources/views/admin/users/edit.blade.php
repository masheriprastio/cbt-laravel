@extends('layouts.spike')

@section('title','Edit Pengguna')
@section('page_title','Edit Pengguna')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
  <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    <div class="mb-4">
      <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
      <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $user->name }}" required>
    </div>
    <div class="mb-4">
      <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
      <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <option value="siswa" {{ $user->role==='siswa' ? 'selected':'' }}>Siswa</option>
        <option value="guru" {{ $user->role==='guru' ? 'selected':'' }}>Guru</option>
        <option value="admin" {{ $user->role==='admin' ? 'selected':'' }}>Admin</option>
      </select>
    </div>
    <div class="mb-4">
      <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
      <input type="text" name="username" id="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $user->username }}">
    </div>
    <div class="mb-4">
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $user->email }}">
    </div>
    <div>
      <a href="{{ route('admin.users.index') }}" class="text-sm py-2 px-4 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300">Batal</a>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
    </div>
  </form>
</div>
@endsection
