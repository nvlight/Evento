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

                <div class="py-2 md:py-5 rounded-md">
                    <div class="sm:flex-col md:flex md:flex-row">
                        <!-- entry create form -->
                        <onepass-entry-create-form class="md:w-4/12" />

                        <!-- entry lists -->
                        <onepass-entry-list class="md:w-8/12" />
                    </div>
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
    }
}
</script>

<style scoped>

</style>
