<template>
    <tr
        :class="[trBgc, 'border-b dark:border-gray-700']"
        class=""
    >
        <td class="item-id border dark:border-none p-2">{{ item.id }}</td>
        <td class="item-id border dark:border-none p-2">{{ item.category_name }} ({{ item.category_id }})</td>
        <td class="item-url border dark:border-none p-2">{{ item.url }}</td>
        <td class="item-email border dark:border-none p-2">{{ item.email }}</td>
        <td class="item-password border dark:border-none p-2">{{ item.password }}</td>

        <td class="item-note border text-center dark:border-none p-2">
            <span v-if="item.note">
                {{ item.note }}
            </span>
            <span v-else>...</span>
        </td>

        <td class="evento_actions buttons_block border dark:border-none p-2">
            <div class="w-fit flex bg-gray-500 p-2 rounded-md mx-auto">

                <mg-pencil-icon-button
                    @click="editItemHanlder(item)"
                    class="mr-1 border-none text-purple-800  self-end
                    focus:ring-purple-500 rounded-sm h-4 w-4"
                ></mg-pencil-icon-button>

                <button
                    class="mr-1"
                    @click="copyItem(item.id)"
                    type="button"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </button>

                <mg-trash-icon-button
                    @click="deleteItem(item.id)"
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

export default {
    name: 'evento-item',
    components: {
    },

    data(){
        return {
            itemPicked: false,
            innerEventoId: 0,
        }
    },

    props: {
        item: {
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
        }),

        deleteItem(id){
            if (!confirm('Действительно удалить?')) return;

            this.$store.dispatch('onepassEntry/delItemQuery', id)
                .then((res) => {
                    if (res?.status === 200 && res?.data?.success === 1){
                        this.$store.commit('notify', {
                            message: 'Запись удалена!',
                            type: 'deleted',
                            timeout: 2500,
                        })
                    }
                })
                .catch((err) => {
                    console.log('delete item - catch: ', err);
                });
        },

        editItemHanlder(item){
            this.$store.dispatch('onepassEntry/setEditItemId', item.id);
            this.$store.commit('onepassEntry/setPressedItemEditBtn');
            this.$store.commit('onepassEntry/setCreateEditFormVisible', true);
        },
        copyItem(){
            this.$store.dispatch('evento/copyItemQuery', this.evento)
                .then((res) => {
                    this.errors = {};
                    this.errors = res.response.data.errors;
                })
                .catch((err) => {
                });
        },

        itemPickedHandler(p){
            this.itemPicked = p;
        },

    },
    computed:{
        ...mapState({
        }),
        ...mapGetters({
        }),

        trBgc(){
            return this.index % 2 != 0
                ? 'bg-gray-50 border-b dark:bg-gray-800'
                : 'bg-white dark:bg-gray-900';
        }
    },

    watch:{

    },

    mounted() {
    }
}
</script>

<style scoped>

</style>
