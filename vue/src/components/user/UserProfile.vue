<template>
    <menu-header title="Профиль пользователя"></menu-header>

    <div class="dark:bg-gray-900 dark:text-white">
        <div class="evento-view max-w-7xl mx-auto py-6 sm:px-6 px-3 lg:px-8  ">
            <!-- Replace with your content -->
            <div class="py-2 md:py-5 rounded-md">

                <div class="w-full sm:w-3/12 mt-2 relative">

                    <div class="mt-3 flex items-center">
                        <div class="text-xl">Загрузка аватара</div>
                        <svg v-if="userAvatarLoadingStatus" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <form @submit.prevent="setAvatar">
                        <div v-if="!userAvatarLoadingStatus" class="mt-3">
                            <div class="flex items-center">
                                <div
                                    v-if="userStoredAvatar"
                                    class=" w-full"
                                >

                                        <a :href="userStoredAvatar">
                                            <img
                                                :src="userStoredAvatar"
                                                class="rounded-xl w-full"
                                                @mouseover="avatarMouseOver"
                                                @mouseleave="avatarMouseLeave"
                                            >
                                        </a>

                                        <div class="img-actions absolute bottom-1 right-1 flex">
                                            <pencil-icon
                                                :class="[this.avatarImageHiddenClass,
                                            'bg-gray-500', 'rounded-md', 'opacity-60',
                                            'cursor-pointer', 'transition-all delay-100',
                                        ]"
                                                @click="changeAvatar"
                                            />
                                            <close-icon
                                                ref="avatarCloseIcon"
                                                :class="[this.avatarImageHiddenClass,
                                            'ml-1', 'bg-gray-500', 'rounded-md', 'opacity-60',
                                            'cursor-pointer', 'transition-all delay-1000',
                                        ]"
                                                @click="delUserAvatar"
                                            />
                                        </div>

                                </div>
                                <div v-show="!userStoredAvatar" class="ml-3">
                                    <label class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="w-8 h-8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        <div class="ml-1">
                                            Установить <br> аватар
                                        </div>
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
import CloseIcon from "../icons/CloseIcon.vue";
import PencilIcon from "../icons/PencilIcon.vue";

export default {

    components: {
        AlertField, MenuHeader, Alert, CloseIcon, PencilIcon,
    },

    data(){

        return {
            user:{
                avatar: '',
                avatarErrors: {},
            },
            avatarImageHiddenClass: '', // hidden
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
            this.$refs.avatar_ref.click();
        },

        delUserAvatar(){
            if (! confirm('Действительно удалить аватар? Дествие невозможно отменить!')) {
                return
            }

            this.$store.dispatch('delUserAvatar');
                // .then(e => {
                //     if (e.data.success){
                //         this.user.avatarErrors = e.response.data.errors;
                //     }
                // })
        },

        avatarMouseOver(){
            //this.avatarImageHiddenClass = '';
        },
        avatarMouseLeave(){
            //this.avatarImageHiddenClass = 'hidden';
        },
    },
    computed:{
        ...mapState({
            'userStoredAvatar': state => state.user.data.full_avatar,
            'userAvatarLoadingStatus': state => state.avatarLoading,
        })
    },

    mounted() {
        this.$store.dispatch('getUser');
    }

}
</script>

<style scoped>

</style>
