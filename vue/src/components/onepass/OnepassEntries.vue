<template>
    <div>
        <menu-header title="Onepass entries"></menu-header>

        <div class="dark:bg-gray-900 dark:text-white">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8  ">

                <router-link
                    :to="{name: 'OnepassCategories'}"
                    class="underline"
                    >onepass categories
                </router-link>

                <div class="py-2 md:py-5 rounded-md">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EventoMenuHeader from "../evento/EventoMenuHeader.vue";
import MenuHeader from "../MenuHeader.vue";
export default {
    components: {
        MenuHeader,
    }
}
</script>

<style scoped>

</style>
