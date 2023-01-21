<template>
    <!-- Pagination -->
    <div v-if="evento_links.length > 3" class="flex justify-center mt-5">
        <nav
            class="relative z-0 inline-flex justify-center rounded-md shadow-sm "
            aria-label="Pagination"
        >
            <a v-for="(link, i) of evento_links"
                :key="i"
                :disabled="!Number(link.label)"
                @click="getPageData($event, link)"
                aria-current="page"
                v-html="linkHtml(i, link.label)"
                href="#"
                class="relative inline-flex items-center px-4 py-2 border test-sm font-medium whitespace-nowrap"
                :class="classes(i, link.active)"
            >
            </a>
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
        }
    },
    methods: {
        //@click="getPageData($event, link)
        getPageData(event, link) {
            event.preventDefault();
            if (!link.url || link.active) {
                return;
            }

            //return;
            //const windowData = Object.fromEntries(new URL(window.location).searchParams.entries());
            //console.log(windowData);
            let newLink = link.url;
            let key = 'evento_filter';
            if (sessionStorage.hasOwnProperty(key)){
                let tmp = JSON.parse(sessionStorage.getItem(key));
                //console.log(tmp);

                let keyValues;
                //keyValues = new URLSearchParams(tmp).toString();
                for(let i in tmp){
                    if (Array.isArray(tmp[i])){

                        tmp[i].forEach(v => {
                            keyValues += `${i}[]=${v}&`;
                        });
                    }else{
                        keyValues += `${i}=${tmp[i]}&`;
                    }
                }

                newLink += '&' + keyValues;
                //console.log(newLink);
            }

            //console.log(link.url);
            this.$store.dispatch("evento/loadItems", {url: newLink})
                .then(response => {
                    if (response.data.success) {
                        this.$store.dispatch('evento/saveCurrentPageToSessionStorage', response.data.data.current_page);
                    }
                })
        },
        linkHtml(i, link_label) {
            return i === 0 ? link_label.replace('Previous', 'Назад')
                : i === this.evento_links.length - 1 ? link_label.replace('Next', 'Вперед')
                    : link_label
        },
        classes(i, link_active) {
            return [
                link_active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-100',
                i === 0 ? 'rounded-l-md' : '',
                i === this.evento_links.length - 1 ? 'rounded-r-md' : '',
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
`
