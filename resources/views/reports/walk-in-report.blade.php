<x-layout>
<x-slot:heading>Walk-In Log Report</x-slot:heading>

<div class="max-w-7xl mx-auto px-4 py-8">

  <form method="GET" action="{{ route('admin.reports.walk_in_log') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex gap-6 items-end flex-wrap">

      {{-- Date Range --}}
      <div>
        <label for="date_range" class="block text-sm font-medium text-gray-700">Date Range</label>
        <small class="ml-1 block text-gray-500">To select one date, click that date twice.</small>
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
      </div>

      {{-- Category --}}
      <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
        <select name="category_id" id="category_id"
          class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">All Categories</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Escalated --}}
      <div>
        <label for="escalated" class="block text-sm font-medium text-gray-700 mb-2">Escalated</label>
        <select name="escalated" id="escalated"
          class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">All</option>
          <option value="1" {{ request('escalated') === '1' ? 'selected' : '' }}>Escalated Only</option>
        </select>
      </div>

      <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors">
        Run Report
      </button>
    </div>
  </form>

  @if(request('date_range') || request('category_id'))
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Summary bar --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
      <h2 class="text-sm font-semibold text-gray-700">
        Results {{ request('date_range') ? 'for ' . request('date_range') : '' }}
      </h2>
      <div class="flex gap-6 text-sm text-gray-500">
        <span>Total walk-ins: <span class="font-semibold text-gray-800">{{ $totalCount }}</span></span>
        @if(request('escalated') !== '1')
          <span>Escalated:
            <span class="font-semibold text-gray-800">{{ $totalEscalated }}</span>
            @if($totalCount > 0)
              <span class="text-red-500 ml-1">({{ round(($totalEscalated / $totalCount) * 100, 1) }}%)</span>
            @endif
          </span>
        @endif
      </div>
    </div>

    <table class="min-w-full text-sm divide-y divide-gray-200">
      <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
        <tr>
          <th class="px-6 py-3 text-left">Category</th>
          <th class="px-6 py-3 text-left">Count</th>
          <th class="px-6 py-3 text-left">Avg Duration</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @foreach($counts as $row)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-3 font-medium text-gray-800">{{ $row['name'] }}</td>
            <td class="px-6 py-3 text-gray-600">{{ $row['count'] }}</td>
            <td class="px-6 py-3 text-gray-600">
              {{ $row['avg_duration'] ? round($row['avg_duration']) . ' min' : '—' }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endif

</div>

</x-layout>
