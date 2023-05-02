<template>
    <!-- Pagination -->
    <div v-if="links.length > 3" class="flex justify-center overflow-auto">
        <nav
            class="relative z-0 inline-flex justify-center rounded-md shadow-sm "
            aria-label="Pagination"
        >
            <ul class="inline-flex items-center -space-x-px">
                <li v-for="(link, i) of links" :key="i">
                    <a
                        :disabled="!Number(link.label)"
                        @click.prevent="getPageData($event, link)"
                        aria-current="page"
                        v-html="linkHtml(i, link.label)"
                        :href="linkHref(i, link)"
                        class="relative inline-flex items-center px-4 py-2 border test-sm font-medium whitespace-nowrap"
                        :class="classes(i, link.active)"
                    >
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!--/ Pagination -->
</template>

<script>


export default {
    name: 'mg-paginator',
    props: {
        links: {
            type: Object,
            required: true,
        },
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
    methods: {
        getPageData(event, link) {
            if (!link.url || link.active) {
                return;
            }

            let routeName = 'OnepassEntries';
            let params = {page: link.label};
            params = {page: (new URL(link.url)).searchParams.get('page')};

            const prData = this.prepareFilterData();
            params = {...params, ...prData};

            this.$router
               //.push({ name: routeName, params: params })
               .push({ name: routeName, query: params })
               //.then( () => { this.$router.go(0) } )
            ;
            let queryString = new URLSearchParams(params).toString();
            //console.log(queryString);
            if (queryString !== '') {
                queryString = `?${queryString}`;
            }

            this.$store.dispatch("onepassEntry/loadItems", queryString)
                .then(response => {
                    if (response.data?.success) {

                    }
                })
        },

        prepareFilterData() {
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

            return filterValues;
        },

        linkHtml(i, link_label) {
            return i === 0 ? link_label.replace('Previous', 'Назад')
                : i === this.links.length - 1 ? link_label.replace('Next', 'Вперед')
                    : link_label
        },
        classes(i, link_active) {
            return [
                link_active ? 'px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' : '',
                i === 0 ? 'px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : '',
                i === this.links.length - 1 ? 'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : '',
                "px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white",
            ];
        },
        linkHref(i, link){
            if (!link.url || link.active) {
                return '#';
            }

            let page = {page: link.label};
            page = (new URL(link.url)).searchParams.get('page');
            return page;
        }
    },

    mounted() {
    }
}
</script>

<style scoped>
</style>
