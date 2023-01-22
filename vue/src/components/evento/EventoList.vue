<template>
    <div class="mt-1 overflow-x-auto relative">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-xl font-semibold">Список событий
                </h1>
                <div v-if="current_page !== 1" class="ml-2">(страница {{current_page}})</div>
            </div>

            <!-- Evento actions -->
            <div class="mr-2 mt-2 flex items-center">

                <mg-modal v-model:show="isDiagramVisible">
                    <evento-diagram-modal @closeModalDialog="isDiagramVisible=false" />
                </mg-modal>

                <!-- show diagrams -->
                <mg-button class="flex items-center text-black focus:ring-0 focus:ring-offset-0 px-2 py-1 mr-2
                    hover:border-indigo-500 transition-colors" @click="showDiagram"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span class="ml-1">Графики</span>
                </mg-button>
                <!--/ show diagrams -->

                <!-- show/hide filters -->
                <div class="flex text-black focus:ring-0 focus:ring-offset-0 px-2 py-1 mr-2
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

        <table v-if="eventos.length" class="mt-2 w-full border border-collapse rounded-md p-3 ">
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
                <template v-for="(evento,index) in eventos"
                    :key="evento.id">
                    <evento-item @doAddFormReset="$emit('doAddFormReset')"
                        :evento="evento" :index="index"></evento-item>
                </template>
            </tbody>
        </table>
        <div v-else>
            <div class="text-center">Еще нет добавленных событий</div>
        </div>

        <mg-modal v-model:show="filterFormVisible" class=""
            :dialog_content_classes="'overflow-auto '"
            >
            <evento-filter-modal
                @doFilterEventos="doFilterEventos"
                @closeModalDialog="filterFormVisible=false"
            />
        </mg-modal>

    </div>
</template>

<script>
import EventoItem from "./EventoItem.vue";
import {mapActions, mapMutations, mapState} from "vuex";
import EventoFilterModal from "./EventoFilterModal.vue";
import MgInputDateLabeled from "../UI/MgInputDateLabeled.vue";
import EventoDiagramModal from "./EventoDiagramModal.vue";

export default {
    name: 'evento-list',
    components: {
        MgInputDateLabeled,
        EventoItem, EventoFilterModal, EventoDiagramModal,
    },
    emits: ['doAddFormReset'],
    props: {
        eventos:{
            type: Object,
            required: true,
        },
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
        }
    },
    methods:{
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

        doFilterEventos(filterData){

        },
        resetEventoFilters(){
            //console.log('resetEventoFilters');
            sessionStorage.removeItem('evento_filter');
            this.filterSeted = false;

            this.$store.dispatch('evento/loadItems', {url:null});
        },
        showDiagram(){
            this.isDiagramVisible = true;
        },

    },
    computed:{
        ...mapState({
            'createEditFormVisible': state => state.evento.createEditFormVisible,
            'tags':         state => state.tag.tags,
            'diagramValue': state => state.diagram.diagram,
            'current_page': state => state.evento.current_page,
        }),
    },
    mounted() {
        this.filterSeted = sessionStorage.hasOwnProperty('evento_filter');
    },
}
</script>

<style scoped>

</style>
