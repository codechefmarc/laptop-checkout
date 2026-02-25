
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
