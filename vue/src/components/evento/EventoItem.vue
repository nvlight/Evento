<template>
    <tr>
        <td class="border text-center">{{ evento.id }}</td>
        <td class="border text-center">{{ evento.date }}</td>
        <td class="border p-2">
            <div class="border w-fit p-2.5 border-gray-300 rounded-md border-red-100 border-none ">
                <button class="cursor-pointer
                        focus:ring-offset-8
                        focus:ring-gray-300 focus:ring-2 focus:rounded-sm">
                    <span v-if="evento.tag_id_first_name"
                          class="bg-gray-400 px-1.5 py-1.5 text-white rounded-md
                                    rounded-r-none
                                ">{{ evento.tag_id_first_name }}
                    </span>
                    <template v-if="evento.tag_id_second_name">
                        <span class="px-1.5 py-1.5 rounded-none border-gray-300
                                font-semibold
                                bg-gray-600
                                text-white"
                        > <span class="text-sm">{{ evento.value }}</span>
                        </span>
                        <span
                            class="bg-gray-400 px-1.5 py-1.5 text-white rounded-md rounded-l-none">{{ evento.tag_id_second_name }}
                        </span>
                    </template>
                    <template v-else>
                        <span class="px-1.5 py-1.5 rounded-r border-gray-300
                                font-semibold
                                bg-gray-600
                                text-white"
                        > <span class="text-sm">{{ evento.value }}</span>
                        </span>
                    </template>
                </button>
            </div>
        </td>
        <td class="border text-center">{{ evento.description }} ...</td>
        <td class="border text-center">
            <div class="w-full flex justify-center items-center">
                <mg-trash-icon-button
                    @click="deleteEvento"
                    class="ml-1 border-none text-red-500 self-end
                            focus:ring-red-500 rounded-sm h-4 w-4
                            "
                    :svgClass="'h-4 w-4'"
                ></mg-trash-icon-button>
                <mg-pencil-icon-button class="ml-1 border-none text-purple-800  self-end
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

    methods:{
        deleteEvento(){
            if (!confirm('Действительно удалить?')) return;

        },
    },
    computed:{

    }
}
</script>

<style scoped>

</style>
