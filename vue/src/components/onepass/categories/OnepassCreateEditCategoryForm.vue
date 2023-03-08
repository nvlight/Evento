<template>
    <div>
        <h1 class="font-semibold text-2xl">Создание</h1>

        <form
            @submit.prevent
            class="mt-2 text-black dark:text-white border-indigo-500 border rounded-md"
        >
            <div class="shadow sm:overflow-hidden sm:rounded-md">

                <div class="space-y-6  px-4 py-5 sm:p-6">
                    <!-- name -->
                    <div>
                        <mg-input-labeled v-model="category.name">Имя</mg-input-labeled>

                        <div v-if="categoryErrors.hasOwnProperty('name')" class="mt-1">
<!--                            <div v-for="nameErr in categoryErrors.name" class="text-red-400 text-sm">-->
<!--                                {{ nameErr }}-->
<!--                            </div>-->

                            <alert-field @hideError="delete categoryErrors.name" :error="categoryErrors.name"/>
                        </div>


                    </div>

                    <!-- image -->
                    <div>
                        <label class="block text-sm font-medium leading-6">Картинка</label>
                        <div class="mt-2 flex items-center">

                            <div
                                v-if="upload_image_src"
                                class="flex"
                            >
                                <img
                                    :src="upload_image_src" alt=""
                                    class="w-auto h-auto rounded-md"
                                >

                                <div class="actions">
                                </div>

                                <div @click="resetCategoryImgModel">
                                    <trash-icon class="h-5 w-5 text-red-500 mr-1"/>
                                </div>

                            </div>
                            <div v-else>
                                <span class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </span>
                            </div>

                            <label
                                type="button"
                                class="ml-5 rounded-md border border-gray-300 bg-white py-1.5 px-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50 cursor-pointer"
                            >
                                <span>изменить</span>
                                <input @change="onImageChoose" name="file-upload" type="file" class="sr-only"/>
                            </label>
                        </div>

                        <div v-if="categoryErrors.hasOwnProperty('image')" class="mt-1">
                            <alert-field @hideError="delete categoryErrors.image" :error="categoryErrors.image"/>
                        </div>
                    </div>

                    <!-- note -->
                    <div>
                        <label for="about" class="block text-sm font-medium leading-6">Примечание</label>
                        <div class="mt-2">
                            <textarea
                                v-model="category.note"
                                rows="3"
                                class="mt-1 block w-full rounded-md border-0 dark:bg-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6"
                                placeholder="введите тут примечание для категории"
                            />
                        </div>
                    </div>
                </div>

                <!-- create / update button -->
                <div class="px-4 py-3 text-right sm:px-6">
                    <mg-button
                        v-if="isCreatedButtonVisible"
                        class="inline-flex justify-center rounded-md bg-green-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500"
                        @click="createItem"
                    >
                        создать
                    </mg-button>

                    <mg-button
                        v-else
                        @click="editItem"
                        class="inline-flex justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                    >
                        обновить
                    </mg-button>
                </div>

            </div>
        </form>
    </div>
</template>

<script>
import {mapMutations} from "vuex";
import { TrashIcon } from "@heroicons/vue/solid";
import { LockClosedIcon } from "@heroicons/vue/solid";
import AlertField from "../../AlertField.vue";

export default {
    components: {
        TrashIcon, LockClosedIcon, AlertField,
    },
    data() {
        return {
            tmpCategory: {
                name: '',
                note: null,
                image: null,
            },
            category: {},
            upload_image_src: null,

            isCreatedButtonVisible: true,

            categoryErrors: {},
        }
    },
    methods: {
        resetForm() {
            this.upload_image_src = null;
            this.category = Object.assign({}, this.tmpCategory);
            this.categoryErrors = {};
        },

        ...mapMutations({
            setCurrentEditId: 'onepassCategory/setCurrentEditItemId',
            setCreatedItemId: 'onepassCategory/setCreatedItemId',
            setCreatedItemStatus: 'onepassCategory/setCreateItemStatus',
        }),

        resetFormHandler() {
            this.isCreatedButtonVisible = !this.isCreatedButtonVisible;
            this.resetForm();
        },
        createItem() {
            this.$store.commit('onepassCategory/setCreateItemStatus', false);

            // тут вылезает ошибка, null он воспринимает как текст! вот уж formData !!
            let data = new FormData();
            for(let key in this.category){
                if ( this.category[key] !== null ){
                    data.append(key, this.category[key]);
                }
            }
            //data.append('name', this.category.name);
            //data.append('note', this.category.note);
            //data.append('image', this.category.image);

            const item = {item: this.category, formData: data}
            this.$store.dispatch('onepassCategory/createItem', item)
                .then(data => {
                    //console.log('onepassCategory/createItem - dispatch success');
                    //console.log(response);
                    if (data?.response?.status === 422) {
                        console.log(data.response.data.errors);
                        this.categoryErrors = data.response.data.errors;
                    }else{
                        this.resetForm();
                    }
                })
                .catch(error => {
                    console.log('onepassCategory/createItem - dispatch error');
                });
        },

        editItem() {
            this.$store.commit('onepassCategory/setCreateItemStatus', false);

            let data = new FormData();
            data.append('name', this.category.name);
            data.append('description', this.category.description);
            data.append('image', this.category.image);

            data.append('_method', 'PUT');

            const item = {item: this.category, formData: data}

            this.$store.dispatch('onepassCategory/editItem', item);
        },

        onImageChoose(ev) {
            this.category.image = ev.target.files[0];
            //console.log(this.category.image);

            const reader = new FileReader();
            reader.onload = () => {
                this.upload_image_src = reader.result;
            }

            // проверяем выбрана ли картинка и его тип должен быть картинкой
            // todo: если это не картинка - как нибудь показать это пользователю, чтобы он смог это сбросить!
            if (this.category.image && this.category.image.type.match(/^image\/(\w+)/)){
                reader.readAsDataURL(this.category.image);
            }
        },
        resetCategoryImgModel() {
            this.upload_image_src = null;
            this.category.image = null;
        },
    },

    computed: {
        categoryName(){
            return this.category?.name;
        }
    },

    watch:{
        categoryName(){
            this.categoryErrors.name = null;
        },
    },

    mounted() {
        this.resetForm();
    },
}
</script>

<style scoped>

</style>
