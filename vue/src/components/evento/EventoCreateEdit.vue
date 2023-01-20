<template>
    <div class="evento_create_form_wrapper p-3 border border-dashed rounded-md">

        <div class="flex justify-between text-xl font-semibold flex items-center ">
            <div class="w-full">
                <h1 v-if="createMode">Добавление события</h1>
                <h1 v-if="editMode" class="text-xl font-semibold">Редактирование события</h1>
            </div>
            <mg-close-icon-button
                @click="closeForm">
            </mg-close-icon-button>
        </div>

<!--        <div>-->
<!--            <div>getCurrentEditItemId: {{getCurrentEditItemId}}</div>-->
<!--            <div>getCurrentEdited: {{typeof getCurrentEditedItem}}</div>-->
<!--        </div>-->

        <form
            @submit.prevent>
            <div class="flex flex-wrap items-center justify-between w-full">

                <div class="main_date mr-2 mt-2">
                    <span>[ {{evento.date}} ]</span>
                    <mg-input-date-labeled v-model="evento.date"></mg-input-date-labeled>
                </div>

                <div class="tag_first  mt-2">
                    <span>Тег основной [ {{evento.tag_id_first}} ]</span>
                    <mg-select v-model="evento.tag_id_first"  class=""
                        :options="tags"
                    >
                        <option value="0">Выберите из списка</option>
                    </mg-select>
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
                    >
                        <option value="0">Выберите из списка</option>
                    </mg-select>
                    <mg-trash-icon-button
                        v-if="!isMainTagButtonVisible" @click="isMainTagButtonVisible = !isMainTagButtonVisible"
                        class="add_anather_tag absolute border-none text-red-500 self-end
                                            absolute right-0 top-1 focus:ring-red-500 rounded-sm h-4 w-4
                                            "
                        :svgClass="'h-4 w-4'"
                    ></mg-trash-icon-button>
                </div>
            </div>

            <div class="w-full">
                <mg-textarea v-model="evento.description" class="border-red-400"
                >Описание</mg-textarea>
            </div>

            <Alert :errors="errors" @resetErrors="errors = {}"></Alert>

            <div class="buttons flex justify-end">
                <div v-if="createMode" class="self-end mt-2">
                    <mg-button
                        @click="createEvento"
                        :class="'bg-green-600 hover:bg-green-700 focus:ring-green-500 flex items-center'"
                    >
                        <mg-spin v-if="createItemLoading"></mg-spin>
                        <span>добавить</span>
                    </mg-button>
                </div>
                <div v-if="editMode" class="self-end mt-2">
                    <mg-button
                        @click="updateEvento()"
                        :class="'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500'"
                    >
                        <mg-spin v-if="updateItemLoading"></mg-spin>
                        <span>обновить</span>
                    </mg-button>
                </div>
            </div>

        </form>
    </div>
</template>

<script>
import {mapGetters, mapMutations, mapState} from "vuex";
import Alert from "../Alert.vue";

export default {
    name: 'evento-create-edit',
    components: { Alert },

    emits: [],
    data(){
        return {
            isMainTagButtonVisible: false,
            isCreateEventoButtonVisible: true,
            evento:{},

            defaultEvento:{
                value: '',
                description: '',
                tag_id_first: 0,
                tag_id_second: 0,
            },

            errors: {},
        }
    },
    methods:{
        ...mapMutations({
            'setCurrentEditItemId': 'evento/setCurrentEditItemId',
        }),

        closeForm(){
            this.resetEvento();
            this.setCurrentEditItemId(0);

            this.$store.dispatch('evento/closeForm');
        },

        createEvento(){
            let data = new FormData();
            for(let key in this.evento){
                data.append(key, this.evento[key]);
            }

            const item = {item: this.tag, formData: data}
            this.$store.dispatch('evento/createItemQuery', item)
                .then((res) => {
                    if (res?.status === 200 && res?.data?.success === 1){
                        this.resetEvento();
                    }else{
                        this.errors = {};
                        let err = res?.response?.data?.errors;
                        if (err) {
                            this.errors = err;
                        }
                    }
                })
                .catch((err) => {
                    console.log('create evento - catch: ', err);
                });
        },

        updateEvento(){
            this.$store.dispatch('evento/updateItem', this.evento)
                .then((res) => {
                    this.errors = {};
                    //console.log('update evento - then: ', res);
                    this.errors = res.response.data.errors;
                })
                .catch((err) => {
                    //console.log('update evento - catch: ', err);
                });
        },

        createModeHandler(){
            this.resetEvento();
            this.evento.date = this.getCurrentDate;
        },

        resetEvento(){
            this.evento = Object.assign({}, this.defaultEvento);
            this.evento.date = this.getCurrentDate;
        },

        editModeHandler(){
            this.editedItem;
        },

        mountedHandler(){
            if (this.createMode){
                this.createModeHandler();
            }
            if (this.editMode){
                this.editModeHandler();
            }
        },

    },
    computed:{
        ...mapState({
            'tags': state => state.tag.tags.items,
            'formVisible': state => state.evento.createEditFormVisible,
            'editMode': state => state.evento.editMode,
            'createMode': state => state.evento.createMode,
            'createButtonClicked': state => state.evento.createButtonClicked,
            'editButtonClicked': state => state.evento.editButtonClicked,

            'createItemLoading': state => state.evento.createItemLoading,
            'updateItemLoading': state => state.evento.updateItemLoading,
        }),

        ...mapGetters({
            'createItemStatus': 'evento/getcreateItemStatus',
            'getCurrentEditItemId': 'evento/getCurrentEditItemId',
            'getCurrentEditedItem': 'evento/getCurrentEditedItem',
            'getItemById': 'evento/getItemById',
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

        editedItem(){
            let filteredItem = this.getItemById(this.getCurrentEditItemId);
            this.evento = Object.assign({}, filteredItem);
            return filteredItem;
        },

    },
    watch:{
        createButtonClicked(nv, ov){
            this.createModeHandler();
        },
        editButtonClicked(nv, ov){
            this.evento = Object.assign({}, this.getCurrentEditedItem);
        },
    },
    mounted() {
        this.mountedHandler();
    },
}
</script>

<style scoped>

</style>
