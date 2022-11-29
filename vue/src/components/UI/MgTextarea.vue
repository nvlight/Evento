<template>
    <label class="mt-3 block">
        <span><slot></slot></span>
        <textarea
            :value="modelValue"
            @input="updateInput"
            class="border border-red-400 w-full rounded h-48
                p-3
                focus:border-indigo-500
                focus:outline-none
        "></textarea>
    </label>
</template>

<script>
export default {
    name: 'mg-textarea',
    props: {
        modelValue: {
            type: [String, Number],
        }
    },

    methods:{
        updateInput(ev){
            this.$emit('update:modelValue', ev.target.value);
        }
    }
}
</script>

<style scoped>

</style>
