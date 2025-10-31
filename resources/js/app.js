import './bootstrap';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';
import 'flatpickr/dist/flatpickr.min.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.flatpickr = flatpickr;
window.Indonesian = Indonesian;
