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
                        dispatch('addItem', res.data.data);
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

        delItem({dispatch}, id){
            dispatch('delItemQuery', id);
        },
        delItemQuery({dispatch,state, commit}, id){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .delete(`/${modelName}/${id}`)
                .then((res)=>{
                    if (res.data.success){
                        commit('delItem', id);
                    }
                    return res;
                })
                .catch( (err) => {
                    console.log('we got error:',err);
                })
            return response;
        },
    },
    mutations: {
        setItems: (state, items) => {
            state.items = items;
        },
        addItem: (state, item) => {
            state.items.unshift(item);
        },

        delItem: (state, id) => {
            state.items = state.items.filter(
                t => t.id != id
            );
        },
    },
    namespaced: true,
}
