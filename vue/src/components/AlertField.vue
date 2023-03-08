<template>
    <div v-if="error" class="bg-red-500 text-white p-2 rounded-md mt-3 relative">
        <div
            v-for="(name, key) in error"
            :key="key"
            class="mr-7"
        >
            <div class="text-xs font-semibold">{{ key + 1}}. {{ name }}</div>
        </div>

        <div
            @click="$emit('hideError')"
            class="close-btn absolute right-1 top-1 bg-red-600 rounded-full p-0.5 cursor-pointer"
        >
            <x-icon class="w-4 h-4"/>
        </div>
    </div>

</template>

<script>
import { XIcon } from '@heroicons/vue/solid'

export default {
    components: {
        XIcon,
    },
    props: {
        error: {
            required: true,
        }
    },
    emits: ['hideError'],
}
</script>

<style scoped>

</style>
