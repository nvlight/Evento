<template>
    <mg-checkbox
        v-model="picked"
        class="py-3 cursor-pointer"
    >
        {{ eventoId }}
    </mg-checkbox>
</template>

<script>

import {mapState} from "vuex";

export default {
    props:{
        itemPicked: {
            required: true,
        },
        eventoId: {
            required: true,
            type: Number,
        }
    },
    emits: ['itemPicked', ],
    data(){
        return {
            picked: false,
        }
    },
    methods: {
    },
    computed:{
        ...mapState({
            'pickedEventosCleared': state => state.evento.pickedEventosCleared,
        }),
    },
    mounted() {
        this.picked = this.itemPicked;
    },
    watch: {
        picked(){
            this.$emit('itemPicked', this.picked);
        },
        pickedEventosCleared(){
            this.picked = false;
        },
    }
}
</script>

<style scoped>

</style>
