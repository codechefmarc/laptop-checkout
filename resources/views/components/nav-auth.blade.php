@auth
  <form method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <div class="flex items-center">
      <p class="mr-3 text-sm font-medium">Hi, {{ auth()->user()->first_name }}</p>
      <button type="submit" class="hover:bg-gray-700 block px-3 py-2 text-sm font-medium cursor-pointer">
        Logout
      </button>
    </div>
  </form>
@else
  <a href="{{ route('login') }}" class="ml-auto ext-gray-900 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-sm font-medium">
    Login
  </a>
@endauth
