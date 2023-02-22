<template>
    <menu-header title="Профиль пользователя"></menu-header>

    <div class="dark:bg-gray-900 dark:text-white">
        <div class="evento-view max-w-7xl mx-auto py-6 sm:px-6 lg:px-8  ">
            <!-- Replace with your content -->
            <div class="py-2 md:py-5 rounded-md">

                <form @submit.prevent="setAvatar">
                    <div>
                        <span class="mt-3">Загрузка аватара</span>
                    </div>

                    <div>
                        <div class="flex items-center">
                            <div
                                v-if="userStoredAvatar"
                                class="w-1/12 mt-2"
                            >
                                <img :src="userStoredAvatar" class="rounded-xl" @click="changeAvatar">
                            </div>
                            <div v-show="!userStoredAvatar" class="ml-3">
                                <div>
                                    Аватара нет!
                                </div>
                                <label>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <input ref="avatar_ref" @change="avatarInputChanged" type="file" class="mt-2 hidden">
                                </label>
                            </div>
                        </div>

                        <AlertField
                            :error="user.avatarErrors?.avatar"
                            @hideError="user.avatarErrors = []"
                        />
                    </div>
                </form>

            </div>
            <!-- /End replace -->
        </div>
    </div>
</template>

<script>
import MenuHeader from "../MenuHeader.vue";
import {mapState} from "vuex";
import Alert from "../Alert.vue";
import AlertField from "../AlertField.vue";

export default {

    components: {
        AlertField,
        MenuHeader, Alert,
    },

    data(){

        return {
            user:{
                avatar: '',
                avatarErrors: {},
            }
        }
    },

    methods:{
        avatarInputChanged(e){
            this.user.avatarErrors = {};

            const fd = new FormData();
            const fileName = e.target.files[0];
            fd.append('avatar', fileName);

            this.$store.dispatch('setUserAvatar', fd)
                .then(e => {
                    if (e.response?.status === 422){
                        this.user.avatarErrors = e.response.data.errors;
                    }
                })
        },

        changeAvatar(){
            //console.log(this.$refs);
            //console.log(this.$refs.avatar_ref);
            this.$refs.avatar_ref.click();
        },

    },
    computed:{
        ...mapState({
            userStoredAvatar: state => state.user.data.avatar,
        })
    },

    mounted() {
        this.$store.dispatch('getUser');

        //console.log(this.$refs);
        //console.log(this.$refs.avatar_ref);
    }

}
</script>

<style scoped>

</style>
