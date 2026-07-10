import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.min.css';

// Initializes the computer model autocomplete select with create-via-modal support.

function initModelSelect() {
  const el = document.getElementById('computer_model_id');
  console.log(el);
  if (!el) return;

  const ts = new TomSelect(el, {
    valueField: 'value',
    labelField: 'text',
    searchField: ['text'],
    maxItems: 1,
    placeholder: 'Search or add a model...',

    load: (query, callback) => {
      if (!query.length) return callback();
      fetch(window.apiRoute + '?q=' + encodeURIComponent(query))
        .then(r => r.json())
        .then(callback)
        .catch(() => callback());
    },

    // Intercept the built-in "Add" option to open our modal instead.
    create: (input) => {
      openModelModal(input, ts);
      return false;
    },

    render: {
      option_create: (data, escape) => {
        return `<div class="create">Add <strong>${escape(data.input)}</strong>&hellip;</div>`;
      },
    },
  });

  // Expose instance so the modal save callback can reference it.
  window._modelTomSelect = ts;
}

function openModelModal(prefill, tsInstance) {
  const modal = document.getElementById('model-create-modal');
  if (!modal) return;

  // Pre-fill manufacturer and model name by splitting on first space.
  const parts = prefill.trim().split(' ');
  document.getElementById('modal-manufacturer').value = parts[0] || '';
  document.getElementById('modal-model-name').value = parts.slice(1).join(' ') || '';
  document.getElementById('modal-release-year').value = '';
  document.getElementById('modal-purchase-date').value = '';
  document.getElementById('modal-error').textContent = '';

  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeModelModal() {
  const modal = document.getElementById('model-create-modal');
  if (modal) modal.style.display = 'none';
  document.body.style.overflow = '';
}

async function saveModelModal() {
  const manufacturer = document.getElementById('modal-manufacturer').value.trim();
  const modelName    = document.getElementById('modal-model-name').value.trim();
  const releaseYear  = document.getElementById('modal-release-year').value;
  const purchaseDate = document.getElementById('modal-purchase-date').value;
  const errorEl      = document.getElementById('modal-error');

  errorEl.textContent = '';

  if (!manufacturer || !modelName || !releaseYear) {
    errorEl.textContent = 'Manufacturer, model name, and release year are required.';
    return;
  }

  try {
    const response = await fetch(window.apiRouteStore, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ manufacturer, model_name: modelName, release_year: releaseYear || null, purchase_date: purchaseDate || null }),
    });

    const data = await response.json();

    if (!response.ok) {
      if (data.errors) {
        errorEl.textContent = Object.values(data.errors).map(e => e[0]).join(' ');
      } else {
        errorEl.textContent = data.message || 'An error occurred.';
      }
      return;
    }

    // Add the new option to TomSelect and select it.
    const ts = window._modelTomSelect;
    if (ts) {
      ts.addOption(data);
      ts.setValue(data.value);
    }

    closeModelModal();

  } catch (e) {
    document.getElementById('modal-error').textContent = 'Network error. Please try again.';
  }
}

// Wire up modal buttons and escape key.
document.addEventListener('DOMContentLoaded', () => {
  initModelSelect();

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModelModal();
  });

  const closeBtn = document.getElementById('modal-close-btn');
  const saveBtn  = document.getElementById('modal-save-btn');
  if (closeBtn) closeBtn.addEventListener('click', closeModelModal);
  if (saveBtn)  saveBtn.addEventListener('click', saveModelModal);
});
