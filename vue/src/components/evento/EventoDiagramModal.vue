<template>
    <div class="max-w-md dark:text-white">
        <h1 class="text-xl font-semibold">Диаграммы</h1>

        <div>
            <form @submit.prevent="loadDiagramData" class="mt-2 flex items-end">
<!--                <mg-input-labeled v-focus v-model="diagramGetParams.year">Выберите год</mg-input-labeled>-->
                <mg-select v-model="diagramGetParams.year" :options="n_years" :optionFieldName="'year'"
                >
                    <option value="">Выберите год</option>
                </mg-select>

                <mg-button class="ml-2 bg-red-400 focus:ring-red-500">
                    <mg-spin v-if="diagramValue.loading"></mg-spin>Показать
                </mg-button>
            </form>
        </div>

        <div class="diagram-data-wrapper mt-2">
            <div v-for="(tag, month) of diagramValue.items">
                <div class="font-semibold mt-2">{{month}}</div>
<!--                <pre>tag: {{tag}}</pre>-->
                <div v-for="(tag_arr,tag_name) of tag">
                    {{tag_name}}: {{tag_arr.sum}}
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
            yy: '',
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
            'years':        state => state.diagram.years,
        }),
        n_years(){
            if (!this.years.length){
                return [];
            }
            let ny = [];
            let i = 0;
            for(let i=0, n=this.years.length; i<n; i++){
                let cur_year = this.years[i].year;
                ny.push({id: cur_year, year: cur_year})
            }
            return ny;
        }
    },
    mounted() {
        this.diagramGetParams.year = this.getCurrentDateObject.year;
        this.$store.dispatch('diagram/resetItems');
        this.$store.dispatch('diagram/loadYears');

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
