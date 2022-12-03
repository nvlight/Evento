<template>
    <div class="dialog"
        v-if="show" @click="hideDialog"
    >
        <div class="dialog__content relative" :class="dialog_content_classes"
             @click.stop
        >
            <mg-close-icon-button @click="hideDialog" class="absolute right-1 top-1 border-blue-400">
            </mg-close-icon-button>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: 'mg-modal',
    components: {},

    props:{
        show: {
            type: Boolean,
            default: false,
        },
        dialog_content_classes: {
            type: String,
            default: '',
        },
    },
    data(){
        return {
        }
    },
    methods: {
        hideDialog(){
            this.$emit('update:show', false);
        },
    },
    computed:{

    },
    mounted(){
    },
}
</script>

<style scoped>
.dialog{
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.5);
    position: fixed;
    display: flex;
    z-index: 1;
}
.dialog__content{
    margin: auto;
    /*width: 100%;*/
    background: white;
    border-radius: 12px;
    min-height: 50px;
    min-width: 300px;
    padding: 20px;
    overflow-y: scroll;
}
</style>
