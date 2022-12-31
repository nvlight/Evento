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

        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Replace with your content -->
            <div class="p-5 border border-gray-300 rounded-md border-dashed">
                <div v-if="eventos.loading" class="font-semibold text-sm text-center mt-2">Загрузка...</div>

                <div v-else>
                    <evento-create-edit v-if="formVisible" />
                    <evento-list :eventos="eventos.items"/>

                    <!-- Pagination -->
                    <div v-if="eventos.links.length" class="flex justify-center mt-5">
                        <nav
                            class="relative z-0 inline-flex justify-center rounded-md shadow-sm"
                            aria-label="Pagination"
                        >
                            <a
                                v-for="(link, i) of eventos.links"
                                :key="i" :disabled="!link.url" href="#" aria-current="page"
                                v-html="link.label"
                                @click="getForPage($event, link)"
                                class="relative inline-flex items-center px-4 py-2 border test-sm font-medium whitespace-nowrap"
                                :class="[
                                    link.active
                                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-100',
                                        i === 0 ? 'rounded-l-md' : '',
                                        i === eventos.links.length-1 ? 'rounded-r-md' : '',
                                ]"
                            >

                            </a>
                        </nav>
                    </div>
                    <!--/ Pagination -->
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
