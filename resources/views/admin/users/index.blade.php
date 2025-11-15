@extends('layouts.spike')

@section('title','Manajemen Pengguna')
@section('page_title','Manajemen Pengguna')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
  <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Pengguna</a>
  <table class="w-full whitespace-no-wrap">
    <thead>
      <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
        <th class="px-4 py-3">ID</th>
        <th class="px-4 py-3">Nama</th>
        <th class="px-4 py-3">Email</th>
        <th class="px-4 py-3">Username</th>
        <th class="px-4 py-3">Role</th>
        <th class="px-4 py-3">Kelas</th>
        <th class="px-4 py-3">Ruang</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y">
    @foreach($users as $u)
      <tr class="text-gray-700">
        <td class="px-4 py-3">{{ $u->id }}</td>
        <td class="px-4 py-3">{{ $u->name }}</td>
        <td class="px-4 py-3">{{ $u->email }}</td>
        <td class="px-4 py-3">{{ $u->username }}</td>
        <td class="px-4 py-3">{{ $u->role }}</td>
        <td class="px-4 py-3">-</td>
        <td class="px-4 py-3">
          <a href="{{ route('admin.users.edit', $u) }}" class="text-sm py-1 px-2 rounded-md bg-blue-100 text-blue-800 hover:bg-blue-200">Edit</a>
          <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline" onsubmit="return confirm('Hapus pengguna ini?');">
            @csrf
            @method('DELETE')
            <button class="text-sm py-1 px-2 rounded-md bg-red-100 text-red-800 hover:bg-red-200">Hapus</button>
          </form>
          <form method="POST" action="{{ route('admin.users.reset', $u) }}" style="display:inline" onsubmit="return confirm('Reset password pengguna ini?');">
            @csrf
            <button class="text-sm py-1 px-2 rounded-md bg-yellow-100 text-yellow-800 hover:bg-yellow-200">Reset Password</button>
          </form>
          <a href="{{ route('admin.users.print.form', $u) }}" class="text-sm py-1 px-2 rounded-md bg-gray-100 text-gray-800 hover:bg-gray-200">Cetak Kartu</a>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
  <div class="mt-4">
    {{ $users->links() }}
  </div>
</div>
@endsection
