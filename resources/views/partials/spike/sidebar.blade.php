@php
  // Helper state aktif
  $isGuru    = auth()->check() && auth()->user()->role === 'guru';
  $isSiswa   = auth()->check() && auth()->user()->role === 'siswa';
  $prefix    = $isGuru ? 'teacher' : ($isSiswa ? 'student' : null);
@endphp

<aside
  class="hidden md:flex md:flex-col md:w-64 md:shrink-0 border-r bg-white"
  style="--sbw:16rem"
>
  <div class="h-16 flex items-center px-5 border-b">
    <a href="{{ $prefix ? url("/$prefix/dashboard") : url('/dashboard') }}"
       class="flex items-center gap-2 font-semibold">
      {{-- Logo/Brand Spike (gunakan asset Spike jika ada) --}}
      <img src="{{ asset('spike/assets/logo.svg') }}" alt="Logo" class="h-7 w-7" onerror="this.style.display='none'">
      <span>CBT Admin</span>
    </a>
  </div>

  <nav class="p-4 flex-1 overflow-y-auto">
    <div class="text-xs uppercase text-slate-500 px-3 mb-2">Menu</div>
    <ul class="space-y-1">
      <li>
        <a href="{{ $prefix ? route($prefix.'.dashboard') : route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100
                  {{ request()->routeIs(($prefix? $prefix.'.' : '').'dashboard') ? 'bg-slate-100 font-medium' : '' }}">
          {{-- Ikon dashboard --}}
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 13h8V3H3v10zM13 21h8v-8h-8v8zM13 3h8v6h-8V3zM3 21h8v-6H3v6z"/></svg>
          <span>Dashboard</span>
        </a>
      </li>

      @if($isGuru)
        <li class="mt-2">
          <div class="text-xs uppercase text-slate-500 px-3 mb-1">Guru</div>
          <a href="{{ route('teacher.tests.index') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100
                    {{ request()->routeIs('teacher.tests.*') ? 'bg-slate-100 font-medium' : '' }}">
            {{-- Ikon tests --}}
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16v16H4z"/><path d="M4 9h16M9 4v16"/></svg>
            <span>Ujian (Tests)</span>
          </a>
          <a href="{{ route('teacher.tests.create') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100
                    {{ request()->routeIs('teacher.tests.create') ? 'bg-slate-100 font-medium' : '' }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14"/></svg>
            <span>Buat Ujian</span>
          </a>
          {{-- Tambahkan tautan lain bila perlu: penilaian, rekap, bank soal --}}
          <a href="{{ route('teacher.tests.index') }}#grading"
             class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 12h14M5 6h14M5 18h14"/></svg>
            <span>Penilaian</span>
          </a>
        </li>
      @endif

      @if($isSiswa)
        <li class="mt-2">
          <div class="text-xs uppercase text-slate-500 px-3 mb-1">Siswa</div>
          <a href="{{ route('student.dashboard') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100
                    {{ request()->routeIs('student.dashboard') ? 'bg-slate-100 font-medium' : '' }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="8" r="4"/><path d="M6 22c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>
            <span>Dashboard</span>
          </a>
          {{-- contoh menu ujian untuk siswa --}}
          <a href="{{ route('student.dashboard') }}#exams"
             class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16v16H4z"/><path d="M8 8h8v8H8z"/></svg>
            <span>Ujian Saya</span>
          </a>
        </li>
      @endif

      @if(auth()->user()->role === 'admin')
        <li class="mt-2">
          <div class="text-xs uppercase text-slate-500 px-3 mb-1">Admin</div>
          <a href="{{ route('admin.users.index') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100
                    {{ request()->routeIs('admin.users.*') ? 'bg-slate-100 font-medium' : '' }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span>Manajemen Pengguna</span>
          </a>
          <a href="{{ route('admin.rooms.index') }}"
             class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100
                    {{ request()->routeIs('admin.rooms.*') ? 'bg-slate-100 font-medium' : '' }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            <span>Manajemen Ruangan</span>
          </a>
        </li>
      @endif
    </ul>
  </nav>

  <div class="mt-auto border-t p-4">
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-100">
      <img src="https://api.dicebear.com/8.x/initials/svg?seed={{ urlencode(auth()->user()->name ?? 'U') }}"
           class="h-6 w-6 rounded-full" alt="avatar">
      <div class="min-w-0">
        <div class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Pengguna' }}</div>
        <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</div>
      </div>
    </a>
  </div>
</aside>
