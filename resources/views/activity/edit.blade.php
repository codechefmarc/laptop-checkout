<x-layout>
<x-slot:heading>
  Edit Activity
</x-slot:heading>

<form method="POST" action="{{ route('activities.patch', $activity->id) }}">
  @csrf
  @method('PATCH')
  <input type="hidden" name="return_url" value="{{ $returnUrl }}">
  <div class="max-w-2xl border-b border-gray-900/10 mx-auto bg-white p-8 rounded-lg shadow-md">

      <div class="flex flex-col gap-y-2">
      <h3 class="text-2xl">Activity Info</h3>
      <ul>
          <li>SRJC: {{ $activity->device->srjc_tag }}</li>
          <li>Serial: {{ $activity->device->serial_number }} </li>
          <li>Model: {{ $activity->device->model_number }}</li>
          <li>Date: {{ $activity->created_at->format('m/d/Y') }} {{ $activity->created_at->format('g:iA') }}
          </li>
        </ul>

        <x-status-select :selected="$activity->status_id" :statuses="$statuses" />

        <div class="mb-3">
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





  <div class="mt-6 sm:flex max-w-2xl items-center justify-between">
    <div class="flex items-center gap-x-6 justify-self-end">
      <button type="submit" class="ml-3 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
      <a href="/" class="text-sm/6 font-semibold text-gray-900 cursor-pointer">Cancel</a>
    </div>
    <div class="flex flex-col gap-3">
      <div class="flex-col items-center">
        <button
          form="delete-activity-form"
          class="text-red-500 text-sm font-bold cursor-pointer"
          onclick="return confirm('Are you sure you want to delete this activity?')"
          >Delete Activity</button>
      </div>
    </div>
  </div>
</div>
</form>

<form id="delete-activity-form" method="POST" action="{{ route('activities.delete', $activity->id) }}" class="hidden">
  @csrf
  @method('DELETE')
  <input type="hidden" name="return_url" value="{{ $returnUrl }}">
</form>

</x-layout>
