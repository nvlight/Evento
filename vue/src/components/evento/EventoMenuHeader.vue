<template>
    <header class="dark:bg-gray-900 dark:text-white dark:shadow-amber-700">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold ">
                {{ title }}
            </h1>
        </div>
    </header>
</template>

<script>
export default {
    props:{
        title: {
            required: true,
            type: String,
        }
    },
}
</script>

<style scoped>
</style>
