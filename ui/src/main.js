
import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import './assets/icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const app = createApp(App); 
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

app.use(router);
app.use(pinia);
app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app');
