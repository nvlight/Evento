<template>
    <!-- Pagination -->
    <div v-if="evento_links.length > 3" class="flex justify-center mt-5">
        <nav
            class="relative z-0 inline-flex justify-center rounded-md shadow-sm "
            aria-label="Pagination"
        >
            <a
                v-for="(link, i) of evento_links"
                :key="i" :disabled="!link.url" href="#" aria-current="page"
                v-html="i === 0 ? link.label.replace('Previous', 'Назад')
                    : i === evento_links.length-1 ? link.label.replace('Next', 'Вперед')
                    : link.label"
                @click="getForPage($event, link)"
                class="relative inline-flex items-center px-4 py-2 border test-sm font-medium whitespace-nowrap"
                :class="[
                    link.active
                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-100',
                        i === 0 ? 'rounded-l-md' : '',
                        i === evento_links.length-1 ? 'rounded-r-md' : '',
                ]"
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
    },
    methods:{
        getForPage(event, link) {
            event.preventDefault();
            if (!link.url || link.active){
                return;
            }
            this.$store.dispatch("evento/loadItems", {url: link.url})
        },
    },
}
</script>

<style scoped>

</style>
