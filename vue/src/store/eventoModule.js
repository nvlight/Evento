import axiosClient from "../axios.js";

export const eventoModule = {
    state: () => ({
        itemModelName: 'evento',

        current_page: sessionStorage.getItem('current_page') ? sessionStorage.getItem('current_page') : 1,

        eventos: {
            items: [],
            loading: false,
            links: [],
            current_page: 0,
            last_page: 0,
            url_path: '',
        },

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
            return state.eventos.items.filter( t => t.id === state.currentEditItemId)?.[0];
        },
        getItemById: (state) => (id) => {
            return state.eventos.items.filter( t => t.id === id)?.[0];
        }
    },
    actions: {
        loadItems({commit, state}, {url = null} = null){
            let response;
            commit('setEventosLoading', true);

            const modelName = state.itemModelName;
            url = url || `/${modelName}`;

            response = axiosClient
                .get(url)
                .then((res)=>{
                    if (res.data.success) {
                        commit('setEventoItems', res.data.data.data);
                        commit('setEventosLinks', res.data.data.links);
                        commit('setCurrentPage', res.data.data.current_page);
                        commit('saveCurrentPageToSessionStorage', res.data.data.current_page)
                        commit('setLastPage', res.data.data.last_page);
                        commit('saveUrlPath', res.data.data.path);
                    }
                    commit('setEventosLoading', false);
                    return res;
                })
                .catch( (err) => {
                    commit('setEventosLoading', false);
                })
            return response;
        },

        filterItems({commit, state}, params){
            commit('setEventosLoading', true);
            const modelName = state.itemModelName;
            let url = `/${modelName}/filter`;
            let response = axiosClient
                .get(`/${modelName}/filter`, { params: params })
                .then((res)=>{
                    if (res.data.success) {
                        commit('setEventoItems', res.data.data.data);
                        commit('setEventosLinks', res.data.data.links);
                    }
                    commit('setEventosLoading', false);
                    return res;
                })
                .catch( (err) => {
                    commit('setEventosLoading', false);
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
                        if (res.data.filteredCount){
                            //dispatch('loadItems', {url: `${state.eventos.url_path}?page=${state.eventos.current_page}`});
                            commit('delItem', state.eventos.items[state.eventos.items.length - 1].id);
                            commit('addItem', res.data.data);
                        }
                    }
                    return res;
                })
                .catch( (err) => {
                    //console.log('catch');
                    commit('setCreateItemLoading', false);
                    return err;
                })
            return response;
        },

        delItem({dispatch, state, commit}, id){
            dispatch('delItemQuery', id);
        },
        delItemQuery({dispatch,state, commit}, id){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .delete(`/${modelName}/${id}`)
                .then((res)=>{
                    if (res.data.success){
                        //console.log(id, state.currentEditItemId, state.editMode);
                        if ( (id === state.currentEditItemId) && (state.editMode) ){
                            commit('setCurrentEditItemId', 0);
                            commit('setEditMode', 0);
                            commit('setCreateEditFormVisible', false);
                        }

                        // if after delete page number is less
                        let last_page = res.data.last_page;
                        //console.log('last_page:', last_page);
                        //console.log('state.current_page:', state.current_page);

                        if (last_page < state.current_page){
                            commit('saveCurrentPageToSessionStorage', last_page);

                            let page_url = `${res.data.path}?page=${last_page}`;
                            //console.log('page_url:', page_url);
                            dispatch('loadItems', {url: page_url});
                        }else{
                            //commit('delItem', id);
                            dispatch('loadItems', {url: `${state.eventos.url_path}?page=${state.eventos.current_page}`});
                        }
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
                        commit('updateItem', res.data.data);
                    }
                    commit('setUpdateItemLoading', false);
                    return res;
                })
                .catch( (err) => {
                    commit('setUpdateItemLoading', false);
                    return err;
                })
            return response;
        },

        closeForm({commit}){
            commit('setCreateEditFormVisible', false);
            commit('setEditMode', false);
            commit('setCreateMode', false);
        },

        getDiagram({commit, state}){
            let response;
            const modelName = state.itemModelName;
            response = axiosClient
                .get(`/${modelName}/diagram`)
                .then((res)=>{
                    return res;
                })
                .catch( (err) => {
                    return err;
                })
            return response;
        },
        saveCurrentPageToSessionStorage({commit}, page) {
            commit('saveCurrentPageToSessionStorage', page);
        },
    },
    mutations: {
        setEventoItems: (state, items) => {
            state.eventos.items = items;
        },
        addItem: (state, item) => {
            state.eventos.items.unshift(item);
        },
        delItem: (state, id) => {
            state.eventos.items = state.eventos.items.filter(
                t => t.id != id
            );
        },
        updateItem(state, evento){
            state.eventos.items = state.eventos.items.map(
                t =>  {
                    if (t.id === evento.id){
                        return evento;
                    }else{
                        return t;
                    }
                }
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

        setEventosLoading(state, value){
            state.eventos.loading = value;
        },
        setEventosLinks(state, value){
            state.eventos.links = value;
        },
        setCurrentPage(state, value){
            state.eventos.current_page = value;
        },
        saveCurrentPageToSessionStorage(state, value){
            sessionStorage.setItem('current_page', value);
            state.current_page = value;
        },
        setLastPage(state, value){
            state.eventos.last_page = value;
        },
        saveUrlPath(state, value){
            state.eventos.url_path = value;
            sessionStorage.setItem('url_path', value);
        },
    },
    namespaced: true,
}
