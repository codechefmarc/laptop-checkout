
<form method="POST" action="{{ route('walk_in_log.store') }}" class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-md">
  @csrf
  <div class="space-y-6">
    <div class="md:flex justify-between gap-4">

      <div class="w-full">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Issue Description</label>
        <textarea
          id="description"
          name="description"
          rows="4"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          required
        >{{ old('description', $walkIn->description ?? '') }}</textarea>
        @error('description')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
        <label for="support_category_id" class="block text-sm font-medium text-gray-700 mb-2">Support Category</label>
        <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach($supportCategories as $category)
          <div class="flex items-center mb-2">
            <input
              id="support_category_{{ $category->id }}"
              name="support_category_id[]"
              type="checkbox"
              value="{{ $category->id }}"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              {{ in_array($category->id, old('support_category_id', isset($walkIn) ? $walkIn->supportCategories->pluck('id')->toArray() : [])) ? 'checked' : '' }}
            >
            <label for="support_category_{{ $category->id }}" class="ml-2 block text-sm text-gray-700">
              {{ $category->name }}
            </label>
          </div>
          @endforeach
        </div>
        @error('support_category_id')
          <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="escalate" class="block text-sm font-medium text-gray-700 mb-2">Escalate to ITC lab specialist?</label>
        <div class="flex items-center gap-4">
          <div class="flex items-center">
            <input
              id="escalate"
              name="escalate"
              type="checkbox"
              value="yes"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
              {{ old('escalate', isset($walkIn) ? ($walkIn->escalated ? 'yes' : 'no') : '') === 'yes' ? 'checked' : '' }}
            >
          </div>
      </div>

  </div>

  <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
    {{ $buttonText ?? 'Add Walk In Log Entry' }}
  </button>
  @if (isset($walkIn))
    <a href="{{ route('walk_in_log') }}" class="ml-4 text-sm/6 font-semibold text-gray-900 cursor-pointer">
      Cancel
    </a>
  @endif
</form>
