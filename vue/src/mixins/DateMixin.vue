<script>
export default {
    name: 'date-mixin',
    computed:{
        getFormattedCurrentDate(){
            const cd = this.getCurrentDateObject;
            return [cd.year, cd.month, cd.day,].join('-');
        },
        getCurrentDateObject(){
            const dt = new Date();
            let year = dt.getFullYear();
            let day = dt.getDate();
            day = day < 10 ? '0' +  day : day;
            let month = dt.getMonth();
            month = month < 10 ? '0' + ( month + 1) : month + 1;
            return {year, month, day};
        },
    },
    created() {
    }
}
</script>

<style scoped>

</style>
