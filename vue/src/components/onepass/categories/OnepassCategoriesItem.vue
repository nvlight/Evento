<template>
    <div class="material-item">
        <div class="flex mt-2 items-center mb-1">
            <onepass-categories-item-actions
                :item_id="item.id"
                @showHideDescription="showHideDescription"
            />
            <span class="ml-1 cursor-pointer title p-1 px-3 rounded-md"
                  @click="showHideDescription">{{item.name}}
            </span>
        </div>
        <div
            class="description"
            v-if="show"
        >
            {{ item.note }}
            <div v-if="item.image_full" class="ml-1 border-l-2 pl-1">
                <img :src="item.image_full"/>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from "vuex";
import OnepassCategoriesItemActions from "./OnepassCategoriesItemActions.vue";

export default {
    components: {
        OnepassCategoriesItemActions,
    },
    emits: [],
    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    data(){
        return {
            show: false,
        }
    },
    methods:{
        ...mapActions({
            'onepassCategoryDel': "onepassCategory/delItem",
            'onepassCategorySetCurrentEditItemIdAction': "onepassCategory/setCurrentEditItemId",
        }),
        ...mapMutations({
            'onepassCategorySetCurrentEditItemIdMutation': 'onepassCategory/setCurrentEditItemId',
        }),

        showHideDescription(){
            this.show = !this.show;
        },
        editMaterialHandler(id){
            //this.$store.commit('tag/setCurrentEditItemId', id);
            this.onepassCategorySetCurrentEditItemIdMutation(id);
        },
        deleteItemHandler(id){
            if (!confirm('Действительно удалить?')) {return}
            //this.$store.dispatch('tag/delItem', id);
            this.onepassCategoryDel(id);

            //this.$store.dispatch('tag/setCurrentEditItemId', 0);
            this.onepassCategorySetCurrentEditItemIdAction(0);
        },
    },
    computed:{
        ...mapGetters({
        }),
    },
    mounted() {
    },
}
</script>

<style scoped>

</style>
