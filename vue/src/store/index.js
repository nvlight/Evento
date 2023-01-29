import {createStore} from "vuex";
import axiosClient from "../axios.js"
import {tagModule} from "./tagModule.js";
import {eventoModule} from "./eventoModule.js";
import {diagramModule} from "./diagramModule.js";

const store = createStore({
    state:{
        user:{
            data:{},
            token: sessionStorage.getItem('TOKEN'),
        },
        notification: {
            show: false,
            type: null,
            message: '',
        },
    },
    actions:{
        register({commit}, user){
            return axiosClient.post('/register', user)
                .then( ({data}) => {
                    commit('setUser', data)
                    return data;
                })
        },
        // 12345678aA@
        // 12345678aA@
        login({commit}, user){
            return axiosClient.post('/login', user)
                .then( ({data}) => {
                    commit('setUser', data)
                    return data;
                })
        },
        logout({commit}){
            return axiosClient.post('/logout')
                .then( response => {
                    commit('logout');
                    return response;
                })
        },
    },
    mutations:{
        logout: (state) => {
            state.user.data  = {};
            state.user.token = null;
            sessionStorage.removeItem('TOKEN');
        },
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data  = userData.user;
            sessionStorage.setItem('TOKEN', userData.token);
        },
        notify(state, {message, type, timeout}){
            state.notification.show = true;
            state.notification.type = type;
            state.notification.message = message;

            setTimeout( () => {
                state.notification.show = false;
            }, timeout ? timeout : 1500)
        },
    },
    modules:{
        tag: tagModule,
        evento: eventoModule,
        diagram: diagramModule,
    },
});

export default store;
