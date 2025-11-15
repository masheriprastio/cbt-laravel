@extends('layouts.spike')

@section('title','Cetak Kartu Ujian')
@section('page_title','Cetak Kartu Ujian')

@section('content')
<div class="max-w-md mx-auto">
  @if(!isset($passwordPlain) || !$passwordPlain)
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h3 class="text-lg font-semibold mb-4">Atur Password Sebelum Cetak</h3>
      <p class="text-gray-600 mb-4">Password akan ditampilkan di kartu yang dicetak. Kosongkan jika tidak ingin mengubah password.</p>
      
      <form method="POST" action="{{ route('admin.users.print.confirm', $user) }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru (opsional)</label>
          <input type="text" name="password" class="w-full text-sm border border-gray-300 rounded-lg p-2" placeholder="Biarkan kosong jika tidak ingin mengubah">
        </div>
        <div class="flex gap-2">
          <a href="{{ route('admin.users.index') }}" class="flex-1 text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
            Batal
          </a>
          <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tampilkan Kartu
          </button>
        </div>
      </form>
    </div>
  @else
    <div class="print-card-wrapper">
      <div class="card-content">
        <div class="card-header">
          <h3>KARTU LOGIN SISWA</h3>
        </div>
        <div class="card-body">
          <div class="card-field">
            <span class="label">Nama:</span>
            <span class="value">{{ $user->name }}</span>
          </div>
          <div class="card-field">
            <span class="label">Username:</span>
            <span class="value font-mono">{{ $user->username }}</span>
          </div>
          <div class="card-field">
            <span class="label">Password:</span>
            <span class="value font-mono">{{ $passwordPlain }}</span>
          </div>
          <div class="card-divider"></div>
          <p class="text-xs text-gray-600">Simpan kartu ini dan jangan bagikan dengan orang lain.</p>
        </div>
        <div class="card-footer">
          <p class="text-xs">{{ now()->format('d M Y H:i') }}</p>
        </div>
      </div>
    </div>

    <div class="mt-6 text-center no-print space-y-2">
      <button onclick="window.print()" class="block w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
        Cetak Kartu
      </button>
      <a href="{{ route('admin.users.index') }}" class="block w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
        Kembali
      </a>
    </div>
  @endif
</div>

@push('head')
<style>
  @media print {
    body { margin: 0; padding: 0; background: white; }
    #spike-shell { display: none; }
    main { all: unset; display: block; }
    section { all: unset; display: block; }
    .no-print { display: none !important; }
  }

  .print-card-wrapper {
    padding: 2rem 0;
  }

  .card-content {
    border: 2px solid #333;
    border-radius: 8px;
    padding: 2rem;
    background: white;
    max-width: 400px;
    margin: 0 auto;
  }

  .card-header {
    text-align: center;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #333;
    padding-bottom: 1rem;
  }

  .card-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 1px;
  }

  .card-body {
    margin-bottom: 1.5rem;
  }

  .card-field {
    margin-bottom: 1.25rem;
  }

  .card-field .label {
    font-size: 12px;
    font-weight: bold;
    color: #666;
    display: block;
    margin-bottom: 0.5rem;
  }

  .card-field .value {
    font-size: 14px;
    font-weight: 600;
    color: #000;
    padding: 0.75rem;
    background: #f5f5f5;
    border-radius: 4px;
    border-left: 3px solid #3b82f6;
    display: block;
  }

  .card-divider {
    border-top: 1px dashed #999;
    margin: 1rem 0;
  }

  .card-footer {
    text-align: right;
    padding-top: 1rem;
    border-top: 1px solid #ddd;
  }

  .card-footer p {
    margin: 0;
    font-size: 11px;
    color: #666;
  }

  .font-mono {
    font-family: 'Courier New', monospace !important;
    letter-spacing: 0.5px;
  }

  @media print {
    .print-card-wrapper { padding: 0; }
    .card-content { max-width: none; border-radius: 0; }
    body, html { margin: 0; padding: 0; }
  }
</style>
@endpush
@endsection
