import { createApp } from 'vue'

import router from './router'
import store  from './store'
import App from './App.vue'
import './index.css'

import components from "./components/UI"

const app = createApp(App);

components.forEach(component => {
    app.component(component.name, component)
});

app
    .use(router)
    .use(store)
    .mount('#app')
