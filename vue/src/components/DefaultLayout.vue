<template>
    <div class="min-h-full">
        <navbar/>
        <eventos/>
    </div>
    <footer class="max-w-7xl m-auto">
        <div class="font-semibold">&copy; 2077 Here you go</div>
    </footer>
</template>

<script>
import Eventos from "../views/Eventos.vue";
import Navbar from "./nav/Navbar.vue";

export default {
    components: {
       Eventos, Navbar,
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
