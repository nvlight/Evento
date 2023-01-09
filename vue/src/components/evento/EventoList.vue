<template>
    <div class="mt-1 overflow-x-auto relative">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold">Список событий</h1>

            <!-- Evento actions -->
            <div class="mr-2 mt-2 flex items-center">

                <!-- show diagrams -->
                <mg-button class="text-black focus:ring-0 focus:ring-offset-0 px-2 py-1 mr-2
                    border-indigo-500 hover:border-red-500" @click="showDiagram"
                    >Diagram
                </mg-button>
                <!--/ show diagrams -->

                <!-- show/hide filters -->
                <mg-button class="text-black focus:ring-0 focus:ring-offset-0 px-0 py-0 mr-2"
                >
                    <!-- reset filters -->
                    <div v-if="isFilterSeted" class="text-red-500 flex items-center"
                        @click="resetEventoFilters"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg> Фильтры
                    </div>

                    <!-- show filters -->
                    <div v-else class="flex items-center"
                         @click="showEventoFilters"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                             class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg> Фильтры
                    </div>
                </mg-button>
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
            <evento-filter-modal @doFilterEventos="doFilterEventos"/>
        </mg-modal>

    </div>
</template>

<script>
import EventoItem from "./EventoItem.vue";
import {mapActions, mapMutations, mapState} from "vuex";
import EventoFilterModal from "./EventoFilterModal.vue";

export default {
    name: 'evento-list',
    components: {
        EventoItem, EventoFilterModal,
    },
    emits: ['doAddFormReset'],
    props: {
        eventos:{
            type: Object,
            required: true,
        },
        isCreateFormButtonVisible:{
            type: Boolean,
            //default: true,
        }
    },
    data(){
        return {
            isLocalCreateFormButtonVisible: undefined,
            filterFormVisible: false,

            filterSeted: false,
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
            console.log('showDiagram');
            this.$store.dispatch("evento/getDiagram");
        },

    },
    computed:{
        ...mapState({
            'createEditFormVisible': state => state.evento.createEditFormVisible,
            'tags': state => state.tag.tags,
        }),

        isFilterSeted(){
            return this.filterSeted;
        },
    },
    mounted() {
        this.filterSeted = sessionStorage.hasOwnProperty('evento_filter');
        //this.setDatesForFilterForm();
        //this.isLocalCreateFormButtonVisible = this.isCreateFormButtonVisible;
    },
    // watch: {
    //     isCreateFormButtonVisible(nv, ov){
    //         this.isLocalCreateFormButtonVisible = this.isCreateFormButtonVisible;
    //     }
    // }
}
</script>

<style scoped>

</style>
