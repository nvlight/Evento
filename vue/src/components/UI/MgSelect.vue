<template>
    <select v-model="modelValue"
        @change="changeOption"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg
        bg-gray-50 block p-2
        focus:border-indigo-500 focus:outline-none">
        <slot></slot>
        <option
            v-for="option in options"
            :key="option.id"
            :value="option.id"
        >
            {{option[optionFieldName]}}
        </option>
    </select>
</template>

<script>
export default {
    name: "mg-select",
    props:{
        modelValue: {
            type: [ Array, Number, String ]
        },
        options: {
            type: [ Array ],
        },
        optionFieldName: {
            type: String,
            default: 'name',
        },
    },
    methods: {
        changeOption(event){
            console.log(event.target)
            console.log(this.modelValue);
            this.$emit('update:modelValue', this.modelValue);
        },
    },
    mounted() {
        console.log(this.options);
        console.log(this.optionFieldName);
    },
}
</script>

<style scoped>
</style>
