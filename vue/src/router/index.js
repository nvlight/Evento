import {createRouter, createWebHistory} from "vue-router";
import HelloWorld from "../components/HelloWorld.vue"
import AuthLayout from "../components/AuthLayout.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"

const routes = [
    {
        path: '/',
        //redirect: '/login',
        name: 'HelloWorld',
        //meta: { requiresAuth: true },
        component: HelloWorld,
        // children: [
        //     {
        //         path: '/dashboard',
        //         name: 'Dashboard',
        //         component: Dashboard,
        //     },
        //     {
        //         path: '/surveys',
        //         name: 'Surveys',
        //         component: Surveys,
        //     },
        // ],
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
    // {
    //     path: '/auth',
    //     redirect: '/login',
    //     name: 'Auth',
    //     component: AuthLayout,
    //     meta: { isGuest: true },
    //     children: [
    //         {
    //             path: '/login',
    //             name: 'Login',
    //             component: Login,
    //         },
    //         {
    //             path: '/register',
    //             name: 'Register',
    //             component: Register,
    //         },
    //     ],
    // },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
})

// router.beforeEach( (to,from, next) => {
//     if (to.meta.requiresAuth && !store.state.user.token){
//         next({name: 'Login'})
//     }else if(store.state.user.token && to.meta.isGuest ){
//         next({name: 'Dashboard'});
//     }else{
//         next();
//     }
// })

export default router;
