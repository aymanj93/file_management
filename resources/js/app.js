import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import App from './App.vue';
import Loading from './elements/Loading.vue';
import { vfmPlugin } from "vue-final-modal";

app.component('App', App);
app.component('Loading', Loading);
app.use(vfmPlugin);

app.mount('#app');
