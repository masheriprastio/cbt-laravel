<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','CBT Admin')</title>

  {{-- Flexy Bootstrap Lite assets (HARUS ada di public/vendor/flexy) --}}
  <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/css/styles.min.css') }}">

  {{-- Vite app (kalau ada) --}}
  @vite(['resources/js/app.js'])
  @stack('styles')
</head>
<body>
  {{-- ==== Wajib: id#main-wrapper + data-* agar sidebarmenu.js bekerja benar ==== --}}
  <div id="main-wrapper"
       data-layout="vertical"
       data-sidebartype="full"             {{-- full | mini-sidebar | iconbar --}}
       data-sidebar-position="fixed"
       data-header-position="fixed"
       data-boxed-layout="full">

    {{-- Sidebar --}}
    @includeIf('partials.flexy.sidebar')

    {{-- Page wrapper --}}
    <div class="page-wrapper">
      {{-- Topbar --}}
      @includeIf('partials.flexy.topbar')

      {{-- Body wrapper (required by Flexy CSS for correct spacing) --}}
      <div class="body-wrapper">

        {{-- Content --}}
        <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <h4 class="mb-0">@yield('page_title','Dashboard')</h4>
            <div class="small text-secondary">@yield('breadcrumb')</div>
          </div>
          @yield('page_actions')
        </div>

        @includeWhen(session('success'),'partials.flexy.alert-success', ['msg'=>session('success')])
        @includeWhen($errors->any(),'partials.flexy.alert-errors')

        @yield('content')
      </div>

        </div>

      </div>

      @includeIf('partials.flexy.footer')
    </div>
  </div>

  {{-- JS Flexy (urutan penting) --}}
  <script src="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/flexy/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('vendor/flexy/assets/js/app.min.js') }}"></script>
  @stack('scripts')
</body>
</html>
