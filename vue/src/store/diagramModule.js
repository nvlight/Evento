import axiosClient from "../axios.js";

export const diagramModule = {
    state: () => ({
        diagram: {
            loading: false,
            items: [],
        },

    }),
    getters:{

    },
    actions: {
        loadItems({commit}, params){
            commit('setItemsLoading', true);
            let response = axiosClient
                .get(`/evento/diagram`, { params: params })
                .then((res)=>{
                    if (res.data.success) {
                        commit('setDiagramData', res.data.diagram);
                    }
                    commit('setItemsLoading', false);
                    return res;
                })
                .catch( (err) => {
                    commit('setItemsLoading', false);
                })
            return response;
        },
        resetItems({commit}){
            commit('setDiagramData', []);
        }
    },
    mutations: {
        setDiagramData(state, data){
            state.diagram.items = data;
        },
        setItemsLoading(state, value){
            state.diagram.loading = value;
        },
    },
    namespaced: true,
}
