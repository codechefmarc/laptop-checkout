<form method="GET" action="{{ route('search') }}" class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
  <div class="space-y-6">
    <div>
        <x-status-select :search="TRUE" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label for="srjc_tag" class="block text-sm font-medium text-gray-700 mb-2">SRJC Tag</label>
        <input type="text"
                id="srjc_tag"
                name="srjc_tag"
                value="{{ request('srjc_tag') }}"
                autofocus="autofocus"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('srjc_tag')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
      </div>
      <div>
        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
        <input type="text"
          id="serial_number"
          name="serial_number"
          value="{{ request('serial_number') }}"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('serial_number')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label for="model_number" class="block text-sm font-medium text-gray-700 mb-2">Model Number</label>
      <input
        type="text"
        id="model_number"
        name="model_number"
        value="{{ request('model_number') }}"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        placeholder="e.g. Dell OptiPlex 7090"
      />
      @error('model_number')
        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
      <small class="block text-gray-500">To select one date, click that date twice.</small>
        <div class="relative max-w-sm">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>
          </div>
          <input
            type="text"
            id="date_range"
            name="date_range"
            value="{{ request('date_range') }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            />
        </div>
    </div>
  </div>
    <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
      Search
    </button>
    <a href="/search" class="ml-3 text-gray-900 cursor-pointer">Reset</a>
</form>
