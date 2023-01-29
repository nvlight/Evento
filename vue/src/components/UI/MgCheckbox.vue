<template>
    <label class="flex items-center">
        <input
            :checked="modelValue"
            @change="updateInput"
            type="checkbox"
            class="rounded-sm focus:ring-2 focus:ring-indigo-600 focus:outline-none"
            :class="classes"
        />
        <span class="ml-1">
            <slot></slot>
        </span>
    </label>
</template>

<script>
export default {
    name: 'mg-checkbox',
    props: {
        modelValue: {
            type: [Boolean],
        },
        classes: {
            type: Array,
            default: [],
        }
    },

    methods:{

        updateInput(ev){
            this.$emit('update:modelValue', ev.target.checked);
        }
    },

    mounted() {
    }
}
</script>

<style scoped>

</style>
