@if(session('duplicate_activity'))
  <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem;">
    <div class="bg-white rounded-lg shadow-xl max-w-md max-h-[90vh] overflow-y-auto">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Duplicate Activity Detected</h3>
        <p class="text-sm text-gray-600 mt-1">This device is currently set to <strong class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 {{ session('duplicate_activity.tailwind_class') }}">{{ session('duplicate_activity.status_name') }}</strong> Do you want to add another activity with this same status?</p>
      </div>
      <form method="POST" action="{{ route('log') }}">
        @csrf
        <input type="hidden" name="force_duplicate" value="1">
        <input type="hidden" name="status_id" value="{{ session('duplicate_activity.status_id') }}" />
        <input type="hidden" name="srjc_tag" value="{{ session('duplicate_activity.srjc_tag') }}">
        <input type="hidden" name="serial_number" value="{{ session('duplicate_activity.serial_number') }}">
        <input type="hidden" name="notes" value="{{ session('duplicate_activity.notes') }}" />

        <div class="px-6 py-4 border-t border-gray-200 flex gap-2 justify-end space-x-3">
          <a href="{{ route('log') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
            Cancel
          </a>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
            Create Duplicate Activity
          </button>
        </div>
      </form>
    </div>
  </div>
  <script>
  // Prevent background scrolling
  document.body.style.overflow = 'hidden';

  // Close on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      window.location.href = "{{ route('log') }}";
    }
  });
  </script>
@endif
