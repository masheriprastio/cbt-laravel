@extends('layouts.flexy')

@section('title','Monitor Ujian')

@section('content')
  <div class="container mt-4">
    <h3>Monitor Ujian</h3>
    <p class="text-muted">Daftar sesi ujian (running / finished). Guru dapat memantau pelanggaran dan siapa peserta.</p>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Ujian</th>
          <th>Peserta</th>
          <th>Dimulai</th>
          <th>Selesai</th>
          <th>Pelanggaran</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sessions as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->test ? $s->test->title : '—' }}</td>
            <td>{{ $s->user ? $s->user->name . ' (' . $s->user->email . ')' : 'Guest' }}</td>
            <td>{{ $s->started_at ? \Illuminate\Support\Carbon::parse($s->started_at)->format('Y-m-d H:i:s') : '—' }}</td>
            <td>{{ $s->finished_at ? \Illuminate\Support\Carbon::parse($s->finished_at)->format('Y-m-d H:i:s') : '—' }}</td>
            <td>{{ $s->violations }}</td>
            <td>{{ $s->status }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
