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
        }),
        ...mapMutations({
            setFilterModalVisible: 'onepassEntry/setFilterModalVisible',
        }),

        resetFilter(){
            this.filter = Object.assign({}, this.filterDefault)
        },

        withoutEmpty(value){
            let withoutEmpty = {};

            for (let key in value){
                switch (typeof(value[key])) {
                    case 'string':
                        if (value[key] !== ''){
                            withoutEmpty[key] = value[key];
                        } break;
                    case 'object':
                        if (Array.isArray(value[key])){
                            if (value[key].length !== 0){
                                withoutEmpty[key] = value[key];
                            }
                        }
                }
            }

            return withoutEmpty;
        },
        filterhandler(){
            const jsonFilterData = JSON.parse(JSON.stringify(this.filter));

            let withoutEmpty = this.withoutEmpty(jsonFilterData);

            let queryString = new URLSearchParams(withoutEmpty).toString();
            //console.log('queryString:', queryString);
            if (queryString !== '') {
                queryString = `?${queryString}`;
            }

            this.$store.dispatch('onepassEntry/loadItems', queryString)
                .then( response => {
                    //console.log('response:', response)
                    if (response.data.success) {
                        //console.log('response.data.success:', response.data.success);
                        this.$store.dispatch('onepassEntry/setFilterObject', withoutEmpty);

                        let routeName = 'OnepassEntries';
                        //let params = {page: link.label};
                        //params = {page: (new URL(link.url)).searchParams.get('page')};

                        //console.log('withoutEmpty:', withoutEmpty);
                        this.$router.push({ name: routeName, query: withoutEmpty });

                        this.setFilterModalVisible(false);
                    }
                })
                .catch(error => {
                    //console.log('onepassEntry/filterModal - dispatch error');
                });
        },

        filledFilterDataInRouteQuery(filterValue){
            let routeQuery = this.$route.query;
            if (Object.keys(routeQuery).length){

                let booleanKeys = [];
                for (let rq in routeQuery){
                    for (let fd in filterValue){
                        if (rq === fd){

                            if (booleanKeys.includes(rq)){
                                //this.filterData[rq] = routeQuery[rq] === 'false' ? false : true;
                                filterValue[rq] = routeQuery[rq] !== 'false';
                            }else if (typeof(routeQuery[rq]) === 'object' && Array.isArray(routeQuery[rq])){
                                filterValue[rq] = routeQuery[rq];
                                // предполагается, что значения массива должны быть целыми числами
                                filterValue[rq] = filterValue[rq].map(v => +v );
                            }else{
                                filterValue[rq] = routeQuery[rq];
                            }
                        }
                    }
                }
            }
        },
    },

    computed: {
        ...mapState({
            filterModalVisible: state => state.onepassEntry.filterModalVisible,
        })
    },

    mounted() {
        this.resetFilter();

        this.filledFilterDataInRouteQuery(this.filter);
    }
}
</script>

<style scoped>

</style>
