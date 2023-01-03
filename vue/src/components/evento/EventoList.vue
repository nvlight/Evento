<template>
    <div class="mt-1 overflow-x-auto relative">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold">Список событий</h1>

            <div class="mr-2 mt-2 flex items-center">
                <mg-button @click="showEventoFilters"
                    class="text-black focus:ring-0 focus:ring-offset-0 px-0 py-0 mr-2 flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                         class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg> Фильтры
                </mg-button>
                <mg-button class="bg-green-600 hover:bg-green-700 transition-colors focus:bg-green-800
                    focus:ring-green-700"
                   @click="addEventoButtonClicked"
                ><span class="text-xs">Добавить событие</span>
                </mg-button>
            </div>
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
            <h1 class="text-2xl font-semibold">Фильтр событий</h1>

            <form @submit.prevent="doFilterEventos" class="mt-2">
                <div class="date-start-end flex">
                    <mg-input-date-labeled v-model="filterData.date.start" >Дата - начало</mg-input-date-labeled>
                    <mg-input-date-labeled v-model="filterData.date.end" class="ml-2"
                    >Дата - конец
                    </mg-input-date-labeled>
                </div>

                <div class="mt-2 tag_list">
                    <mg-input-labeled class="block " :classes="'w-full'"

                    >добавить теги</mg-input-labeled>
                    <div class="flex mt-2">
                        <div class="bg-green-600 text-white w-fit px-2 py-1 rounded-md cursor-pointer">Доход</div>
                        <div class="ml-1.5 bg-red-400 text-white w-fit px-2 py-1 rounded-md cursor-pointer">Расход</div>
                    </div>
                </div>

                <div class="date-start-end flex mt-3">
                    <mg-input-labeled v-model="filterData.sum.start" >Сумма начальная</mg-input-labeled>
                    <mg-input-labeled v-model="filterData.sum.end" class="ml-2"
                    >Сумма конечная
                    </mg-input-labeled>
                </div>

                <div class="flex justify-between">
                    <button type="reset" class=" mt-2 bg-indigo-300 text-white p-2 rounded-md hover:bg-indigo-400
                        focus:bg-indigo-500"
                    >сбросить
                    </button>
                    <button class="ml-2 mt-2 bg-indigo-500 text-white p-2 rounded-md hover:bg-indigo-600
                        focus:bg-indigo-700"
                        >Фильтровать
                    </button>
                </div>

            </form>
<!--            <div class="mt-2" v-for="(i,index) in [1,4,5,6,6,6,6,6,6,6,6,6,6,6,6,6,...[1,4,5,6,6,6,6,6,6,6,6,6,6,6,6,6,]-->
<!--                ,[1,4,5,6,6,6,6,6,6,6,6,6,6,6,6,6,]-->
<!--                ]">-->
<!--                <h3>sdklfjdlkf {{index}}</h3>-->
<!--            </div>-->
        </mg-modal>

    </div>
</template>

<script>
import EventoItem from "./EventoItem.vue";
import {mapActions, mapMutations, mapState} from "vuex";
export default {
    name: 'evento-list',
    components: {
        EventoItem,
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
            filterData: {
                date: {
                    start: 1,
                    end: 1,
                },
                sum: {
                   start: 0,
                   end: 107000,
                },
                tag_arr: [],
                orderById: 'desc / asc',
            }
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

        doFilterEventos(){
            console.log('doFilterEventos');
        },

        setDatesForFilterForm(){
            this.filterData.date.start = this.getCurrentDate;
            this.filterData.date.end = this.getCurrentDate;
        },

    },
    computed:{
        ...mapState({
            'createEditFormVisible': state => state.evento.createEditFormVisible,
        }),

        getCurrentDate(){
            const dt = new Date();
            let year = dt.getFullYear();
            let day = dt.getDate();
            day = day < 10 ? '0' +  day : day;
            let month = dt.getMonth();
            month = month < 10 ? '0' + ( month + 1) : month + 1;
            return [year, month, day,].join('-');
        },

    },
    mounted() {
        this.setDatesForFilterForm();
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
