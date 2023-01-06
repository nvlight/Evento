`
<template>
    <!-- Pagination -->
    <div v-if="evento_links.length > 3" class="flex justify-center mt-5">
        <nav
            class="relative z-0 inline-flex justify-center rounded-md shadow-sm "
            aria-label="Pagination"
        >
<!--            :href="hrefLink(i, link.label)"-->
            <a v-for="(link, i) of evento_links"
                :key="i"
                :disabled="!Number(link.label)"
                @click="getForPage($event, link)"
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
        //@click="getForPage($event, link)
        getForPage(event, link) {
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
                        //console.log('success', response.data.data.current_page);
                        //window.history.pushState(
                        //    null,
                        //    document.title,
                        //    `${window.location.pathname}?page=${response.data.data.current_page}`
                        //)
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
        hrefLink(i, link_label) {
            return i === 0
                ? this.prev
                : i === this.evento_links.length - 1 ? this.next
                    : `${this.searchParams}&page=${link_label}`
        },
    },
    computed: {
        searchParams(){
            const params_object = Object.fromEntries(new URL(window.location).searchParams.entries());
            let find = false;
            this.needKeys.forEach(v => {
                if (params_object.hasOwnProperty(v)){
                    find = true;
                }
            });

            if (!find){
                return 'eventos?';
            }

            if (params_object.hasOwnProperty('page')){
                delete params_object['page'];
            }
            console.log('after delete page:');
            console.log(params_object);
            return '?' + (new URLSearchParams(params_object)).toString();
        },
        prev() {
            return this.current_page - 1 > 0
                ? `${this.searchParams}&page=${this.current_page - 1}`
                : `${this.searchParams}&page=1`;
        },
        next() {
            return this.current_page + 1 < this.last_page
                ? `${this.searchParams}&page=${this.current_page + 1}`
                : `${this.searchParams}&page=${this.last_page}`;
        },
    },
    mounted() {
        //console.log('current_page:', this.current_page);
        //console.log('last_page:', this.last_page);
        //console.log(this.$router.currentRoute);

        //console.log('-----paginator------');
        const params = (new URL(document.location)).search;
        const params_object = Object.fromEntries(new URL(window.location).searchParams.entries());

        //console.log(params);
        //console.log(params_object);
        //console.log((new URLSearchParams(params_object)).toString());
    }
}
</script>

<style scoped>

</style>
`
