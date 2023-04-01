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
                case 'copy':
                    notClass = `bg-purple-700`;
                    break;

                    // <button type="button" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Purple</button>

                case 'copy_code': // onepass - copy password
                    notClass = `bg-gray-500`;
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
