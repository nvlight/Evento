<template>
    <div>
        <menu-header title="Записи"></menu-header>

        <div class="dark:bg-gray-900 dark:text-white">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8  ">

                <router-link
                    :to="{name: 'OnepassCategories'}"
                    class="underline"
                >Категории
                </router-link>

                <div class="mt-5">
                    <button
                        @click="createItemHandler"
                        type="button"
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    >Создать запись
                    </button>
                </div>

                <div v-if="1==2">
                    <div class="mt-3">formVisibleStatus: {{ formVisibleStatus }}</div>
                    <div class="mt-0">formMode: {{ formMode }}</div>
                    <div class="mt-0">editItemId: {{ editItemId }}</div>
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
import {mapActions, mapMutations, mapState} from "vuex";

export default {
    components: {
        MenuHeader, OnepassEntryCreateEditForm, OnepassEntryList,
    },

    data(){
        return {
        }
    },

    methods:{
        ...mapActions({

        }),
        ...mapMutations({
            setFormMode: "onepassEntry/setFormMode",
            setFormVisible: "onepassEntry/setCreateEditFormVisible",
            setEditedItemId: "onepassEntry/setEditedItemId",
        }),

        createItemHandler(){
            this.setFormMode('create');
            this.setFormVisible(true);
        },

        closeFormHandler(){
            this.setFormMode(null);
            this.setFormVisible(false);
            this.setEditedItemId(0);
        }
    },

    computed:{
        ...mapState({
            editBtnClicked: state => state.onepassEntry.pressedItemEditBtn,
            formVisibleStatus: state => state.onepassEntry.createEditFormVisible,
            formMode: state => state.onepassEntry.formMode,
            editItemId: state => state.onepassEntry.editedItemId,
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
        }
    }
}
</script>

<style scoped>

</style>
