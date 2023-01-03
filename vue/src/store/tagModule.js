import axiosClient from "../axios.js";

export const tagModule = {
    state: () => ({
        itemModelName: 'tag',

        tags: {
            items: [],
            loading: false,
        },

        currentEditItemId: 0,
        crudModalVisible: true,
        createItemStatus: false,
        createdItemId: 0,
    }),
    getters: {
        getCrudModalVisible(state){
            return state.crudModalVisible;
        },
        getCurrentEditItemId(state){
            return state.currentEditItemId;
        },
        getItemById: (state) => (id) => {
            return state.tags.items.filter(t => t.id === id)?.[0];
        },
        getCreateItemStatus(state){
            return state.createItemStatus;
        },
        getCurrentEditItem(state){
            return state.tags.items.filter(t => t.id === state.currentEditItemId)?.[0];
        },
        getCreatedItemId(state){
            return state.createdItemId;
        }
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
            //console.log('createItem');
            //console.log(item);
            response = axiosClient
                .post(`/${modelName}`, item.formData)
                .then((res)=>{
                    if (res.data.success) {
                        const itemClone = Object.assign({}, item.item);
                        itemClone.id  = res.data.savedId;
                        itemClone.img = res.data.img;
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
                    console.log('we got error:',err);
                })
            return response;
        },

        editItem({dispatch}, item){
            return dispatch('editItemQuery', item);
        },
        editItemQuery({commit, state}, item){
            let response;
            const modelName = state.itemModelName;
            //console.log(item)
            response = axiosClient
                .post(`/${modelName}/${item.item.id}`, item.formData)
                .then((res)=>{
                    if (res.data.success) {
                        const updItem = Object.assign({}, item.item);
                        updItem.img = res.data.img;
                        commit('editItem', updItem);
                    }
                    return res;
                })
                .catch( (err) => {
                    console.log('we got error:',err);
                })
            return response;
        },

        delItem({dispatch}, id){
            dispatch('delItemQuery', id);
            console.log(id);
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
            return commit('setCurrentEditItemId', id);
        },
        setCreatedItemId({commit}, id){
            return commit('setCreatedItemId', id);
        },
    },
    mutations: {
        setItems: (state, items) => {
            state.tags.items = items;
        },
        addItem: (state, item) => {
            state.tags.items.unshift(item);
        },
        delItem: (state, id) => {
            state.tags.items = state.tags.items.filter(
                t => t.id != id
            );
        },
        editItem: (state, item) => {
            let find = state.tags.items.filter(
                t => t.id === item.id
            );
            if (find.length){
                // todo: эта строка ниже не работает, пришлось обойти (!) свойста объекта и делать присваивание вручную!
                // не только не работает, да еще и парализует работу ниже идуещего цикла!
                //find[0] = material;

                for (let key in find[0]){
                    //console.log(find[0][key]);
                    //console.log(material[key]);
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
            state.tags.loading = value;
        },
    },
    namespaced: true,
}
