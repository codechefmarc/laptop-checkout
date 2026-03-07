<x-layout>
<x-slot:heading>
  {{ isset($user) ? 'Edit User' : 'Create New User' }}
</x-slot:heading>

<form method="POST" action="{{ isset($isProfile)
    ? route('profile.update')
    : (isset($user) ? route('admin.users.update', $user) : route('admin.users.store'))
}}">
  @csrf
  @if(isset($user))
    @method('PUT')
  @endif

  <div class="max-w-2xl border-b border-gray-900/10 mx-auto bg-white p-8 rounded-lg shadow-md">
    <div class="pb-12">
      <div class="mt-10 flex flex-col gap-y-2">
        <h3 class="text-2xl">User Information</h3>

        {{-- First Name --}}
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500 text-sm">*</span></label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
                id="first_name"
                type="text"
                name="first_name"
                required
                value="{{ old('first_name', $user->first_name ?? '') }}"
                class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
            @error('first_name')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Last Name --}}
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500 text-sm">*</span></label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
                id="last_name"
                type="text"
                name="last_name"
                required
                value="{{ old('last_name', $user->last_name ?? '') }}"
                class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
            @error('last_name')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Email --}}
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500 text-sm">*</span></label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
                id="email"
                type="email"
                name="email"
                required
                value="{{ old('email', $user->email ?? '') }}"
                @cannot('users.admin') ? readonly @endcannot
                class="block min-w-0 grow py-1.5 pl-3 pr-3 text-base text-gray-900 bg-gray-200 placeholder:text-gray-400 focus:outline-none sm:text-sm/6 read-only:bg-gray-300 read-only:cursor-not-allowed" />
            </div>
            @error('email')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Role --}}
        @can('users.admin')
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500 text-sm">*</span></label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <select
                id="role"
                name="role"
                required
                class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 focus:outline-none sm:text-sm/6">
                <option value="">Select a role...</option>
                @foreach($roles as $role)
                  <option value="{{ $role->name }}" {{ old('role', isset($user) ? $user->roles->first()?->name ?? '' : "") === $role->name ? 'selected' : '' }}>
                    {{ $role->display_name ?? $role->name }}
                  </option>
                @endforeach
              </select>
            </div>
            @error('role')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
        @endcan

        {{-- Password --}}
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
            @if(!isset($user))<span class="text-red-500 text-sm">*</span>@endif
            @if(isset($user))<span class="text-gray-400 font-normal text-xs">(leave blank to keep current)</span>@endif
          </label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
                id="password"
                type="password"
                name="password"
                {{ !isset($user) ? 'required' : '' }}
                class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
            @error('password')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Confirm Password --}}
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirm Password
            @if(!isset($user))<span class="text-red-500 text-sm">*</span>@endif
          </label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                {{ !isset($user) ? 'required' : '' }}
                class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="mt-6 flex items-center justify-end gap-x-6">
    @can('users.admin')
      <a href="{{ route('admin.users.index') }}" class="text-sm/6 font-semibold text-gray-900 cursor-pointer">Cancel</a>
    @endcan

    @cannot('users.admin')
      <a href="{{ route('welcome') }}" class="text-sm/6 font-semibold text-gray-900 cursor-pointer">Cancel</a>
    @endcan
      <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        {{ isset($user) ? 'Update User' : 'Create User' }}
      </button>
    </div>

  </div>
</form>

</x-layout>
