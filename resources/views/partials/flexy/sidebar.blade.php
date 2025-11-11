@php
  $role = auth()->user()->role ?? null;
  $isGuru  = $role === 'guru';
  $isSiswa = $role === 'siswa';
@endphp

<nav class="sidebar bg-white border-end" id="sidebar">
  <div class="px-3 py-3 border-bottom d-flex align-items-center gap-2">
    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
      <img src="{{ asset('vendor/flexy/assets/images/logos/logo.svg') }}" alt="logo" width="28" height="28"
           onerror="this.replaceWith(document.createElement('span'));"
           class="me-2">
      <span class="fw-semibold">CBT Admin</span>
    </a>
    <button class="btn btn-sm ms-auto d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
      ☰
    </button>
  </div>

  <div class="collapse d-md-block" id="sidebarMenu">
    <ul class="nav flex-column py-2">

      <li class="nav-item">
        <a class="nav-link d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}"
           href="{{ route('dashboard') }}">
          <i class="ti ti-layout-dashboard me-2"></i><span>Dashboard</span>
        </a>
      </li>

      @if($isGuru)
  <li class="nav-item mt-2 px-3 text-uppercase small text-secondary">Guru</li>

  <li class="nav-item">
    <a class="nav-link d-flex align-items-center {{ request()->routeIs('teacher.tests.*') ? 'active fw-semibold' : '' }}"
       href="{{ route('teacher.tests.index') }}">
      <i class="ti ti-notebook me-2"></i><span>Ujian</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link d-flex align-items-center {{ request()->routeIs('teacher.tests.create') ? 'active fw-semibold' : '' }}"
       href="{{ route('teacher.tests.create') }}">
      <i class="ti ti-square-plus me-2"></i><span>Buat Ujian</span>
    </a>
  </li>

  {{-- ITEM BARU: Tambah Soal (aktif saat select/create soal) --}}
  <li class="nav-item">
    <a class="nav-link d-flex align-items-center
              {{ request()->routeIs('teacher.questions.select') || request()->routeIs('teacher.questions.create') ? 'active fw-semibold' : '' }}"
       href="{{ route('teacher.questions.select') }}">
      <i class="ti ti-pencil-plus me-2"></i><span>Tambah Soal</span>
    </a>
  </li>
@endif


      @if($isSiswa)
        <li class="nav-item mt-2 px-3 text-uppercase small text-secondary">Siswa</li>
        <li class="nav-item">
          <a class="nav-link d-flex align-items-center {{ request()->routeIs('student.dashboard') ? 'active fw-semibold' : '' }}"
             href="{{ route('student.dashboard') }}">
            <i class="ti ti-user me-2"></i><span>Dashboard Siswa</span>
          </a>
        </li>
      @endif

      <li class="nav-item mt-2">
        <a class="nav-link d-flex align-items-center" href="{{ route('profile.edit') }}">
          <i class="ti ti-settings me-2"></i><span>Profil</span>
        </a>
      </li>

    </ul>
  </div>

  <div class="mt-auto border-top p-3 small">
    <div class="d-flex align-items-center gap-2">
      <img src="https://api.dicebear.com/8.x/initials/svg?seed={{ urlencode(auth()->user()->name ?? 'U') }}"
           class="rounded-circle" width="28" height="28" alt="me">
      <div class="flex-grow-1">
        <div class="fw-medium text-truncate">{{ auth()->user()->name ?? 'Pengguna' }}</div>
        <div class="text-secondary text-truncate">{{ auth()->user()->email ?? '' }}</div>
      </div>
    </div>
  </div>
</nav>

<style>
  /* ukuran sidebar ala Flexy */
  #sidebar { width: 260px; min-height: 100vh; }
  @media (max-width: 767.98px) { #sidebar { position: fixed; z-index: 1040; } }
  .nav-link.active { background-color: var(--bs-gray-100); border-radius: .5rem; }
</style>
