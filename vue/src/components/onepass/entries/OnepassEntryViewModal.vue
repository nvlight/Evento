<template>
    <div class="font-sans1">
        <h3 class=" text-2xl">Entry view modal</h3>

        <div class="mt-2">
            <template
                v-for="(itemValue, key) in viewItem"
            >
                <div v-if="itemValue"
                    class="flex justify-between"
                >
                    <template v-if="key == 'note'">
                        <mg-textarea v-model="note" class="w-full">note</mg-textarea>
                    </template>
                    <template v-else>
                        <div>{{ key }}: </div>
                        <div class="font-semibold">{{ itemValue }}</div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {

    data(){
        return {
            note: '',
        }
    },

    computed:{
        ...mapGetters({
            viewItem: "onepassEntry/getViewItem",
        }),
    },

    mounted(){
        this.note = this.viewItem.note;
    }
}
</script>

<style lang="scss" scoped>

</style>
