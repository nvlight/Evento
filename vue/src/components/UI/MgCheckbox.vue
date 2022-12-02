<template>
<!--    <div>-->
<!--        {{options}}-->
<!--    </div>-->
<!--    <div>{{modelValue}}</div>-->


    <select v-model="modelValue"
        @change="changeOption"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg
        bg-gray-50 block p-2
        focus:border-indigo-500 focus:outline-none">
        <option value="0">Выберите из списка</option>
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
    name: "mg-checkbox",
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
            //console.log(event.target)
            this.$emit('update:modelValue', this.modelValue);
        },
    }
}
</script>

<style scoped>
</style>
