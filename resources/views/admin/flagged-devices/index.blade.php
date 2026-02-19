<x-layout>
<x-slot:heading>
  Library Comparison Tool
</x-slot:heading>


<div class="max-w-7xl mx-auto px-4 py-8">


<form method="POST" action="{{ route('admin.flagged_devices.bulk_destroy') }}" id="bulkForm">
    @csrf
    @method('DELETE')

    <div class="flex justify-between items-center mb-4">
        <div class="m-4 text-right">
            <a href="{{ route('export.flagged-devices') }}"
                class="inline-flex items-center px-4 py-2 bg-green-400 cursor-pointer hover:bg-green-700 hover:text-white text-black-200 text-xs font-medium rounded-md">
                📊 Export CSV
            </a>
        </div>
        <button type="submit"
            id="bulkDeleteBtn"
            disabled
            onclick="return confirm('Permanently delete all selected devices and their activities?')"
            class="inline-flex items-center rounded-md bg-red-600 hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-semibold px-4 py-2 transition-colors">
            🗑 Delete Selected (<span id="selectedCount">0</span>)
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                    </th>
                    <th class="px-4 py-3 text-left">Tag</th>
                    <th class="px-4 py-3 text-left">Serial</th>
                    <th class="px-4 py-3 text-left">Model</th>
                    <th class="px-4 py-3 text-left">Flag Reason</th>
                    <th class="px-4 py-3 text-left">Last Status</th>
                    <th class="px-4 py-3 text-left">Flagged</th>
                    <th class="px-4 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($devices as $device)
                    @php $lastActivity = $device->activities->sortByDesc('created_at')->first(); @endphp
                    <tr class="bg-red-50">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="device_ids[]" value="{{ $device->id }}"
                                class="row-checkbox rounded border-gray-300">
                        </td>
                        <td class="px-4 py-3 font-mono font-medium text-gray-800">{{ $device->srjc_tag ?? '—' }}</td>
                        <td class="px-4 py-3 font-mono text-gray-600">{{ $device->serial_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $device->model_number }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $device->flag_note ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($lastActivity?->status)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $lastActivity->status->tailwind_class }}">
                                    {{ $lastActivity->status->status_name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">None</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $device->updated_at->format('m/d/Y') }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.flagged_devices.destroy', $device) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors"
                                    onclick="return confirm('Permanently delete {{ $device->srjc_tag }} and all its activities?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>
</div>
</x-layout>
<script>
    const selectAll    = document.getElementById('selectAll');
    const checkboxes   = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');

    function updateButton() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCount.textContent = checked;
        bulkDeleteBtn.disabled = checked === 0;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateButton();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateButton));
</script>
