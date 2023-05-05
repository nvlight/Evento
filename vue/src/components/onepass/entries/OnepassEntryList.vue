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

            filterDefault:{
                category_ids: [],
                url: "",
                email: "",
                login: "",
                phone: "",
                name: "",
                note: "",
            },
        }
    },

    methods:{

        prepareFilterData()
        {
            // посмотреть есть ли в queryString нужные нам, если есть отправить их вместе с запросом!
            //console.log(this.$route.query);

            let filterValues = {}
            for (let fkey in this.filterDefault){
                for (let rkey in this.$route.query){
                    if (fkey === rkey){
                        filterValues[rkey] = this.$route.query[rkey];
                    }
                }
            }

            let queryString = new URLSearchParams(filterValues)

            // console.log('page:', this.$route.query.page);
            // add current page if exists
            if (this.$route.query.page){
                queryString.append('page', this.$route.query.page);
            }

            if (queryString !== '') {
                queryString = `?${queryString}`;
            }
            //console.log(queryString);

            return queryString;
        },
    },

    computed: {
        ...mapState({
            entries: state => state.onepassEntry.items,
        }),
    },

    mounted() {
        const prData = this.prepareFilterData();

        this.$store.dispatch("onepassEntry/loadItems", prData);
    },

    watch:{
    },
}
</script>

<style scoped>

</style>
