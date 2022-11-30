<template>
    <!-- enctype="multipart/form-data"-->
    <form @submit.prevent>
        <div class="flex justify-between items-center">
            <h3 class="text-2xl">Создание нового тега</h3>
            <mg-close-icon-button @click="resetForm()"></mg-close-icon-button>
        </div>

        <mg-input-labeled class="mt-3 block" v-model="tag.name">Название</mg-input-labeled>
        <div class="justify-between mt-2">

            <!-- <mg-input-labeled v-model="tag.img">Иконка</mg-input-labeled>-->
<!--            <div>-->
<!--                <span>tagImgModel: {{tagImgModel}}</span>-->
<!--            </div>-->
<!--            <div>tag:{{tag}}</div>-->
<!--            <div>imgFile:{{JSON.stringify(imgFile)}}</div>-->
<!--            <div>imgFile:{{imgFile}}</div>-->
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
        <mg-textarea v-model="tag.description">Описание</mg-textarea>

        <mg-button @click="create">создать</mg-button>
    </form>
</template>

<script>
import {mapGetters, mapState} from "vuex";
import axiosClient from "../../axios.js";

export default {
    name: 'tag-create-form',
    components: {},

    data(){
        return {
            defaultTag: {
                name: 'Название',
                img: '',
                description: '',
            },

            tag: {},
            tagImgModel: {},
            imgFile: null,
        }
    },
    methods: {
        create(){
            this.$store.dispatch('tag/setCurrentEditItem', false);
            //this.$store.dispatch('tag/createItem', this.tag);

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let data = new FormData();
            data.append('name', this.tag.name);
            data.append('description', this.tag.description);
            data.append('img', this.imgFile);
            //data = this.tag;
            // todo: выставление FormData, автоматически делает 'content-type': 'multipart/form-data' ! сюрприз!
            axiosClient.post('tag', data) // config
                .then(function (res) {
                    //console.log(res.data)
                })
                .catch(function (err) {
                    console.log(err);
                });

        },
        resetForm(){
            this.tag = Object.assign({}, this.defaultTag);
        },

        onImageChoose(ev){
            const file = ev.target.files[0];
            this.imgFile = file;

            const reader = new FileReader();
            reader.onload = () => {
                this.tagImgModel.image     = reader.result;
                this.tagImgModel.image_url = reader.result;
            }

            reader.readAsDataURL(file);
        },
    },
    computed: {
        ...mapState({
        }),
        ...mapGetters({
            'createItemStatus': 'tag/getCreateItemStatus',
        }),
    },
    mounted() {
        this.resetForm();
    },
    watch:{
        createItemStatus(nv,ov){
            if (nv){
                this.resetForm();
                this.$store.dispatch('tag/setCurrentEditItem', false);
            }
        },
        tagImgModel: {
            handler(nv,ov){
                  this.tag.img  = this.imgFile;
            },
            deep: true,
        }
    },
}
</script>

<style scoped>

</style>
