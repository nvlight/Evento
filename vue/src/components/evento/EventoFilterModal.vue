<template>
    <div>
        <h1 class="text-2xl font-semibold">Фильтр событий</h1>

        <form @submit.prevent="doFilterEventos" class="mt-2">
            <div class="date-start-end flex">
                <mg-input-date-labeled v-model="filterData.date_start" >Дата - начало</mg-input-date-labeled>
                <mg-input-date-labeled v-model="filterData.date_end" class="ml-2"
                >Дата - конец
                </mg-input-date-labeled>
            </div>

            <div class="mt-2 tag_list">
                <div class="relative">
                    <div class="relative">
                        <mg-input-labeled v-model="filterData.filter_text" class="block " :classes="'w-full'"
                        >добавить тег
                        </mg-input-labeled>

                        <!-- reset add_tag text_filter -->
                        <div v-if="filterData.filter_text.length > 1"
                             class="bg-red-400 text-white w-fit rounded-md top-8 right-2 absolute cursor-pointer
                                hover:bg-red-500 focus:bg-red-600 transition-colors">
                            <svg @click="this.filterData.filter_text=''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                 class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <!--/ reset add_tag text_filter -->
                    </div>

                    <div class="flex">
                        <template v-for="(tag,i) in tagTextFilterRs">
                            <div class="cursor-pointer title p-1 px-3 rounded-md text-center mt-1"
                                  :class="[
                                      i == 0 ? '' : 'ml-1',
                                  ]"
                                  :style="[
                                    tag.bg_color ? `background-color: ${tag.bg_color}` : 'background-color: #5CB85C',
                                    tag.text_color ? `color: ${tag.text_color}` : 'color: #fff',
                                  ]"
                                  @click="addFilterTagId(tag.id)">{{tag.name}}
                            </div>
                        </template>
                    </div>
                </div>

                <div v-if="filterRs.length" class="mt-2">Добавленные теги:</div>
                <div v-if="filterRs.length" class="flex flex-wrap">
                    <div v-for="(tag,i) in filterRs" class="mt-1">
                        <div class="cursor-pointer title p-1 px-3 rounded-md"
                              :class="[
                                  i == 0 ? '' : 'ml-1',
                              ]"
                              :style="[
                                tag.bg_color ? `background-color: ${tag.bg_color}` : 'background-color: #5CB85C',
                                tag.text_color ? `color: ${tag.text_color}` : 'color: #fff',
                              ]"
                              @click="removeFilterTagId(tag.id)">{{tag.name}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="date-start-end flex mt-3">
                <mg-input-labeled v-model="filterData.sum_start" >Сумма начальная</mg-input-labeled>
                <mg-input-labeled v-model="filterData.sum_end" class="ml-2"
                >Сумма конечная
                </mg-input-labeled>
            </div>

            <div class="flex justify-between">
                <button type="reset" class=" mt-2 bg-indigo-300 text-white p-2 rounded-md hover:bg-indigo-400
                        focus:bg-indigo-500"
                >сбросить
                </button>
                <button class="ml-2 mt-2 bg-indigo-500 text-white p-2 rounded-md hover:bg-indigo-600
                        focus:bg-indigo-700"
                >Фильтровать
                </button>
            </div>
        </form>

    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import DateMixin from "../../mixins/DateMixin.vue";

export default {
    name: 'evento-filter-modal',
    emits: ['doFilterEventos', 'closeModalDialog'],
    mixins: [DateMixin, ],
    data(){
        return {
            filterData: {
                date_start: 1,
                date_end: 2,
                sum_start: 0,
                sum_end: 107000,
                filter_text: '',
                tag_arr: [], //[122, 123],
                orderById: 'desc / asc',
            },
            sessionFilter: {},
        }
    },
    methods:{
        ...mapActions({
            'filterItems': 'evento/filterItems',
        }),
        doFilterEventos(){
            sessionStorage.setItem('evento_filter', JSON.stringify(this.filterData)),
            this.filterItems(this.filterData);
        },

        setDatesForFilterForm(){
            if (! Object.keys(this.sessionFilter).length){
                this.filterData.date_start = this.getFormattedCurrentDate;
                this.filterData.date_end   = this.getFormattedCurrentDate;
            }
        },
        removeFilterTagId(id){
            let index = this.filterData.tag_arr.indexOf(id);
            if (index !== -1) {
                this.filterData.tag_arr.splice(index, 1);
            }
        },
        addFilterTagId(id){
            let index = this.filterData.tag_arr.indexOf(id);
            if (index === -1) {
                this.filterData.tag_arr = [id, ...this.filterData.tag_arr, ];
            }
            this.filterData.filter_text='';
        },

        setSessionFilterData(){
            let key = 'evento_filter';
            if (sessionStorage.hasOwnProperty(key)) {
                this.sessionFilter = (JSON.parse(sessionStorage.getItem(key)));
                this.filterData = this.sessionFilter;
            }
        },
    },
    computed:{
        ...mapState({
            'tags': state => state.tag.tags,
        }),

        filterRs(){
            return this.tags.items.filter(t => {
                for (let i=0, n=this.filterData.tag_arr.length; i<n; i++){
                    if (t.id === this.filterData.tag_arr[i]){
                        return t.id;
                    }
                }
            });
        },

        tagTextFilterRs(){
            if (this.filterData.filter_text.length < 2){
                return [];
            }
            return this.tags.items.filter(t => t.name.toLowerCase().includes(this.filterData.filter_text.toLowerCase()));
        },
    },
    mounted() {
        this.setSessionFilterData();
        this.setDatesForFilterForm();

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
