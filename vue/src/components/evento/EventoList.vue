<template>
    <div class="mt-3">
        <h1 class="text-xl font-semibold">Список событий</h1>
        <table class="mt-2 w-full border border-collapse rounded-md p-3">
            <thead>
            <tr>
                <th class="border">#</th>
                <th class="border">Дата</th>
                <th class="border">Описание</th>
                <th class="border">Подробнее</th>
                <th class="border">Действия</th>
            </tr>
            </thead>
            <tbody>
                <template v-for="evento in eventos"
                    :key="evento.id">
                    <evento-item @doAddFormReset="$emit('doAddFormReset')" :evento="evento"></evento-item>
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
