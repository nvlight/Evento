<template>
    <tr
        :class="[trBgc, 'border-b dark:border-gray-700']"
        class=""
    >
        <td class="border text-center dark:border-none p-2">
            <evento-pickable-checkbox
                :evento-id="evento.id"
                :item-picked="itemPicked"
                @itemPicked="itemPickedHandler"
            />
        </td>
        <td class="border text-center dark:border-none p-2 whitespace-nowrap">{{ evento.date }}</td>

        <td class="tags_main_info border dark:border-none p-2">
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

        <td class="description border text-center dark:border-none p-2">
            <span v-if="evento.description">
                {{ evento.description }}
            </span>
            <span v-else>...</span>
        </td>

        <td class="evento_actions buttons_block border  dark:border-none p-2">
            <div class="w-fit flex bg-gray-500 p-2 rounded-md mx-auto">

                <mg-pencil-icon-button
                    @click="editEventoHanlder(evento)"
                    class="mr-1 border-none text-purple-800  self-end
                    focus:ring-purple-500 rounded-sm h-4 w-4"
                ></mg-pencil-icon-button>

                <button
                    class="mr-1"
                    @click="coptyEvento(evento.id)"
                    type="button"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </button>

                <mg-trash-icon-button
                    @click="deleteEvento(evento.id)"
                    class="mr-1 border-none text-red-500 self-end
                            focus:ring-red-500 rounded-sm h-4 w-4
                            "
                    :svgClass="'h-4 w-4'"
                ></mg-trash-icon-button>
            </div>
        </td>
    </tr>

</template>

<script>
import {mapGetters, mapMutations, mapState} from "vuex";
import EventoPickableCheckbox from "./EventoPickableCheckbox.vue";

export default {
    name: 'evento-item',
    components: {
        EventoPickableCheckbox,
    },

    data(){
        return {
            itemPicked: false,
            innerEventoId: 0,
        }
    },

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
        ...mapMutations({
            'togglePickedEvento': "evento/togglePickedEvento",
        }),

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
                            type: 'deleted',
                            timeout: 2500,
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
        coptyEvento(){
            this.$store.dispatch('evento/copyItemQuery', this.evento)
                .then((res) => {
                    this.errors = {};
                    //console.log('update evento - then: ', res);
                    this.errors = res.response.data.errors;
                })
                .catch((err) => {
                    //console.log('update evento - catch: ', err);
                });
        },

        itemPickedHandler(p){
            this.itemPicked = p;
        },

    },
    computed:{
        ...mapState({
            'current_page': state => state.evento.current_page,
            'evento_filter': state => state.evento.evento_filter,
        }),
        ...mapGetters({
            'currentEditedItem': 'evento/getCurrentEditedItem',
        }),

        trBgc(){
            return this.index % 2 != 0
                ? 'bg-gray-50 border-b dark:bg-gray-800'
                : 'bg-white dark:bg-gray-900';
        }
    },

    watch:{
        itemPicked(nv){
            this.togglePickedEvento({id: this.innerEventoId, value: nv})
        }
    },

    mounted() {
        this.innerEventoId = this.evento.id;
    }
}
</script>

<style scoped>

</style>
