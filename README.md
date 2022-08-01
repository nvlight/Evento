<p align="center">
    <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a>
    <a href="https://laravel.com" target="_blank"><img src="https://avatars.githubusercontent.com/u/6128107?s=200&v=4" width="200"></a>
</p>

## Evento project

Simple project with laravel 8 + vue 3.

## Dev install

> npm init vite vue

choose vue without ts

> cd vue

> npm i 

check HMR
> npm run dev

open in browser http://localhost:5173/ (in my case)

> 

install vuex
>npm i -S vuex@next

install vue-router
>npm i -S vue-router@next

Install Tailwind CSS with Vue 3 and Vite <br>
https://tailwindcss.com/docs/guides/vite
>npm i -D tailwindcss postcss autoprefixer <br>
>npx tailwindcss init -p<br>

tailwind.config.js
````
/** @type {import('tailwindcss').Config} */ 
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
````
create filex index.css -> ./src/index.css
````
@tailwind base;
@tailwind components;
@tailwind utilities;
````
Main.vue
````
import { createApp } from 'vue'
import App from './App.vue'
import './index.css'

createApp(App).mount('#app')
````

for components in https://tailwindui.com/components, im use login/register install
> npm i @headlessui/vue <br>
> npm i @heroicons/vue <br> 


[//]: # (- [Simple, fast routing engine]&#40;https://laravel.com/docs/routing&#41;.)

[//]: # (- [Powerful dependency injection container]&#40;https://laravel.com/docs/container&#41;.)

[//]: # (- Multiple back-ends for [session]&#40;https://laravel.com/docs/session&#41; and [cache]&#40;https://laravel.com/docs/cache&#41; storage.)

[//]: # (- Expressive, intuitive [database ORM]&#40;https://laravel.com/docs/eloquent&#41;.)

[//]: # (- Database agnostic [schema migrations]&#40;https://laravel.com/docs/migrations&#41;.)

[//]: # (- [Robust background job processing]&#40;https://laravel.com/docs/queues&#41;.)

[//]: # (- [Real-time event broadcasting]&#40;https://laravel.com/docs/broadcasting&#41;.)

[//]: # ()
[//]: # (Laravel is accessible, powerful, and provides tools required for large, robust applications.)
