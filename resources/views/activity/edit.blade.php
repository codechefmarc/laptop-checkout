<x-layout>
<x-slot:heading>
  Edit Device And Activity
</x-slot:heading>

<form method="POST" action="{{ route('activities.patch', $activity->id) }}">
  @csrf
  @method('PATCH')
  <div class="sm:flex justify-between max-w-2xl border-b border-gray-900/10">
    <div class="pb-12">
      <div class="mt-10 flex flex-col gap-y-8">
        <h3 class="text-2xl">Device</h3>
        <div class="">
          <label for="srjc_tag" class="block text-sm font-medium text-gray-700 mb-2">SRJC Tag</label>
          <div class="mt-2">
          <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
              id="srjc_tag"
              type="text"
              name="srjc_tag"
              value="{{ $activity->device->srjc_tag }}"
              class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
            @error('srjc_tag')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="">
          <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Serial Number <span class="text-red-500 text-sm">*</span></label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
              id="serial_number"
              type="text"
              name="serial_number"
              required
              value="{{ $activity->device->serial_number }}"
              class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
            @error('serial_number')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="">
          <label for="model_number" class="block text-sm font-medium text-gray-700 mb-2">Model Number <span class="text-red-500 text-sm">*</span></label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
              id="model_number"
              type="text"
              name="model_number"
              required
              value="{{ $activity->device->serial_number }}"
              class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
            @error('model_number')
              <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

      </div>
    </div>

    <!--Activity-->

    <div class="">
      <div class="mt-10 flex flex-col gap-y-8">
        <h3 class="text-2xl">Activity</h3>
        <x-status-select :selected="$activity->status_id" :statuses="$statuses" />

        <div class="">
          <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
          <div class="mt-2">
            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
              <input
              id="notes"
              type="text"
              name="notes"
              value="{{ $activity->notes }}"
              class="block min-w-0 grow py-1.5 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
            </div>
          </div>
        </div>

      </div>
    </div>


  </div>

  <div class="mt-6 sm:flex max-w-2xl items-center justify-between">
    <div class="flex items-center gap-x-6 justify-self-end">
      <button type="submit" class="ml-3 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
      <a href="/" class="text-sm/6 font-semibold text-gray-900 cursor-pointer">Cancel</button>
    </div>
    <div class="flex flex-col gap-3">
      <div class="flex-col items-center">
        <button form="delete-device-form" class="text-red-500 font-bold cursor-pointer">Delete Device</button>
        <small class="block text-gray-500">Deleting a device will also delete any associated activities.</small>
      </div>
      <div class="flex-col items-center">
        <button form="delete-activity-form" class="text-red-500 font-bold cursor-pointer">Delete Activity</button>
      </div>
    </div>
  </div>
</form>
<form id="delete-device-form" method="POST" action="{{ route('device.delete', $activity->device->id) }}" class="hidden">
  @csrf
  @method('DELETE')
</form>

<form id="delete-activity-form" method="POST" action="{{ route('activities.delete', $activity->id) }}" class="hidden">
  @csrf
  @method('DELETE')
</form>

</x-layout>
