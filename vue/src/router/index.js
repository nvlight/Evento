import {createRouter, createWebHistory} from "vue-router";
import DefaultLayout from "../components/DefaultLayout.vue"
import AuthLayout from "../components/AuthLayout.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"
import store from "../store"
import Eventos from "../views/Eventos.vue";
import UserProfile from "../components/user/UserProfile.vue";
import Onepass from "../views/Onepass.vue";
import OnepassCategories from "../components/onepass/OnepassCategories.vue";
import Tags from "../views/Tags.vue";

const routes = [
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
                path: '/tags',
                name: 'Tags',
                component: Tags, //TagIndex,
            },
            {
                path: '/profile',
                name: 'Profile',
                component: UserProfile,
            },
        ],
    },

    {
        path: '/onepass',
        redirect: '/onepass/entries',
        meta: { requiresAuth: true },
        component: DefaultLayout,
        children: [
            {
                path: '/onepass/entries',
                name: 'OnepassEntries',
                component: Onepass,
            },
            {
                path: '/onepass/categories',
                name: 'OnepassCategories',
                component: OnepassCategories,
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
