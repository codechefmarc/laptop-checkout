<x-layout>
<x-slot:heading>
  Create Status
</x-slot:heading>

<form method="POST" action="{{ route('taxonomy.status.store') }}">
  @csrf

  <input type="hidden" name="return_url" value="{{ $returnUrl }}">
  <input type="hidden" name="tailwind_class" value="bg-slate-500">
  <div class="max-w-2xl border-b border-gray-900/10 mx-auto bg-white p-8 rounded-lg shadow-md">
    <div class="pb-12">
      <div class="mt-10 flex flex-col gap-y-2">
        <h3 class="text-2xl">Status</h3>
        <div class="">
          <label for="status_name" class="block text-sm font-medium text-gray-700 mb-2">Status Name</label>
          <div class="mt-2">
          <div class="flex items-center gap-4">
                    <input type="text"
                      name="status_name"
                      id="status_name"
                      value="{{ old('status_name', '' ) }}"
                      class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-48"
                      oninput="updatePreview()" />
                    <div id="status_preview"
                         class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 {{ old('tailwind_class', 'bg-gray-500') }}">
                      {{ old('status_name', 'Status' ) }}
                    </div>
                  </div>
            @error('status_name')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div>
          <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (for ordering)</label>
          <input
            type="number"
            id="weight"
            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-48"
            name="weight"
            value="{{ old('weight', 100) }}"
            />
        </div>

         <p class="block text-sm font-medium text-gray-700">Color</p>

        <div class="flex flex-wrap gap-2" id="swatches">
          @php
            $colors = config('taxonomy.colors');
          @endphp
          @foreach($colors as $key => $color)
            <button type="button"
              onclick="selectColor({{ $key }}, '{{ $color }}')"
              class="swatch w-7 h-7 rounded-full {{ $color }} ring-offset-2 transition-all duration-150 {{ old('tailwind_class', 'bg-slate-500') === $color ? 'ring-2 ring-gray-800' : '' }}"
              data-color="{{ $color }}">
            </button>
          @endforeach
        </div>

      </div>
    </div>
<div class="mt-6 sm:flex max-w-2xl items-center justify-between">
    <div class="flex items-center gap-x-6 justify-self-end">
      <button type="submit" class="ml-3 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add Status</button>
      <a href="{{ route('taxonomy.status.index') }}" class="text-sm/6 font-semibold text-gray-900 cursor-pointer">Cancel</a>
    </div>

  </div>

  </div>
</form>

@push('footer_scripts')
    @vite('resources/js/taxonomy.js')
@endpush

</x-layout>
