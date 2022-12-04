<template>
    <button
        class="relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium
        rounded-md text-white focus:outline-none
         focus:ring-2 focus:ring-offset-2
        ">
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "mg-button",
    props: {
        classes: {
            type: String,
            default: '',
        }
    }
}
</script>

<style scoped>

</style>
