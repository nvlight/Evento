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

                <tag-list class="w-7/12 w-full ml-5 border border-dotted border p-3" :tags="tags">
                </tag-list>
            </div>
        </mg-modal>

        <div>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <div class="p-5 border border-gray-300 rounded-md border-dashed">
                    <h1 class="text-xl font-semibold">Добавление события</h1>
                    <form @submit.prevent>
                        <div class="mt-3 flex items-center ">

                            <div class="main_date">
                                <span>[ {{eventoDate}} ]</span>
                                <mg-input-date-labeled v-model="eventoDate"></mg-input-date-labeled>
                            </div>

                            <div class="tag_first w-3/12 ml-3">
                                <span>Тег основной [ {{tagValue.tag_id_first}} ]</span>
                                <mg-checkbox v-model="tagValue.tag_id_first"  class="w-full"
                                    :options="tags"
                                ></mg-checkbox>
                            </div>

                            <div class="tag_value ml-3">
                                <span>Значение [ {{tagValue.value}} ]</span>
                                <mg-input-labeled class="tag_value " v-model="tagValue.value"></mg-input-labeled>
                            </div>

                            <mg-plus-icon-button
                                v-if="isMainTagButtonVisible" @click="isMainTagButtonVisible = !isMainTagButtonVisible"
                                class="add_anather_tag block bg-green-600
                                focus:ring-green-600 rounded-md text-white p-2 self-end ml-3"
                            >Добавить тег</mg-plus-icon-button>

                            <div v-if="!isMainTagButtonVisible" class="tag_first w-3/12 ml-3">
                                <span>Тег вторичный [ {{tagValue.tag_id_second}} ]</span>
                                <mg-checkbox v-model="tagValue.tag_id_second"  class="w-full"
                                     :options="tags"
                                ></mg-checkbox>
                            </div>

                            <mg-trash-icon-button
                                v-if="!isMainTagButtonVisible" @click="isMainTagButtonVisible = !isMainTagButtonVisible"
                                class="add_anather_tag block border-red-500 text-red-500
                                focus:ring-red-600 rounded-md p-2 self-end ml-3"
                            >Удалить тег</mg-trash-icon-button>

                        </div>

                        <div class="w-4/12">
                            <mg-textarea v-model="tagValue.description">Описание</mg-textarea>
                        </div>

                        <div>
                            <mg-button @click="createEvento" class="h-fit">добавить</mg-button>
                        </div>
                    </form>
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
import MgTextarea from "../components/UI/MgTextarea.vue";
import MgTrashIconButton from "../components/UI/MgTrashIconButton.vue";

export default {
    name: "Eventos",
    components: {
        MgTrashIconButton,
        MgTextarea,
        TagIndex, TagItem, TagList, TagCreateForm, TagEditForm, TagCreateEditForm,
    },
    data(){
        return {
            tagValue:{
                value: '',
                description: '',
                evento_id: 0,
                tag_id_first: 0,
                tag_id_second: 0,
            },
            eventoDate: '',

            isMainTagButtonVisible: true,
        }
    },
    props: {
        tagModalVisible: {
            type: Object,
        },
    },
    methods:{
        ...mapActions({
            'loadItems': 'tag/loadItems',
        }),

        createEvento(ev){
            //console.log('createEvento', ev.target);
        },
    },
    computed:{
        ...mapState({
            'tags': state => state.tag.items,
        }),

        getCurrentDate(){
            const dt = new Date();
            let year = dt.getFullYear();
            let day = dt.getDay(); if (day < 10) { day = '0' +day }
            let month = dt.getMonth();
            let fullDt = [year, month, day, ].join('-')
            return fullDt;
        },
    },
    watch:{
    },
    mounted() {
        this.loadItems();
        this.eventoDate = this.getCurrentDate;
    },

}
</script>

<style scoped>
</style>
