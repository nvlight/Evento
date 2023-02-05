<template>
    <div class="bg-red-500 text-white p-3 rounded-md mt-3 relative">
        <div
            v-for="(name, key) in error"
            :key="key"
            class="mr-7"
        >
            <div class="font-semibold">{{ key + 1}}. {{ name }}</div>
        </div>

        <div
            @click="$emit('hideError')"
            class="close-btn absolute right-1 top-1 bg-red-600 rounded-full p-0.5 cursor-pointer"
        >
            <close-icon />
        </div>
    </div>

</template>

<script>
import CloseIcon from "./icons/CloseIcon.vue";

export default {
    components: {
        CloseIcon,
    },
    props: {
        error: {
            type: Array,
            required: true,
        }
    },
    emits: ['hideError'],
}
</script>

<style scoped>

</style>
