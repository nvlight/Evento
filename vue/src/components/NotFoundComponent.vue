<template>
    <div class="container mx-auto">
        <h1 class="mt-3 font-semibold text-3xl">Страница не найдена</h1>
        <h1 class="mt-3 font-semibold text-xl">Возможно страница была перемещена или удалена</h1>
        <router-link
            class="hover:underline"
            :to="{name: 'Auth'}">Перейти на главную страницу
        </router-link>
    </div>
</template>

<script>
export default {
    name: "NotFoundComponent.vue"
}
</script>

<style scoped>

</style>
