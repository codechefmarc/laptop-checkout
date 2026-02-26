<x-layout>
  <x-slot:heading>
    Statuses
  </x-slot:heading>

  <x-slot:containerClass>mx-auto max-w-2xl px-4 py-6 sm:px-6 lg:px-8</x-slot:containerClass>

  <div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6">
      <h3 class="text-xl font-semibold text-gray-800">Statuses</h3>
      <a href="{{ route('taxonomy.status.create') }}"
        class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
        </svg>
        Add Status
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8"></th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operations</th>
          </tr>
        </thead>
        <tbody id="sortable-list" data-reorder-url="{{ route('taxonomy.status.reorder') }}">
          @forelse($statuses as $status)
            <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-50 transition-colors duration-200"
                data-id="{{ $status->id }}">

              <td class="px-6 py-4 whitespace-nowrap cursor-grab text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 8h16M4 16h16" />
                </svg>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <div class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 {{ $status->tailwind_class }}">
                  {{ $status->status_name }}
                </div>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900 weight-display">{{ $status->weight }}</div>
             </td>

              <td class="px-6 py-3 text-left">
                <a href="{{ route('taxonomy.status.edit', $status) }}"
                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                  Edit
                </a>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="2" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                No statuses found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200" id="save-bar" style="display: none;">
      <button id="save-order"
              class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
        Save Order
      </button>
      <span id="save-feedback" class="ml-3 text-sm text-green-600" style="display: none;">Order saved!</span>
    </div>

  </div>

  @push('footer_scripts')
      @vite('resources/js/taxonomy.js')
  @endpush

</x-layout>
