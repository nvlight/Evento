<template>
    <form @submit.prevent>
        <div class="flex justify-between items-center">
            <h3 class="text-2xl">Редактирование тега</h3>
            <mg-close-icon-button @click="$emit('editFormClosedBtnPressed')"></mg-close-icon-button>
        </div>

        <mg-input-labeled class="mt-3 block" v-model="currentTag.name">Название</mg-input-labeled>
        <div class="flex justify-between mt-2">
            <mg-input-labeled v-model="currentTag.img">Иконка</mg-input-labeled>
        </div>
        <mg-textarea v-model="currentTag.description">Описание</mg-textarea>

        <mg-button @click="editTag">обновить</mg-button>
    </form>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: 'tag-edit-form',
    components: {},
    emits: ['editFormClosedBtnPressed'],
    data(){
        return {
        }
    },

    methods: {
        editTag(){
            //console.log(this.currentTag);
            //return;
            this.$store.dispatch('tag/editItem', this.currentTag);
        },

    },
    computed: {
        ...mapGetters({
            getItemById: "tag/getItemById",
            currentEditMaterialId: "tag/getCurrentEditItemId",
        }),

        currentTag(){
            const currEditMattId = this.currentEditMaterialId;
            const currEditMatt   = this.getItemById(currEditMattId);
            return Object.assign({}, currEditMatt);
        }
    },
    mounted() {
    },
    watch:{
    }

}
</script>

<style scoped>
</style>
