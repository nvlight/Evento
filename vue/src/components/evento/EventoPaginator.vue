<template>
    <!-- Pagination -->
    <div v-if="evento_links.length > 3" class="flex justify-center overflow-auto">
        <nav
            class="relative z-0 inline-flex justify-center rounded-md shadow-sm "
            aria-label="Pagination"
        >
            <ul class="inline-flex items-center -space-x-px">
                <li v-for="(link, i) of evento_links" :key="i">
                    <a
                        :disabled="!Number(link.label)"
                        @click.prevent="getPageData($event, link)"
                        aria-current="page"
                        v-html="linkHtml(i, link.label)"
                        href="#"
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
    name: 'evento-paginator',
    props: {
        evento_links: {
            type: Object,
            required: true,
        },
        current_page: {
            type: [Number],
        },
        last_page: {
            type: [Number],
        },
    },
    data(){
        return {
            filterDefault: {
                date_start: 1,
                date_end: 2,
                sum_start: 0,
                sum_end: 107000,
                filter_text: '',
                tag_arr: [], //[122, 123],
                orderById: 'desc / asc',
                zeroFill: false,
                pickAllTags: false,
            },
        }
    },
    methods: {
        getPageData(event, link) {
            if (!link.url || link.active) {
                return;
            }

            let routeName = 'Eventos';
            let params = {page: link.label};
            params = {page: (new URL(link.url)).searchParams.get('page')};

            const prData = this.prepareFilterData();
            params = {...params, ...prData};

            this.$router
                //.push({ name: routeName, params: params })
                .push({ name: routeName, query: params });

            let queryString = new URLSearchParams(params).toString();
            if (queryString !== '') {
                queryString = `?${queryString}`;
            }

            this.$store.dispatch("evento/loadItems", queryString)
                .then(response => {
                    if (response.data?.success) {
                    }
                })
        },

        prepareFilterData() {
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
                : i === this.evento_links.length - 1 ? link_label.replace('Next', 'Вперед')
                : link_label
        },
        classes(i, link_active) {
            return [
                link_active ? 'px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' : '',
                i === 0 ? 'px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : '',
                i === this.evento_links.length - 1 ? 'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' : '',
                "px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white",
            ];
        },
    },
    computed: {
    },
    mounted() {
        const params = (new URL(document.location)).search;
        const params_object = Object.fromEntries(new URL(window.location).searchParams.entries());
    }
}
</script>

<style scoped>

</style>
