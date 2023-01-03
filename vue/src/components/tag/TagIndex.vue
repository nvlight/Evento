<template>

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Tags
            </h1>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="p-5 border border-gray-300 rounded-md border-dashed">
            <mg-loading v-if="tags.loading" class="m-auto">Загрузка...</mg-loading>

            <div v-else class="flex">
                <tag-create-edit-form class="w-5/12 border border p-3"></tag-create-edit-form>

                <tag-list class="w-7/12 w-full ml-5 border-2 border-dotted border p-3" :tags="tags.items">
                </tag-list>
            </div>
        </div>
    </div>

</template>

<script>
import Navbar from "../nav/Navbar.vue";
import TagCreateEditForm from "./TagCreateEditForm.vue";
import TagList from "./TagList.vue";
import {mapActions, mapState} from "vuex";
import MgFooter from "../footer/MgFooter.vue";

export default {
    name: 'tag-index',
    components: {
        Navbar, TagCreateEditForm, TagList, MgFooter,
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
