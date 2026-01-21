@props(
  [
    'search' => FALSE,
    'pools' => $pools,
  ]
)
<label for="pool_id" class="block text-sm font-medium text-gray-700 mb-2">Pool
  @if(!$search)
    <span class="text-red-500 text-sm">*</span>
  @endif
  </label>
<div class="sm:flex gap-8">
  <div>
    <select
      class="mb-2 h-[42px] block px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
      id="pool_id"
      name="pool_id"
      {{ !$search ? 'required' : '' }}
    >

    @if($search)
      <option value="any">&mdash; Any &mdash;</option>
    @endif

      @foreach ($pools as $pool)
        @php
          if($search) {
            $checked = (request('pool_id') == $pool->id) ? 'selected' : FALSE;
          } else {
            $checked = ($selected == $pool->id) ? 'selected' : FALSE;
          }
        @endphp
        <option
          value="{{ $pool->id }}"
          {{ $checked ? 'selected' : '' }}
        >{{ $pool->name }}</option>
      @endforeach
    </select>
  </div>

</div>
