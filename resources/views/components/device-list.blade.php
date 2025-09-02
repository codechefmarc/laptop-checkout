<hr class="h-px my-8 bg-gray-300 border-0 dark:bg-gray-700">

<div class="bg-white shadow-lg rounded-lg overflow-hidden">
  <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800">{{ $slot }}</h2>
  </div>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            SRJC Tag
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Serial Number
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Model Number
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Date Updated
          </th>
          @auth
            @if(auth()->user()->canEdit())
              <th colspan="2" class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                Operations
              </th>
            @endif
          @endauth
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @forelse($devices as $device)
          <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-50 transition-colors duration-200">

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $device->srjc_tag }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $device->serial_number }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $device->model_number }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $device->updated_at->format('m/d/Y') }}</div>
              <div class="text-xs text-gray-500">{{ $device->updated_at->format('g:iA') }}</div>
            </td>
            @auth
              @if(auth()->user()->canEdit())
                <td>
                  <div class="text-sm text-gray-900">
                    <a class="text-blue-500 font-semibold hover:text-gray-800" href="{{ route('devices.edit', $device->id) }}">Edit Device</a>
                  </div>
                </td>
              @endif
            @endauth
          </tr>
        @empty
          <tr><td colspan="6" class="text-center py-8">No activities found</td></tr>
        @endforelse

      </tbody>
    </table>
  </div>



  @if ($devices->hasPages())
    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
      {{ $devices->links() }}
    </div>
  @endif

  <div class="m-4 text-right">
    <a href="{{ route('export.devices', request()->query()) }}"
      class="inline-flex items-center px-4 py-2 bg-green-400 cursor-pointer hover:bg-green-700 hover:text-white text-black-200 text-xs font-medium rounded-md">
      📊 Export CSV
    </a>
  </div>

</div>
