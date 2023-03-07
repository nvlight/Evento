<template>
    <div>
        <menu-header title="Onepass Categories"></menu-header>

        <div class="dark:bg-gray-900 dark:text-white">
            <div class="evento-view max-w-7xl mx-auto py-6 sm:px-6 lg:px-8  ">

                <router-link
                    :to="{name: 'OnepassEntries'}"
                    class="underline"
                >onepass entries
                </router-link>

                <div class="py-2 md:py-5 rounded-md">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MenuHeader from "../MenuHeader.vue";

export default {
    components: {
        MenuHeader,
    }
}
</script>

<style scoped>

</style>
