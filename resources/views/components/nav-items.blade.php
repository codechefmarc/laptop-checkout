@props(['mobile' => false])

@php
  $link = $mobile
    ? 'block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100'
    : 'px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors';

  $dropdownBtn = $mobile
    ? 'w-full flex justify-between items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100'
    : 'flex items-center gap-1 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors';

  $dropdownMenu = $mobile
    ? 'pl-4 mt-1 space-y-1'
    : 'absolute left-0 mt-1 w-52 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50';

  $dropdownItem = $mobile
    ? 'block px-3 py-2 rounded-md text-sm text-gray-600 hover:bg-gray-100'
    : 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50';
@endphp

@can('laptops.edit')
<a href="{{ route('log') }}" class="{{ $link }} {{ request()->routeIs('log') ? 'bg-indigo-50 text-indigo-700' : '' }}">Log Checkout</a>
@endcan

@can('walkin.edit')
<a href="{{ route('walk_in_log.index') }}" class="{{ $link }} {{ request()->routeIs('walk_in_log.index') ? 'bg-indigo-50 text-indigo-700' : '' }}">Log Walk-In</a>
@endcan

@canany(['laptops.reports', 'walkin.reports'])
<div class="{{ $mobile ? '' : 'relative' }}" x-data="{ open: false }" @click.outside="open = false">
  <button @click="open = !open" class="{{ $dropdownBtn }}">
    Reports
    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>
  <div x-show="open" x-cloak x-transition class="{{ $dropdownMenu }}">
    @can('laptops.reports')
    <a href="{{ route('search') }}" class="{{ $dropdownItem }}">Search Checkouts</a>
    @endcan
    @can('laptops.reports')
    <a href="{{ route('reports.checkout_laptops') }}" class="{{ $dropdownItem }}">Laptop Reports</a>
    @endcan
    @can('walkin.reports')
    <a href="{{ route('reports.walk_in_log') }}" class="{{ $dropdownItem }}">Walk-In Reports</a>
    @endcan
  </div>
</div>
@endcanany

@canany(['users.admin', 'laptops.admin', 'support.admin'])
<div class="{{ $mobile ? '' : 'relative' }}" x-data="{ open: false }" @click.outside="open = false">
  <button @click="open = !open" class="{{ $dropdownBtn }}">
    Administration
    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>
  <div x-show="open" x-cloak x-transition class="{{ $dropdownMenu }}">
    @can('users.admin')
    <a href="{{ route('admin.users.index') }}" class="{{ $dropdownItem }}">Manage Users</a>
    <a href="{{ route('admin.roles.index') }}" class="{{ $dropdownItem }}">Manage Roles</a>
    @endcan
    @can('laptops.admin')
    <a href="{{ route('admin.flagged_devices.index') }}" class="{{ $dropdownItem }}">Flagged Devices</a>
    @endcan
    @can('laptops.admin')
    <a href="{{ route('admin.library_comparison.index') }}" class="{{ $dropdownItem }}">Library Comparison</a>
    @endcan
    @can('laptops.admin')
    <div class="{{ $mobile ? 'pl-2' : 'border-t border-gray-100 mt-1 pt-1' }}">
      @if(!$mobile)<p class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wide">Taxonomy</p>@endif
      <a href="{{ route('taxonomy.status.index') }}" class="{{ $dropdownItem }}">Statuses</a>
      <a href="{{ route('taxonomy.pool.index') }}" class="{{ $dropdownItem }}">Pools</a>
      <a href="{{ route('taxonomy.support_category.index') }}" class="{{ $dropdownItem }}">Support Categories</a>
    </div>
    @endcan
  </div>
</div>
@endcanany

{{-- Profile / Logout --}}
@if(Auth::user())
<div class="{{ $mobile ? 'border-t border-gray-200 pt-3 mt-3' : 'relative ml-2 pl-2 border-l border-gray-200' }}" x-data="{ open: false }" @click.outside="open = false">
  <button @click="open = !open" class="{{ $dropdownBtn }}">
    <span>{{ Auth::user()->first_name }}</span>
    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>
  <div x-show="open" x-cloak x-transition class="{{ $mobile ? 'pl-4 mt-1 space-y-1' : 'absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50' }}">
    <a href="{{ route('profile.edit') }}" class="{{ $dropdownItem }}">My Profile</a>
    <div class="{{ $mobile ? '' : 'border-t border-gray-100 mt-1 pt-1' }}">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
          Sign Out
        </button>
      </form>
    </div>
  </div>
</div>
@else
<a href="{{ route('login') }}" class="{{ $link }}">
    Login
</a>
@endif
