import {createStore} from "vuex";
import axiosClient from "../axios.js"
import {tagModule} from "./tagModule.js";

const store = createStore({
    state:{
        user:{
            data:{},
            token: sessionStorage.getItem('TOKEN'),
        },
        siteImgStaticPath: "http://laravel8-evento:87/storage/",
    },
    getters:{
        getSiteImgStaticPath(state){
            return state.siteImgStaticPath;
        }
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
        }
    },
    modules:{
        tag: tagModule,
    },
});

export default store;
