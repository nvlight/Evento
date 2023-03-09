import axiosClient from "../axios.js";

export const onepassCategoryModule = {
    state: () => ({
        itemModelName: 'onepass/category',

        items: {
            list: [],
            loading: false,
        },

        currentEditItemId: 0,
        crudModalVisible: true,
        createItemStatus: false,
        createdItemId: 0,

        // если кнопка редактирования на итем нажата - фиксирует изменения, чтобы потом отследить!
        itemEditBtnClickChanged: false,
    }),
    getters: {
        getCrudModalVisible(state){
            return state.crudModalVisible;
        },
        getCurrentEditItemId(state){
            return state.currentEditItemId;
        },
        getItemById: (state) => (id) => {
            return state.items.list.filter(t => t.id === id)?.[0];
        },
        getCreateItemStatus(state){
            return state.createItemStatus;
        },
        getCurrentEditItem(state){
            return state.items.list.filter(t => t.id === state.currentEditItemId)?.[0];
        },
        getCreatedItemId(state){
            return state.createdItemId;
        },
        getiItemEditBtnClickChanged(state){
            return state.itemEditBtnClickChanged;
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
            commit('setCreateItemStatus', false);
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
                        const itemClone = Object.assign({}, item.item);
                        itemClone.id  = res.data.savedId;
                        itemClone.image = res.data.image;
                        itemClone.image_full = res.data.image_full;
                        commit('setCreateItemStatus', true);
                        dispatch('setCreatedItemId', itemClone.id);
                        dispatch('addItem', itemClone);
                    }else{
                        commit('setCreateItemStatus', false);
                    }
                    return res;
                })
                .catch( (err) => {
                    commit('setCreateItemStatus', false);
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
        setCurrentEditItemId: (state, id) => {
            state.currentEditItemId = id;
        },
        setCreateItemStatus: (state, value) => {
            state.createItemStatus = value;
        },
        setCreatedItemId: (state, value) => {
            state.createdItemId = value;
        },

        setItemsLoading(state, value){
            state.items.loading = value;
        },

        itemEditBtnClickChanged(state, value){
            state.itemEditBtnClickChanged = ! state.itemEditBtnClickChanged;
        }
    },
    namespaced: true,
}
