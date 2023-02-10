<template>
    <mg-button
        class="hover:text-indigo-100"
        @click="deleteEvents"
    >
        <slot></slot>
    </mg-button>
</template>

<script>
export default {

    methods:{
        deleteEvents(){
            if (!confirm('Действительно удалить выделенные элементы?')) return;

            this.$store.dispatch('evento/delItemsByIds')
                .then((response) => {
                    console.log('response');
                })
                .catch( (error) => {
                    console.log('error');
                })
        },
    }
}
</script>

<style scoped>

</style>
