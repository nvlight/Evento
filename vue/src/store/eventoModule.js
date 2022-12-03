import axiosClient from "../axios.js";

export const eventoModule = {
    state: () => ({
        itemModelName: 'evento',
        items: [],
    }),
    getters:{
    },
    actions: {
        loadItems({commit, state}){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .get(`/${modelName}`) // // .get("/tags")
                .then((res)=>{
                    if (res.data.success) {
                        commit('setItems', res.data.data);
                    }
                    return res;
                })
                .catch( (err) => {
                    console.log('we got error:',err);
                })
            return response;
        },
        createItem({dispatch, commit}, item){
            return dispatch('createItemQuery', item);
        },
        createItemQuery({commit,dispatch,state}, item){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .post(`/${modelName}`, item.formData)
                .then((res)=>{
                    if (res.data.success) {
                        const itemClone = Object.assign({}, item.item);
                        itemClone.id  = res.data.savedId;
                        dispatch('addItem', itemClone);
                    }else{
                    }
                    return res;
                })
                .catch( (err) => {
                    console.log('we got error:',err);
                })
            return response;
        },
        addItem({commit}, item){
            return commit('addItem', item);
        },
    },
    mutations: {
        setItems: (state, items) => {
            state.items = items;
        },
        addItem: (state, item) => {
            state.items.unshift(item);
        },
    },
    namespaced: true,
}
