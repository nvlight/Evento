import axiosClient from "../axios.js";

export const eventoModule = {
    state: () => ({
        itemModelName: 'evento',
        items: [],
        createItemStatus: 'none',
        currentEditItemId: 0,
        updateItemLoading: false,
    }),
    getters:{
        getcreateItemStatus(state){
            return state.createItemStatus;
        },
        getCurrentEditItemId(state){
            return state.currentEditItemId;
        },
        getCurrentEditedItem: (state) => {
            return state.items.filter( t => t.id === state.currentEditItemId)?.[0];
        },
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
            commit('setCreateItemStatus', 'start');
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
                        commit('setCreateItemStatus', 'success');
                    }else{
                        commit('setCreateItemStatus', 'fail');
                    }
                    return res;
                })
                .catch( (err) => {
                    commit('setCreateItemStatus', 'fail');
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

        setCurrentEditItemId({commit}, id){
            commit('setCurrentEditItemId', id);
        },

        updateItem({dispatch, commit}, item){
            commit('setUpdateItemLoading', true);
            return dispatch('updateItemQuery', item);
        },
        updateItemQuery({commit,dispatch,state}, item){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .patch(`/${modelName}/${item.id}`, item.formData)
                .then((res)=>{
                    if (res.data.success) {
                        dispatch('addItem', res.data.data);
                        commit('setUpdateItemLoading', true);
                    }else{
                        commit('setUpdateItemLoading', true);
                    }
                    return res;
                })
                .catch( (err) => {
                    commit('setUpdateItemLoading', false);
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
        setCreateItemStatus: (state, value) => {
            state.createItemStatus = value;
        },

        setCurrentEditItemId: (state, id) => {
            state.currentEditItemId = id
        },

        setUpdateItemLoading(state, value){
            state.updateItemLoading = value;
        }
    },
    namespaced: true,
}
