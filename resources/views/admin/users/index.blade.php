@extends('layouts.spike')

@section('title','Manajemen Pengguna')
@section('page_title','Manajemen Pengguna')

@section('content')
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
  {{-- Tabs --}}
  <div class="border-b border-gray-200">
    <div class="flex gap-4 px-6 pt-6 pb-0">
      <button type="button" class="tab-btn active py-3 px-4 border-b-2 border-blue-500 text-blue-600 font-medium" data-tab="list">Daftar Pengguna</button>
      <button type="button" class="tab-btn py-3 px-4 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-900" data-tab="import">Import dari Excel</button>
      <button type="button" class="tab-btn py-3 px-4 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-900" data-tab="print">Cetak Kartu Login</button>
    </div>
  </div>

  {{-- Tab: Daftar Pengguna --}}
  <div id="list-tab" class="tab-content p-6">
    <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">+ Tambah Pengguna</a>
    <table class="w-full whitespace-no-wrap mt-4">
    <thead>
      <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
        <th class="px-4 py-3">ID</th>
        <th class="px-4 py-3">Nama</th>
        <th class="px-4 py-3">Email</th>
        <th class="px-4 py-3">Username</th>
        <th class="px-4 py-3">Role</th>
        <th class="px-4 py-3">Dibuat</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y">
    @foreach($users as $u)
      <tr class="text-gray-700 hover:bg-gray-50">
        <td class="px-4 py-3 text-sm">{{ $u->id }}</td>
        <td class="px-4 py-3 text-sm">{{ $u->name }}</td>
        <td class="px-4 py-3 text-sm">{{ $u->email ?? '-' }}</td>
        <td class="px-4 py-3 text-sm font-mono text-blue-600">{{ $u->username ?? '-' }}</td>
        <td class="px-4 py-3 text-sm"><span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ $u->role }}</span></td>
        <td class="px-4 py-3 text-sm text-gray-500">{{ $u->created_at?->format('d M Y') ?? '-' }}</td>
        <td class="px-4 py-3 text-sm flex gap-1">
          <a href="{{ route('admin.users.edit', $u) }}" class="py-1 px-2 rounded-md bg-blue-100 text-blue-800 hover:bg-blue-200 text-xs">Edit</a>
          <form method="POST" action="{{ route('admin.users.destroy', $u) }}" style="display:inline" onsubmit="return confirm('Hapus pengguna ini?');">
            @csrf
            @method('DELETE')
            <button class="py-1 px-2 rounded-md bg-red-100 text-red-800 hover:bg-red-200 text-xs">Hapus</button>
          </form>
          <form method="POST" action="{{ route('admin.users.reset', $u) }}" style="display:inline" onsubmit="return confirm('Reset password?');">
            @csrf
            <button class="py-1 px-2 rounded-md bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-xs">Reset</button>
          </form>
          <a href="{{ route('admin.users.print.form', $u) }}" class="py-1 px-2 rounded-md bg-green-100 text-green-800 hover:bg-green-200 text-xs">Cetak</a>
        </td>
      </tr>
    @endforeach
    </tbody>
    </table>
    <div class="mt-4">{{ $users->links() }}</div>
  </div>

  {{-- Tab: Import dari Excel --}}
  <div id="import-tab" class="tab-content hidden p-6">
    <div class="max-w-2xl">
      <h3 class="text-lg font-semibold mb-4">Import Data Siswa dari Excel</h3>
      <p class="text-gray-600 mb-4">Format file CSV/Excel yang diperlukan (dengan header):</p>
      <div class="bg-gray-100 p-4 rounded mb-4 text-sm font-mono overflow-x-auto">Nama,Email,Username<br>Andi Wijaya,andi@example.com,andi<br>Budi Santoso,budi@example.com,budi</div>
      <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div><label class="block text-sm font-medium text-gray-700 mb-2">File Excel (CSV)</label><input type="file" name="file" accept=".csv,.xlsx,.xls" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-2">Role untuk semua siswa</label><select name="role" class="block w-full text-sm border border-gray-300 rounded-lg p-2"><option value="siswa" selected>Siswa</option><option value="guru">Guru</option><option value="admin">Admin</option></select></div>
        <div class="flex gap-2"><button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Import File</button><button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Reset</button></div>
      </form>
      @if(session('import_message'))<div class="mt-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">{{ session('import_message') }}</div>@endif
      @if($errors->has('file'))<div class="mt-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">{{ $errors->first('file') }}</div>@endif
    </div>
  </div>

  {{-- Tab: Cetak Kartu Login --}}
  <div id="print-tab" class="tab-content hidden p-6">
    <div class="max-w-2xl">
      <h3 class="text-lg font-semibold mb-4">Cetak Kartu Login Siswa</h3>
      <form method="GET" action="{{ route('admin.users.print.all') }}" class="space-y-4">
        <div><label class="block text-sm font-medium text-gray-700 mb-2">Filter Role</label><select name="role" class="block w-full text-sm border border-gray-300 rounded-lg p-2"><option value="">Semua Role</option><option value="siswa">Siswa</option><option value="guru">Guru</option><option value="admin">Admin</option></select></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-2">Filter Dibuat Setelah</label><input type="date" name="from_date" class="block w-full text-sm border border-gray-300 rounded-lg p-2"></div>
        <div class="flex gap-2"><button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">Cetak Semua</button></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
btn.addEventListener('click', function() {
const tabId = this.dataset.tab + '-tab';
document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
document.querySelectorAll('.tab-btn').forEach(b => {b.classList.remove('border-blue-500','text-blue-600');b.classList.add('border-transparent','text-gray-600');});
document.getElementById(tabId).classList.remove('hidden');
this.classList.remove('border-transparent','text-gray-600');
this.classList.add('border-blue-500','text-blue-600');
});
});
</script>
@endpush
@endsection
