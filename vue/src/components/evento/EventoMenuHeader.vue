<template>
    <header class="dark:bg-gray-900 dark:text-white dark:shadow-amber-700">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between">
                <h1 class="text-3xl font-bold ">
                    {{ title }}
                </h1>

                <div class="flex flex-wrap items-center">
                    <div class="flex items-center flex-wrap ml-5 p-1 text-xl ">
                        <CheckCircleIcon class="h-6 w-6 text-white-500 mr-1"/>
                        <div v-if="pickedEventos.length" class="mr-3 flex items-center">
                            {{pickedEventos.length}}
                        </div>
                        <div>Выделить все</div>
                    </div>

                    <template v-if="pickedEventos.length">
                        <!-- <li class="ml-1">pickedEventos: {{pickedEventos}}</li>-->
                        <picked-evento-copy-button class="ml-1 p-1 ">копировать</picked-evento-copy-button>
                        <picked-evento-delete-button class="ml-1 p-1 ">удалить</picked-evento-delete-button>
                    </template>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import {mapState} from "vuex";
import {CheckCircleIcon} from "@heroicons/vue/outline";
import PickedEventoCopyButton from "./PickedEvento/PickedEventoCopyButton.vue";
import PickedEventoDeleteButton from "./PickedEvento/PickedEventoDeleteButton.vue";

export default {
    components: {
        CheckCircleIcon, PickedEventoCopyButton, PickedEventoDeleteButton,
    },
    props:{
        title: {
            required: true,
            type: String,
        }
    },
    computed:{
        ...mapState({
            pickedEventos: state => state.evento.pickedEventos,
        }),
    },
}
</script>

<style scoped>
</style>
