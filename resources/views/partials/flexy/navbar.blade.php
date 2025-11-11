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
        {{-- Quick link (opsional) --}}
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex">Profil</a>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="mb-0">
          @csrf
          <button class="btn btn-sm btn-outline-danger">Keluar</button>
        </form>
      </div>
    </div>
  </div>
</header>
