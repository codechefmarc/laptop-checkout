
document.addEventListener('DOMContentLoaded', function () {
  const tbody = document.getElementById('sortable-list');
  const saveBar = document.getElementById('save-bar');
  const saveBtn = document.getElementById('save-order');
  const saveFeedback = document.getElementById('save-feedback');
  const reorderUrl = tbody.dataset.reorderUrl;

  Sortable.create(tbody, {
    handle: 'td:first-child',
    animation: 150,
    onEnd: function () {
      saveBar.style.display = 'block';

      const rows = tbody.querySelectorAll('tr[data-id]');
      rows.forEach((row, index) => {
          const weightDisplay = row.querySelector('.weight-display');
          if (weightDisplay) weightDisplay.textContent = (index + 1) * 10;
      });
    }
  });

  saveBtn.addEventListener('click', async function () {
    const rows = tbody.querySelectorAll('tr[data-id]');
    const order = Array.from(rows).map(row => row.dataset.id);

    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    try {
      const response = await fetch(reorderUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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



const selectedColors = {};
const tailwindClassInput = document.querySelector(`input[name="tailwind_class"]`);

window.updatePreview = function() {
  const input = document.getElementById('status_name');
  const preview = document.getElementById('status_preview');
  preview.textContent = input.value || 'Preview';
};

window.selectColor = function(id, color) {
  selectedColors[id] = color;

  // Update swatch ring
  const swatches = document.querySelectorAll(`#swatches .swatch`);
  swatches.forEach(s => {
    s.classList.remove('ring-2', 'ring-gray-800');
    if (s.dataset.color === color) {
      s.classList.add('ring-2', 'ring-gray-800');
    }
  });

  // Update preview badge color
  const preview = document.getElementById('status_preview');
  preview.className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full text-neutral-50 ${color}`;
  tailwindClassInput.value = color;
};
