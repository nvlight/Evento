<template>
    <div class="px-2">
        <h1 class="font-semibold text-2xl">{{ title }}</h1>

        <div
            v-for="item in items"
            :key="item.id"
        >
            <onepass-categories-item :item="item" />
        </div>
    </div>
</template>

<script>
import OnepassCategoriesItem from "./OnepassCategoriesItem.vue";

export default {
    props: {
        items: {
            type: Object,
            required: true,
        },
        title: {
            type: String,
            default: 'Список категорий',
        }
    },
    components: {
        OnepassCategoriesItem,
    },
}
</script>

<style scoped>

</style>
