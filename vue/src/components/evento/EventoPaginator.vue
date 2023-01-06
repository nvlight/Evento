`
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
                aria-current="page"
                v-html="linkHtml(i, link.label)"
                :href="hrefLink(i, link.label)"
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
    methods: {
        //@click="getForPage($event, link)
        getForPage(event, link) {
            event.preventDefault();
            if (!link.url || link.active) {
                return;
            }

            return;
            const windowData = Object.fromEntries(new URL(window.location).searchParams.entries());
            console.log(windowData);

            this.$store.dispatch("evento/loadItems", {url: link.url})
                .then(response => {
                    if (response.data.success) {
                        console.log('success', response.data.data.current_page);
                        window.history.pushState(
                            null,
                            document.title,
                            `${window.location.pathname}?page=${response.data.data.current_page}`
                        )

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
                    : '?page=' + link_label
        },
    },
    computed: {
        prev() {
            return this.current_page - 1 > 0
                ? `?page=${this.current_page - 1}`
                : `?page=1`;
        },
        next() {
            return this.current_page + 1 < this.last_page
                ? `?page=${this.current_page + 1}`
                : `?page=${this.last_page}`;
        },
    },
    mounted() {
        //console.log('current_page:', this.current_page);
        //console.log('last_page:', this.last_page);
    }
}
</script>

<style scoped>

</style>
`
