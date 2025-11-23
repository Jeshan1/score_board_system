import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { store } from './store/auth.js';
import './bootstrap.js'


const app = createApp(App);

app.use(store);   // ✅ this is correct
app.use(router);

app.mount('#app');
