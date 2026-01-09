@props(['statusFilterInfo' => null])

<form method="GET" action="{{ route('search') }}" class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
  <div class="space-y-6">
    <div class="md:flex gap-5 items-center">
      <div>
          <x-status-select :search="TRUE" />

          @php
            $statusId = request('status_id');
            $isExclusion = str_starts_with($statusId ?? '', 'not_');
            $isMultiple = str_contains($statusId ?? '', ',');
            $showEmptyModels = request('show_empty_models') == 'true';
          @endphp

          {{-- Status filter indicator --}}
          @if(isset($statusFilterInfo) && $statusFilterInfo)
            @if($statusFilterInfo['type'] === 'exclusion')
              <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 bg-orange-100 text-orange-800 text-sm rounded-full">
                <i class="fa-solid fa-filter"></i>
                <span>Excluding: {{ $statusFilterInfo['name'] }}</span>
              </div>
            @elseif($statusFilterInfo['type'] === 'multiple')
              <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                <i class="fa-solid fa-filter"></i>
                <span>Statuses: {{ implode(', ', $statusFilterInfo['names']) }}</span>
              </div>
            @endif
          @endif

          {{-- Empty models filter indicator --}}
          @if($showEmptyModels)
            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">
              <i class="fa-solid fa-filter"></i>
              <span>Showing only devices without model numbers</span>
            </div>
          @endif
      </div>

      @php
        if (request()->has('current_status_only')) {
          $current_status_checked = request('current_status_only') == 'on' ? 'checked' : '';
        } else {
          $current_status_checked = 'checked';
        }
      @endphp

      <div>
        <input type="hidden" name="current_status_only" value="off">
        <input
        type="checkbox"
        name="current_status_only"
        id="current_status_only"
        value="on"
        {{ $current_status_checked }}
        >
        <label for="current_status_only">Show current status only</label>
      </div>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
        <div class="relative max-w-sm">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <i class="fa-solid fa-calendar text-gray-500"></i>
          </div>
          <input
            type="text"
            id="date_range"
            name="date_range"
            value="{{ request('date_range') }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
           />
        </div>
        <small class="ml-1 block text-gray-500">To select one date, click that date twice.</small>
      </div>
    </div>
    <div>
      <x-pool-select :search="TRUE" />
    </div>
  </div>
    <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
      Search
    </button>
    <a href="{{ route('search') }}" class="ml-3 text-gray-900 cursor-pointer">Reset</a>
</form>
