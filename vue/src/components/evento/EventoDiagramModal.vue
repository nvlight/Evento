<template>
    <div class="max-w-md">
        <h1 class="text-xl font-semibold">Диаграммы</h1>

        <div>
            <form @submit.prevent="loadDiagramData" class="mt-2 flex items-end">
                <mg-input-labeled v-focus v-model="diagramGetParams.year">Выберите год</mg-input-labeled>
                <mg-button class="ml-2 bg-red-400 focus:ring-red-500">
                    <mg-spin v-if="diagramValue.loading"></mg-spin>Показать
                </mg-button>
            </form>
        </div>

        <div class="diagram-data-wrapper mt-2">
            <div v-for="(tag, month) of diagramValue.items">
                <div class="font-semibold mt-2">{{month}}</div>
                <div v-for="tag_arr of tag">
                    {{tag_arr.name}}: {{tag_arr.sum}}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import MgInputLabeled from "../UI/MgInputLabeled.vue";
import DateMixin from "../../mixins/DateMixin.vue";

export default {
    name: 'evento-diagram-modal',
    components: { MgInputLabeled },
    emits: ['closeModalDialog', ],
    mixins: [ DateMixin, ],
    data(){
        return {
            diagramGetParams:{
                year: 0,
            },
        }
    },
    methods:{
        ...mapActions({
        }),
        loadDiagramData(){
            this.$store.dispatch("diagram/loadItems", this.diagramGetParams);
        },
    },
    computed: {
        ...mapState({
            'diagramValue': state => state.diagram.diagram,
        }),
    },
    mounted() {
        this.diagramGetParams.year = this.getCurrentDateObject.year;
        this.$store.dispatch('diagram/resetItems');

        window.addEventListener('keydown', (e) => {
            if (e.key == 'Escape') {
                this.$emit('closeModalDialog');
            }
        });
    }
}
</script>

<style scoped>

</style>
