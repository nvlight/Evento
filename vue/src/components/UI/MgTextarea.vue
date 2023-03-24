<template>
    <label class="mt-3 block dark:text-white">
        <span><slot></slot></span>
        <textarea
            :value="modelValue"
            @input="updateInput"
            class="border w-full rounded h-20 dark:bg-gray-900
                p-3
                focus:border-indigo-500
                focus:outline-none"
            :class="classes"
            :placeholder="placeholder"
        ></textarea>
    </label>
</template>

<script>
export default {
    name: 'mg-textarea',
    props: {
        modelValue: {
            type: [String, Number],
        },
        classes: {
            type: Array,
            default: [],
        },
        placeholder: {
            type: [String, Number],
        }
    },

    methods:{

        updateInput(ev){
            this.$emit('update:modelValue', ev.target.value);
        }
    },

    mounted() {
    }
}
</script>

<style scoped>

</style>
