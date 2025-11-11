<header class="h-16 border-b bg-white flex items-center px-4 md:px-6 gap-3">
  {{-- Tombol toggle sidebar (mobile) – opsional, jika kamu menambahkan JS --}}
  <button class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-lg hover:bg-slate-100"
          type="button" aria-label="Toggle sidebar">
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
  </button>

  {{-- Breadcrumb/Title sederhana --}}
  <div class="flex-1 min-w-0">
    <div class="text-base font-semibold truncate">
      @yield('page_title','Dashboard')
    </div>
    <div class="text-xs text-slate-500 truncate">
      {{-- contoh breadcrumb ringan --}}
      <span>Home</span>
      @hasSection('page_title')
        <span class="mx-1">/</span>
        <span class="text-slate-700">@yield('page_title')</span>
      @endif
    </div>
  </div>

  {{-- Search kecil (opsional) --}}
  <form action="#" method="GET" class="hidden md:block">
    <div class="relative">
      <input type="text" name="q" placeholder="Cari..."
             class="h-10 w-64 rounded-lg border px-3 pr-8 focus:outline-none focus:ring"
             autocomplete="off">
      <span class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
      </span>
    </div>
  </form>

  {{-- User menu sederhana --}}
  <div class="ml-3">
    <div class="flex items-center gap-3">
      <a href="{{ route('profile.edit') }}"
         class="hidden md:flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100">
        <img src="https://api.dicebear.com/8.x/initials/svg?seed={{ urlencode(auth()->user()->name ?? 'U') }}"
             class="h-8 w-8 rounded-full" alt="avatar">
        <div class="min-w-0 text-left">
          <div class="text-sm font-medium leading-tight truncate">{{ auth()->user()->name ?? 'Pengguna' }}</div>
          <div class="text-xs text-slate-500 leading-tight truncate">{{ auth()->user()->email ?? '' }}</div>
        </div>
      </a>

      {{-- Logout --}}
      {{-- resources/views/partials/flexy/navbar.blade.php --}}
<form action="{{ route('logout') }}" method="POST" class="mb-0">
  @csrf
  <button class="btn btn-sm btn-outline-danger">Keluar</button>
</form>

    </div>
  </div>
</header>
