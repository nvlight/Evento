<template>
    <div>
        <menu-header title="События"></menu-header>

        <div class="dark:bg-gray-900 dark:text-white">
            <div class="evento-view max-w-screen-2xl mx-auto py-6 sm:px-6 lg:px-8  ">
                <!-- Replace with your content -->

                <router-link
                    :to="{name: 'Tags'}"
                    class="underline"
                >Теги
                </router-link>

                <div class="py-2 md:py-5 rounded-md">

                    <evento-create-edit v-if="formVisible" />
                    <evento-list class="rounded-t-md"/>

                </div>
                <!-- /End replace -->
            </div>
        </div>
    </div>

</template>

<script>
import TagList from "../components/tag/TagList.vue";
import TagItem from "../components/tag/TagItem.vue";
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";
import TagCreateEditForm from "../components/tag/TagCreateEditForm.vue";
import MgTrashIconButton from "../components/UI/MgTrashIconButton.vue";
import MgSelect from "../components/UI/MgSelect.vue";
import EventoList from "../components/evento/EventoList.vue";
import EventoCreateEdit from "../components/evento/EventoCreateEdit.vue";
import EventoPaginator from "../components/evento/EventoPaginator.vue";
import MgLoading from "../components/UI/MgLoading.vue";
import EventoMenuHeader from "../components/evento/EventoMenuHeader.vue";
import MenuHeader from "../components/MenuHeader.vue";

export default {
    name: "Eventos",
    components: {
        MenuHeader,
        MgLoading,
        MgSelect, MgTrashIconButton,
        TagItem, TagList, TagCreateEditForm,
        EventoList, EventoCreateEdit, EventoPaginator, EventoMenuHeader,
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
        }),

        ...mapMutations({
            'setCurrentEditItemId': 'evento/setCurrentEditItemId',
        }),

    },
    computed:{
        ...mapState({
            'eventos': state => state.evento.eventos,
            'formVisible': state => state.evento.createEditFormVisible,
            'current_page': state => state.evento.current_page,
            'url_path': state => state.evento.eventos.url_path,
            'pickedEventos': state => state.evento.pickedEventos,
        }),

        ...mapGetters({
        }),
    },
    watch:{
    },
    mounted() {
        this.loadTagItems();

        //
    },

}
</script>

<style scoped>
</style>
