<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl">CBT Admin (Spike)</h2>
  </x-slot>

  {{-- Asset public dari template (gambar, favicon, dll) --}}
  {{-- Pindahkan semua file public Spike ke resources/spike-public dan publish ke /public via vite atau manual copy --}}
  {{-- Untuk cepat: copy manual ke public/spike/* lalu pakai asset('spike/...') --}}

  <div id="spike-app" class="min-h-[60vh]">
    <!-- Vue SPA akan mount di sini -->
  </div>

  @vite(['resources/js/spike/main.ts'])  {{-- entry SPA --}}
</x-app-layout>
