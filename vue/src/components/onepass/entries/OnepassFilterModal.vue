<template>
    <div class="max-w-md">
        <h1 class="text-2xl font-semibold">Фильтр записей</h1>

        <form @submit.prevent="filterhandler" class="mt-2">
            <div class="mt-5">
                Выберите категории
            </div>

            <mg-input-labeled v-focus class="block mt-2" :classes="'w-full'" placeholder="Url" v-model="filter.url">Url</mg-input-labeled>
            <mg-input-labeled class="block mt-2" :classes="'w-full'" placeholder="Емейл" v-model="filter.email">Емейл</mg-input-labeled>
            <mg-input-labeled class="block mt-2" :classes="'w-full'" placeholder="Телефон" v-model="filter.phone">Телефон</mg-input-labeled>
            <mg-input-labeled class="block mt-2" :classes="'w-full'" placeholder="Логин" v-model="filter.login">Логин</mg-input-labeled>
            <mg-input-labeled class="block mt-2" :classes="'w-full'" placeholder="Имя" v-model="filter.name">Имя</mg-input-labeled>
            <mg-textarea v-model="filter.note" placeholder="Примечание"/>

            <div class="flex justify-between mt-2">
                <button @click="resetFilter" type="button" class="text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Сброс</button>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Фильтровать</button>
            </div>
        </form>
    </div>
</template>

<script>
import {mapActions, mapMutations, mapState} from "vuex";

export default {

    data(){
        return {
            filter: {

            },
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
        ...mapActions({
            doFilter: 'onepassEntry/filterItems',
        }),
        ...mapMutations({
            setFilterModalVisible: 'onepassEntry/setFilterModalVisible',
        }),

        resetFilter(){
            this.filter = Object.assign({}, this.filterDefault)
        },

        filterhandler(){
            const jsonFilterData = JSON.parse(JSON.stringify(this.filter));

            const withoutEmpty = {};

            for (let key in jsonFilterData){
                switch (typeof(jsonFilterData[key])) {
                     case 'string':
                        if (jsonFilterData[key] !== ''){
                            withoutEmpty[key] = jsonFilterData[key];
                        } break;
                     case 'object':
                         if (Array.isArray(jsonFilterData[key])){
                             if (jsonFilterData[key].length !== 0){
                                 withoutEmpty[key] = jsonFilterData[key];
                             }
                         }
                }
            }

            this.$store.dispatch('onepassEntry/setFilterObject', withoutEmpty);
            this.doFilter(this.filter);

            this.setFilterModalVisible(false);
        }
    },

    computed: {
        ...mapState({
            filterModalVisible: state => state.onepassEntry.filterModalVisible,
        })
    },

    mounted() {
        this.resetFilter();

    }
}
</script>

<style scoped>

</style>
