@auth
  @if(auth()->user()->canEdit())
    <x-nav-link href="/log" :active="request()->is('/log')">Log Activity</x-nav-link>
  @endif
@endauth

@auth
  @if(auth())
    <x-nav-link href="/search" :active="request()->is('search')">Search</x-nav-link>
    <x-nav-link href="/reports" :active="request()->is('reports')">Reports</x-nav-link>
  @endif
@endauth

@auth
  @if(auth()->user()->isAdmin())
    <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->is('admin/users')">Manage Users</x-nav-link>
  @endif
@endauth
