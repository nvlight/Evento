<template>
    <div>
        <h1 class="text-xl font-semibold">Диаграммы</h1>

        <div>
            <form @submit.prevent="loadDiagramData" class="mt-2 flex items-end">
                <mg-input-labeled v-model="diagramGetParams.year">Выберите год</mg-input-labeled>
                <mg-button class="ml-2 bg-red-400 focus:ring-red-500">Показать</mg-button>
            </form>
        </div>

        <div class="mt-2">
            <div v-if="diagramValue.loading">Loading...</div>
            <div v-else>
                <div>
                    diagramValue{{diagramValue}}
                </div>
                diagramValueData: {{diagramValue.items}}
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import MgInputLabeled from "../UI/MgInputLabeled.vue";

export default {
    name: 'evento-diagram-modal',
    components: {MgInputLabeled},
    emits: [],
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
        this.diagramGetParams.year = (new Date()).getFullYear()
    }
}
</script>

<style scoped>

</style>
