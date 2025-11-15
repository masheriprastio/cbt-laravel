@extends('layouts.spike')

@section('title','Cetak Kartu Login Semua Siswa')
@section('page_title','Cetak Kartu Login Semua Siswa')

@section('content')
<div class="print-container">
  @forelse($users as $user)
    <div class="print-card">
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
          <div class="card-divider"></div>
          <p class="text-xs text-gray-600 mb-2">Setiap peserta harus menyimpan username untuk login ujian. Password akan diberikan oleh pengawas ujian.</p>
        </div>
        <div class="card-footer">
          <p class="text-xs">{{ $user->created_at?->format('d M Y H:i') }}</p>
        </div>
      </div>
    </div>
  @empty
    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 text-yellow-800">
      Tidak ada data pengguna yang sesuai dengan filter.
    </div>
  @endforelse
</div>

<div class="mt-6 text-center no-print">
  <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
    Cetak
  </button>
  <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded ml-2">
    Kembali
  </a>
</div>

@push('head')
<style>
  @media print {
    body { margin: 0; padding: 0; background: white; }
    .spike-shell { display: none; }
    main { all: unset; display: block; }
    section { all: unset; display: block; }
    .no-print { display: none !important; }
  }

  .print-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    padding: 2rem;
  }

  .print-card {
    page-break-inside: avoid;
    break-inside: avoid;
  }

  .card-content {
    border: 2px solid #333;
    border-radius: 8px;
    padding: 1.5rem;
    background: white;
    min-height: 200px;
    display: flex;
    flex-direction: column;
  }

  .card-header {
    text-align: center;
    margin-bottom: 1rem;
    border-bottom: 2px solid #333;
    padding-bottom: 0.75rem;
  }

  .card-header h3 {
    margin: 0;
    font-size: 14px;
    font-weight: bold;
    letter-spacing: 1px;
  }

  .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
  }

  .card-field {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
  }

  .card-field .label {
    font-size: 11px;
    font-weight: bold;
    color: #666;
    margin-bottom: 0.25rem;
  }

  .card-field .value {
    font-size: 13px;
    font-weight: 600;
    color: #000;
    padding: 0.5rem;
    background: #f5f5f5;
    border-radius: 4px;
    border-left: 3px solid #3b82f6;
  }

  .card-divider {
    border-top: 1px dashed #999;
    margin: 0.75rem 0;
  }

  .card-footer {
    text-align: right;
    padding-top: 0.75rem;
    border-top: 1px solid #ddd;
  }

  .font-mono {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
  }

  @media print {
    .print-container {
      padding: 0;
      gap: 1rem;
    }
    body, html { margin: 0; padding: 0; }
  }
</style>
@endpush
@endsection
