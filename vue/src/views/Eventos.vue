<template>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Eventos
            </h1>
        </div>
    </header>

    <main>
        <mg-modal v-model:show="tagModalVisible.value"
            :dialog_content_classes="'w-6/12 mt-5 mb-5'"
            >
            <div class="flex">
                <tag-create-edit-form class="w-5/12 border border p-3"></tag-create-edit-form>

                <tag-list class="w-7/12 w-full ml-5 border-2 border-dotted border p-3" :tags="tags">
                </tag-list>
            </div>
        </mg-modal>

        <div>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <div class="p-5 border border-gray-300 rounded-md border-dashed">
                    <div class="evento_create_form_wrapper p-3 border border-dashed rounded-md">
                        <h1 class="text-xl font-semibold">Добавление события</h1>
                        <form @submit.prevent>
                            <div class="mt-3 flex items-center justify-between">

                                <div class="main_date">
                                    <span>[ {{evento.date}} ]</span>
                                    <mg-input-date-labeled v-model="evento.date"></mg-input-date-labeled>
                                </div>

                                <div class="tag_first ">
                                    <span>Тег основной [ {{evento.tag_id_first}} ]</span>
                                    <mg-select v-model="evento.tag_id_first"  class=""
                                        :options="tags"
                                    ></mg-select>
                                </div>

                                <div class="tag_value ">
                                    <span>Значение [ {{evento.value}} ]</span>
                                    <mg-input-labeled class="" v-model="evento.value"></mg-input-labeled>
                                </div>

                                <mg-plus-icon-button
                                    v-if="isMainTagButtonVisible" @click="isMainTagButtonVisible = !isMainTagButtonVisible"
                                    class="add_anather_tag block bg-green-600
                                    focus:ring-green-600 rounded-md text-white p-2 self-end ml-3"
                                >привязать тег</mg-plus-icon-button>

                                <div v-if="!isMainTagButtonVisible" class="tag_first
                                    relative">
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

                                <div class="self-end">
                                    <mg-button @click="createEvento" class="">добавить</mg-button>
                                </div>
                            </div>

                            <div class="w-4/12">
                                <mg-textarea v-model="evento.description" class="border-red-400">Описание</mg-textarea>
                            </div>
                        </form>
                    </div>
                    <div class="evento_table mt-3">
                        <evento-list :eventos="eventos"></evento-list>
                    </div>
                </div>
                <!-- /End replace -->
            </div>
        </div>
    </main>
</template>

<script>
import TagCreateForm from "../components/tag/TagCreateForm.vue";
import TagList from "../components/tag/TagList.vue";
import TagItem from "../components/tag/TagItem.vue";
import TagIndex from "../components/tag/TagIndex.vue"
import TagEditForm from "../components/tag/TagEditForm.vue";
import {mapActions, mapState} from "vuex";
import TagCreateEditForm from "../components/tag/TagCreateEditForm.vue";
import MgTrashIconButton from "../components/UI/MgTrashIconButton.vue";
import MgSelect from "../components/UI/MgSelect.vue";
import EventoList from "../components/evento/EventoList.vue";

export default {
    name: "Eventos",
    components: {
        MgSelect,
        MgTrashIconButton,
        TagIndex, TagItem, TagList, TagCreateForm, TagEditForm, TagCreateEditForm, EventoList,
    },
    data(){
        return {
            evento:{
                value: '',
                description: '',
                evento_id: 0,
                tag_id_first: 0,
                tag_id_second: 0,
                date: '2022-12-03',
            },

            isMainTagButtonVisible: false,
        }
    },
    props: {
        tagModalVisible: {
            type: Object,
        },
    },
    methods:{
        ...mapActions({
            'loadTagItems': 'tag/loadItems',
            'loadEventos': 'evento/loadItems',
        }),

        createEvento(){
            let data = new FormData();
            for(let key in this.evento){
                data.append(key, this.evento[key]);
            }

            const item = {item: this.tag, formData: data}
            this.$store.dispatch('evento/createItem', item);
        },
    },
    computed:{
        ...mapState({
            'tags': state => state.tag.items,
            'eventos': state => state.evento.items,
        }),

        getCurrentDate(){
            const dt = new Date();
            let year = dt.getFullYear();
            let day = dt.getDate();
            if (day < 10) { day = '0' + day }
            let month = dt.getMonth();
            let fullDt = [year, month, day, ].join('-')
            return fullDt;
        },
    },
    watch:{
    },
    mounted() {
        this.loadTagItems();
        this.loadEventos();
        this.evento.date = this.getCurrentDate;
    },

}
</script>

<style scoped>
</style>
