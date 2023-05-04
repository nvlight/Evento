<template>
    <menu-header title="Теги"></menu-header>

    <div class="dark:bg-gray-900 dark:text-white">
        <div class="max-w-screen-2xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="p-5 border border-gray-300 rounded-md border-dashed">
                <mg-loading v-if="tags.loading" class="m-auto">Загрузка...</mg-loading>

                <div v-else class="flex">
                    <tag-create-edit-form class="w-4/12 border p-3" />
                    <tag-list class="w-8/12 w-full ml-5 border-2 border-dotted border p-3" :tags="tags.items" />
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import Navbar from "../components/nav/Navbar.vue";
import TagCreateEditForm from "../components/tag/TagCreateEditForm.vue";
import TagList from "../components/tag/TagList.vue";
import {mapActions, mapState} from "vuex";
import MgFooter from "../components/footer/MgFooter.vue";
import EventoMenuHeader from "../components/evento/EventoMenuHeader.vue";
import MenuHeader from "../components/MenuHeader.vue";

export default {
    name: 'tag-index',
    components: {
        Navbar, TagCreateEditForm, TagList, MgFooter, EventoMenuHeader, MenuHeader
    },

    methods: {
        ...mapActions({
            'loadTagItems': 'tag/loadItems',
        }),
    },
    computed: {
        ...mapState({
            'tags': state => state.tag.tags,
        }),
    },

    mounted() {
        this.loadTagItems();
    },
}
</script>

<style scoped>

</style>
