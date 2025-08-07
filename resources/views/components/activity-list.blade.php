@props(['activities'])
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
  <!-- Your table structure -->
  <tbody class="bg-white divide-y divide-gray-200">
{{ dd($activities) }}
    @forelse($activities as $activity)

    {{ dd($activity->status_id) }}
    @empty
        <tr>
            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                No activities found.
            </td>
        </tr>
    @endforelse
  </tbody>

  <!-- Pagination -->
  <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
      {{ $activities->links() }}
  </div>
</div>
