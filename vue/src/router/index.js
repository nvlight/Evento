import {createRouter, createWebHistory} from "vue-router";
import DefaultLayout from "../components/DefaultLayout.vue"
import AuthLayout from "../components/AuthLayout.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"
import store from "../store"
import Eventos from "../views/Eventos.vue";
import TagIndex from "../components/tag/TagIndex.vue";
import EventosFilter from "../views/EventosFilter.vue";

const routes = [
    {
        path: '/',
        redirect: '/eventos',
        meta: { requiresAuth: true },
        component: DefaultLayout,
        children: [
            {
                path: '/eventos',
                name: 'Eventos',
                component: Eventos,
            },
            {
                path: '/eventos/filter',
                name: 'EventosFilter',
                component: EventosFilter,
            },
            {
                path: '/tags',
                name: 'Tags',
                component: TagIndex,
            },
        ],
    },

    {
        path: '/auth',
        redirect: '/login',
        name: 'Auth',
        component: AuthLayout,
        meta: { isGuest: true,},
        children: [
            {
                path: '/login',
                name: 'Login',
                component: Login,
            },
            {
                path: '/register',
                name: 'Register',
                component: Register,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach( (to,from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token){
        next({name: 'Login'})
    }else if(store.state.user.token && to.meta.isGuest ){
        next({name: 'Eventos'});
    }else{
        next();
    }
})

export default router;
