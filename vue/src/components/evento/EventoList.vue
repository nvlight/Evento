<template>
    <div class="mt-3 overflow-x-auto relative">
        <h1 class="text-xl font-semibold">Список событий</h1>
        <table class="mt-2 w-full border border-collapse rounded-md p-3 ">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="py-3 px-6">#</th>
                    <th class="py-3 px-6">Дата</th>
                    <th class="py-3 px-6">Описание</th>
                    <th class="py-3 px-6">Подробнее</th>
                    <th class="py-3 px-6">Действия</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(evento,index) in eventos"
                    :key="evento.id">
                    <evento-item @doAddFormReset="$emit('doAddFormReset')"
                                 :evento="evento" :index="index"></evento-item>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
import EventoItem from "./EventoItem.vue";
export default {
    name: 'evento-list',
    components: {
        EventoItem,
    },
    emits: ['doAddFormReset'],
    props: {
        eventos:{
            type: Object,
            required: true,
        },
    },
}
</script>

<style scoped>

</style>
