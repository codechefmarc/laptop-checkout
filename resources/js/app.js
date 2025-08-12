import './bootstrap';
import '@tailwindplus/elements';

import flatpickr from "flatpickr";
import "../../node_modules/flatpickr/dist/flatpickr.css";

const date_range = document.getElementById('date_range');
if (date_range) {
  flatpickr(date_range, {
    mode: "range",
  });
}
