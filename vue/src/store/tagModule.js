import axiosClient from "../axios.js";

export const tagModule = {
    state: () => ({
        itemModelName: 'tag',
        items: [],
        currentEditItemId: 0,
        crudModalVisible: true,
        createItemStatus: false,
    }),
    getters: {
        getCrudModalVisible(state){
            return state.crudModalVisible;
        },
        getCurrentEditItemId(state){
            return state.currentEditItemId;
        },
        getItemById: (state) => (id) => {
            return state.items.filter(t => t.id === id)?.[0];
        },
        getCreateItemStatus(state){
            return state.createItemStatus;
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
            commit('setCreateItemStatus', false);
            return dispatch('createItemQuery', item);
        },
        addItem({commit}, item){
            return commit('addItem', item);
        },
        createItemQuery({commit,dispatch,state}, item){
            let response;
            const modelName = state.itemModelName;
            console.log('createItem');
            console.log(item);
            response = axiosClient
                .post(`/${modelName}`, item)
                .then((res)=>{
                    if (res.data.success) {
                        const itemClone = Object.assign({}, item);
                        itemClone.id = res.data.savedId;
                        commit('setCreateItemStatus', true);
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
            response = axiosClient
                .patch(`/${modelName}/${item.id}`, item)
                .then((res)=>{
                    if (res.data.success) {
                        commit('editItem', item);
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

        setCurrentEditItem({commit}, id){
            return commit('setCurrentItemId', id);
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
        editItem: (state, item) => {
            let find = state.items.filter(
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
        setCurrentItemId: (state, id) => {
            state.currentEditItemId = id;
        },
        setCreateItemStatus: (state, value) => {
            state.createItemStatus = value;
        }
    },
    namespaced: true,
}
