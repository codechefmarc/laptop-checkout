@props(['search' => FALSE])
<p class="block text-sm font-medium text-gray-700 mb-2">Status
  @if(!$search)
    <span class="text-red-500 text-sm">*</span>
  @endif
</p>
<div class="sm:flex gap-8">

  @if($search)
  <div>
  <input
        type="radio"
        id="status_any"
        name="status_id"
        value="any"
        {{ request('status_id') === 'any' || !request('status_id') ? 'checked' : '' }}
      >
      <label for="status_any">Any</label>
  </div>
      @endif

  @foreach ($statuses as $status)
  @php
    if($search) {
      $checked = (request('status_id') == $status->id) ? 'checked' : false;
    } else {
      $checked = ($selected == $status->id) ? 'checked' : false;
    }
  @endphp
  <div>

      <input
      type="radio"
      id="status_{{ $status->id }}"
      name="status_id"
      value="{{ $status->id }}"
      {{ !$search ? 'required' : '' }}
      {{ $checked ? 'checked' : '' }}
      >

      <label for="status_{{ $status->id }}">{{ $status->status_name }}</label>
    </div>

  @endforeach

</div>
