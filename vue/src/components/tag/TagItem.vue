<template>
    <div class="material-item border-b border-l-cyan-600">
        <div class="flex mt-2">
            <div class="flex items-center">
                <mg-show-icon-button @click="showHideDescription"></mg-show-icon-button>
                <mg-edit-icon-button @click="editMaterialHandler(tag.id)"></mg-edit-icon-button>
                <mg-trash-icon-button @click="deleteItemHandler(tag.id)"
                    class="text-red-500 border-0"
                ></mg-trash-icon-button>
            </div>
            <span class="font-light">[{{tag.id}}]</span>
            <span class="ml-1 cursor-pointer title" @click="showHideDescription">{{tag.name}}
            </span>
        </div>
        <div class="description" v-if="show">{{ tag.description }}
            <div v-if="tag.img" class="ml-1 border-l-2 pl-1">
                <img :src="img_src"/>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: 'tag-item',
    components: {},
    emits: [],
    props: {
        tag: {
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
    computed:{
        ...mapGetters({
        }),

        img_src(){
            return this.$store.getters['getSiteImgStaticPath'] + this.tag.img;
        },
    },
    mounted() {
    }
}
</script>

<style scoped>

</style>
