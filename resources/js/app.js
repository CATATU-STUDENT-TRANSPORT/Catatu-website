import './bootstrap';
import { createApp } from 'vue';
import Alpine from 'alpinejs';

import SeatPicker from './components/SeatPicker.vue';
import RouteMap from './components/RouteMap.vue';

// Mount a Vue app per element marked `data-vue-root`. We don't mount Vue at
// the layout level — the runtime-only build can't compile arbitrary Blade
// HTML as a template, which previously blanked out non-Vue pages.
document.querySelectorAll('[data-vue-root]').forEach((el) => {
    const app = createApp({});
    app.component('seat-picker', SeatPicker);
    app.component('route-map', RouteMap);
    app.mount(el);
});

window.Alpine = Alpine;
Alpine.start();
