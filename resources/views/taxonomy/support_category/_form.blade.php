<form method="POST" action="{{ route('taxonomy.support_category.store') }}">
  @csrf

  <input type="hidden" name="return_url" value="{{ $returnUrl }}">
  <input type="hidden" name="tailwind_class" value="bg-slate-500">
  <div class="max-w-2xl border-b border-gray-900/10 mx-auto bg-white p-8 rounded-lg shadow-md">
    <div class="pb-12">
      <div class="mt-10 flex flex-col gap-y-2">
        <div class="">
          <label for="support_category_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
          <div class="mt-2">
          <div class="flex items-center gap-4">
                    <input type="text"
                      name="name"
                      id="name"
                      value="{{ old('name', $supportCategory->name ?? '') }}"
                      class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-48"
                      autofocus="autofocus"
                      />
                  </div>
            @error('name')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <div class="mt-2">
            <textarea name="description" id="description" rows="3" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-full">{{ old('description', $supportCategory->description ?? '') }}</textarea>
              @error('description')
                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
              @enderror
            </div>
        </div>

        <div>
          <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (for ordering)</label>
          <input
            type="number"
            id="weight"
            class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-48"
            name="weight"
            value="{{ old('weight', $supportCategory->weight ?? 100) }}"
            />
        </div>

      </div>
    </div>
<div class="mt-6 sm:flex max-w-2xl items-center justify-between">
    <div class="flex items-center gap-x-6 justify-self-end">
      <button type="submit" class="ml-3 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $buttonText ?? 'Save Support Category' }}</button>
      <a href="{{ route('taxonomy.support_category.index') }}" class="text-sm/6 font-semibold text-gray-900 cursor-pointer">Cancel</a>
    </div>

  </div>

  </div>
</form>
