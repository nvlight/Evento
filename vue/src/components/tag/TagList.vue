<template>
    <div>
        <h1 class="font-semibold text-2xl">{{ title }}</h1>

        <div v-for="tag in tags"
             :key="tag.id"
        >
            <tag-item
                :tag="tag"
                :show_title="false"
                @editBtnClicked="$emit('editBtnClicked')"
            >
            </tag-item>
        </div>
    </div>
</template>

<script>
import TagItem from "./TagItem.vue";

export default {
    name: 'tag-list',
    props: {
        tags: {
            type: Object,
            required: true,
        },
        title: {
            type: String,
            default: 'Элементы',
        }
    },
    components: {
        TagItem
    },
}
</script>

<style scoped>

</style>
