<template>
    <button
        type="button"
        class="flex items-center justify-center border text-sm
               focus:ring-2 focus:ring-offset-2 ">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="w-6 h-6 pr-0.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span><slot></slot></span>
    </button>
</template>

<script>
export default {
    name: 'mg-plus-icon-button',
}
</script>

<style scoped>

</style>
