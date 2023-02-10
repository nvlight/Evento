<template>
    <mg-button
        class="hover:text-indigo-100"
        @click="copyEvents"
    >
        <slot></slot>
    </mg-button>
</template>

<script>
export default {
    components: {

    },
    methods:{
        copyEvents(){
            this.$store.dispatch('evento/copyItems')
                .then((response) => {
                    //console.log('response');
                })
                .catch( (error) => {
                    //console.log('error');
                })
        }
    }
}
</script>

<style scoped>

</style>
