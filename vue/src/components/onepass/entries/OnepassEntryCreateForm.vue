<template>
    <div class="">
        <h1>Onepass - create Entry</h1>

        <form
            @submit.prevent="saveEntry"
            class="mt-2 mr-2 text-black dark:text-white border-indigo-500 border rounded-md"
            method="POST"
        >
            <div class="shadow sm:overflow-hidden sm:rounded-md">
                <div class="space-y-6  px-4 py-5 sm:p-6">
                    <!--                    <pre>entry.category: {{ this.entry.category }}</pre>-->

                    <!--                    <pre>categories: {{ categories.list[0] }}-->
                    <!--                    </pre>-->

                    <div>
                        <ComboboxBasic
                            labelName="Категория"
                            @categoryChanged="categoryChanged"
                            :people="categories.list"
                            v-if="!categories.loading"
                            class="block"
                        />
                        <div v-if="formErrors.hasOwnProperty('category')" class="mt-1">
                            <alert-field @hideError="delete formErrors.category" :error="formErrors.category"/>
                        </div>
                    </div>

                    <div>
                        <mg-input-labeled class="block" :classes="'w-full'" placeholder="Url" v-model="entry.url"/>
                        <div v-if="formErrors.hasOwnProperty('url')" class="mt-1">
                            <alert-field @hideError="delete formErrors.url" :error="formErrors.url"/>
                        </div>
                    </div>

                    <div>
                        <mg-input-labeled class="block" :classes="'w-full'" placeholder="Емейл" v-model="entry.email"/>
                        <div v-if="formErrors.hasOwnProperty('email')" class="mt-1">
                            <alert-field @hideError="delete formErrors.email" :error="formErrors.email"/>
                        </div>
                    </div>

                    <div>
                        <mg-input-labeled class="block" :classes="'w-full'" placeholder="Телефон" v-model="entry.phone"/>
                        <div v-if="formErrors.hasOwnProperty('phone')" class="mt-1">
                            <alert-field @hideError="delete formErrors.phone" :error="formErrors.phone"/>
                        </div>
                    </div>

                    <div>
                        <mg-input-labeled class="block" :classes="'w-full'" placeholder="Логин" v-model="entry.login"/>
                        <div v-if="formErrors.hasOwnProperty('login')" class="mt-1">
                            <alert-field @hideError="delete formErrors.login" :error="formErrors.login"/>
                        </div>
                    </div>

                    <div>
                        <mg-input-labeled class="block" :classes="'w-full'" placeholder="Имя" v-model="entry.name"/>
                        <div v-if="formErrors.hasOwnProperty('name')" class="mt-1">
                            <alert-field @hideError="delete formErrors.name" :error="formErrors.name"/>
                        </div>
                    </div>

                    <hr>
                    <div>
                        <mg-password-input-labeled class="block" :classes="'w-full'" placeholder="Пароль" v-model="entry.password" />
                        <div v-if="formErrors.hasOwnProperty('password')" class="mt-1">
                            <alert-field @hideError="delete formErrors.password" :error="formErrors.password"/>
                        </div>
                    </div>

                    <div>
                        <mg-password-input-labeled class="block" :classes="'w-full'" placeholder="Пароль" v-model="entry.password_confirmation" />
                        <div v-if="formErrors.hasOwnProperty('password_confirmation')" class="mt-1">
                            <alert-field @hideError="delete formErrors.password_confirmation" :error="formErrors.password_confirmation"/>
                        </div>
                    </div>

                    <mg-textarea placeholder="Здесь можно указать все, что будет связано с добавляемой записью. ">Примечание</mg-textarea>

                    <div class="text-right py-3">
                        <button type="submit"
                                class="inline-flex justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                            Сохранить
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6">Photo</label>
                        <div class="mt-2 flex items-center">
                                  <span class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100">
                                    <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                      <path
                                          d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                  </span>
                            <button type="button"
                                    class="ml-5 rounded-md border border-gray-300 bg-white py-1.5 px-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50">
                                Change
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium leading-6 ">Cover photo</label>
                        <div
                            class="mt-2 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                     fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload"
                                           class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="file-upload" type="file"
                                               class="sr-only"/>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</template>

<script>
import ComboboxBasic from "../../../components/UI_v2.0/ComboboxBasic.vue"
import {mapState} from "vuex";
import AlertField from "../../AlertField.vue";

export default {
    components: {
        ComboboxBasic, AlertField,
    },

    data(){
        return {
            entryDefault:{
                category: 0,
                url: "",
                password: "",
                password_confirmation: "",

                email: "", // nullable
                login: "", // nullable
                phone: "", // nullable
                name: "",  // nullable

                note: "",  // nullable
            },
            entry: {
            },

            formErrors: {},
        }
    },

    methods:{
        resetForm() {
            this.category = Object.assign({}, this.entryDefault);
            this.formErrors = {};
        },

        categoryChanged(v){
            console.log('categoryChanged:', v);
            this.entry.category = v;
        },
        saveEntry(){
            console.log('saveEntry...');

            // тут вылезает ошибка, null он воспринимает как текст! вот уж formData !!
            let data = new FormData();
            for(let key in this.entry){
                if ( this.entry[key] !== null ){
                    data.append(key, this.entry[key]);
                }
            }

            const item = {item: this.entry, formData: data}
            this.$store.dispatch('onepassEntry/createItem', item)
                .then(data => {
                    if (data?.response?.status === 422) {
                        console.log(data.response.data.errors);
                        this.formErrors = data.response.data.errors;
                    }else{
                        this.resetForm();
                    }
                })
                .catch(error => {
                    console.log('onepassEntry/createItem - dispatch error');
                });
        },
    },

    computed: {
        ...mapState({
            categories: state => state.onepassCategory.items,
        }),
    },

    mounted() {
        this.entry = Object.assign({}, this.entryDefault);
        this.$store.dispatch("onepassCategory/loadItems");
    },

    watch:{
        categories:{
            handler(nv, ov){
                if (nv.loading === false){
                    this.entry.category = this.categories.list[0];
                }
            },
            deep: true,
        }
    },
}
</script>

<style scoped>

</style>
