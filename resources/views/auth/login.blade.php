{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.flexy-blank')

@section('title','Masuk')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4 p-lg-5">
        <div class="d-flex align-items-center mb-4">
          <img src="{{ asset('vendor/flexy/assets/images/logos/logo.svg') }}"
               alt="logo" width="32" height="32"
               onerror="this.replaceWith(document.createElement('span'));">
          <h1 class="h5 fw-semibold ms-2 mb-0">CBT Admin</h1>
        </div>

        <h2 class="h4 fw-semibold mb-1">Masuk</h2>
        <p class="text-secondary mb-4">Silakan login untuk melanjutkan.</p>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
  <div class="d-flex justify-content-between">
    <label for="password" class="form-label">Password</label>
    @if (Route::has('password.request'))
      <a class="small text-decoration-underline" href="{{ route('password.request') }}">Lupa password?</a>
    @endif
  </div>

  <div class="input-group">
    <input id="password" type="password" name="password"
           class="form-control @error('password') is-invalid @enderror"
           required autocomplete="current-password" aria-describedby="togglePassword">
    <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Tampilkan/Sembunyikan password" aria-pressed="false">
      {{-- Eye (show) --}}
      <svg class="icon-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/>
        <circle cx="12" cy="12" r="3"/>
      </svg>
      {{-- Eye-off (hide) --}}
      <svg class="icon-off d-none" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M3 3l18 18"/>
        <path d="M10.58 10.58a2 2 0 0 0 2.84 2.84"/>
        <path d="M16.24 16.24A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a20.76 20.76 0 0 1 5.06-5.94"/>
        <path d="M9.88 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a20.76 20.76 0 0 1-3.22 4.19"/>
      </svg>
    </button>
    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const pwd = document.getElementById('password');
  const btn = document.getElementById('togglePassword');
  const iconOn  = btn.querySelector('.icon-on');   // eye
  const iconOff = btn.querySelector('.icon-off');  // eye-off

  function toggle() {
    const show = pwd.type === 'password';
    pwd.type = show ? 'text' : 'password';
    iconOn.classList.toggle('d-none', show === false);   // show eye when type=password
    iconOff.classList.toggle('d-none', show === true);
    btn.setAttribute('aria-pressed', String(show));
  }

  btn.addEventListener('click', toggle);
});
</script>
@endpush


          <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">Ingat saya</label>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Masuk</button>
          </div>
        </form>

        @if (Route::has('register'))
          <div class="text-center mt-3 small">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-decoration-underline">Daftar</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
