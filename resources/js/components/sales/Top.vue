<template>
    <div class="top">
        test
    </div>
</template>
<style scoped>
.top-bar img {
    width:100%;
}
</style>
<script>
    export default {
        props:{
            image: String,
        },
        data() {
            return {
                
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
