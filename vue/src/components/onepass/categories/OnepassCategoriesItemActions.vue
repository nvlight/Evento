<template>
    <div class="flex items-center bg-gray-400 px-2 py-1 rounded-md">
        <mg-show-icon-button @click="$emit('showHideDescription')"></mg-show-icon-button>
        <mg-edit-icon-button @click="editItemHandler(item_id)"></mg-edit-icon-button>
        <mg-trash-icon-button
            @click="deleteItemHandler(item_id)"
            class="text-red-500 border-0"
        ></mg-trash-icon-button>
    </div>
</template>

<script>
import {mapActions, mapMutations} from "vuex";

export default {

    props:{
        item_id: {
            required: true,
            type: Number,
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
        editItemHandler(id){
            console.log('editItemHandler:', id);
            //this.$store.commit('tag/setCurrentEditItemId', id);
            this.$store.commit('onepassCategory/itemEditBtnClickChanged');
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
}
</script>

<style scoped>

</style>
