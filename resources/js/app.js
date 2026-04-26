import './bootstrap';
import { createApp } from 'vue';
import Alpine from 'alpinejs';

import SeatPicker from './components/SeatPicker.vue';
import RouteMap from './components/RouteMap.vue';

const app = createApp({});
app.component('seat-picker', SeatPicker);
app.component('route-map', RouteMap);

const vueRoot = document.getElementById('vue-app');
if (vueRoot) {
    app.mount(vueRoot);
}

window.Alpine = Alpine;
Alpine.start();
