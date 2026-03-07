<!DOCTYPE html>
<html class="h-full bg-gray-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image">
    <title>{{ $heading }} - ITC Database</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script src="https://kit.fontawesome.com/d6c74f4872.js" crossorigin="anonymous"></script>

  </head>
  <body class="h-full">

    @if(env('APP_ENV') === 'local')
      <div class="bg-gray-600 pt-1 pb-1 {{ env('APP_ENV') === 'local' ? 'bg-yellow-600' : '' }} text-white flex justify-end items-center">
        <p class="mr-auto ml-3">DEVELOPMENT</p>
      </div>
    @endif

    <x-nav/>
      <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold tracking-tight text-gray-900">ITC Database</h1>
          <h2 class="font-bold text-2xl text-gray-600">{{ $heading }}</h2>
        </div>
      </header>
      <main>

        <div class="{{ $containerClass ?? 'mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8' }}">

            {{-- Flash Messages --}}
            @if(session('info'))
              <div class="mt-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded mb-4">
                {{ session('info') }}
              </div>
            @endif
            @if(session('success'))
              <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
              </div>
            @endif

            @if(session('error'))
              <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
              </div>
            @endif

          {{ $slot }}
        </div>
      </main>



        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

    <script>
      // Set the API route to be used in the autocomplete script.
      window.apiRoute = '{{ route("api.model-numbers") }}';
    </script>

    @stack('footer_scripts')

  </body>
</html>
