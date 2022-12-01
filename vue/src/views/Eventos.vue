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
                <div class="px-4 py-6 sm:px-0">

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

export default {
    name: "Eventos",
    components: {
        TagIndex, TagItem, TagList, TagCreateForm, TagEditForm, TagCreateEditForm,
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
            'loadItems': 'tag/loadItems',
        }),
    },
    computed:{
        ...mapState({
            'tags': state => state.tag.items,
        }),
    },
    watch:{
    },
    mounted() {
        this.loadItems();
    },

}
</script>

<style scoped>
</style>
