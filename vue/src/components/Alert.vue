<template>
    <div v-if="Object.keys(errors).length"
         class="bg-red-500 text-white p-3 rounded-md mt-3 relative"
    >
        <div v-for="(error_array, error_key) in errors" :key="error_key"
             class="">
            <div class="font-semibold">{{ error_key }}:</div>

            <!--                    <div v-if="key>0" class="mt-2"></div>-->

            <div v-for="(err, j) in error_array">
                {{ j+1 }}. {{err}}
            </div>
        </div>

        <div @click="$emit('resetErrors')"
            class="close-btn absolute right-1 top-1 bg-red-600 rounded-full p-0.5 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>

</template>

<script>
export default {
    name: 'alert',
    props: {
        errors: {
            type: Object,
            required: true,
        }
    },
    emits: ['resetErrors'],
}
</script>

<style scoped>

</style>
