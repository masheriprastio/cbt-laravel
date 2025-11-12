<!doctype html>
<html lang="id" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Dashboard')</title>

    {{-- Flexy CSS (Bootstrap + Theme) --}}
    <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/css/styles.min.css') }}">

    {{-- (Opsional) ikon tabler bawaan demo --}}
    {{-- <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/css/icons/tabler-icons.min.css') }}"> --}}

    @stack('head')
    {{-- ... --}}
  @stack('styles')
  </head>
  <body class="bg-body-tertiary">

    <div class="d-flex">
      {{-- Sidebar kiri --}}
      @include('partials.flexy.sidebar')

      <div class="flex-grow-1 d-flex flex-column min-vh-100">
        {{-- Topbar --}}
        @include('partials.flexy.navbar')

        {{-- Konten --}}
        <main class="flex-grow-1">
          <div class="container-fluid py-4">
            @hasSection('page_title')
              <div class="mb-3">
                <h1 class="h4 fw-semibold mb-0">@yield('page_title')</h1>
                @hasSection('breadcrumb')
                  <div class="small text-secondary mt-1">@yield('breadcrumb')</div>
                @endif
              </div>
            @endif

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
          </div>
        </main>

        {{-- Footer --}}
        @include('partials.flexy.footer')
      </div>
    </div>

    {{-- Flexy JS (jQuery + Bootstrap + Theme scripts) --}}
    <script src="{{ asset('vendor/flexy/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/flexy/assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('vendor/flexy/assets/js/app.min.js') }}"></script>
    {{-- <script src="{{ asset('vendor/flexy/assets/js/dashboard.js') }}"></script> --}}

    @vite(['resources/js/tinymce-init.js'])
    @stack('scripts')
  </body>
</html>
