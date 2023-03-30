import axiosClient from "../axios.js";

export const onepassEntryModule = {
    state: () => ({
        itemModelName: 'onepass/entry',

        items: {
            list: [],
            loading: false,
        },

        createdItemStatus: false,
        editedItemId: 0,

        // чтобы сделать watch за итемом. В частности позволяет определить 2-е и более нажатие на один и тот же итем
        pressedItemEditBtn: false, // true/false
    }),
    getters: {
        getItemById: (state) => (id) => {
            return state.items.list.filter(t => t.id === id)?.[0];
        },
        getEditedItem(state){
            return state.items.list.filter(t => t.id === state.editedItemId)?.[0];
        },
    },
    actions: {
        loadItems({commit, state}){
            let response;
            const modelName = state.itemModelName;
            commit('setItemsLoading', true);
            response = axiosClient
                .get(`/${modelName}`)
                .then((res)=>{
                    if (res.data.success) {
                        commit('setItems', res.data.data);
                    }
                    commit('setItemsLoading', false);
                    return res;
                })
                .catch( (err) => {
                    commit('setItemsLoading', false);
                    return err;
                })
            return response;
        },

        createItem({dispatch, commit}, item){
            commit('setCreatedItemStatus', true);
            return dispatch('createItemQuery', item);
        },
        addItem({commit}, item){
            return commit('addItem', item);
        },
        createItemQuery({commit,dispatch,state}, item){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .post(`/${modelName}`, item.formData)
                .then((res)=>{
                    if (res.data.success) {
                        //const itemClone = Object.assign({}, item.item);
                        //itemClone.id = res.data.storedId;
                        commit('addItem', res.data.item);
                    }
                    commit('setCreatedItemStatus', false);
                    return res;
                })
                .catch( (err) => {
                    commit('setCreatedItemStatus', false);
                    return err;
                })
            return response;
        },

        editItem({dispatch}, item){
            return dispatch('editItemQuery', item);
        },
        editItemQuery({commit, state}, item){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .post(`/${modelName}/${item.item.id}`, item.formData)
                .then((res)=>{
                    if (res.data.success) {
                        const updItem = Object.assign({}, item.item);
                        updItem.image = res.data.image;
                        updItem.image_full = res.data.image_full;
                        commit('editItem', updItem);
                    }
                    return res;
                })
                .catch( (err) => {
                    return err;
                })
            return response;
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
                    return err;
                })
            return response;
        },

        setCurrentEditItemId({commit}, id){
            return commit('setCurrentEditItemId', id);
        },
        setCreatedItemId({commit}, id){
            return commit('setCreatedItemId', id);
        },
    },
    mutations: {
        setItems: (state, items) => {
            state.items.list = items;
        },
        addItem: (state, item) => {
            state.items.list.unshift(item);
        },
        delItem: (state, id) => {
            state.items.list = state.items.list.filter(
                t => t.id != id
            );
        },
        editItem: (state, item) => {
            let find = state.items.list.filter(
                t => t.id === item.id
            );
            if (find.length){
                for (let key in find[0]){
                    find[0][key] = item[key];
                }
            }
        },
        setEditedItemId: (state, id) => {
            state.editedItemId = id;
        },

        setItemsLoading(state, value){
            state.items.loading = value;
        },
        setCreatedItemStatus(state, value){
            state.createdItemStatus = value;
        },

        setPressedItemEditBtn(state){
            state.pressedItemEditBtn = ! state.pressedItemEditBtn;
        }
    },
    namespaced: true,
}