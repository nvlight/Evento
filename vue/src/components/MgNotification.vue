<template>
    <div v-if="notification.show" @click="notification.show = false"
        class="fixed right-4 rounded-md cursor-pointer bottom-4 py-2 px-4 text-white animate-fade-in-down"
        :class="[notification_class]"
        >
        {{ notification.message }}
    </div>
</template>

<script>

import {mapState} from "vuex";

export default {

    computed: {
        ...mapState({
            'notification': state => state.notification,
        }),

        notification_class(){
            let notClass = "";

            switch (this.notification.type) {
                case 'created':
                    notClass = `bg-emerald-500`;
                    break;
                case 'updated':
                    notClass = `bg-blue-500`;
                    break;
                case 'deleted':
                    notClass = `bg-red-500`;
                    break;
                case 'error':
                    notClass = `bg-red-500`;
                    break;
            }

            return notClass;
        }
    },
}

</script>

<style scoped>
</style>
