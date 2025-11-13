@php
  use Illuminate\Support\Str;
  $user = auth()->user();
  $initials = $user ? collect(explode(' ', $user->name))->map(fn($p)=>Str::upper(Str::substr($p,0,1)))->take(2)->implode('') : 'GU';
@endphp

<nav class="navbar navbar-expand-lg bg-white border-bottom px-3" style="min-height:64px">
  <div class="container-fluid px-0">
    {{-- kiri: logo/crumb kecil (opsional kosong, Flexy punya sidebar sendiri) --}}
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center gap-2 mb-0">
        <img src="{{ asset('vendor/flexy/assets/images/logos/favicon.png') }}" alt="logo" width="24" height="24">
        <span class="fw-semibold d-none d-sm-inline">{{ config('app.name','CBT Admin') }}</span>
      </a>
    </div>

    {{-- kanan: profil --}}
    <ul class="navbar-nav ms-auto align-items-center">
      @auth
        <li class="nav-item dropdown">
          <a class="nav-link d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center"
                  style="width:32px;height:32px;font-size:.85rem">{{ $initials }}</span>
            <span class="d-none d-md-inline">{{ $user->name }}</span>
            <i class="ti ti-chevron-down small text-muted"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            {{-- Profil (Breeze) --}}
            @if(Route::has('profile.edit'))
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                  <i class="ti ti-user"></i><span>Profil</span>
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
            @endif

            {{-- Logout (Breeze: POST /logout) --}}
            <li>
              <form method="POST" action="{{ route('logout') }}" id="topbar-logout-form">
                @csrf
                <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                  <i class="ti ti-logout"></i><span>Keluar</span>
                </button>
              </form>
            </li>
          </ul>
        </li>
      @else
        {{-- Jika belum login (opsional) --}}
        @if(Route::has('login'))
          <li class="nav-item"><a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">Masuk</a></li>
        @endif
      @endauth
    </ul>
  </div>
</nav>
