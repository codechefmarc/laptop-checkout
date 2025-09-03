@props(
  [
    'search' => FALSE,
    'pools' => $pools,
  ]
)
<p class="block text-sm font-medium text-gray-700 mb-2">Pool
  @if(!$search)
    <span class="text-red-500 text-sm">*</span>
  @endif
</p>
<div class="sm:flex gap-8">
  <div>
    <select
      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900"
      id="pool_id"
      name="pool_id"
      {{ !$search ? 'required' : '' }}
    >

    @if($search)
      <option value="any">-- Any --</option>
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
