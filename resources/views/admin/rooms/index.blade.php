@extends('layouts.spike')

@section('title', 'Manajemen Ruangan')

@section('page_title', 'Manajemen Ruangan')
@section('page_subtitle', 'Kelola daftar ruangan ujian.')

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      padding: 0.5em 1em;
      border: 1px solid #ddd;
      margin: 0 2px;
      border-radius: 4px;
      cursor: pointer;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      background-color: #007bff;
      color: white !important;
      border-color: #007bff;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
      cursor: not-allowed;
      opacity: 0.5;
    }
  </style>
@endpush

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
  <div class="flex justify-end mb-4">
    <a href="{{ route('admin.rooms.create') }}" class="px-4 py-2 rounded-lg bg-blue-500 hover:bg-blue-700 text-white font-medium">
      Tambah Ruangan
    </a>
  </div>

  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
      <strong class="font-bold">Sukses!</strong>
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
  @endif

  <div class="overflow-x-auto">
    <table id="rooms-table" class="min-w-full bg-white">
      <thead class="bg-gray-200">
        <tr>
          <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">#</th>
          <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">Nama Ruangan</th>
          <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">Jumlah Siswa</th>
          <th class="py-2 px-4 border-b text-left text-sm font-semibold text-gray-700">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rooms as $room)
          <tr class="hover:bg-gray-50">
            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $loop->iteration }}</td>
            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $room->name }}</td>
            <td class="py-2 px-4 border-b text-sm text-gray-700">{{ $room->users_count }}</td>
            <td class="py-2 px-4 border-b text-sm text-gray-700">
              <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-blue-500 hover:text-blue-700 font-medium">Edit</a>
              <span class="text-gray-300 mx-1">|</span>
              <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="py-4 px-4 border-b text-center text-gray-500">
              Belum ada ruangan yang dibuat.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#rooms-table').DataTable({
        "pageLength": 10,
        "language": {
          "search": "Cari:",
          "lengthMenu": "Tampilkan _MENU_ entri per halaman",
          "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
          "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
          "infoFiltered": "(disaring dari _MAX_ total entri)",
          "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Berikutnya",
            "previous": "Sebelumnya"
          },
          "zeroRecords": "Tidak ada data yang cocok ditemukan",
          "emptyTable": "Tidak ada data yang tersedia di tabel"
        }
      });
    });
  </script>
@endpush
