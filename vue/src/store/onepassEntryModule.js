import axiosClient from "../axios.js";

export const onepassEntryModule = {
    state: () => ({
        itemModelName: 'onepass/entry',

        items: {
            list: [],
            loading: false,

            paginatorData: {
                current_page: 0,
                first_page_url: "",
                from: 1,
                last_page: 1,
                last_page_url: "",
                next_page_url: "",
                path: "",
                per_page: 10,
                prev_page_url: null,
                to: 10,
                total: 0,
                links: [],
            },
            paginatorDataTmp: {
                current_page: 1,
                first_page_url: "http://laravel8-evento:87/api/onepass/entry?page=1",
                from: 1,
                last_page: 4,
                last_page_url: "http://laravel8-evento:87/api/onepass/entry?page=4",
                links: [{url: null, label: "&laquo; Назад", active: false}],
                next_page_url: "http://laravel8-evento:87/api/onepass/entry?page=2",
                path: "http://laravel8-evento:87/api/onepass/entry",
                per_page: 10,
                prev_page_url: null,
                to: 10,
                total: 33,
            }
        },

        createdItemStatus: false,
        editedItemId: 0,

        // чтобы сделать watch за итемом. В частности позволяет определить 2-е и более нажатие на один и тот же итем
        pressedItemEditBtn: false, // true/false

        createEditFormVisible: false,

        formMode: null, // save/edit

        filterModalVisible: false,

        filterObject: {},

        // чтобы сделать watch за итемом. В частности позволяет определить 2-е и более нажатие на один и тот же итем
        pressedItemViewBtn: false, // true/false
        viewItemId: 0,
        itemViewModalVisible: false,
    }),
    getters: {
        getItemById: (state) => (id) => {
            return state.items.list?.filter(t => t.id === id)?.[0];
        },
        getEditedItem(state){
            return state.items.list?.filter(t => t.id === state.editedItemId)?.[0];
        },
        isFilterEmpty(state){
            return Object.keys(state.filterObject).length;
        },
        getViewItem(state){
            return state.items.list.filter(t => t.id === state.viewItemId)?.[0];
        },
    },
    actions: {
        loadItems({commit, state}, queryString = ''){
            let response;
            const modelName = state.itemModelName;
            commit('setItemsLoading', true);

            response = axiosClient
                .get(`/${modelName}${queryString}`)
                .then((res)=>{
                    if (res.data.success) {
                        commit('setItems', res.data.data.data);
                        commit('setPaginatorData', res.data.data);
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
        updateItemQuery({commit, state}, item){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .post(`/${modelName}/${item.item.id}`, item.formData)
                .then((res)=>{
                    if (res.data.success) {
                        const updItem = Object.assign({}, item.item);
                        commit('updateItem', updItem);
                    }
                    return res;
                })
                .catch( (err) => {
                    return err;
                })
            return response;
        },

        copyItemQuery({commit,dispatch,state}, id){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .post(`/${modelName}/copy/${id}`)
                .then((res)=>{
                    if (res.data.success) {
                        commit('addItem', res.data.item);
                    }
                    //commit('setCreatedItemStatus', false);
                    return res;
                })
                .catch( (err) => {
                    //commit('setCreatedItemStatus', false);
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

        setEditItemId({commit}, id){
            return commit('setEditedItemId', id);
        },
        setCreatedItemId({commit}, id){
            return commit('setCreatedItemId', id);
        },

        setFilterObject( { commit }, value) {
            return commit('setFilterObject', value);
        },

        clearFilterObject( { commit, dispatch }) {
            return dispatch('setFilterObject', {});
        },

        setViewItemId({commit}, id){
            return commit('setViewItemId', id);
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
        updateItem: (state, item) => {
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
        },

        setCreateEditFormVisible(state, value){
            state.createEditFormVisible = value;
        },

        setFormMode(state, value){
            state.formMode = value;
        },

        setFilterModalVisible(state, value){
            state.filterModalVisible = value;
        },

        setFilterObject(state, value){
            state.filterObject = value;
        },

        setViewItemId: (state, id) => {
            state.viewItemId = id;
        },
        setPressedItemViewBtn(state){
            state.pressedItemViewBtn = ! state.pressedItemViewBtn;
        },
        setViewModalVisible(state, value){
            state.itemViewModalVisible = value;
        },

        setPaginatorData(state, value){
            state.items.paginatorData.current_page = value.current_page;
            state.items.paginatorData.last_page = value.last_page;
            state.items.paginatorData.links = JSON.parse(JSON.stringify(value.links));
        }
    },
    namespaced: true,
}
