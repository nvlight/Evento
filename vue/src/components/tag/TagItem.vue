<template>
    <div class="material-item border-b border-l-cyan-600">
        <div class="flex mt-2">
            <div class="flex items-center">
                <mg-show-icon-button @click="showHideDescription"></mg-show-icon-button>
                <mg-edit-icon-button @click="editMaterialHandler(tag.id)"></mg-edit-icon-button>
                <mg-trash-icon-button @click="deleteItemHandler(tag.id)"></mg-trash-icon-button>
            </div>
            <span class="font-light">[{{tag.id}}]</span>
            <span class="ml-1 cursor-pointer title" @click="showHideDescription">{{tag.name}} </span>
            <div v-if="tag.img" class="ml-1 border-l-2 pl-1">
                <a :href="siteImgStaticPath+tag.img">img</a>
            </div>
        </div>
        <div class="description" v-if="show">{{ tag.description }}</div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: 'tag-item',
    components: {},
    emits: ['editBtnClicked'],
    props: {
        tag: {
            type: Object,
            required: true,
        },
        show_title: {
            type: Boolean,
            required: true,
        }
    },
    data(){
        return {
            show: false,
        }
    },
    methods:{
        showHideDescription(){
            this.show = !this.show;
        },
        editMaterialHandler(id){
            this.$store.dispatch('tag/setCurrentEditItem', id);
            this.$emit('editBtnClicked');
        },
        deleteItemHandler(id){
            if (!confirm('Действительно удалить?')) {return}
            this.$store.dispatch('tag/delItem', id);
            this.$store.dispatch('tag/setCurrentEditItem', true);
        },
    },
    computed:{
        ...mapGetters({
            getCreateItemStatus: 'tag/getCreateItemStatus',
        }),
        siteImgStaticPath(){
            return "http://laravel8-evento:87/storage/";
        }
    },
    mounted() {
        this.show = this.show_title;
    }
}
</script>

<style scoped>

</style>
