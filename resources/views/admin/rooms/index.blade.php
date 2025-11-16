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
              <button data-room-id="{{ $room->id }}" class="view-participants-btn text-green-500 hover:text-green-700 font-medium">Lihat Peserta</button>
              <span class="text-gray-300 mx-1">|</span>
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

<!-- Modal -->
<div id="participants-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              Daftar Peserta
            </h3>
            <div class="mt-2">
              <ul id="participants-list" class="list-disc list-inside">
                <!-- Participants will be loaded here -->
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="button" id="close-modal-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
          Tutup
        </button>
      </div>
    </div>
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

      $('.view-participants-btn').on('click', function() {
        var roomId = $(this).data('room-id');
        var modal = $('#participants-modal');
        var list = $('#participants-list');

        list.html('<li>Loading...</li>');
        modal.removeClass('hidden');

        $.ajax({
          url: '/admin/rooms/' + roomId + '/participants',
          method: 'GET',
          success: function(data) {
            list.empty();
            if (data.length > 0) {
              $.each(data, function(index, participant) {
                list.append('<li>' + participant.name + '</li>');
              });
            } else {
              list.append('<li>Tidak ada peserta di ruangan ini.</li>');
            }
          },
          error: function() {
            list.html('<li>Gagal memuat data.</li>');
          }
        });
      });

      $('#close-modal-btn').on('click', function() {
        $('#participants-modal').addClass('hidden');
      });
    });
  </script>
@endpush
