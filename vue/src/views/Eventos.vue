<template>

    <header class="dark:bg-gray-900 dark:text-white dark:shadow-amber-700">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold ">
                Eventos
            </h1>
        </div>
    </header>

    <div class="dark:bg-gray-900 dark:text-white">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8  ">
            <!-- Replace with your content -->
            <div class="p-5 border border-gray-300 rounded-md border-dashed">

                <mg-loading v-if="eventos.loading" class="m-auto">Загрузка...</mg-loading>

                <div v-else>
                    <evento-create-edit v-if="formVisible" />
                    <evento-list :eventos="eventos.items"/>
                    <evento-paginator :evento_links="eventos.links"
                      :current_page="eventos.current_page"
                      :last_page="eventos.last_page"
                    />
                </div>
            </div>
            <!-- /End replace -->
        </div>
    </div>

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
import MgLoading from "../components/UI/MgLoading.vue";

export default {
    name: "Eventos",
    components: {
        MgLoading,
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
            'loadFilteredEventos': "evento/filterItems",
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
        },

        loadEventosHandler(){
            // нужно получить page, если он есть и загрузить данные именно с этой страницы
            //const params = (new URL(document.location)).search;
            //const params_object = Object.fromEntries(new URL(window.location).searchParams.entries());
            //const params_string = (new URLSearchParams(obj)).toString();

            let page_key = "current_page";
            let url_path_key = "url_path";
            let filter_key = 'evento_filter';

            if (sessionStorage.hasOwnProperty(page_key) && sessionStorage.hasOwnProperty(url_path_key) ) {
                let page = sessionStorage.getItem(page_key);
                let url_path = sessionStorage.getItem(url_path_key);
                let url = { url: url_path + `?page=${page}`};

                if (sessionStorage.hasOwnProperty(filter_key)){
                    let params = {page: page}
                    params = {...params, ...JSON.parse(sessionStorage.getItem(filter_key)) };
                    this.loadFilteredEventos(params);
                }else{
                    this.loadEventos(url);
                }
            }else{
                this.loadEventos({});
            }

            // if (sessionStorage.hasOwnProperty(key)){
            //     console.log(sessionStorage.getItem(page_key))
            //     let params = JSON.parse(sessionStorage.getItem(key));
            //     if (sessionStorage.hasOwnProperty(page_key) && sessionStorage.hasOwnProperty(url_path_key) ) {
            //         params.current_page = +sessionStorage.getItem(page_key);
            //         let page = sessionStorage.getItem(page_key);
            //         let url_path = sessionStorage.getItem(url_path_key);
            //         let url = { url: url_path + `?page=${page}`};
            //     }else{
            //         this.loadFilteredEventos(params);
            //     }
            //     console.log(params);
            //
            // }else{
            //
            //     if (sessionStorage.hasOwnProperty(page_key) && sessionStorage.hasOwnProperty(url_path_key) ) {
            //
            //     }else {
            //
            //     }
            // }
        },
    },
    computed:{
        ...mapState({
            'eventos': state => state.evento.eventos,
            'formVisible': state => state.evento.createEditFormVisible,
            'current_page': state => state.evento.current_page,
            'url_path': state => state.evento.eventos.url_path,
        }),

        ...mapGetters({
        }),
    },
    watch:{
    },
    mounted() {
        this.loadTagItems();
        this.loadEventosHandler();
    },

}
</script>

<style scoped>
</style>
