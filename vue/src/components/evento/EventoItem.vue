<template>
    <tr :class="[index % 2 != 0
                ? 'bg-gray-50 border-b dark:bg-gray-800'
                : 'bg-white dark:bg-gray-900'
                , ' border-b dark:border-gray-700'
            ]"
        >
        <td class="border text-center p-2">{{ evento.id }}</td>
        <td class="border text-center p-2">{{ evento.date }}</td>

        <td class="tags_main_info border p-2">
            <div class="border w-fit p-2.5 border-gray-300 rounded-md border-red-100 border-none ">
                <div class="flex items-center cursor-pointer items-stretch">
                    <div class="py-1.5 px-2 bg-gray-200 text-black">{{ evento.tag_id_first_name }}
                    </div>

                    <div v-if="!evento.tag_id_second && evento.value"
                         class="px-1.5 py-0.5 font-semibold bg-gray-600 text-white flex items-center">
                        <div class="text-sm">{{ evento.value }}</div>
                    </div>

                    <div v-if="evento.tag_id_second" class="py-1.5 px-2 bg-green-600 text-white flex items-center"
                    >{{ evento.tag_id_second_name }}
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
                <mg-trash-icon-button
                    @click="deleteEvento(evento.id)"
                    class="ml-1 border-none text-red-500 self-end
                            focus:ring-red-500 rounded-sm h-4 w-4
                            "
                    :svgClass="'h-4 w-4'"
                ></mg-trash-icon-button>
                <mg-pencil-icon-button
                    @click="editEventoHanlder(evento)"
                    class="ml-1 border-none text-purple-800  self-end
                    focus:ring-purple-500 rounded-sm h-4 w-4"
                ></mg-pencil-icon-button>
            </div>
        </td>
    </tr>
</template>

<script>
import { mapGetters} from "vuex";

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
            this.$store.dispatch('evento/delItem', id);
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
        ...mapGetters({
            'currentEditedItem': 'evento/getCurrentEditedItem',
        }),
    }
}
</script>

<style scoped>

</style>
