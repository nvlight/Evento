<template>
    <div class="flex flex-col h-screen justify-between">
        <navbar/>

        <main class="mb-auto">
            <RouterView></RouterView>
        </main>

        <mg-footer/>
    </div>
</template>

<script>
import Navbar from "./nav/Navbar.vue";
import MgFooter from "./footer/MgFooter.vue";

export default {
    components: {
        Navbar, MgFooter,
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
    },
    computed:{
    },
    mounted() {
    },
}

</script>
