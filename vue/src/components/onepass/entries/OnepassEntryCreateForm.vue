<template>
    <div class="">
        <h1>OnepassCreateCategoryForm</h1>

        <form
            @submit.prevent="add"
            class="text-black dark:text-white border-indigo-500 border rounded-md"
            method="POST"
        >
            <div class="shadow sm:overflow-hidden sm:rounded-md">

                <div class="space-y-6  px-4 py-5 sm:p-6">

<!--                    <pre>entry.category: {{ this.entry.category }}</pre>-->

<!--                    <pre>categories: {{ categories.list[0] }}-->
<!--                    </pre>-->

                    <ComboboxBasic
                        labelName="Категория"
                        @categoryChanged="categoryChanged"
                        :people="categories.list"
                        v-if="!categories.loading"
                    />

                    <div>
                        <mg-input-labeled v-model="entry.name">Имя</mg-input-labeled>
                    </div>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-3 sm:col-span-2">
                            <label for="company-website"
                                   class="block text-sm font-medium leading-6">Website</label>
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-500 sm:text-sm">http://</span>
                                <input
                                    type="text" name="company-website" id="company-website"
                                    class="block w-full flex-1 rounded-none rounded-r-md border-0 py-1.5 dark:bg-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="www.example.com"
                                />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="about" class="block text-sm font-medium leading-6 ">About</label>
                        <div class="mt-2">
                                    <textarea
                                        id="about" name="about" rows="3"
                                        class="mt-1 block w-full rounded-md border-0 dark:bg-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:py-1.5 sm:text-sm sm:leading-6"
                                        placeholder="you@example.com"
                                    />
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Brief description for your profile. URLs are hyperlinked.</p>
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
                <div class="px-4 py-3 text-right sm:px-6">
                    <button type="submit"
                            class="inline-flex justify-center rounded-md bg-indigo-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import ComboboxBasic from "../../../components/UI_v2.0/ComboboxBasic.vue"
import {mapState} from "vuex";

export default {
    components: {
        ComboboxBasic,
    },

    data(){
        return {
            entryDefault:{
                category: 0,
                url: "",
                password: "",

                email: "", // nullable
                login: "", // nullable
                phone: "", // nullable
                name: "",  // nullable

                note: "",  // nullable
            },
            entry: {
            },

        }
    },

    methods:{
        categoryChanged(v){
            console.log('categoryChanged:', v);
            this.entry.category = v;
        }
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
