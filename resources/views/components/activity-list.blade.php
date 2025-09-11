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
            Date
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Device
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Status
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            User
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Notes
          </th>
          <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
            Operations
          </th>
        </tr>
      </thead>

      <tbody class="bg-white divide-y divide-gray-200">
        @forelse($activities as $activity)
          <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-50 transition-colors duration-200">

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $activity->created_at->format('m/d/Y') }}</div>
              <div class="text-xs text-gray-500">{{ $activity->created_at->format('g:iA') }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">SRJC: {{ $activity->device->srjc_tag }}</div>
              <div class="text-xs text-gray-500">{{ $activity->device->serial_number ? 'SN: ' . $activity->device->serial_number : '' }}</div>
              <div class="text-xs text-gray-500">{{ $activity->device->model_number }}</div>
              @if($activity->device->pool && $activity->device->pool->id !== 1)
                <div class="text-xs text-violet-500">{{ $activity->device->pool->name }}</div>
              @endif
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $activity->status->tailwind_class }} text-neutral-50">
                {{ $activity->status->status_name }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ $activity->username }}
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-900">{{ $activity->notes }}</div>
            </td>
            <td class="px-6 py-4 flex gap-1 justify-around">
            @auth
              @if(!auth()->user()->isReadOnly())
                  <div class="text-center text-sm text-gray-900">
                    <a class="text-blue-500 text-lg font-semibold hover:text-gray-800" title="Edit activity" href="{{ route('activities.edit', $activity->id) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                  </div>
              @endif
              @if(auth()->user()->canEdit())
                  <div class="text-center text-sm text-gray-900">
                    <a class="text-blue-500 text-lg font-semibold hover:text-gray-800" title="Edit device" href="{{ route('devices.edit', $activity->device->id) }}"><i class="fa-solid fa-laptop-file"></i></a>
                  </div>
              @endif
            @endauth
              <div class="text-center text-sm text-gray-900">
                <a class="text-blue-500 text-lg font-semibold hover:text-gray-800" title="Show all activity for this device" href="{{ route('search', ['srjc_tag' => $activity->device->srjc_tag, 'serial_number' => $activity->device->serial_number, 'status_id' => 'any']) }}"><i class="fa-solid fa-magnifying-glass"></i></a>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center py-8">No activities found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($activities->hasPages())
    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
      {{ $activities->links() }}
    </div>
  @endif

  @if($activities !== null && $activities->count() > 0)
    <div class="m-4 text-right">
      <a href="{{ route('export.activities', request()->query()) }}"
        class="inline-flex items-center px-4 py-2 bg-green-400 cursor-pointer hover:bg-green-700 hover:text-white text-black-200 text-xs font-medium rounded-md">
        📊 Export CSV
      </a>
    </div>
  @endif

</div>
