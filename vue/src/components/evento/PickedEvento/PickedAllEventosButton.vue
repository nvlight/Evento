<template>
    <div
        class="cursor-pointer"
        @click="toggleAllPickedEventos"
        >
        <div v-if="! pickedEventos.length">
            Выделить все
        </div>
        <div v-else>
            Снять все
        </div>
    </div>
</template>

<script>
import {mapMutations, mapState} from "vuex";

export default {

    methods:{
        ...mapMutations({
            'toggleAllPickedEventos': 'evento/toggleAllPickedEventos',
        }),
    },
    computed: {
        ...mapState({
            'isPickedAllEventos': state => state.evento.isPickedAllEventos,
            'pickedEventos': state => state.evento.pickedEventos,
        })
    },
}
</script>

<style scoped>

</style>
