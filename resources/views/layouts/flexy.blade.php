<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','CBT Admin')</title>

  {{-- Flexy assets (sudah kamu salin ke public/vendor/flexy) --}}
  <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/css/style.min.css') }}">

  {{-- TinyMCE config (optional). Configure via config/services.php using the
       TINYMCE_LICENSE and TINYMCE_BASE_URL environment variables. --}}
  @php $tm = config('services.tinymce', ['license'=>'','base_url'=>'/vendor/tinymce']); @endphp
  @if(!empty($tm['license']))
    <script>window.tinymceLicenseKey = "{{ $tm['license'] }}";</script>
  @endif
  @if(!empty($tm['base_url']))
    <script>window.tinymceBaseUrl = "{{ $tm['base_url'] }}";</script>
  @endif

  @vite(['resources/js/app.js']) {{-- Vite umum proyekmu --}}
  @stack('styles')
</head>
<body>
  <div id="main-wrapper" class="d-flex">
    @includeIf('partials.flexy.sidebar')
    <div class="page-wrapper w-100">
      @includeIf('partials.flexy.topbar')
      <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <h4 class="mb-0">@yield('page_title','Dashboard')</h4>
            <div class="small text-secondary">@yield('breadcrumb')</div>
          </div>
          @yield('page_actions')
        </div>

  @includeWhen(session('success'),'partials.flexy.alert-success', ['msg'=>session('success')])
  {{-- Do not show validation errors on the public dashboard route; skip for 'dashboard' name to avoid stale messages --}}
  @php $currentRoute = \Request::route() ? \Request::route()->getName() : null; @endphp
  @if($currentRoute !== 'dashboard')
    @includeWhen($errors->any(),'partials.flexy.alert-errors')
  @endif

        @yield('content')
      </div>
      @includeIf('partials.flexy.footer')
    </div>
  </div>

  <script src="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('vendor/flexy/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('vendor/flexy/assets/js/app.min.js') }}"></script>
  @stack('scripts')
</body>
</html>
