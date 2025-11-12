{{-- resources/views/layouts/flexy-blank.blade.php --}}
<!doctype html>
<html lang="id" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Masuk')</title>

    <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flexy/assets/css/styles.min.css') }}">
    @stack('head')
  </head>
  <body class="bg-body-tertiary">

    <main class="min-vh-100 d-flex align-items-center">
      <div class="container">
        @yield('content')

        {{-- Development diagnostics: show bootstrap / tinymce status when APP_DEBUG=true --}}
        @includeIf('partials.flexy.diagnostics')
      </div>
    </main>

    <script src="{{ asset('vendor/flexy/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/flexy/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/flexy/assets/js/app.min.js') }}"></script>
    @stack('scripts')
  </body>
</html>
