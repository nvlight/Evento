<template>
    <form @submit.prevent>
        <div class="flex justify-between items-center">
            <h3 class="text-2xl">
                <span v-if="isCreatedButtonVisible">Создание </span>
                <span v-else>Редактирование</span>
                тега
            </h3>
            <mg-close-icon-button
                v-if="!isCreatedButtonVisible"
                @click="resetFormHandler"
            >
            </mg-close-icon-button>
        </div>

<!--        <div>getCurrentEditMaterialId: {{getCurrentEditMaterialId}}</div>-->
<!--        <div>editFormShow: {{editFormShow}}</div>-->
<!--        <div>tag.img: {{img_src}}</div>-->
<!--        <div>isImgSrcFileObject: {{typeof isImgSrcFileObject}}</div>-->

        <div v-if="false" class="debuggg">
            <div class="flex justify-between">
                <span class="font-semibold">getCurrentEditMaterial: </span>
                <span class="font-semibold">{{getCurrentEditMaterial}}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">tag: </span>
                <span class="font-semibold">{{tag}}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">getCreateItemStatus: </span>
                <span class="font-semibold">{{getCreateItemStatus}}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">getCreatedItemId:</span>
                <span class="font-semibold">{{getCreatedItemId}}</span>
            </div>
        </div>

        <mg-input-labeled class="mt-3 block" v-model="tag.name">Название</mg-input-labeled>
        <div class="flex justify-between mt-2">
            <div>
                <label class="block text-sm font-medium text-gray-700" for="">
                    Иконка
                </label>
                <div class="mt-1 flex items-center">
                    <div>
                        <img v-if="tag.img && !isImgSrcFileObject" :src="img_src" class="h-10 w-10" alt="">
                        <div v-else>
                            <img
                                v-if="tagImgModel.image_url"
                                :src="tagImgModel.image_url"
                                :alt="tagImgModel.title"
                                class="w-10 h-10 object-cover rounded-md"
                            >
                            <span v-else class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100"
                            >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[80%] w-[80%] text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                        </div>
                    </div>

                    <button type="button" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative">
                        <input type="file"
                               @change="onImageChoose"
                               class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer"
                        >Изменить</button>
                </div>
            </div>
        </div>

        <div class="tag_colors">
            <div class="font-semibold">
                Цвет текста и фона
            </div>
            <div class="flex items-center justify-between">
                <div class="flex-col">
                    <div class="flex justify-between">
                        <span>Цвет текста: </span>
                        <input type="color" v-model="tag.text_color" class="ml-2">
                    </div>
                    <div class="flex justify-between">
                        <span>Цвет фона:</span>
                        <input type="color" v-model="tag.bg_color" class="ml-2">
                    </div>
                </div>

                <div>
                    <div class="text-center text-sm">Просмотр тега</div>
                    <div class="mt-1 w-fit rounded-md cursor-pointer" :style="['background-color: '+tag.bg_color]">
                        <div class="p-2 " :style="['color: '+tag.text_color]">{{ tag.name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <mg-textarea v-model="tag.description">Описание</mg-textarea>

        <div class="flex">
            <mg-button
                v-if="isCreatedButtonVisible"
                @click="createTag" class="bg-green-600 hover:bg-green-700 focus:ring-green-500"
            >создать
            </mg-button>
            <mg-button
                v-else
                @click="editTag" class="bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500"
            >обновить
            </mg-button>
        </div>

    </form>
</template>

<script>
import {mapGetters, mapMutations} from "vuex";

export default {
    name: 'tag-create-edit-form',
    components: {},
    emits: [],
    props:{
    },
    data(){
        return {
            isCreatedButtonVisible: true,

            tagImgModel: {},
            tag: {},

            defaultTag: {
                name: 'Название',
                img: '',
                description: '',
                text_color: '#ffffff',
                bg_color: '#000000',
            },
        }
    },

    methods: {
        ...mapMutations({
            setCurrentEditId: 'tag/setCurrentEditItemId',
            setCreatedItemId: 'tag/setCreatedItemId',
            setCreatedItemStatus: 'tag/setCreateItemStatus',
        }),
        resetForm(){
            this.tag = Object.assign({}, this.defaultTag);
        },
        resetFormHandler(){
            this.isCreatedButtonVisible = !this.isCreatedButtonVisible;
            this.tagImgModel.image_url = null;
            this.resetForm();

            // this.setCurrentEditId(0);
            // this.setCreatedItemStatus(false);
            // this.setCreatedItemId(0);
        },
        createTag(){
            this.$store.commit('tag/setCreateItemStatus', false);

            let data = new FormData();
            data.append('name', this.tag.name);
            data.append('description', this.tag.description);
            data.append('img', this.tag.img);
            data.append('text_color', this.tag.text_color);
            data.append('bg_color', this.tag.bg_color);

            const item = {item: this.tag, formData: data}
            this.$store.dispatch('tag/createItem', item);
        },
        editTag(){
            this.$store.commit('tag/setCreateItemStatus', false);

            let data = new FormData();
            data.append('name',        this.tag.name);
            data.append('description', this.tag.description);
            data.append('img',         this.tag.img);
            data.append('text_color',  this.tag.text_color);
            data.append('bg_color',    this.tag.bg_color);

            // https://stackoverflow.com/questions/47676134/laravel-request-all-is-empty-using-multipart-form-data
            data.append('_method','PUT');

            const item = {item: this.tag, formData: data}

            this.$store.dispatch('tag/editItem', item);
        },
        onImageChoose(ev){
            this.tag.img = ev.target.files[0];

            const reader = new FileReader();
            reader.onload = () => {
                this.tagImgModel.image_url = reader.result;
            }
            reader.readAsDataURL(this.tag.img);
        },
    },
    computed: {
        ...mapGetters({
            getCurrentEditMaterialId: "tag/getCurrentEditItemId",
            getCurrentEditMaterial: "tag/getCurrentEditItem",
            getCreateItemStatus: "tag/getCreateItemStatus",
            getCreatedItemId: "tag/getCreatedItemId",
        }),

        isImgSrcFileObject(){
            return (typeof this.tag.img) === 'object';
        },

        img_src(){
            return this.$store.getters['getSiteImgStaticPath'] + this.tag.img;
        },
    },
    mounted() {
        this.resetForm();
        this.isCreatedButtonVisible = true;
    },
    watch:{
        getCurrentEditMaterialId(nv,ov){
            //console.log('getCurrentEditMaterialId', nv)
            const currEdtMaterial = this.getCurrentEditMaterial;
            if ( currEdtMaterial ){
                this.tagImgModel.image_url = null;
                this.tag = Object.assign({}, currEdtMaterial);
                this.isCreatedButtonVisible = false;
            }else{
                this.resetFormHandler();
            }
        },
        getCreateItemStatus(nv,ov){
            //console.log('getCreateItemStatus', nv)
            if (nv){
                this.setCurrentEditId(this.getCreatedItemId);
            }
        },

    },

}
</script>

<style scoped>
</style>
