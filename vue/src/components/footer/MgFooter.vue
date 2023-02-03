<template>
    <footer class="p-4 bg-white rounded-lg shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2022-{{ getCurrentDateObject.year }} <a href="/"
                                                                                    class="hover:underline">Eventos™</a>
    </span>
        <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
            <li>
                <a :href="$store.state.mainDevSiteUrl" class="mr-4 hover:underline md:mr-6 ">Martin Dev</a>
            </li>
        </ul>
    </footer>

</template>

<script>
import dateMixin from "../../mixins/DateMixin.vue";

export default {

    mixins: [ dateMixin, ],

    data(){
        return {

        }
    },

    mounted() {
    }
}
</script>

<style scoped>

</style>
