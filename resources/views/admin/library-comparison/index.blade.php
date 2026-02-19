<x-layout>
<x-slot:heading>
  Library Comparison Tool
</x-slot:heading>


<div class="max-w-7xl mx-auto px-4 py-8">

    <p class="text-gray-500 mb-6 text-sm">Paste SRJC tags or serial numbers from the library export, select the incoming status, and compare against our database.</p>

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
                            id="btn-srjc-tag"
                            onclick="setIdentifierType('srjc_tag')"
                            class="flex-1 py-2 text-sm font-medium transition-colors bg-indigo-600 text-white">
                            SRJC Tag
                        </button>
                        <button type="button"
                            id="btn-serial-number"
                            onclick="setIdentifierType('serial_number')"
                            class="flex-1 py-2 text-sm font-medium transition-colors bg-white text-gray-700">
                            Serial Number
                        </button>
                    </div>
                    <input type="hidden" name="identifier_type" id="identifier_type"
                        value="{{ old('identifier_type', $last_identifier_type ?? 'srjc_tag') }}">
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

    @isset($results)
      @php
        $counts = collect($results)->countBy('result_type');
      @endphp


    @if($counts->get('mismatch', 0) > 0 || $counts->get('delete_flag', 0) > 0)
    <div class="flex gap-3 mb-4">

        @if($counts->get('mismatch', 0) > 0)
            <form method="POST" action="{{ route('admin.library_comparison.update-all') }}">
                @csrf
                @foreach($results as $row)
                    @if($row['result_type'] === 'mismatch')
                        <input type="hidden" name="updates[]" value="{{ $row['device']->id }}:{{ $row['mapped_status']->id }}">
                    @endif
                @endforeach
                <button type="submit"
                    class="rounded-lg bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-semibold px-4 py-2 transition-colors"
                    onclick="return confirm('Update all {{ $counts->get('mismatch', 0) }} mismatched devices?')">
                    ⚡ Update All Mismatches ({{ $counts->get('mismatch', 0) }})
                </button>
            </form>
        @endif

        @if($counts->get('delete_flag', 0) > 0)
            <form method="POST" action="{{ route('admin.library_comparison.flag-all') }}">
              <input type="hidden" name="note" value="{{ session('lc_incoming_status') }}">

                @csrf
                @foreach($results as $row)
                    @if($row['result_type'] === 'delete_flag')
                        <input type="hidden" name="device_ids[]" value="{{ $row['device']->id }}">
                    @endif
                @endforeach
                <button type="submit"
                    class="rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 transition-colors"
                    onclick="return confirm('Flag all {{ $counts->get('delete_flag', 0) }} devices for review?')">
                    ⚑ Flag All for Review ({{ $counts->get('delete_flag', 0) }})
                </button>
            </form>
        @endif

      </div>
    @endif
  @endisset
    {{-- ------------------------------------------------------------------ --}}
    {{-- RESULTS TABLE                                                       --}}
    {{-- ------------------------------------------------------------------ --}}
    @isset($results)

        {{-- Summary bar --}}
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
                                <div class="font-medium text-gray-800">{{ $row['device']->srjc_tag }}</div>
                                <div class="text-xs text-gray-500">{{ $row['device']->model_name }}</div>
                                <div class="text-xs text-gray-400">S/N: {{ $row['device']->serial_number }}</div>
                            @else
                                <span class="text-gray-400 italic">Not in database</span>
                            @endif
                        </td>

                        {{-- Current status --}}
                        <td class="px-4 py-3">
                            @if($row['current_status'])
                                <span class="nline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $row['current_status']->tailwind_class }} text-neutral-50">
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
                                ⚑ {{ session('lc_incoming_status') }} — Flag for Review
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
                                <form method="POST" action="{{ route('log') }}">
                                  @csrf
                                  <input type="hidden" name="srjc_tag" value="{{ $row['identifier_type'] === 'srjc_tag' ? $row['identifier'] : '' }}">
                                  <input type="hidden" name="serial_number" value="{{ $row['identifier_type'] === 'serial_number' ? $row['identifier'] : '' }}">
                                  <input type="hidden" name="status_id" value="{{ $row['mapped_status']?->id }}">
                                  <input type="hidden" name="username" value="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                                  <input type="hidden" name="return_url" value="{{ route('admin.library_comparison.recompare') }}">
                                  <button type="submit"
                                      class="inline-flex items-center gap-1 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors">
                                      Add Device
                                  </button>
                                </form>

                            {{-- DELETE FLAG: flag for review --}}
                            @elseif($row['result_type'] === 'delete_flag')
                                <form method="POST" action="{{ route('admin.library_comparison.flag-device') }}">
                                    @csrf
                                    <input type="hidden" name="device_id" value="{{ $row['device']->id }}">
                                    <input type="hidden" name="note" value="{{ session('lc_incoming_status') }}">
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 rounded-md bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 transition-colors"
                                        onclick="return confirm('Flag {{ $row['device']->srjc_tag }} for manual review?')">
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

  <x-device-modal />

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
        const btnSRJCTag    = document.getElementById('btn-srjc-tag');
        const btnSerialNumber = document.getElementById('btn-serial-number');

        if (type === 'srjc_tag') {
            btnSRJCTag.classList.add('bg-indigo-600', 'text-white');
            btnSRJCTag.classList.remove('bg-white', 'text-gray-700');
            btnSerialNumber.classList.add('bg-white', 'text-gray-700');
            btnSerialNumber.classList.remove('bg-indigo-600', 'text-white');
        } else {
            btnSerialNumber.classList.add('bg-indigo-600', 'text-white');
            btnSerialNumber.classList.remove('bg-white', 'text-gray-700');
            btnSRJCTag.classList.add('bg-white', 'text-gray-700');
            btnSRJCTag.classList.remove('bg-indigo-600', 'text-white');
        }
    }

</script>
</x-layout>
