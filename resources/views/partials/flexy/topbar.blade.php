@php
  use Illuminate\Support\Str;
  $user = auth()->user();
  $initials = $user ? collect(explode(' ', $user->name))->map(fn($p)=>Str::upper(Str::substr($p,0,1)))->take(2)->implode('') : 'GU';
@endphp

<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
      {{-- toggler for small screens --}}
      <a class="nav-link sidebartoggler d-block d-xl-none" href="javascript:void(0)"><i class="ti ti-menu-2"></i></a>

      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item dropdown">
          <a class="nav-link d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
            <span class="avatar rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center"
                  style="width:32px;height:32px;font-size:.85rem">{{ $initials }}</span>
            <span class="d-none d-md-inline">{{ $user?->name }}</span>
            <i class="ti ti-chevron-down small text-muted"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="ti ti-user me-2"></i>Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="dropdown-item" type="submit"><i class="ti ti-logout me-2"></i>Keluar</button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
