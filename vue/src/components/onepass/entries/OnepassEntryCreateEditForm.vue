<template>
    <form
        @submit.prevent="saveOrUpdateEntry"
        class="text-black dark:text-white border-indigo-500 border rounded-md"
        method="POST"
    >
        <div class="shadow sm:overflow-hidden sm:rounded-md relative">
            <div>
                <span class="absolute right-2 top-2 bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-1.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300"
                >
                    <x-icon
                        @click="closeCreateFormHandler"
                        class="h-5 w-5 cursor-pointer"
                    />
                </span>
            </div>

            <div class="m-2 p-2 hidden">
                <pre>getEditedItem: {{getEditedItem}}</pre>
            </div>

            <div class="space-y-6 px-4 py-5 sm:p-6">
                <!--       <pre>entry.category: {{ this.entry.category_id }}</pre>-->

                <!--                    <pre>categories: {{ categories.list[0] }}-->
                <!--                    </pre>-->

                <!-- comboBasic - эта штука так и не сработала у меня !!! -->
                <div v-if="1==2">
                    <ComboboxBasic
                        labelName="Категория"
                        :people="categories.list"
                        v-if="!categories.loading"
                        class="block"
                    />
                    <div v-if="formErrors.hasOwnProperty('category')" class="mt-1">
                        <alert-field @hideError="delete formErrors.category_id" :error="formErrors.category_id"/>
                    </div>
                </div>

                <div class="mt-5">
                    <label>
                        <span>Категория</span>
                        <mg-select
                            class="block w-full"
                            v-model="entry.category_id"
                            v-if="!categories.loading"
                            :options="categories.list"
                        >
                            <option value="0">Выберите категорию</option>
                        </mg-select>
                    </label>
                    <div v-if="formErrors.hasOwnProperty('category_id')" class="mt-1">
                        <alert-field @hideError="delete formErrors.category_id" :error="formErrors.category_id"/>
                    </div>
                </div>

                <div>
                    <mg-input-labeled class="block" :classes="'w-full'" placeholder="Url" v-model="entry.url">Url адрес веб-приложения</mg-input-labeled>
                    <div v-if="formErrors.hasOwnProperty('url')" class="mt-1">
                        <alert-field @hideError="delete formErrors.url" :error="formErrors.url"/>
                    </div>
                </div>

                <div>
                    <mg-input-labeled class="block" :classes="'w-full'" placeholder="Емейл" v-model="entry.email">Емейл</mg-input-labeled>
                    <div v-if="formErrors.hasOwnProperty('email')" class="mt-1">
                        <alert-field @hideError="delete formErrors.email" :error="formErrors.email"/>
                    </div>
                </div>

                <div>
                    <mg-input-labeled class="block" :classes="'w-full'" placeholder="Телефон" v-model="entry.phone">Телефон</mg-input-labeled>
                    <div v-if="formErrors.hasOwnProperty('phone')" class="mt-1">
                        <alert-field @hideError="delete formErrors.phone" :error="formErrors.phone"/>
                    </div>
                </div>

                <div>
                    <mg-input-labeled class="block" :classes="'w-full'" placeholder="Логин" v-model="entry.login">Логин</mg-input-labeled>
                    <div v-if="formErrors.hasOwnProperty('login')" class="mt-1">
                        <alert-field @hideError="delete formErrors.login" :error="formErrors.login"/>
                    </div>
                </div>

                <div>
                    <mg-input-labeled class="block" :classes="'w-full'" placeholder="Имя" v-model="entry.name">Имя</mg-input-labeled>
                    <div v-if="formErrors.hasOwnProperty('name')" class="mt-1">
                        <alert-field @hideError="delete formErrors.name" :error="formErrors.name"/>
                    </div>
                </div>

                <div>
                    <mg-password-input-labeled class="block" :classes="'w-full'" placeholder="Пароль" v-model="entry.password">Пароль</mg-password-input-labeled>
                    <div v-if="formErrors.hasOwnProperty('password')" class="mt-1">
                        <alert-field @hideError="delete formErrors.password" :error="formErrors.password"/>
                    </div>
                </div>

                <div>
                    <mg-password-input-labeled class="block" :classes="'w-full'" placeholder="Подтверждение пароля" v-model="entry.password_confirmation" >Подтверждение пароля</mg-password-input-labeled>
                    <div v-if="formErrors.hasOwnProperty('password_confirmation')" class="mt-1">
                        <alert-field @hideError="delete formErrors.password_confirmation" :error="formErrors.password_confirmation"/>
                    </div>
                </div>

                <mg-textarea v-model="entry.note" placeholder="Причания для записи.">Примечание</mg-textarea>

                <div class="text-right py-3">
                    <button
                        type="submit"
                        :class="[
                            isFormSubmitModeCreated ? 'focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800': '',
                            isFormSubmitModeUpdated ? 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800': '',
                        ]"
                    >
                        {{ formSubmitBtnCaption }}
                    </button>
                </div>

            </div>

        </div>
    </form>
</template>

<script>
import ComboboxBasic from "../../../components/UI_v2.0/ComboboxBasic.vue"
import {mapGetters, mapMutations, mapState} from "vuex";
import AlertField from "../../AlertField.vue";
import {XIcon, ClipboardIcon} from "@heroicons/vue/solid";

export default {
    components: {
        ComboboxBasic, AlertField, XIcon
    },

    data(){
        return {
            entryDefault:{
                category_id: 0,
                category_id2: 0,
                url: "",
                password: "",
                password_confirmation: "",

                email: "", // nullable
                login: "", // nullable
                phone: "", // nullable
                name: "",  // nullable

                note: "",  // nullable
            },
            entry: {
            },

            formErrors: {},
        }
    },

    methods:{
        ...mapMutations({
            setFormMode: "onepassEntry/setFormMode",
            setFormVisible: "onepassEntry/setCreateEditFormVisible",
            setEditedItemId: "onepassEntry/setEditedItemId",
        }),

        resetForm() {
            this.entry = Object.assign({}, this.entryDefault);
            this.formErrors = {};
        },

        saveOrUpdateEntry(){
            if (this.formMode === 'create'){
                this.saveEntry();
            }else if (this.formMode === 'edit'){
                this.updateEntry();
            }
        },
        saveEntry(){
            // тут вылезает ошибка, null он воспринимает как текст! вот уж formData !!
            let data = new FormData();
            for(let key in this.entry){
                if ( this.entry[key] !== null ){
                    data.append(key, this.entry[key]);
                }
            }

            const item = {item: this.entry, formData: data}
            this.$store.dispatch('onepassEntry/createItem', item)
                .then(data => {
                    if (data?.response?.status === 422) {
                        this.formErrors = data.response.data.errors;
                    }else{
                        this.$store.commit('notify', {
                            message: 'Запись сохранена!',
                            type: 'created',
                            timeout: 2500,
                        })

                        this.resetForm();
                        this.setFormMode(null);
                        this.setFormVisible(false);
                        this.setEditedItemId(0);
                    }
                })
                .catch(error => {
                    console.log('onepassEntry/createItem - dispatch error');
                });
        },
        updateEntry(){
            // тут вылезает ошибка, null он воспринимает как текст! вот уж formData !!
            let data = new FormData();
            for(let key in this.entry){
                if ( this.entry[key] !== null ){
                    data.append(key, this.entry[key]);
                }
            }
            data.append('_method','PATCH');

            const item = {item: this.entry, formData: data}
            this.$store.dispatch('onepassEntry/updateItemQuery', item)
                .then(data => {
                    if (data?.response?.status === 422) {
                        this.formErrors = data.response.data.errors;
                    }else{
                        this.$store.commit('notify', {
                            message: 'Запись обновлена!',
                            type: 'updated',
                            timeout: 2500,
                        })

                        this.resetForm();
                        this.setFormMode(null);
                        this.setFormVisible(false);
                        this.setEditedItemId(0);
                    }
                })
                .catch(error => {
                    console.log('onepassEntry/createItem - dispatch error');
                });
        },
        closeCreateFormHandler(){
            this.resetForm();
            this.$emit('closeCreateForm');
        }
    },

    computed: {
        ...mapState({
            categories: state => state.onepassCategory.items,
            formMode: state => state.onepassEntry.formMode,
            editItemId: state => state.onepassEntry.editedItemId,
            editBtnPressed: state => state.onepassEntry.pressedItemEditBtn,
        }),
        ...mapGetters({
            getEditedItem: "onepassEntry/getEditedItem",
        }),

        formSubmitBtnCaption(){
            let caption = '';

            if (this.formMode === 'create'){
                caption = 'Сохранить';
            }else if (this.formMode === 'edit'){
                caption = 'Обновить';
            }

            return caption;
        },

        isFormSubmitModeCreated(){
            return this.formMode === 'create';
        },
        isFormSubmitModeUpdated(){
            return this.formMode === 'edit';
        },
    },

    mounted() {
        this.entry = Object.assign({}, this.entryDefault);
        this.$store.dispatch("onepassCategory/loadItems");
        //this.$store.dispatch("onepassEntry/loadItems");
    },

    watch:{
        formMode(nv){
            if (nv === 'edit' && this.editItemId){
                this.entry = Object.assign({}, this.getEditedItem);
                this.entry.password_confirmation = this.entry.password;
            }
        },

        editBtnPressed(){
            if (this.formMode === 'edit' && this.editItemId){
                this.entry = Object.assign({}, this.getEditedItem);
                this.entry.password_confirmation = this.entry.password;
            }
        }
        // categories:{
        //     handler(nv, ov){
        //         if (nv.loading === false){
        //             this.entry.category_id = this.categories.list[0].id;
        //         }
        //     },
        //     deep: true,
        // }
    },
}
</script>

<style scoped>

</style>
