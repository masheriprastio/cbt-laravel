@php
  $user = auth()->user();
@endphp

<aside class="left-sidebar" data-sidebarbg="skin6">
  {{-- Scroll sidebar --}}
  <div class="scroll-sidebar" data-simplebar>
    {{-- Brand --}}
    <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-2">
      <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <img src="{{ asset('vendor/flexy/assets/images/logos/favicon.png') }}" alt="logo" width="22" height="22">
        <span class="fw-semibold">CBT Admin</span>
      </a>
      {{-- toggler (opsional) --}}
      <a class="nav-toggler d-block d-xl-none" href="javascript:void(0)"><i class="ti ti-x"></i></a>
    </div>

    {{-- Menu --}}
    <nav class="sidebar-nav">
      <ul id="sidebarnav">
        <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'selected' : '' }}">
          <a class="sidebar-link" href="{{ route('dashboard') }}">
            <i class="ti ti-home-2 me-2"></i><span>Dashboard</span>
          </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('teacher.tests.*') ? 'selected' : '' }}">
          <a class="sidebar-link" href="{{ route('teacher.tests.index') }}">
            <i class="ti ti-list-details me-2"></i><span>Ujian</span>
          </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('teacher.questions.*') ? 'selected' : '' }}">
          <a class="sidebar-link" href="{{ route('teacher.questions.select', ['hide_empty'=>1]) }}">
            <i class="ti ti-pencil-plus me-2"></i><span>Tambah Soal</span>
          </a>
        </li>

        <li class="sidebar-item {{ request()->routeIs('profile.*') ? 'selected' : '' }}">
          <a class="sidebar-link" href="{{ route('profile.edit') }}">
            <i class="ti ti-user me-2"></i><span>Profil</span>
          </a>
        </li>
      </ul>
    </nav>

    {{-- Card user (opsional) --}}
    @auth
    <div class="px-3 py-3 border-top small">
      <div class="fw-semibold">{{ $user->name }}</div>
      <div class="text-muted">{{ $user->email }}</div>
    </div>
    @endauth
  </div>
</aside>
