<!-- Activity List Component -->
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
  <!-- Table Header -->
  <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800">{{ $slot }}</h2>
  </div>

  <!-- Table Container -->
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <!-- Table Header -->
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
        </tr>
      </thead>

      <!-- Table Body -->
      <tbody class="bg-white divide-y divide-gray-200">
        <!-- Sample Row 1 -->

        @forelse($activities as $activity)
          <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-50 transition-colors duration-200">

          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $activity->created_at->format('d/m/Y') }}</div>
            <div class="text-xs text-gray-500">{{ $activity->created_at->format('g:iA') }}</div>
          </td>

          <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">SRJC: {{ $activity->device->srjc_tag }}</div>
            <div class="text-xs text-gray-500">{{ $activity->device->serial_number ? 'SN: ' . $activity->device->serial_number : '' }}</div>
            <div class="text-xs text-gray-500">{{ $activity->device->model_number }}</div>
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
        </tr>
          @empty
        <tr><td colspan="5" class="text-center py-8">No activities found</td></tr>
        @endforelse




      </tbody>
    </table>
  </div>

  <!-- Table Footer / Pagination Area -->

  @if ($activities->hasPages())
    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
      {{ $activities->links() }}
    </div>
  @endif
</div>
