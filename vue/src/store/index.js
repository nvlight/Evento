import {createStore} from "vuex";
import axiosClient from "../axios.js"
import {tagModule} from "./tagModule.js";
import {eventoModule} from "./eventoModule.js";
import {diagramModule} from "./diagramModule.js";
import {onepassCategoryModule} from "./onepassCategoryModule.js";

const store = createStore({
    state:{
        mainDevSiteUrl: 'https://mgdev.ru',
        darkModeSessionStorageKey: 'darkMode',
        darkMode: Boolean(sessionStorage.getItem('darkMode')),
        user:{
            data:{},
            token: sessionStorage.getItem('TOKEN'),
        },
        notification: {
            show: false,
            type: null,
            message: '',
        },
        avatarLoading: false,
    },
    getters:{
        isDarkModeEnabled: () => (state) => {
            return state.darkMode;
        },
    },
    actions:{
        getUser({commit}){
            return axiosClient.get('/user')
                .then( response => {
                    if (response.data){
                        commit('setUserData', response.data)
                    }
                    return response;
                })
        },
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
        setUserAvatar({commit}, data){
            commit('setAvatarLoading', true);
            return axiosClient.post('/user/profile/avatar', data)
                .then( response => {

                    if (response.data.success){
                        commit('setUserData', response.data.user);
                        commit('setAvatarLoading', false);
                    }

                    return response;
                })
                .catch( response => {
                    commit('setAvatarLoading', false);
                    return response;
                })
        },
        delUserAvatar({commit}){
            commit('setAvatarLoading', true);
            return axiosClient.delete('/user/profile/avatar')
                .then( response => {

                    if (response.data.success){
                        commit('setUserData', {});
                        commit('setAvatarLoading', false);
                    }

                    return response;
                })
                .catch( response => {
                    commit('setAvatarLoading', false);
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
        setUserData: (state, userData) => {
            state.user.data  = userData;
        },
        notify(state, {message, type, timeout}){
            state.notification.show = true;
            state.notification.type = type;
            state.notification.message = message;

            setTimeout( () => {
                state.notification.show = false;
            }, timeout ? timeout : 1500)
        },
        toggleDarkMode(state){
            state.darkMode = !state.darkMode;
            const dmSessKey = state.darkModeSessionStorageKey;

            let sdm = sessionStorage.getItem(dmSessKey);
            if (Boolean(sdm)) {
                sessionStorage.setItem(dmSessKey, '');
            }else{
                sessionStorage.setItem(dmSessKey, '1');
            }
        },
        setAvatarLoading(state, value){
            state.avatarLoading = value;
        }
    },
    modules:{
        tag: tagModule,
        evento: eventoModule,
        diagram: diagramModule,
        onepassCategory: onepassCategoryModule,
    },
});

export default store;
