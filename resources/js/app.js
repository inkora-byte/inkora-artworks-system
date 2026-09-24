// Inkora Artworks Management System
// resources/js/app.js

import './bootstrap';

// Bootstrap 5 JS (dropdowns, modals, tooltips, etc.)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Alpine.js — used for the dynamic add/remove-row pattern (Bill of Materials,
// Purchase Order line items, Custom Order materials) and other lightweight
// interactivity.
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// SweetAlert2 — exposed globally so Blade views/components call Swal.fire(...)
// directly without a per-page import.
import Swal from 'sweetalert2';
window.Swal = Swal;

// Chart.js — exposed globally for Dashboard/Reports charts.
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;

// DataTables — modern (2.x) jQuery-free core. Exposed globally as
// `window.DataTable` so Blade views initialize tables with:
//   new DataTable('#table-id', { ...options })
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;

// Inkora shared UI helpers (Milestone 0.6) — small, reusable JS utilities
// used by Blade Components (e.g. <x-confirm-button>) so every destructive
// action across every future module gets the same SweetAlert2 confirmation
// behavior from one place, never reimplemented per view.
window.Inkora = window.Inkora || {};

Inkora.confirmSubmit = function (form, options = {}) {
    Swal.fire({
        title: options.title || 'Are you sure?',
        text: options.text || 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: options.confirmButtonText || 'Yes, proceed',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#7E6BAF',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};
