<nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileOpen: false }">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">

      {{-- Logo --}}
      <div class="shrink-0">
        <a href="{{ url('/') }}">
          <img src="{{ asset('images/srjc-logo-wide.webp') }}" alt="Santa Rosa Junior College" class="w-50 h-auto" />
        </a>
      </div>

      {{-- Desktop --}}
      <div class="hidden md:flex items-center gap-1">
        <x-nav-items />
      </div>

      {{-- Mobile Hamburger --}}
      <div class="md:hidden flex items-center">
        <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-md text-gray-500 hover:bg-gray-100">
          <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

    </div>
  </div>

  {{-- Mobile Menu --}}
  <div x-show="mobileOpen" x-transition class="md:hidden border-t border-gray-200 px-4 py-3 space-y-1">
    <x-nav-items :mobile="true" />
  </div>

</nav>
