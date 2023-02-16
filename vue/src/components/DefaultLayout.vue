<template>
    <div :class="dark ? 'dark' : 'light'">
        <div class="bg-white dark:bg-gray-900 flex flex-col h-screen justify-between" >
            <navbar/>

            <main class="mb-auto dark:bg-gray-900">
                <RouterView></RouterView>
            </main>

            <mg-notification/>

            <mg-footer/>
        </div>
    </div>
</template>

<script>
import Navbar from "./nav/Navbar.vue";
import MgFooter from "./footer/MgFooter.vue";
import MgNotification from "./MgNotification.vue"
import {mapState} from "vuex";

export default {
    components: {
        Navbar, MgFooter, MgNotification,
    },
    provide(){
        return {
            //tagModalVisible: this.tagModalVisible,
            //tagModalVisible: computed(() => this.tagModalVisible),
        }
    },
    data(){
        return {
        }
    },
    methods:{
        toggleThemeColor(){
            if (sessionStorage.getItem('darkMode') === '0'){
                sessionStorage.setItem('darkMode', '1');
                this.darkMode = 1;
            }else if (sessionStorage.getItem('darkMode') === '1'){
                sessionStorage.setItem('darkMode', '0');
                this.darkMode = 0;
            }
        },
    },
    computed: {
        ...mapState({
            'darkMode' : state => state.darkMode,
        }),
        dark(){
            return this.darkMode;
        },
        darkIcon(){
            return Boolean(!this.darkMode);
        },
    },

    mounted() {
    }
}

</script>
