import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index.js';
import 'vue3-easy-data-table/dist/style.css';
import 'bootstrap/dist/css/bootstrap-grid.min.css';
import 'bootstrap/dist/css/bootstrap-utilities.min.css';
import "../asset/css/style.css";
import "../asset/css/responsive.css";
import "../asset/css/font-awesome.min.css";
import store from './modules/index.js';

const app = createApp(App);
app.use(router);
app.use(store);
app.mount('#app');
