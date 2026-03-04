<hr class="h-px my-8 bg-gray-300 border-0">
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
  <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800">{{ $type }} walk-ins today ({{ $walkIns->total() }})</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">

      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Date
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            User
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Description
          </th>
          <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
            Categories
          </th>
          <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
            Escalated
          </th>
          <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
            Duration
          </th>
          @if($type === 'Active')
            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          @endif

        </tr>
      </thead>

      <tbody class="bg-white divide-y divide-gray-200">
        @forelse($walkIns as $walkIn)
          <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-50 transition-colors duration-200">

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $walkIn->created_at->format('m/d/Y') }}</div>
              <div class="text-xs text-gray-500">{{ $walkIn->created_at->format('g:iA') }}</div>
            </td>


            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ $walkIn->username }}
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-900">{{ $walkIn->description }}</div>
            </td>

            <td>
              <div class="flex flex-wrap gap-2">
                @foreach($walkIn->supportCategories as $category)
                  <span class="px-2 py-1 my-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">{{ $category->name }}</span>
                @endforeach
              </div>

            </td>

            <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              @if($walkIn->escalated)
                <span class="px-2 py-1 text-xs font-medium">Yes</span>
              @else
                <span class="px-2 py-1 text-xs font-medium">No</span>
              @endif
            </td>

              @php
              $durationDisplay = $walkIn->created_at->diffInMinutes(now()) <= 60
                ? round($walkIn->created_at->diffInMinutes(now())) . ' minutes'
                : round($walkIn->created_at->diffInMinutes(now()) / 60, 1) . ' hours';
              @endphp
            <td class="text-center px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $durationDisplay }}
              </td>


            @if($type === 'Active')
              <td class="text-center px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a href="{{ route('walk_in_log.edit', $walkIn->id) }}" class="block m-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 text-sm">Edit & Complete</a>
                <form action="{{ route('walk_in_log.complete', $walkIn->id) }}" method="POST" class="inline-block">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="block rounded-md bg-green-600 hover:bg-green-700 text-white py-1 px-3 text-sm">Complete</button>
                </form>
              </td>
            @endif

            </tr>
        @empty
          <tr><td colspan="6" class="text-center py-8">No activities found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($walkIns->hasPages())
    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
      {{ $walkIns->links() }}
    </div>
  @endif

  @if($walkIns !== null && $walkIns->count() > 0)
    <div class="m-4 text-right">

    </div>
  @endif

</div>
