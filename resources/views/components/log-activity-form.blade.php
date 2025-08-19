<form method="POST" action="/" class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
  @csrf
  <div class="space-y-6">
    <div>
        <x-status-select />
    </div>

    <div>
      <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Optional Notes</label>
      <input type="text"
        id="notes"
        name="notes"
        value="{{ old('notes') }}"
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label for="srjc_tag" class="block text-sm font-medium text-gray-700 mb-2">SRJC Tag</label>
        <input type="text"
                id="srjc_tag"
                name="srjc_tag"
                value="{{ old('srjc_tag') }}"
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
          value="{{ old('serial_number') }}"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        @error('serial_number')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
        </div>
    </div>

  </div>

  <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
    Log Device Activity
  </button>
</form>
