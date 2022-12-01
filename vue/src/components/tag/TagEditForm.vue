<template>
    <form @submit.prevent>
        <div class="flex justify-between items-center">
            <h3 class="text-2xl">Редактирование тега</h3>
            <mg-close-icon-button @click="$emit('editFormClosedBtnPressed')"></mg-close-icon-button>
        </div>

        <div>currentTag: {{currentTag}}</div>
        <div>imgSrvName: {{imgSrvName}}</div>

        <mg-input-labeled class="mt-3 block" v-model="currentTag.name">Название</mg-input-labeled>
        <div class="flex justify-between mt-2">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="">
                    Иконка
                </label>
                <div class="mt-1 flex items-center">
                    <img
                        v-if="tagImgModel.image_url"
                        :src="tagImgModel.image_url"
                        :alt="tagImgModel.title"
                        class="w-10 h-10 object-cover rounded-md"
                    >
                    <span
                        v-else
                        class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[80%] w-[80%] text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <button type="button" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative">
                        <input type="file"
                           @change="onImageChoose"
                           class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer"
                        >Change</button>
                </div>
            </div>
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
            tagImgModel: {},
            img: '',
        }
    },

    methods: {
        editTag(){
            let data = new FormData();
            data.append('name',        this.currentTag.name);
            data.append('description', this.currentTag.description);
            data.append('img',         this.currentTag.img);

            // https://stackoverflow.com/questions/47676134/laravel-request-all-is-empty-using-multipart-form-data
            data.append('_method','PUT');

            const item = {item: this.currentTag, formData: data}

            this.$store.dispatch('tag/editItem', item);
        },
        onImageChoose(ev){
            this.img = ev.target.files[0];

            const reader = new FileReader();
            reader.onload = () => {
                this.tagImgModel.image_url = reader.result;
            }

            reader.readAsDataURL(this.img);
        },
        customImgChoose(srvImage){
            const reader = new FileReader();
            reader.onload = () => {
                this.tagImgModel.image_url = reader.result;
            }
            reader.readAsDataURL(srvImage);
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
            if (this.img){
                currEditMatt.img     = this.img;
            }
            return Object.assign({}, currEditMatt);
        },

        siteImgStaticPath(){
            return "http://laravel8-evento:87/storage/";
        },

        imgSrvName(){
            const nm = this.siteImgStaticPath + this.currentTag.img;
            //this.customImgChoose(nm);
            return nm;
        },
    },
    mounted() {
    },
    watch:{
    }

}
</script>

<style scoped>
</style>
