require('./bootstrap');

window.Vue = require('vue');
window.EventBus = new Vue();


Vue.component('dragable-component', require('./components/DragableComponent.vue').default);
Vue.component('theme-component', require('./components/ThemeComponent.vue').default);
Vue.component('banner-component', require('./components/BannerComponent.vue').default);
Vue.component('banner-one-component', require('./components/banners/bannerOneComponent.vue').default);
Vue.component('banner-two-component', require('./components/banners/bannertwoComponent.vue').default);
Vue.component('banner-three-component', require('./components/banners/bannerthreeComponent.vue').default);
Vue.component('banner-four-component', require('./components/banners/bannerfourComponent.vue').default);
Vue.component('banner-five-component', require('./components/banners/bannerfiveComponent.vue').default);
Vue.component('banner-six-component', require('./components/banners/bannersixComponent.vue').default);
Vue.component('banner-seven-component', require('./components/banners/bannersevenComponent.vue').default);
Vue.component('banner-eight-component', require('./components/banners/bannereightComponent.vue').default);
Vue.component('banner-nine-component', require('./components/banners/bannernineComponent.vue').default);
Vue.component('banner-ten-component', require('./components/banners/bannertenComponent.vue').default);
Vue.component('banner-eleven-component', require('./components/banners/bannerelevenComponent.vue').default);
Vue.component('banner-twelve-component', require('./components/banners/bannertwelveComponent.vue').default);
Vue.component('banner-thirteen-component', require('./components/banners/bannerthirteenComponent.vue').default);
Vue.component('banner-fourteen-component', require('./components/banners/bannerfourteenComponent.vue').default);
Vue.component('banner-fifteen-component', require('./components/banners/bannerfifteenComponent.vue').default);
Vue.component('banner-sixteen-component', require('./components/banners/bannersixteenComponent.vue').default);
Vue.component('banner-seventeen-component', require('./components/banners/bannerseventeenComponent.vue').default);
Vue.component('banner-eighteen-component', require('./components/banners/bannereighteenComponent.vue').default);
Vue.component('banner-nineteen-component', require('./components/banners/bannernineteenComponent.vue').default);
Vue.component('carousal-banner-component', require('./components/banners/carousalBannerComponent.vue').default);
Vue.component('ad-banner1-component', require('./components/banners/adBanner1Component.vue').default);
Vue.component('ad-banner2-component', require('./components/banners/adBanner2Component.vue').default);
Vue.component('carousel1-slider-component', require('./components/sliders/carousal1Component.vue').default);
Vue.component('carousel2-slider-component', require('./components/sliders/carousal2Component.vue').default);
Vue.component('carousel3-slider-component', require('./components/sliders/carousal3Component.vue').default);
Vue.component('carousel4-slider-component', require('./components/sliders/carousal4Component.vue').default);
Vue.component('carousel5-slider-component', require('./components/sliders/carousal5Component.vue').default);
Vue.component(
    'example-component',
    require('./components/ExampleComponent.vue').default
);
// Sales WebApp General
Vue.component('sales-header', require('./components/sales/Header.vue').default);
Vue.component('sales-footer', require('./components/sales/Footer.vue').default);
Vue.component('sales-customfooter', require('./components/sales/CustomFooter.vue').default);
// Sales WebApp Pages
Vue.component('sales-landing', require('./components/sales/Landing.vue').default);
Vue.component('sales-pages', require('./components/sales/Pages.vue').default);
Vue.component('sales-ask', require('./components/sales/Ask.vue').default);
Vue.component('sales-ask-pricelist', require('./components/sales/AskPriceList.vue').default);
Vue.component('sales-ask-brochure', require('./components/sales/AskBrochure.vue').default);
Vue.component('sales-ask-extra-brochure', require('./components/sales/AskExtraBrochure.vue').default);
Vue.component('sales-ask-extra-pricelist', require('./components/sales/AskExtraPricelist.vue').default);
Vue.component('sales-keep', require('./components/sales/Keep.vue').default);
Vue.component('sales-show', require('./components/sales/Show.vue').default);
Vue.component('sales-show-makes', require('./components/sales/ShowMakes.vue').default);
Vue.component('sales-show-models', require('./components/sales/ShowModels.vue').default);
Vue.component('sales-show-variants', require('./components/sales/ShowVariants.vue').default);
Vue.component('sales-show-item-list', require('./components/sales/ShowItemList.vue').default);
Vue.component('sales-show-item-detail', require('./components/sales/ShowItemDetail.vue').default);
Vue.component('sales-verify', require('./components/sales/Verify.vue').default);
Vue.component('sales-campaign', require('./components/sales/Campaign.vue').default);
Vue.component('sales-campaign-detail', require('./components/sales/CampaignDetail.vue').default);
// Dynamic
Vue.component('testinghtml', require('./components/sales/Test.vue').default);

const app = new Vue({
    el: '#app',
    // mounted() {
    //   Echo.channel('rehan')
    //   .listen('CreateUser' , (user) =>{
    //     console.log(user.record);
    //     app.records = user.record;
    //   });
    // },

});