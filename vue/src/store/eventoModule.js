import axiosClient from "../axios.js";

export const eventoModule = {
    state: () => ({
        itemModelName: 'evento',
        items: [],

        createItemLoading: false,
        updateItemLoading: false,

        currentEditItemId: 0,

        editButtonClicked: false,
        createButtonClicked: false,

        createEditFormVisible: false,
        editMode: false,
        createMode: false,
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
        getItemById: (state) => (id) => {
            return state.items.filter( t => t.id === id)?.[0];
        }
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
            commit('setCreateItemLoading', true);
            return dispatch('createItemQuery', item);
        },

        createItemQuery({commit,dispatch,state}, item){
            let response;
            commit('setCreateItemLoading', true);
            const modelName = state.itemModelName;
            response = axiosClient
                .post(`/${modelName}`, item.formData)
                .then((res)=>{
                    commit('setCreateItemLoading', false);
                    if (res.data.success) {
                        dispatch('addItem', res.data.data);
                    }
                    //console.log('then');
                    return res;
                })
                .catch( (err) => {
                    //console.log('catch');
                    commit('setCreateItemLoading', false);
                    return err;
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
                .patch(`/${modelName}/${item.id}`, item)
                .then((res)=>{
                    if (res.data.success) {
                        commit('delItem', res.data.data.id);
                        dispatch('addItem', res.data.data);
                    }
                    commit('setUpdateItemLoading', false);
                    return res;
                })
                .catch( (err) => {
                    commit('setUpdateItemLoading', false);
                    //console.log('we got error:',err);
                    return err;
                })
            return response;
        },

        closeForm({commit}){
            commit('setCreateEditFormVisible', false);
            commit('setEditMode', false);
            commit('setCreateMode', false);
        }
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

        setCreateItemLoading: (state, value) => {
            state.createItemLoading = value;
        },
        updateItemLoading: (state, value) => {
            state.updateItemLoading = value;
        },

        setCurrentEditItemId: (state, id) => {
            state.currentEditItemId = id
        },

        setUpdateItemLoading(state, value){
            state.updateItemLoading = value;
        },

        editButtonClicked(state){
            state.editButtonClicked = !state.editButtonClicked;
            state.editMode = true;
            state.createMode = false;
        },
        createButtonClicked(state){
            state.createButtonClicked = !state.createButtonClicked;
            state.editMode = false;
            state.createMode = true;
        },

        setEditMode(state, value){
            state.editMode = value;
        },
        setCreateMode(state, value){
            state.createMode = value;
        },

        setCreateEditFormVisible(state, value){
            state.createEditFormVisible = value;
        },
    },
    namespaced: true,
}
