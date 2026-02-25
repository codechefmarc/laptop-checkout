@auth
  @if(auth()->user()->canEdit() || auth()->user()->isStudent())
    <x-nav-link href="{{ route('log') }}" :active="request()->routeIs('log')">Log Activity</x-nav-link>
  @endif
@endauth

@auth
  @if(auth())
    <x-nav-link href="{{ route('search') }}" :active="request()->routeIs('search')">Search</x-nav-link>
    <x-nav-link href="{{ route('reports') }}" :active="request()->routeIs('reports')">Reports</x-nav-link>
  @endif
@endauth

@auth
  @if(auth()->user()->isAdmin())

    <details class="relative">
      <summary class="flex items-center cursor-pointer text-sm font-medium mr-3">
        Administration
        <svg class="ml-1 h-4 w-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </summary>
      <div class="w-50 mt-2 shadow-lg rounded-md py-1 bg-gray-200 absolute right-[50%] transform-[translateX(50%)]">
        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-300">
          Manage Users
        </a>
        <a href="{{ route('taxonomy.status.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-300">
          Manage Statuses
        </a>
        <a href="{{ route('admin.library_comparison.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-300">
          Library Comparison
        </a>
        <a href="{{ route('admin.flagged_devices.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-300">
          Flagged Devices
        </a>
      </div>
    </details>

  @endif
@endauth
