<template>
    <footer
        class="p-4 bg-gray-900 shadow
            text-center
            md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 text-white
        "
    >
        <span class="text text-gray-500 sm:text-center text-gray-400">© 2022-{{ getCurrentDateObject.year }} <a href="/"
                                                                                        class="hover:underline">Eventos™</a>
        </span>
        <ul class="mt-3 text-gray-500 text-gray-400 mt-0 text-center">
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
