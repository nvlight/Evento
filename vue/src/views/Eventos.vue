<template>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Eventos
            </h1>
        </div>
    </header>

    <main>
        <mg-modal :dialog_content_classes="'w-6/12 mt-5 mb-5 overflow-y-scroll'"
            >

        </mg-modal>

        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Replace with your content -->
            <div class="p-5 border border-gray-300 rounded-md border-dashed">
                <div v-if="eventos.loading" class="font-semibold text-sm text-center mt-2">Загрузка...</div>

                <div v-else>
                    <evento-create-edit v-if="formVisible" />
                    <evento-list :eventos="eventos.items"/>
                    <evento-paginator :evento_links="eventos.links"/>
                </div>
            </div>
            <!-- /End replace -->
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
import EventoPaginator from "../components/evento/EventoPaginator.vue";

export default {
    name: "Eventos",
    components: {
        MgSelect, MgTrashIconButton,
        TagIndex, TagItem, TagList, TagCreateForm, TagEditForm, TagCreateEditForm,
        EventoList, EventoCreateEdit, EventoPaginator,
    },
    data(){
        return {
        }
    },
    props: {
    },
    methods:{
        ...mapActions({
            'loadTagItems': 'tag/loadItems',
            'loadEventos': 'evento/loadItems',
        }),

        ...mapMutations({
            'setCurrentEditItemId': 'evento/setCurrentEditItemId',
        }),

         getForPage(event, link) {
                event.preventDefault();
                if (!link.url || link.active){
                    return;
                }
            this.$store.dispatch("evento/loadItems", {url: link.url})
        }
    },
    computed:{
        ...mapState({
            'tags': state => state.tag.items,
            'eventos': state => state.evento.eventos,
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
