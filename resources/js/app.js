import.meta.glob([ '../fonts/**', ]);
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import axios from 'axios';

const app = createApp(App);
app.use(createPinia());
app.use(router);
app.mount('#app');

const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}


