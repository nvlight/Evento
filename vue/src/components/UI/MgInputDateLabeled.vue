<template>
    <label class="block text-sm font-medium text-gray-700 dark:text-white">
        <span><slot></slot></span>
        <input type="date"
           @input="updateInput"
           :value="modelValue"
           class="
                dark:bg-gray-900
                p-2
                block w-full shadow-sm sm:text-sm border border-gray-300
                focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none
                rounded-md
            "
        >
    </label>
</template>

<script>
export default {
    name: 'mg-input-date-labeled',
    props: {
        modelValue: {
            type: [String, Number],
        },
        emits: ['update:modelValue'],
    },
    methods: {
        updateInput(event){
            this.$emit('update:modelValue', event.target.value)
        }
    }
}
</script>

<style scoped>

</style>
