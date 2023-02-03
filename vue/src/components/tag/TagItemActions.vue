<template>
    <div class="flex items-center bg-gray-400 px-2 py-1 rounded-md">
        <mg-show-icon-button @click="$emit('showHideDescription')"></mg-show-icon-button>
        <mg-edit-icon-button @click="editMaterialHandler(tag_id)"></mg-edit-icon-button>
        <mg-trash-icon-button
            @click="deleteItemHandler(tag_id)"
            class="text-red-500 border-0"
        ></mg-trash-icon-button>
    </div>
</template>

<script>
export default {

    props:{
        tag_id: {
            required: true,
            type: Number,
        }
    },
    methods:{
        showHideDescription(){
            this.show = !this.show;
        },
        editMaterialHandler(id){
            this.$store.commit('tag/setCurrentEditItemId', id);
        },
        deleteItemHandler(id){
            if (!confirm('Действительно удалить?')) {return}
            this.$store.dispatch('tag/delItem', id);
            this.$store.dispatch('tag/setCurrentEditItemId', 0);
        },
    },
}
</script>

<style scoped>

</style>
