<template>
    <tr :class="[index % 2 != 0
                ? 'bg-gray-50 border-b dark:bg-gray-800'
                : 'bg-white dark:bg-gray-900'
                , ' border-b dark:border-gray-700'
            ]"
        >
        <td class="border text-center p-2">{{ evento.id }}</td>
        <td class="border text-center p-2 whitespace-nowrap">{{ evento.date }}</td>

        <td class="tags_main_info border p-2">
            <div class="border w-fit p-2.5 border-gray-300 rounded-md border-red-100 border-none ">
                <div class="flex items-center cursor-pointer items-stretch">
                    <div class="py-1.5 px-2 bg-gray-200 text-black flex items-center whitespace-nowrap"
                         :style="[
                            evento.tag1_bg_color ? `background-color: ${evento.tag1_bg_color}` : 'background-color: #5CB85C',
                            evento.tag1_text_color ? `color: ${evento.tag1_text_color}` : 'color: #fff',
                         ]"
                    >
                        {{ evento.tag_id_first_name }}
                    </div>

                    <div v-if="!evento.tag_id_second && evento.value"
                         class="px-1.5 py-0.5 font-semibold bg-gray-600 text-white flex items-center"
                    >
                        <div class="text-sm">{{ evento.value }}</div>
                    </div>

                    <div v-if="evento.tag_id_second"
                        class="py-1.5 px-2 bg-green-600 text-white flex items-center"
                         :style="[
                            evento.tag2_bg_color ? `background-color: ${evento.tag2_bg_color}` : 'background-color: #5CB85C',
                            evento.tag2_text_color ? `color: ${evento.tag2_text_color}` : 'color: #fff',
                         ]"
                    >
                        {{ evento.tag_id_second_name }}
                        <div v-if="evento.value" class="px-1.5 py-0.5 ml-2 rounded-md font-semibold bg-gray-600 text-white">
                            <span class="text-sm">{{ evento.value }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </td>

        <td class="description border text-center p-2">
            <span v-if="evento.description">
                {{ evento.description }}
            </span>
            <span v-else>...</span>
        </td>

        <td class="buttons_block border text-center p-2">
            <div class="w-full flex justify-center items-center">
                <mg-pencil-icon-button
                    @click="editEventoHanlder(evento)"
                    class="ml-1 border-none text-purple-800  self-end
                    focus:ring-purple-500 rounded-sm h-4 w-4"
                ></mg-pencil-icon-button>
                <mg-trash-icon-button
                    @click="deleteEvento(evento.id)"
                    class="ml-1 border-none text-red-500 self-end
                            focus:ring-red-500 rounded-sm h-4 w-4
                            "
                    :svgClass="'h-4 w-4'"
                ></mg-trash-icon-button>
            </div>
        </td>
    </tr>
</template>

<script>
import {mapGetters, mapState} from "vuex";

export default {
    name: 'evento-item',
    components: {},

    props: {
        evento: {
            type: Object,
            required: true,
        },
        index:{
            type: Number,
            required: true,
        }
    },
    emits: [],

    methods:{
        deleteEvento(id){
            if (!confirm('Действительно удалить?')) return;
            let isFilterActive = this.evento_filter ? 1 : 0;
            const delParams = {
                'current_page': this.current_page,
                'filter_active': isFilterActive,
                ...JSON.parse(this.evento_filter),
            }
            this.$store.dispatch('evento/delItemQuery', {id, params: delParams})
                .then((res) => {
                    if (res?.status === 200 && res?.data?.success === 1){
                        this.$store.commit('notify', {
                            message: 'Evento удален!',
                            type: 'error',
                            timeout: 1500,
                        })
                    }
                })
                .catch((err) => {
                    console.log('delete evento - catch: ', err);
                });
        },

        editEventoHanlder(evento){
            //console.log('editEventoHanlder:', evento.id);
            this.$store.dispatch('evento/setCurrentEditItemId', evento.id);
            //console.log(this.currentEditedItem);
            this.$store.commit('evento/editButtonClicked');
            this.$store.commit('evento/setCreateEditFormVisible', true);
        },

    },
    computed:{
        ...mapState({
            'current_page': state => state.evento.current_page,
            'evento_filter': state => state.evento.evento_filter_active,
        }),
        ...mapGetters({
            'currentEditedItem': 'evento/getCurrentEditedItem',
        }),
    }
}
</script>

<style scoped>

</style>
