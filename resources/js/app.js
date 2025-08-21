import './bootstrap';
import '@tailwindplus/elements';

// Date range picker.
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

const date_range = document.getElementById('date_range');
if (date_range) {
  flatpickr(date_range, {
    mode: "range",
  });
}

if (window.apiRoute === undefined) {
  window.apiRoute = '/api/model-numbers/search';
}

// Autocomplete.
import TomSelect from 'tom-select';
import "tom-select/dist/css/tom-select.min.css"

if (document.getElementById('model_number')) {

  new TomSelect('#model_number', {
    create: true,
    createOnBlur: true,
    maxItems: 1,
    load: function(query, callback) {
      if (!query.length) return callback();

      fetch(window.apiRoute + '?q=' + encodeURIComponent(query))
      .then(response => response.json())
      .then(json => {
        callback(json.map(item => ({
          value: item.model_number,
          text: item.model_number
        })));
      }).catch(() => callback());
    }
  });
}
