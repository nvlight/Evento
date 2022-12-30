<template>
    <div class="mt-3 overflow-x-auto relative">
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold">Список событий</h1>

            <div>
                <mg-button class="bg-green-600 hover:bg-green-700 transition-colors focus:bg-green-800"
                   @click="addEventoButtonClicked"
                ><span class="text-xs">Добавить событие</span></mg-button>
            </div>
        </div>
        <table class="mt-2 w-full border border-collapse rounded-md p-3 ">
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
        }
    },
    computed:{
        ...mapState({
            'createEditFormVisible': state => state.evento.createEditFormVisible,
        }),
    },
    mounted() {
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
