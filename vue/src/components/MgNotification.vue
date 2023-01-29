<template>
    <div v-if="notification.show" @click="notification.show = false"
        class="fixed right-4 rounded-md cursor-pointer bottom-4 py-2 px-4 text-white animate-fade-in-down"
        :class="[notification.type === 'success' ? 'bg-emerald-500' : 'bg-red-500']"
        >
        {{ notification.message }}
    </div>
</template>

<script setup>
import {computed} from "vue";
import {useStore} from "vuex";

const store = useStore();
const notification = computed( () => store.state.notification);

</script>

<style scoped>
</style>
