<template>
    <div>
        <menu-header title="Категории"></menu-header>

        <div class="dark:bg-gray-900 dark:text-white">
            <div class="onepass-categories-index max-w-7xl mx-auto py-6 sm:px-6 lg:px-8  ">

                <router-link
                    :to="{name: 'OnepassEntries'}"
                    class="underline"
                >Все записи
                </router-link>

                <div class="mt-3 container flex w-full">
                    <div class="createOrEdit_categoryForm w-3/12">
                        <OnepassCreateEditCategoryForm/>
                    </div>
                    <div class="onepassCategories-list w-9/12">
                        <OnepassCategoriesList :items="categories" />
                    </div>
                </div>


            </div>

        </div>

    </div>
</template>

<script>
import MenuHeader from "../components/MenuHeader.vue";
import OnepassCreateEditCategoryForm from "../components/onepass/categories/OnepassCreateEditCategoryForm.vue";
import OnepassCategoriesList from "../components/onepass/categories/OnepassCategoriesList.vue";
import {mapActions, mapState} from "vuex";

export default {
    components: {
        MenuHeader, OnepassCreateEditCategoryForm, OnepassCategoriesList,
    },

    data(){
        return {
        }
    },

    methods:{
        ...mapActions({
            'loadOnePassCategoryItems': 'onepassCategory/loadItems',
        }),
    },

    computed: {
        ...mapState({
            'categories': state => state.onepassCategory.items.list,
        }),
    },

    mounted() {
        this.loadOnePassCategoryItems();
    },
}
</script>

<style scoped>

</style>
