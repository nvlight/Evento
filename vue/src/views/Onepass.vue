<template>
    <div>
        <menu-header title="Записи"></menu-header>

        <div class="dark:bg-gray-900 dark:text-white">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8  ">

                <router-link
                    :to="{name: 'OnepassCategories'}"
                    class="underline"
                >Категории
                </router-link>

                <div class="mt-5">
                    <span @click="createFormVisible=true"
                          class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    >
                        Создать запись
                    </span>
                </div>

                <div class="py-2 md:py-5 rounded-md">
                    <!-- entry create form -->
                    <onepass-entry-create-form
                        v-show="createFormVisible"
                        @closeCreateForm="createFormVisible=false"
                    />

                    <!-- entry lists -->
                    <onepass-entry-list class="" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import MenuHeader from "../components/MenuHeader.vue";
import OnepassEntryCreateForm from "../components/onepass/entries/OnepassEntryCreateForm.vue";
import OnepassEntryList from "../components/onepass/entries/OnepassEntryList.vue";

export default {
    components: {
        MenuHeader, OnepassEntryCreateForm, OnepassEntryList,
    },

    data(){
        return {
            createFormVisible: false,
        }
    },

}
</script>

<style scoped>

</style>
