<template>
    <div class="content">
        <sales-landing 
            v-if="screen == 'landing'"
            :content="content.landing.content"
        ></sales-landing> 
        <sales-show 
            v-if="screen == 'showme'"
            :items="[
                { name: 'Car 1', image: 'https://via.placeholder.com/360x360', description: '<div>hi</div>'},
                { name: 'Car 2', image: 'https://via.placeholder.com/360x360', description: '<div>hi</div>'},
                { name: 'Car 3', image: 'https://via.placeholder.com/360x360', description: '<div>hi</div>'},
            ]"
            :content="content.showme.content"
        ></sales-show>
        <sales-ask 
            v-if="screen == 'askme'"
            :links="[
                { type: 'brochures', name: 'Brochure', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/call-now.svg',},
                { type: 'priceLists', name: 'Price List', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/call-now.svg'},
                { type: 'promotions', name: 'Promotions', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/call-now.svg'},
                { type: 'videos', name: 'Video', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/call-now.svg'},
                { type: 'external', name: 'Calculator', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/call-now.svg'},
                { type: 'external', name: 'Price Checker', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/call-now.svg'},
            ]"
            :brochures="[]"
            :promotions="[]"
            :videos="[]"
            :priceLists="[]"
            :content="content.askme.content"
        ></sales-ask>
        <sales-keep 
            v-if="screen == 'keepme'"
            :links="[
                { name: 'Brochure', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/keep-banner-placeholder.png',},
                { name: 'Price List', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/keep-banner-placeholder.png'},
                { name: 'Promotions', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/keep-banner-placeholder.png'},
                { name: 'Video', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/keep-banner-placeholder.png'},
                { name: 'Brochure', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/keep-banner-placeholder.png'},
                { name: 'Calculator', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/keep-banner-placeholder.png'},
                { name: 'Price Checker', url: 'https://www.google.com', icon: 'http://localhost:9999/new/images/sales/keep-banner-placeholder.png'},
            ]"
            :content="content.keepme.content"
        ></sales-keep>
    </div>
</template>
<style scoped>
</style>
<script>
    export default {
        props:{
            content: Object
        },
        data() {
            return {
                screen: 'landing',
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
