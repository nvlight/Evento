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
            :dialog_content_classes="'w-6/12 mt-5 mb-5 overflow-y-scroll'"
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
                    <evento-create-edit v-if="formVisible" />
                    <evento-list :eventos="eventos"/>
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
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";
import TagCreateEditForm from "../components/tag/TagCreateEditForm.vue";
import MgTrashIconButton from "../components/UI/MgTrashIconButton.vue";
import MgSelect from "../components/UI/MgSelect.vue";
import EventoList from "../components/evento/EventoList.vue";
import EventoCreateEdit from "../components/evento/EventoCreateEdit.vue";

export default {
    name: "Eventos",
    components: {
        MgSelect,
        MgTrashIconButton,
        TagIndex, TagItem, TagList, TagCreateForm, TagEditForm, TagCreateEditForm, EventoList, EventoCreateEdit
    },
    data(){
        return {
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

        ...mapMutations({
            'setCurrentEditItemId': 'evento/setCurrentEditItemId',
        }),

    },
    computed:{
        ...mapState({
            'tags': state => state.tag.items,
            'eventos': state => state.evento.items,
            'formVisible': state => state.evento.createEditFormVisible,
        }),

        ...mapGetters({
        }),
    },
    watch:{
    },
    mounted() {
        this.loadTagItems();
        this.loadEventos();
    },

}
</script>

<style scoped>
</style>
