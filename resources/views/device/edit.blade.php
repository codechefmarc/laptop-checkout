<x-layout>
<x-slot:heading>
  Edit Device
</x-slot:heading>

<form method="POST" action="/">
  @csrf
  @method('PATCH')
  <div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
      <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-4">
          <label for="title" class="block text-sm/6 font-medium text-gray-900">SRJC Tag</label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
              id="title"
              type="text"
              name="title"
              placeholder="Citizen"
              value="{{ $device->stjc_tag }}"
              class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" required />
            </div>
            @error('title')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="sm:col-span-4">
          <label for="salary" class="block text-sm/6 font-medium text-gray-900">Serial Number</label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
              id="salary"
              type="text"
              name="salary"
              placeholder="$50,000/yr"
              value="{{ $device->serial_number }}"
              class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" required />
            </div>
            @error('salary')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

      </div>
      <!-- @if($errors->any())
        <div class="mt-10">
          <ul>
            @foreach($errors->all() as $error)
              <li class="text-red-500 italic">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif -->
    </div>
  </div>

  <div class="mt-6 flex items-center justify-between gap-x-6">
    <div class="flex-col items-center">
      <button form="delete-form" class="text-red-500 text-sm font-bold">Delete</button>
      <small class="block text-gray-500">Note: deleting a device will also delete any associated activities.</small>
    </div>
    <div class="flex items-center gap-x-6">
      <a href="/" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
      <button type="submit" class="ml-3 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
    </div>
  </div>
</form>
<form id="delete-form" method="POST" action="/" class="hidden">
  @csrf
  @method('DELETE')
</form>

</x-layout>
