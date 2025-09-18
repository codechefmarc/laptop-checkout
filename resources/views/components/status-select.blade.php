@props(
  [
    'search' => FALSE,
    'statuses' => $statuses,
  ]
)

<label for="status_select" class="block text-sm font-medium text-gray-700 mb-2">Status
  @if(!$search)
    <span class="text-red-500 text-sm">*</span>
  @endif
</label>
<select
  class="mb-2 h-[42px] block px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
  name="status_id"
  id="status_select"
  {{ !$search ? 'required' : '' }}
>
  @if($search)
    <option value="any">&mdash; Any &mdash;</option>
  @else
    <option value="">&mdash; Select &mdash;</option>
  @endif
  @foreach ($statuses as $status)
    @php
      if($search) {
        $checked = (request('status_id') == $status->id) ? 'selected' : false;
      } else {
        $checked = ($selected == $status->id) ? 'selected' : false;
      }
    @endphp
    <option
      value="{{ $status->id }}"
      {{ $checked }}
    >
      {{ $status->status_name }}
    </option>
  @endforeach
</select>
