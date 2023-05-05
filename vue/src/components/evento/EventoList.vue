<template>
    <div class="mt-2 p-2 relative overflow-x-auto border border-indigo-500 rounded-md ">
        <div class="flex justify-between items-center px-1">

            <div class="flex items-center flex-wrap">
                <h1 class="text-xl font-semibold">Список событий
                </h1>
            </div>

<!--            <div>this.filterSeted: {{ this.filterSeted }}</div>-->

            <!-- Evento actions -->
            <div class="mr-1 mt-2 flex items-center justify-end flex-wrap">

                <mg-modal
                    v-model:show="isDiagramVisible"
                    :dialog_content_classes="'overflow-auto '"
                >
                    <evento-diagram-modal @closeModalDialog="isDiagramVisible=false" />
                </mg-modal>

                <mg-modal
                    v-model:show="filterFormVisible" class=""
                    :dialog_content_classes="'overflow-auto '"
                >
                    <evento-filter-modal
                        @closeModalDialog="closeModalHandler"
                    />
                </mg-modal>

                <!-- show diagrams -->
                <mg-button class="flex items-center text-black dark:text-white focus:ring-0 focus:ring-offset-0 px-2 py-1 mr-2
                    hover:border-indigo-500 transition-colors" @click="showDiagram"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span class="ml-1">Графики</span>
                </mg-button>
                <!--/ show diagrams -->

                <!-- show/hide filters -->
                <div class="flex text-black dark:text-white focus:ring-0 focus:ring-offset-0 px-2 py-1 mr-2
                    hover:border-indigo-500 transition-colors"
                >
                    <!-- reset filters -->
                    <div v-if="filterSeted" class="text-red-500 flex items-center cursor-pointer"
                        @click="resetEventoFilters"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>

                    <!-- show filters -->
                    <div class="flex items-center cursor-pointer"
                         @click="showEventoFilters"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg> Фильтры
                    </div>
                </div>
                <!--/ show/hide filters -->

                <!-- Evento create button -->
                <mg-button class="bg-green-600 hover:bg-green-700 transition-colors focus:bg-green-800
                    focus:ring-green-700" @click="addEventoButtonClicked">
                    <span class="text-xs">Добавить событие</span>
                </mg-button>
                <!--/ Evento create button -->
            </div>

            <!--/ Evento actions -->
        </div>


        <mg-loading v-if="eventos.loading" class="m-auto">Загрузка...</mg-loading>

        <div v-else>

            <table
                v-if="eventos.items.length"
                class="mt-2 w-full border dark:border-none border-collapse rounded-md p-3"
            >
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="py-3 px-6">#</th>
                    <th class="py-3 px-6">Дата</th>
                    <th class="py-3 px-6">Описание</th>
                    <th class="py-3 px-6">Подробнее</th>
                    <th class="py-3 px-6">Действия</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(evento,index) in eventos.items"
                          :key="evento.id">
                    <evento-item
                        @doAddFormReset="$emit('doAddFormReset')"
                        :evento="evento"
                        :index="index"
                    />
                </template>
                </tbody>
            </table>
            <div v-else>
                <div class="text-center">Еще нет добавленных событий</div>
            </div>

            <evento-paginator
                class="mt-5"
                :evento_links="eventos.links"
                :current_page="eventos.current_page"
                :last_page="eventos.last_page"
            />

        </div>

    </div>
</template>

<script>
import EventoItem from "./EventoItem.vue";
import {mapActions, mapMutations, mapState} from "vuex";
import EventoFilterModal from "./EventoFilterModal.vue";
import MgInputDateLabeled from "../UI/MgInputDateLabeled.vue";
import EventoDiagramModal from "./EventoDiagramModal.vue";
import EventoPaginator from "./EventoPaginator.vue";
import MgLoading from "../UI/MgLoading.vue";

export default {
    name: 'evento-list',
    components: {
        MgLoading, EventoPaginator,
        MgInputDateLabeled,
        EventoItem, EventoFilterModal, EventoDiagramModal,
    },
    emits: ['doAddFormReset'],
    props: {
        isCreateFormButtonVisible:{
            type: Boolean,
        },
    },
    data(){
        return {
            isLocalCreateFormButtonVisible: undefined,
            filterFormVisible: false,

            filterSeted: false,
            isDiagramVisible: false,

            diagramData: [],

            filterData: {
                date_start: 1,
                date_end: 2,
                sum_start: 0,
                sum_end: 107000,
                filter_text: '',
                tag_arr: [], //[122, 123],
                orderById: 'desc_asc',
                zeroFill: false,
                pickAllTags: false,
            },

        }
    },
    methods:{
        ...mapActions({
            'loadEventos': 'evento/loadItems',
            'loadFilteredEventos': "evento/filterItems",
        }),
        ...mapMutations({
            'setCreateEditFormVisible': "evento/setCreateEditFormVisible",
            'setCreateMode': "evento/setCreateMode",
        }),

        addEventoButtonClicked(){
            this.$store.commit('evento/createButtonClicked');
            this.setCreateMode(true);
            this.setCreateEditFormVisible(true);
        },

        showEventoFilters(){
            this.filterFormVisible = true;
        },

        resetEventoFilters(){
            this.filterSeted = false;
            this.$router.push({ name: 'Eventos'});
            this.$store.commit('evento/setFilterDataSeted', false);
            this.$store.dispatch("evento/loadItems");
        },
        showDiagram(){
            this.isDiagramVisible = true;
        },

        closeModalHandler(){
            this.filterFormVisible = false;
            this.$store.commit('evento/setFilterModalVisible', false);
        },

        prepareFilterData() {
            let filterValues = {}
            for (let fkey in this.filterData){
                for (let rkey in this.$route.query){
                    if (fkey === rkey){
                        filterValues[rkey] = this.$route.query[rkey];
                    }
                }
            }

            let queryString = new URLSearchParams(filterValues)

            // console.log('page:', this.$route.query.page);
            // add current page if exists
            if (this.$route.query.page){
                queryString.append('page', this.$route.query.page);
            }

            queryString = queryString.toString();
            if (queryString !== '') {
                queryString = `?${queryString}`;
            }

            return queryString;
        },

        isOneFilterQueryParamExists(){
            let isExists = false;

            for (let fkey in this.filterData){
                for (let rkey in this.$route.query){
                    if (fkey === rkey){
                        isExists = true;
                        break;
                    }
                }
            }

            return isExists;
        },

        updateFilterParamExists(){
            this.filterSeted = this.isOneFilterQueryParamExists();
        },
    },
    computed:{
        ...mapState({
            'createEditFormVisible': state => state.evento.createEditFormVisible,
            'tags':         state => state.tag.tags,
            'diagramValue': state => state.diagram.diagram,
            'current_page': state => state.evento.current_page,
            'evento_filter': state => state.evento.evento_filter,
            isFilterDataSeted: state => state.evento.isFilterDataSeted,

            eventos: state => state.evento.eventos,
        }),
    },
    watch:{
        filterFormVisible(nv){
            if (!nv){
                this.$store.commit('evento/setFilterModalVisible', false);
            }
        },

        isFilterDataSeted(nv){
            this.filterSeted = nv;
        }
    },

    updated() {
        //console.log('updated');
        //this.updateFilterParamExists();
    },

    mounted() {
        this.updateFilterParamExists();

        const prData = this.prepareFilterData();
        this.$store.dispatch('evento/loadItems', prData);
    },
}
</script>

<style scoped>

</style>
