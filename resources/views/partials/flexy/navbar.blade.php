<header class="bg-white border-bottom">
  <div class="container-fluid">
    <div class="d-flex align-items-center" style="height: 56px;">
      {{-- Toggle sidebar (mobile) --}}
      <button class="btn d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        ☰
      </button>

      <div class="flex-grow-1">
        <span class="fw-semibold">@yield('page_title','Dashboard')</span>
      </div>

      <div class="d-flex align-items-center gap-2">
        {{-- User panel (avatar -> dropdown with Profile & Logout) --}}
        <div class="dropdown">
          <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center" type="button" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://api.dicebear.com/8.x/initials/svg?seed={{ urlencode(auth()->user()->name ?? 'U') }}" alt="avatar" class="rounded-circle me-2" width="32" height="32">
            <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Pengguna' }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuBtn">
            <li class="px-3 py-2">
              <div class="fw-medium">{{ auth()->user()->name ?? 'Pengguna' }}</div>
              <div class="text-muted small">{{ auth()->user()->email ?? '' }}</div>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="ti ti-user me-2"></i> Profil
              </a>
            </li>
            <li>
              <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                  <i class="ti ti-logout me-2"></i> Keluar
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</header>
