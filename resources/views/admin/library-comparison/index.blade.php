<x-layout>
<x-slot:heading>
  Library Comparison Tool
</x-slot:heading>


<div class="max-w-7xl mx-auto px-4 py-8">

    <p class="text-gray-500 mb-6 text-sm">Paste tag numbers or serial numbers from the library export, select the incoming status, and compare against our database.</p>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ------------------------------------------------------------------ --}}
    {{-- INPUT FORM                                                          --}}
    {{-- ------------------------------------------------------------------ --}}
    <form method="POST" action="{{ route('admin.library_comparison.compare') }}" id="compareForm">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Identifier type toggle --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Identifier Type</label>
                    <div class="flex rounded-lg overflow-hidden border border-gray-300">
                        <button type="button"
                            id="btn-tag"
                            onclick="setIdentifierType('tag')"
                            class="flex-1 py-2 text-sm font-medium transition-colors bg-indigo-600 text-white">
                            Tag Number
                        </button>
                        <button type="button"
                            id="btn-serial"
                            onclick="setIdentifierType('serial')"
                            class="flex-1 py-2 text-sm font-medium transition-colors bg-white text-gray-700 hover:bg-gray-50">
                            Serial Number
                        </button>
                    </div>
                    <input type="hidden" name="identifier_type" id="identifier_type"
                        value="{{ old('identifier_type', $last_identifier_type ?? 'tag') }}">
                </div>

                {{-- Incoming status dropdown --}}
                <div>
                    <label for="incoming_status" class="block text-sm font-medium text-gray-700 mb-2">
                        Incoming Status <span class="text-gray-400 font-normal">(their label)</span>
                    </label>
                    <select name="incoming_status" id="incoming_status"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($incomingStatuses as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('incoming_status', $last_incoming_status ?? '') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit --}}
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-colors">
                        Run Comparison
                    </button>
                </div>

            </div>

            {{-- Paste area --}}
            <div class="mt-5">
                <label for="identifiers" class="block text-sm font-medium text-gray-700 mb-2">
                    Paste Identifiers <span class="text-gray-400 font-normal">(one per line, from Excel)</span>
                </label>
                <textarea name="identifiers" id="identifiers" rows="6"
                    placeholder="ABC123&#10;DEF456&#10;GHI789"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('identifiers', $last_identifiers ?? '') }}</textarea>
                @error('identifiers')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </form>

    {{-- ------------------------------------------------------------------ --}}
    {{-- RESULTS TABLE                                                       --}}
    {{-- ------------------------------------------------------------------ --}}
    @isset($results)

        {{-- Summary bar --}}
        @php
            $counts = collect($results)->countBy('result_type');
        @endphp
        <div class="flex flex-wrap gap-3 mb-4">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 text-green-800 text-xs font-semibold px-3 py-1">
                ✅ Match: {{ $counts->get('match', 0) }}
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1">
                ⚠️ Mismatch: {{ $counts->get('mismatch', 0) }}
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1">
                ➕ Not Found: {{ $counts->get('not_found', 0) }}
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 text-red-800 text-xs font-semibold px-3 py-1">
                ⚑ Flag for Review: {{ $counts->get('delete_flag', 0) }}
            </span>
            @if($counts->get('unmapped', 0))
            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1">
                ❓ Unmapped: {{ $counts->get('unmapped', 0) }}
            </span>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Identifier</th>
                        <th class="px-4 py-3 text-left">Device</th>
                        <th class="px-4 py-3 text-left">Current Status</th>
                        <th class="px-4 py-3 text-left">Incoming → Mapped</th>
                        <th class="px-4 py-3 text-left">Result</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($results as $row)
                    <tr class="
                        @if($row['result_type'] === 'match') bg-green-50
                        @elseif($row['result_type'] === 'mismatch') bg-yellow-50
                        @elseif($row['result_type'] === 'not_found') bg-blue-50
                        @elseif($row['result_type'] === 'delete_flag') bg-red-50
                        @else bg-gray-50
                        @endif
                    ">
                        {{-- Identifier --}}
                        <td class="px-4 py-3 font-mono font-medium text-gray-800">
                            {{ $row['identifier'] }}
                            <span class="text-gray-400 text-xs">({{ $row['identifier_type'] }})</span>
                        </td>

                        {{-- Device info --}}
                        <td class="px-4 py-3 text-gray-600">
                            @if($row['device'])
                                <div class="font-medium text-gray-800">{{ $row['device']->tag }}</div>
                                <div class="text-xs text-gray-500">{{ $row['device']->model_name }}</div>
                                <div class="text-xs text-gray-400">S/N: {{ $row['device']->serial }}</div>
                            @else
                                <span class="text-gray-400 italic">Not in database</span>
                            @endif
                        </td>

                        {{-- Current status --}}
                        <td class="px-4 py-3">
                            @if($row['current_status'])
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $row['current_status']->tailwind_class }}">
                                    {{ $row['current_status']->status_name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">None</span>
                            @endif
                        </td>

                        {{-- Mapped status --}}
                        <td class="px-4 py-3">
                            @if($row['delete_flag'])
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-700">
                                    ⚑ Lost & Paid — Flag for Review
                                </span>
                            @elseif($row['mapped_status'])
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $row['mapped_status']->tailwind_class }}">
                                    {{ $row['mapped_status']->status_name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">No mapping</span>
                            @endif
                        </td>

                        {{-- Result badge --}}
                        <td class="px-4 py-3">
                            @if($row['result_type'] === 'match')
                                <span class="text-green-700 font-semibold text-xs">✅ Match</span>
                            @elseif($row['result_type'] === 'mismatch')
                                <span class="text-yellow-700 font-semibold text-xs">⚠️ Mismatch</span>
                            @elseif($row['result_type'] === 'not_found')
                                <span class="text-blue-700 font-semibold text-xs">➕ Not Found</span>
                            @elseif($row['result_type'] === 'delete_flag')
                                <span class="text-red-700 font-semibold text-xs">⚑ Review Needed</span>
                            @else
                                <span class="text-gray-500 font-semibold text-xs">❓ Unmapped</span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-4 py-3">

                            {{-- MISMATCH: update status --}}
                            @if($row['result_type'] === 'mismatch')
                                <form method="POST" action="{{ route('admin.library_comparison.update-status') }}">
                                    @csrf
                                    <input type="hidden" name="device_id" value="{{ $row['device']->id }}">
                                    <input type="hidden" name="status_id" value="{{ $row['mapped_status']->id }}">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-md bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors"
                                        onclick="return confirm('Update {{ $row['device']->srjc_tag }} to {{ $row['mapped_status']->status_name }}?')">
                                        Update Status
                                    </button>
                                </form>

                            {{-- NOT FOUND: add device modal trigger --}}
                            @elseif($row['result_type'] === 'not_found')
                                <button type="button"
                                    onclick="openAddModal('{{ $row['identifier'] }}', '{{ $row['identifier_type'] }}', {{ $row['mapped_status'] ? $row['mapped_status']->id : 'null' }})"
                                    class="inline-flex items-center gap-1 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors">
                                    Add Device
                                </button>

                            {{-- DELETE FLAG: flag for review --}}
                            @elseif($row['result_type'] === 'delete_flag')
                                <form method="POST" action="{{ route('admin.library_comparison.flag-device') }}">
                                    @csrf
                                    <input type="hidden" name="device_id" value="{{ $row['device']->id }}">
                                    <input type="hidden" name="note" value="Lost and paid — flagged via library comparison tool.">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors"
                                        onclick="return confirm('Flag {{ $row['device']->tag }} for manual review?')">
                                        Flag for Review
                                    </button>
                                </form>

                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endisset

</div>

{{-- ------------------------------------------------------------------ --}}
{{-- ADD DEVICE MODAL                                                    --}}
{{-- ------------------------------------------------------------------ --}}
<div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Add New Device</h2>

        <form method="POST" action="{{ route('admin.library_comparison.add-device') }}">
            @csrf

            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tag Number</label>
                    <input type="text" name="tag" id="modal-tag" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                    <input type="text" name="serial" id="modal-serial" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model Name</label>
                    <input type="text" name="model_name" id="modal-model" required
                        list="model-suggestions"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <datalist id="model-suggestions">
                        @isset($models)
                            @foreach($models as $model)
                                <option value="{{ $model }}">
                            @endforeach
                        @endisset
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pool</label>
                    <select name="pool_id" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a pool…</option>
                        @foreach($pools as $pool)
                            <option value="{{ $pool->id }}">{{ $pool->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Initial Status</label>
                    <select name="status_id" id="modal-status" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a status…</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeAddModal()"
                    class="flex-1 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium py-2 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 transition-colors">
                    Add Device
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ------------------------------------------------------------------ --}}
{{-- JS                                                                  --}}
{{-- ------------------------------------------------------------------ --}}
<script>
    // Identifier type toggle
    const savedType = document.getElementById('identifier_type').value;
    setIdentifierType(savedType);

    function setIdentifierType(type) {
        document.getElementById('identifier_type').value = type;
        const btnTag    = document.getElementById('btn-tag');
        const btnSerial = document.getElementById('btn-serial');

        if (type === 'tag') {
            btnTag.classList.add('bg-indigo-600', 'text-white');
            btnTag.classList.remove('bg-white', 'text-gray-700');
            btnSerial.classList.add('bg-white', 'text-gray-700');
            btnSerial.classList.remove('bg-indigo-600', 'text-white');
        } else {
            btnSerial.classList.add('bg-indigo-600', 'text-white');
            btnSerial.classList.remove('bg-white', 'text-gray-700');
            btnTag.classList.add('bg-white', 'text-gray-700');
            btnTag.classList.remove('bg-indigo-600', 'text-white');
        }
    }

    // Add device modal
    function openAddModal(identifier, type, statusId) {
        // Pre-fill whichever field matches the identifier type
        if (type === 'tag') {
            document.getElementById('modal-tag').value = identifier;
            document.getElementById('modal-serial').value = '';
        } else {
            document.getElementById('modal-serial').value = identifier;
            document.getElementById('modal-tag').value = '';
        }

        // Pre-select the mapped status if available
        if (statusId) {
            const sel = document.getElementById('modal-status');
            for (let opt of sel.options) {
                if (opt.value == statusId) { opt.selected = true; break; }
            }
        }

        document.getElementById('addModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
    }

    // Close modal on backdrop click
    document.getElementById('addModal').addEventListener('click', function(e) {
        if (e.target === this) closeAddModal();
    });
</script>
</x-layout>
