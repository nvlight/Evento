<template>
    <div class="material-item">
        <div class="flex mt-2 items-center mb-1">
            <tag-item-actions
                :tag_id="tag.id"
                @showHideDescription="showHideDescription"
            />
            <span class="ml-1 cursor-pointer title p-1 px-3 rounded-md"
                  :style="[
                    tag.bg_color ? `background-color: ${tag.bg_color}` : 'background-color: #5CB85C',
                    tag.text_color ? `color: ${tag.text_color}` : 'color: #fff',
                  ]"
                  @click="showHideDescription">{{tag.name}}
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
import TagItemActions from "./TagItemActions.vue";

export default {
    name: 'tag-item',
    components: {
        TagItemActions,
    },
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
