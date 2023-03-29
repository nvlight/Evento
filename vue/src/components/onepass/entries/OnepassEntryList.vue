<template>
    <div>
        <div class="mt-2 p-2 text-black dark:text-white border-indigo-500 border rounded-md ">
            <div>
                <mg-loading v-if="entries.loading" class="m-auto">Загрузка...</mg-loading>
                <div v-else class="overflow-x-auto">
                    <table
                        v-if="entries.list.length"
                        class="mt-2 w-full border dark:border-none border-collapse rounded-md p-3 "
                    >
                        <caption class="text-left mb-1">Список записей</caption>
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="py-3 px-6">#</th>
                                <th class="py-3 px-6">Категория</th>
                                <th class="py-3 px-6">Урл</th>
                                <th class="py-3 px-6">Емейл</th>
                                <th class="py-3 px-6">Пароль</th>
                                <th class="py-3 px-6">Примечание</th>
                                <th class="py-3 px-6">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template
                                v-for="item in entries.list"
                                :key="item.id">
                                <onepass-entry-item
                                    :index="item.id"
                                    :item="item"
                                />
                            </template>
                        </tbody>
                    </table>
                    <div v-else>
                        <div class="text-center">Еще нет добавленных событий</div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from "vuex";
import OnepassEntryItem from "./OnepassEntryItem.vue";

export default {
    components: {
        OnepassEntryItem
    },

    data(){
        return {

        }
    },

    methods:{

    },

    computed: {
        ...mapState({
            entries: state => state.onepassEntry.items,
        }),
    },

    mounted() {
        this.$store.dispatch("onepassEntry/loadItems");
    },

    watch:{
    },
}
</script>

<style scoped>

</style>
