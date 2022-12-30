<template>
    <div class="evento_create_form_wrapper p-3 border border-dashed rounded-md">
        <!--                        <div>createItemStatus: {{createItemStatus}}</div>-->
        <!--                        <div>getCurrentEditItemId: {{getCurrentEditItemId}}</div>-->
        <!--                        <div>getCurrentEditedItem: {{getCurrentEditedItem}}</div>-->

        <div class="flex justify-between">
            <div>
                <h1 v-if="isCreateEventoButtonVisible" class="text-xl font-semibold">Добавление события</h1>
                <h1 v-else class="text-xl font-semibold">Редактирование события</h1>
            </div>
            <mg-close-icon-button
                v-if="!isCreateEventoButtonVisible"
                @click="resetUpdateMode"></mg-close-icon-button>
        </div>

        <form @submit.prevent>
            <div class="flex flex-wrap items-center justify-between w-full">

                <div class="main_date mr-2 mt-2">
                    <span>[ {{evento.date}} ]</span>
                    <mg-input-date-labeled v-model="evento.date"></mg-input-date-labeled>
                </div>

                <div class="tag_first  mt-2">
                    <span>Тег основной [ {{evento.tag_id_first}} ]</span>
                    <mg-select v-model="evento.tag_id_first"  class=""
                        :options="tags"
                    ></mg-select>
                </div>

                <div class="tag_value mt-2">
                    <span>Значение [ {{evento.value}} ]</span>
                    <mg-input-labeled class="" v-model="evento.value"></mg-input-labeled>
                </div>

                <mg-plus-icon-button
                    v-if="isMainTagButtonVisible" @click="isMainTagButtonVisible = !isMainTagButtonVisible"
                    class="add_anather_tag block bg-green-600 mt-2
                                focus:ring-green-600 rounded-md text-white p-2 self-end ml-3"
                >привязать тег</mg-plus-icon-button>

                <div v-if="!isMainTagButtonVisible" class="tag_first relative  mt-2">
                    <span>Тег вторичный [ {{evento.tag_id_second}} ]</span>
                    <mg-select v-model="evento.tag_id_second" class=""
                               :options="tags"
                    ></mg-select>
                    <mg-trash-icon-button
                        v-if="!isMainTagButtonVisible" @click="isMainTagButtonVisible = !isMainTagButtonVisible"
                        class="add_anather_tag absolute border-none text-red-500 self-end
                                            absolute right-0 top-1 focus:ring-red-500 rounded-sm h-4 w-4
                                            "
                        :svgClass="'h-4 w-4'"
                    ></mg-trash-icon-button>
                </div>
            </div>

            <div class="buttons">
                <div v-if="isCreateEventoButtonVisible" class="self-end mt-2">
                    <mg-button
                        @click="createEvento"
                        :class="'bg-green-600 hover:bg-green-700 focus:ring-green-500 flex items-center'"
                    >
                        <div
                            v-if="createItemStatus  == 'start'"
                            class="flex items-center justify-center mr-2 ">
                            <div class="w-3 h-3 border-b-2 border-red-900 rounded-full animate-spin"></div>
                        </div>
                        <span>добавить</span>
                    </mg-button>
                </div>
                <div v-if="!isCreateEventoButtonVisible" class="self-end mt-2">
                    <mg-button
                        @click="updateEvento(evento.id)"
                        :class="'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'"
                    >обновить
                    </mg-button>
                </div>
            </div>

            <div class="w-full">
                <mg-textarea v-model="evento.description" class="border-red-400"
                >Описание</mg-textarea>
            </div>
        </form>
    </div>
</template>

<script>
import {mapGetters, mapMutations, mapState} from "vuex";

export default {
    name: 'evento-create-edit',
    data(){
        return {
            isMainTagButtonVisible: false,
            isCreateEventoButtonVisible: true,
            evento:{},
        }
    },
    methods:{
        ...mapMutations({
            'setCurrentEditItemId': 'evento/setCurrentEditItemId',
        }),

        resetUpdateMode(){
            this.resetEventoForm();
            this.setCurrentEditItemId(0);
            this.isCreateEventoButtonVisible = true;
        },

        resetEventoForm(){
            this.evento = Object.assign({}, this.defaultEvento);
        },

        createEvento(){
            console.log('createEvento');
            let data = new FormData();
            for(let key in this.evento){
                data.append(key, this.evento[key]);
            }

            const item = {item: this.tag, formData: data}
            this.$store.dispatch('evento/createItem', item);
        },

        updateEvento(id){
            console.log('updateEvento: ', id);
            this.$store.dispatch('evento/updateItem', this.evento);
        },
    },
    computed:{
        ...mapState({
            'tags': state => state.tag.items,
        }),

        ...mapGetters({
            'createItemStatus': 'evento/getcreateItemStatus',
            'getCurrentEditItemId': 'evento/getCurrentEditItemId',
            'getCurrentEditedItem': 'evento/getCurrentEditedItem',
        }),

        getCurrentDate(){
            const dt = new Date();
            let year = dt.getFullYear();
            let day = dt.getDate() < 10 ? day = '0' +  dt.getDate() : dt.getDate();
            let month = dt.getMonth();
            return [year, month, day,].join('-');
        },
    },
    watch:{
        getCurrentEditItemId(nv, ov){
            //console.log('changed: ', nv);
            if (nv){
                this.evento = Object.assign({}, this.getCurrentEditedItem);
                this.isCreateEventoButtonVisible = false;
            }
        }
    },
    mounted() {
        this.resetEventoForm();
        this.evento.date = this.getCurrentDate;
    },
}
</script>

<style scoped>

</style>
