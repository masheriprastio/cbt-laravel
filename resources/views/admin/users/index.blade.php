@extends('layouts.flexy')

@section('title','Manajemen Pengguna')
@section('page_title','Manajemen Pengguna')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>
    <table class="table table-striped">
      <thead>
        <tr><th>ID</th><th>Nama</th><th>Email</th><th>Username</th><th>Role</th><th>Kelas</th><th>Ruang</th><th></th></tr>
      </thead>
      <tbody>
      @foreach($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->username }}</td>
          <td>{{ $u->role }}</td>
          <td>-</td>
          <td>
            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline" onsubmit="return confirm('Hapus pengguna ini?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Hapus</button>
            </form>
            <form method="POST" action="{{ route('admin.users.reset', $u) }}" style="display:inline" onsubmit="return confirm('Reset password pengguna ini?');">
              @csrf
              <button class="btn btn-sm btn-outline-warning">Reset Password</button>
            </form>
            <a href="{{ route('admin.users.print.form', $u) }}" class="btn btn-sm btn-outline-secondary">Cetak Kartu</a>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    {{ $users->links() }}
  </div>
</div>
@endsection
