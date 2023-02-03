<template>
    <div
        :class="wrapper_class"
        v-if="show" @click="hideDialog"
    >
        <div
            class="dark:text-white dark:bg-gray-900"
            :class="[$style.dialog__content, 'relative' ,dialog_content_classes]"
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
            wrapper_class: [ this.$style.dialog ],
        }
    },
    methods: {
        hideDialog(){
            this.$emit('update:show', false);
        },
        transitionHandler(){
            this.wrapper_class.push(this.$style.tran);
        },
    },
    computed:{

    },
    mounted(){

    },
    watch:{
        show(nv){
            this.wrapper_class =  [ this.$style.dialog ];
            if (nv){
                setTimeout(this.transitionHandler, 0);
            }
        }
    },
}
</script>

<!--npm i --save-dev sass -->
<style lang="scss" module>
    .tran{
        transition: all ease 0.3s;
        background: rgba(0,0,0,0.5);
    }
    .dialog{
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        position: fixed;
        display: flex;
        z-index: 1;
        &__content{
            margin: auto;
            background: white;
            border-radius: 12px;
            min-height: 50px;
            min-width: 300px;
            padding: 20px;
            max-height: 80vh;
        }
    }
</style>
