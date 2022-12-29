<template>
    <tr>
        <td class="border text-center">{{ evento.id }}</td>
        <td class="border text-center">{{ evento.date }}</td>
        <td class="border p-2">
            <div class="border w-fit p-2.5 border-gray-300 rounded-md border-red-100 border-none ">
                <button class="rounded-md cursor-pointer bg-gray-400 px-1.5 py-1.5 text-white
                    focus:ring-offset-1 focus:ring-gray-300 focus:ring-2 focus:rounded-sm">
                    <div class="flex items-center">
                        <span v-if="evento.tag_id_first_name"
                              class="

                                    ">{{ evento.tag_id_first_name }}
                        </span>
                        <template v-if="!evento.tag_id_second && !evento.value">

                        </template>

                        <div v-if="evento.value" class="px-1.5 py-0.5 ml-2 mr-2  font-semibold bg-gray-600 text-white">
                            <span class="text-sm">{{ evento.value }}</span>
                        </div>

                        <span v-if="evento.tag_id_second"
                              class="py-1.5 text-white  ">{{ evento.tag_id_second_name }}
                        </span>
                    </div>
                </button>
            </div>
        </td>
        <td class="border text-center">
            <span v-if="evento.description">
                {{ evento.description }}
            </span>
            <span v-else>...</span>
        </td>
        <td class="border text-center">
            <div class="w-full flex justify-center items-center">
                <mg-trash-icon-button
                    @click="deleteEvento(evento.id)"
                    class="ml-1 border-none text-red-500 self-end
                            focus:ring-red-500 rounded-sm h-4 w-4
                            "
                    :svgClass="'h-4 w-4'"
                ></mg-trash-icon-button>
                <mg-pencil-icon-button
                    @click="$store.dispatch('evento/setCurrentEditItemId', evento.id)"
                    class="ml-1 border-none text-purple-800  self-end
                    focus:ring-purple-500 rounded-sm h-4 w-4"
                ></mg-pencil-icon-button>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    name: 'evento-item',
    components: {},

    props: {
        evento: {
            type: Object,
            required: true,
        }
    },
    emits: ['doAddFormReset'],

    methods:{
        deleteEvento(id){
            if (!confirm('Действительно удалить?')) return;
            this.$store.dispatch('evento/delItem', id);
            this.$emit('doAddFormReset');
        },
    },
    computed:{

    }
}
</script>

<style scoped>

</style>
