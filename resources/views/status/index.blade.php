<x-layout>
  <x-slot:heading>
    Statuses
  </x-slot:heading>

  <x-slot:containerClass>mx-auto max-w-2xl px-4 py-6 sm:px-6 lg:px-8</x-slot:containerClass>

  <hr class="h-px my-8 bg-gray-300 border-0">

  <div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
      <h2 class="text-xl font-semibold text-gray-800">Statuses</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 w-8"></th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3"></th>
          </tr>
        </thead>
        <tbody id="sortable-statuses">
          @forelse($statuses as $status)

            {{-- View Row --}}
            <tr class="view-row odd:bg-white even:bg-gray-50 hover:bg-gray-50 transition-colors duration-200"
                data-id="{{ $status->id }}">
              <td class="px-6 py-4 whitespace-nowrap cursor-grab text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 {{ $status->tailwind_class }}">
                  {{ $status->status_name }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <button onclick="openEdit({{ $status->id }})"
                        class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Edit</button>
              </td>
            </tr>

            {{-- Edit Row (hidden by default) --}}
            <tr class="edit-row bg-indigo-50 border-l-4 border-indigo-400" id="edit-row-{{ $status->id }}" style="display: none;">
              <td class="px-6 py-4 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>
              </td>
              <td class="px-6 py-4" colspan="2">
                <div class="space-y-4">

                  {{-- Name input with live preview --}}
                  <div class="flex items-center gap-4">
                    <input type="text"
                           id="name-input-{{ $status->id }}"
                           value="{{ $status->status_name }}"
                           class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-48"
                           oninput="updatePreview({{ $status->id }})" />
                    <div id="preview-{{ $status->id }}"
                         class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 {{ $status->tailwind_class }}">
                      {{ $status->status_name }}
                    </div>
                  </div>

                  {{-- Color swatches --}}
                  <div class="flex flex-wrap gap-2" id="swatches-{{ $status->id }}">
                    @php
                      $colors = config('taxonomy.colors');
                    @endphp
                    @foreach($colors as $color)
                      <button type="button"
                              onclick="selectColor({{ $status->id }}, '{{ $color }}')"
                              class="swatch w-7 h-7 rounded-full {{ $color }} ring-offset-2 transition-all duration-150 {{ $status->tailwind_class === $color ? 'ring-2 ring-gray-800' : '' }}"
                              data-color="{{ $color }}">
                      </button>
                    @endforeach
                  </div>

                  {{-- Actions --}}
                  <div class="flex items-center gap-3">
                    <button onclick="saveRow({{ $status->id }})"
                            id="save-btn-{{ $status->id }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-1.5 rounded-lg transition-colors duration-200">
                      Save
                    </button>
                    <button onclick="cancelEdit({{ $status->id }})"
                            class="text-gray-500 hover:text-gray-700 text-sm">
                      Cancel
                    </button>
                    <span id="row-feedback-{{ $status->id }}" class="text-sm text-green-600" style="display:none;">Saved!</span>
                  </div>

                </div>
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                No statuses found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Reorder save bar --}}
    <div class="px-6 py-4 border-t border-gray-200" id="save-bar" style="display: none;">
      <button id="save-order"
              class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
        Save Order
      </button>
      <span id="save-feedback" class="ml-3 text-sm text-green-600" style="display: none;">Order saved!</span>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {

      // Track selected colors per row
      const selectedColors = {};

      // Expose functions to global scope for onclick handlers
      window.openEdit = function(id) {
        document.getElementById('edit-row-' + id).style.display = '';
        document.querySelector(`tr.view-row[data-id="${id}"]`).style.display = 'none';
      };

      window.cancelEdit = function(id) {
        document.getElementById('edit-row-' + id).style.display = 'none';
        document.querySelector(`tr.view-row[data-id="${id}"]`).style.display = '';
      };

      window.updatePreview = function(id) {
        const input = document.getElementById('name-input-' + id);
        const preview = document.getElementById('preview-' + id);
        preview.textContent = input.value || 'Preview';
      };

      window.selectColor = function(id, color) {
        selectedColors[id] = color;

        // Update swatch ring
        const swatches = document.querySelectorAll(`#swatches-${id} .swatch`);
        swatches.forEach(s => {
          s.classList.remove('ring-2', 'ring-gray-800');
          if (s.dataset.color === color) {
            s.classList.add('ring-2', 'ring-gray-800');
          }
        });

        // Update preview badge color
        const preview = document.getElementById('preview-' + id);
        preview.className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 ${color}`;
      };

      window.saveRow = async function(id) {
        const name = document.getElementById('name-input-' + id).value.trim();
        const color = selectedColors[id] || null;
        const saveBtn = document.getElementById('save-btn-' + id);
        const feedback = document.getElementById('row-feedback-' + id);

        if (!name) return;

        const payload = { status_name: name };
        if (color) payload.tailwind_class = color;

        // Add Laravel method spoofing for PUT
        payload._method = 'PUT';

        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

        try {
          const response = await fetch(`/taxonomy/status/${id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
          });

          if (response.ok) {
            // Update the view row badge
            const viewBadge = document.querySelector(`tr.view-row[data-id="${id}"] div`);
            viewBadge.textContent = name;
            if (color) {
              viewBadge.className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 ${color}`;
            }

            feedback.style.display = 'inline';
            setTimeout(() => {
              feedback.style.display = 'none';
              cancelEdit(id);
            }, 1500);
          } else {
            alert('Failed to save. Please try again.');
          }
        } catch (e) {
          alert('An error occurred. Please try again.');
        } finally {
          saveBtn.disabled = false;
          saveBtn.textContent = 'Save';
        }
      };

      // Sortable
      const tbody = document.getElementById('sortable-statuses');
      const saveBar = document.getElementById('save-bar');
      const saveBtn = document.getElementById('save-order');
      const saveFeedback = document.getElementById('save-feedback');

      Sortable.create(tbody, {
        handle: 'td:first-child',
        animation: 150,
        filter: '.edit-row',
        preventOnFilter: false,
        onEnd: function () {
          saveBar.style.display = 'block';
        }
      });

      saveBtn.addEventListener('click', async function () {
        const rows = tbody.querySelectorAll('tr.view-row[data-id]');
        const order = Array.from(rows).map(row => row.dataset.id);

        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';

        try {
          const response = await fetch("{{ route('taxonomy.status.reorder') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order })
          });

          if (response.ok) {
            saveFeedback.style.display = 'inline';
            setTimeout(() => saveFeedback.style.display = 'none', 3000);
          } else {
            alert('Failed to save order. Please try again.');
          }
        } catch (e) {
          alert('An error occurred. Please try again.');
        } finally {
          saveBtn.disabled = false;
          saveBtn.textContent = 'Save Order';
        }
      });

    });
  </script>

</x-layout>
