<template>
    <div>
        <menu-header title="Записи"></menu-header>


        <div class="dark:bg-gray-900 dark:text-white">
            <div class="max-w-screen-2xl mx-auto py-6 sm:px-6 lg:px-8  ">

                <router-link
                    :to="{name: 'OnepassCategories'}"
                    class="underline"
                >Категории
                </router-link>

                <div class="entry-actions mt-5 flex flex-wrap justify-between">
                    <div class="left-side">
                        <button
                            @click="createItemHandler"
                            type="button"
                            class="mt-1 focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                        >Создать запись
                        </button>
                    </div>

                    <div class="right-side flex">
                        <button
                            v-if="isFilterEmpty"
                            @click="clearFilterHandler"
                            class="mt-1 flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        >
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" >
                                <path fill-rule="evenodd" d="M6.72 5.66l11.62 11.62A8.25 8.25 0 006.72 5.66zm10.56 12.68L5.66 6.72a8.25 8.25 0 0011.62 11.62zM5.105 5.106c3.807-3.808 9.98-3.808 13.788 0 3.808 3.807 3.808 9.98 0 13.788-3.807 3.808-9.98 3.808-13.788 0-3.808-3.807-3.808-9.98 0-13.788z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3">Сбросить фильтры</span>
                        </button>

                        &nbsp;&nbsp;

                        <button
                            @click="togglefilterModalVisible"
                            type="button"
                            class="mt-1 flex items-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            <adjustments-icon class="w-6 h-6" />
                            <span class="ml-3">Фильтры</span>
                        </button>
                    </div>
                </div>

                <!-- filter modal -->
                <mg-modal v-model:show="localFilterModalVisible">
                    <onepass-filter-modal/>
                </mg-modal>

                <!-- filter modal -->
                <mg-modal v-model:show="localItemViewModalVisible">
                    <OnepassEntryViewModal/>
                </mg-modal>

                <div v-if="1==2">
                    <div class="mt-3">formVisibleStatus: {{ formVisibleStatus }}</div>
                    <div class="mt-0">formMode: {{ formMode }}</div>
                    <div class="mt-0">editItemId: {{ editItemId }}</div>
                    <div class="mt-0">isFilterEmpty: {{ isFilterEmpty }}</div>
                </div>

                <div class="py-2 md:py-5 rounded-md">
                    <!-- entry create form -->
                    <onepass-entry-create-edit-form
                        v-show="formVisibleStatus"
                        @closeCreateForm="closeFormHandler"
                    />

                    <!-- entry lists -->
                    <onepass-entry-list class="" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import MenuHeader from "../components/MenuHeader.vue";
import OnepassEntryCreateEditForm from "../components/onepass/entries/OnepassEntryCreateEditForm.vue";
import OnepassEntryList from "../components/onepass/entries/OnepassEntryList.vue";
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";
import {AdjustmentsIcon, AtSymbolIcon} from "@heroicons/vue/solid"
import OnepassFilterModal from "../components/onepass/entries/OnepassFilterModal.vue";
import OnepassEntryViewModal from "../components/onepass/entries/OnepassEntryViewModal.vue";

export default {
    components: {
        MenuHeader, OnepassEntryCreateEditForm, OnepassEntryList, OnepassFilterModal,
        AdjustmentsIcon, AtSymbolIcon, OnepassEntryViewModal,
    },

    data(){
        return {
            localFilterModalVisible: false,
            localItemViewModalVisible: false,
        }
    },

    methods:{
        ...mapActions({

        }),
        ...mapMutations({
            setFormMode: "onepassEntry/setFormMode",
            setFormVisible: "onepassEntry/setCreateEditFormVisible",
            setEditedItemId: "onepassEntry/setEditedItemId",
            setFilterModalVisible: 'onepassEntry/setFilterModalVisible',
        }),

        createItemHandler(){
            this.setFormMode('create');
            this.setFormVisible(true);
        },

        closeFormHandler(){
            this.setFormMode(null);
            this.setFormVisible(false);
            this.setEditedItemId(0);
        },

        togglefilterModalVisible(){
            console.log('togglefilterModalVisible', this.filterModalVisible);

            this.setFilterModalVisible(! this.filterModalVisible );
            this.localFilterModalVisible = this.filterModalVisible;
        },

        clearFilterHandler(){
            // :to="{name: 'OnepassEntries'}"
            this.$router.go();
        }
    },

    computed:{
        ...mapState({
            editBtnClicked: state => state.onepassEntry.pressedItemEditBtn,
            formVisibleStatus: state => state.onepassEntry.createEditFormVisible,
            formMode: state => state.onepassEntry.formMode,
            editItemId: state => state.onepassEntry.editedItemId,
            filterModalVisible: state => state.onepassEntry.filterModalVisible,
            itemViewModalVisible: state => state.onepassEntry.itemViewModalVisible,
            pressedItemViewBtnClicked: state => state.onepassEntry.pressedItemViewBtn,
        }),
        ...mapGetters({
            isFilterEmpty: "onepassEntry/isFilterEmpty",
        }),
    },

    watch:{
        editBtnClicked(nv, ov){
            this.createEditFormVisible = true;
            this.setFormMode('edit');
        },
        formVisibleStatus(nv){
            if (nv){
                this.createEditFormVisible = true;
            }else{
                this.createEditFormVisible = false;
            }
        },
        localFilterModalVisible(nv){
            this.setFilterModalVisible( this.localFilterModalVisible );
        },
        filterModalVisible(nv){
            if (! nv){
                this.localFilterModalVisible = false;
            }
        },

        itemViewModalVisible(nv){
            if (! nv){
                this.localItemViewModalVisible = false;
            }
        },

        pressedItemViewBtnClicked(nv){
            this.localItemViewModalVisible = true;
        },
    },

    mounted() {
        this.localFilterModalVisible = this.filterModalVisible;
        this.localItemViewModalVisible = this.itemViewModalVisible;
    }
}
</script>

<style scoped>

</style>
