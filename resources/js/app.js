import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index.js';
import 'vue3-easy-data-table/dist/style.css';
import DataTable from 'datatables.net-vue3'
import DataTablesLib from 'datatables.net-bs5';
import 'bootstrap/dist/css/bootstrap-grid.min.css';
import 'bootstrap/dist/css/bootstrap-utilities.min.css';

const app = createApp(App);

app.use(router);
DataTable.use(DataTablesLib);
app.mount('#app');