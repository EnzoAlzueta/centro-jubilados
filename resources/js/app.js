import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// import './bootstrap'; // Configuración base de Laravel

// 1. Importar Bootstrap (JS)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// 2. Importar jQuery y hacerlo global (necesario para DataTables)
import jQuery from 'jquery';
window.$ = jQuery;
window.jQuery = jQuery;

// 3. Importar DataTables (Estilo Bootstrap 5)
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;

// 4. Importar TomSelect
import TomSelect from 'tom-select';
window.TomSelect = TomSelect;

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';
window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.esLocale = esLocale;
window.interactionPlugin = interactionPlugin;
