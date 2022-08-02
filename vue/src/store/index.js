import {createStore} from "vuex";

const store = createStore({
    state:{
        user:{
            data:{
                name: 'Martin',
                age : 33,
            },
            token: "cool",
            // token: null,
        }
    },
    getters:{},
    actions:{
        register(){

        }
    },
    mutations:{},
    modules:{},
})

export default store;
