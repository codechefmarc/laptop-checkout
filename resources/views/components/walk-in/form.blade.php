
<form method="POST" action="{{ route('walk_in_log.store') }}" class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-md">
  @csrf
  <div class="space-y-6">

    <div>
        <label for="support_category_id" class="block text-md font-medium text-gray-700 mb-2">
          Category
          <span class="text-red-500 text-sm">*</span>
        </label>
        <div class="grid gap-3 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach($supportCategories as $category)
          <div class="flex items-center">
            <input
              id="support_category_{{ $category->id }}"
              name="support_category_id[]"
              type="checkbox"
              value="{{ $category->id }}"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              {{ in_array($category->id, old('support_category_id', isset($walkIn) ? $walkIn->supportCategories->pluck('id')->toArray() : [])) ? 'checked' : '' }}
            >
            <label for="support_category_{{ $category->id }}" class="ml-2 block text-sm text-gray-700" title="{{ $category->description }}">
              {{ $category->name }}
            </label>
          </div>
          @endforeach
        </div>
        @error('support_category_id')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="w-full">
        <label for="description" class="block text-md font-medium text-gray-700 mb-2">
          Description <small class="text-gray-500">(optional)</small>
        </label>
        <textarea
          id="description"
          name="description"
          rows="2"
          class="w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >{{ old('description', $walkIn->description ?? '') }}</textarea>
        @error('description')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
      </div>


      @php
        $durationValue = isset($walkIn) ? round($walkIn->created_at->diffInMinutes(now()), 0) : '';
      @endphp

      <div>
        <label for="duration_minutes" class="block text-md font-medium text-gray-700 mb-2">
          Est. duration <small class="text-gray-500">(minutes)</small>
          @if(isset($type) && $type === 'edit')
            <span class="text-red-500 text-sm">*</span>
          @endif
        </label>
        <div class="flex items-center gap-2">
          <input
            type="number"
            id="duration_minutes"
            name="duration_minutes"
            @if(isset($type) && $type === 'edit') required @endif
            value="{{ old('duration_minutes', $walkIn->duration_minutes ?? $durationValue) }}"
            class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          >
          <span id="hour-conversion">
            @php
              if ($durationValue >= 60) {
                echo round($durationValue / 60, 1) . ' hours';
              }
            @endphp
          </span>
        </div>
        @if(!isset($type) || $type !== 'edit')
          <small class="text-xs text-gray-500">Leave empty to start timer.</small>
        @endif
        @error('duration_minutes')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-baseline gap-4">
        <input
          id="escalate"
          name="escalate"
          type="checkbox"
          value="yes"
          class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
          {{ old('escalate', isset($walkIn) ? ($walkIn->escalated ? 'yes' : 'no') : '') === 'yes' ? 'checked' : '' }}
        >
        <label for="escalate" class="text-md font-medium text-gray-700 mb-2">Escalate to ITC lab specialist</label>
      </div>

  </div>

  <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
    {{ $buttonText ?? 'Add Walk In Log Entry' }}
  </button>
  @if (isset($walkIn))
    <a href="{{ route('walk_in_log.index') }}" class="ml-4 text-sm/6 font-semibold text-gray-900 cursor-pointer">
      Cancel
    </a>
  @endif
</form>

<script>
  const durationInput = document.getElementById('duration');
  const hourConversion = document.getElementById('hour-conversion');

  durationInput.addEventListener('input', () => {
    const minutes = parseFloat(durationInput.value);
    if (!isNaN(minutes) && minutes >= 60) {
      const hours = (minutes / 60).toFixed(1);
      hourConversion.textContent = `${hours} hours`;
    } else {
      hourConversion.textContent = 'minutes';
    }
  });
</script>
