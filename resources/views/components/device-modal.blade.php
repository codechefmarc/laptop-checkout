@if(session('device_not_found') || (old('creating_device') && $errors->any()))
<!-- Modal Overlay -->
<div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 1rem;">

  <!-- Modal Content -->
  <div class="bg-white rounded-lg shadow-xl max-w-md max-h-[90vh] overflow-y-auto">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
      <h3 class="text-lg font-semibold text-gray-900">New Device Details</h3>
      <p class="text-sm text-gray-600 mt-1">This device doesn't exist yet. Please provide additional details.</p>
    </div>

    <!-- Form -->
    <form method="POST" action="/">
      @csrf

      <input type="hidden" name="creating_device" value="1">

      <!-- Hidden fields for activity data -->
      <input type="hidden" name="status_id" value="{{ old('status_id') }}" />
      <input type="hidden" name="notes" value="{{ old('notes') }}" />
      <input type="hidden" name="username" value="{{ old('username') }}" />

      <div class="px-6 py-4 space-y-4">
        <!-- SRJC Tag -->
        <div>
          <label for="srjc_tag" class="block text-sm font-medium text-gray-700 mb-2">SRJC Tag</label>
          <input
            type="text"
            id="srjc_tag"
            name="srjc_tag"
            value="{{ session('device_data.srjc_tag') ?: old('srjc_tag') }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="e.g. SRJC001234"
          />
          @error('srjc_tag')
            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Serial Number -->
        <div>
          <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Serial Number <span class="text-red-500 text-sm">*</span></label>
          <input
            type="text"
            id="serial_number"
            name="serial_number"
            required
            value="{{ session('device_data.serial_number') ?: old('serial_number') }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="e.g. ABC123456789"
          />
          @error('serial_number')
            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Model Number -->
        <div class="mb-8">
          <label for="model_number" class="block text-sm font-medium text-gray-700 mb-2">Model Number <span class="text-red-500 text-sm">*</span></label>
          <input
            type="text"
            id="model_number"
            name="model_number"
            value="{{ old('model_number') }}"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="e.g. Dell OptiPlex 7090"
          />
          @error('model_number')
            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-gray-200 flex gap-2 justify-end space-x-3">
        <a href="/" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
          Cancel
        </a>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
          Create Device & Log Activity
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
    window.location.href = '/';
  }
});
</script>
@endif
