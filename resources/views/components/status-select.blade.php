<p class="block text-sm font-medium text-gray-700 mb-2">Status</p>
<div class="sm:flex gap-8">

  @foreach ($statuses as $status)
  @php
    $checked = ($selected == $status->id) ? 'checked' : FALSE;
  @endphp
  <div>
      <input
      type="radio"
      id="status_{{ $status->id }}"
      name="status_id"
      value="{{ $status->id }}"
      required
      {{ $checked }}
      >
      <label for="status_{{ $status->id }}">{{ $status->status_name }}</label>
    </div>

  @endforeach

</div>
