import './bootstrap';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';
import 'flatpickr/dist/flatpickr.min.css';
import Alpine from 'alpinejs';
import Swal from "sweetalert2";

import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

import '@fullcalendar/core/locales/id';

window.Swal = Swal;
window.FullCalendar = { Calendar, dayGridPlugin, timeGridPlugin, interactionPlugin };

window.Alpine = Alpine;
Alpine.start();

window.flatpickr = flatpickr;
window.Indonesian = Indonesian;
