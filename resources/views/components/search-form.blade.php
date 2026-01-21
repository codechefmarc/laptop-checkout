@props(['statusFilterInfo' => null, 'poolName' => null])

@php
  // Check if this is a search results page (any search parameters present)
  $hasSearchParams = request()->hasAny(['status_id', 'srjc_tag', 'serial_number', 'model_number', 'date_range', 'pool_id']);
  $isCollapsed = $hasSearchParams; // Auto-collapse if search was performed
@endphp

<div x-data="{
  collapsed: {{ $isCollapsed ? 'true' : 'false' }},
  toggle() { this.collapsed = !this.collapsed }
}"
class="max-w-2xl mx-auto">

  {{-- Collapsed View: Active Filters Summary --}}
  <div x-show="collapsed"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       class="bg-white p-4 rounded-lg shadow-md mb-4">

    <div class="flex items-center justify-between flex-wrap gap-3">
      <div class="flex items-center gap-3 flex-wrap w-full">
        <span class="text-sm font-medium text-gray-700">
          <i class="fa-solid fa-filter mr-1"></i>
          Active Filters:
        </span>

        {{-- Show active filter badges --}}
        @if(isset($statusFilterInfo) && $statusFilterInfo)
          @if($statusFilterInfo['type'] === 'single')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-sm rounded-md">
              <strong>Status:</strong> {{ $statusFilterInfo['name'] }}
            </span>
          @elseif($statusFilterInfo['type'] === 'exclusion')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-50 text-orange-700 text-sm rounded-md">
              <strong>Excluding Status:</strong> {{ $statusFilterInfo['name'] }}
            </span>
          @elseif($statusFilterInfo['type'] === 'multiple')
            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-sm rounded-md">
              <strong>Statuses:</strong> {{ implode(', ', $statusFilterInfo['names']) }}
            </span>
          @endif
        @endif

        @if(request('current_status_only') === 'off')
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 text-sm rounded-md">
            Showing all status history
          </span>
        @endif

        @if(request('show_empty_models') == 'true')
              <div class="inline-flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-md">
                <span>Showing only devices without model numbers</span>
              </div>
            @endif

        @if(request('srjc_tag'))
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-sm rounded-md">
            <strong>Tag:</strong> {{ request('srjc_tag') }}
          </span>
        @endif

        @if(request('serial_number'))
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-sm rounded-md">
            <strong>Serial:</strong> {{ request('serial_number') }}
          </span>
        @endif

        @if(request('model_number'))
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-sm rounded-md">
            <strong>Model:</strong> {{ request('model_number') }}
          </span>
        @endif

        @if(request('date_range'))
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-sm rounded-md">
            <strong>Dates:</strong> {{ request('date_range') }}
          </span>
        @endif

        @if(request('pool_id') && request('pool_id') != 'any')
          <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-sm rounded-md">
            <strong>Pool:</strong> {{ $poolName }}
          </span>
        @endif

        @if(!$hasSearchParams)
          <span class="text-sm text-gray-500 italic">No filters applied</span>
        @endif
      </div>
      <div class="flex items-center justify-end gap-4 w-full">
        <a href="{{ route('search') }}" class="text-sm text-gray-900 cursor-pointer">Reset</a>
        <button @click="toggle()"
                type="button"
                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-md transition">
          <i class="fa-solid fa-chevron-down"></i>
          Modify Search
        </button>
      </div>
    </div>
  </div>

  {{-- Expanded View: Full Search Form --}}
  <form method="GET"
        action="{{ route('search') }}"
        x-show="!collapsed"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-white p-8 rounded-lg shadow-md">

    {{-- Collapse button (only show after initial search) --}}
    @if($hasSearchParams)
      <div class="flex justify-end mb-4">
        <button @click="toggle()"
                type="button"
                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-100 rounded-md transition">
          <i class="fa-solid fa-chevron-up"></i>
          Collapse
        </button>

      </div>
    @endif

    <div class="space-y-6">
      <div class="md:flex gap-5 items-center">
        <div>
            <x-status-select :search="TRUE" />
        </div>
        <div>
          <x-pool-select :search="TRUE" />
        </div>
        <div>
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
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes contains</label>
          <input type="text"
                  id="notes"
                  name="notes"
                  value="{{ request('notes') }}"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          @error('notes')
            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
          @enderror
      </div>
    </div>
      <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Search
      </button>
      <a href="{{ route('search') }}" class="ml-3 text-gray-900 cursor-pointer">Reset</a>
  </form>
</div>
