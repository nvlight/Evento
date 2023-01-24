import { createApp } from 'vue'

import router from './router'
import store  from './store'
import App from './App.vue'
import './index.css'

import components from "./components/UI"
import directives from "./directives";

const app = createApp(App);

components.forEach(component => {
    app.component(component.name, component)
});
directives.forEach( directive => {
    app.directive(directive.name, directive);
});

app
    .use(router)
    .use(store)
    .mount('#app')
