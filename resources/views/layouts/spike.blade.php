<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','CBT Admin')</title>

  {{-- Muat hanya asset yang ADA --}}
  @vite(['resources/js/app.js']) {{-- Breeze/Tailwind --}}
  {{-- Aktifkan baris di bawah HANYA jika style.scss sudah lengkap --}}
  @vite(['resources/js/spike/src/scss/style.scss'])

  {{-- DataTables --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">

  @stack('head')
</head>
<body class="h-full bg-slate-50 antialiased">
  <div id="spike-shell" class="min-h-screen flex">
    @include('partials.spike.sidebar')
    <div class="flex-1 min-w-0 flex flex-col">
      @include('partials.spike.navbar')
      <main class="flex-1 min-w-0">
        <section class="p-6">
          @hasSection('page_title')
            <h1 class="text-2xl font-semibold mb-4">@yield('page_title')</h1>
          @endif

          @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-green-800">
              {{ session('success') }}
            </div>
          @endif

          @yield('content')
        </section>
      </main>
    </div>
  </div>

  {{-- jQuery & DataTables --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>

  @stack('scripts')
</body>
</html>
