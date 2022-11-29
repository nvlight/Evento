<template>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Eventos
            </h1>
        </div>
    </header>
    <main>
        <mg-dialog v-model:show="tagModalVisible">
            <h3>Tags Dialog</h3>
        </mg-dialog>

        <div>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <div class="px-4 py-6 sm:px-0">

                </div>
                <!-- /End replace -->
            </div>
        </div>
    </main>
</template>

<script>
export default {
    name: "Eventos",
    components: {
    },
    data(){
        return {
            tagModalVisible: false,
        }
    },
    methods:{
        showTagModal(){
          this.tagModalVisible = true;
        },
        hideTagModal(){
            this.tagModalVisible = false;
        },
    }
}
</script>

<style scoped>
</style>
