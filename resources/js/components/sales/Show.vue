<template>
    <div class="content">
        <!-- <ul><li v-for="(car, index) in showme" v-bind:key="index">{{car}}</li></ul> -->
        <div class="carousel-wrapper" v-if="screen == 'listing'">
            <carousel-3d :width="300" :height="200" :disable3d="true" :space="310">
                <slide v-for="(model, index) in showme" v-bind:key="index" :index="index" >
                    <figure v-on:mouseup="showModels(model)">
                        <img :src="'https://manager.spincar.com/web-preview/walkaround-thumb/tanyaje/'+model.items[0].vim.toLowerCase()+'/md'">
                        <!-- <figcaption>
                            {{item.name}}
                        </figcaption> -->
                    </figure>
                </slide>
            </carousel-3d>
            <div class="show-grid" v-if="screen == 'listing'">
                <ul class="show-links">
                    <li v-for="(model, index) in showme" v-bind:key="index">
                    <figure v-on:mouseup="showModels(model)">
                        <img :src="'https://manager.spincar.com/web-preview/walkaround-thumb/tanyaje/'+model.items[0].vim.toLowerCase()+'/md'">
                            <figcaption>
                                {{model.title}}
                            </figcaption>
                        </figure>
                    </li>
                </ul>
            </div>
        </div>
        <div class="show-grid" v-if="screen == 'listingModel'">
            <ul class="show-links">
                <li v-for="(item, index) in itemSelected.items" v-bind:key="index">
                    <figure v-on:mouseup="openDetail(item)">
                        <img :src="'https://manager.spincar.com/web-preview/walkaround-thumb/tanyaje/'+item.vim.toLowerCase()+'/md'">
                        <figcaption>
                            {{item.title}}
                        </figcaption>
                    </figure>
                </li>
            </ul>
        </div>
        <div class="show-detail" v-if="screen == 'detail'">
            <div class="main_media" v-if="modelSelected.vim">
                <!-- <div id="spin-car-section" class="spin-car-section"></div> -->
                <iframe :src="'https://spins.spincar.com/tanyaje/'+modelSelected.vim.toLowerCase()" width="100%" ></iframe>
            </div>
            <div class="thumbnails">
                <div v-for="(thumb,index) in modelSelected.images" v-bind:key="index">
                    <img :src="thumb">
                </div>
            </div>
            <div class="detail-content">
                <div class="row">
                    <div class="col-7" style="padding-right:0">
                        <div class="left-content">
                            <div class="description" v-html="modelSelected.description"></div>
                            <ul class="icon-list">
                                <li>
                                    <img src="/new/images/sales/icon_color.jpg">
                                    <span>{{modelSelected.color}}</span>
                                </li>
                                <li>
                                    <img src="/new/images/sales/icon_cartype.jpg">
                                    <span>{{modelSelected.car_type}}</span>
                                </li>
                                <li>
                                    <img src="/new/images/sales/icon_mileage.jpg">
                                    <span>{{modelSelected.mileage}}</span>
                                </li>
                                <li>
                                    <img src="/new/images/sales/icon_enginecapacity.jpg">
                                    <span>{{modelSelected.engine_capacity}}</span>
                                </li>
                                <li>
                                    <img src="/new/images/sales/icon_fueltype.jpg">
                                    <span>{{modelSelected.fuel_type}}</span>
                                </li>
                                <li>
                                    <img src="/new/images/sales/icon_calendar.jpg">
                                    <span>{{modelSelected.year}}</span>
                                </li>
                                <li>
                                    <img src="/new/images/sales/icon_seat.jpg">
                                    <span>{{modelSelected.seats}}</span>
                                </li>
                                <li>
                                    <img src="/new/images/sales/icon_transmission.jpg">
                                    <span>{{modelSelected.transmission}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="right-content">
                            <h1>{{modelSelected.title}}</h1>
                            <p>RM{{modelSelected.price}}</p>
                            <hr />
                            <ul class="icon-list">
                                <li><a href="#"><img src="/new/images/sales/icon_showbtn1.jpg"></a></li>
                                <li><a href="#"><img src="/new/images/sales/icon_showbtn2.jpg"></a></li>
                                <li><a href="#"><img src="/new/images/sales/icon_showbtn3.jpg"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.content {
    width: 100%;
}
.content img {
    margin:0 auto;
    display:block;
    width: 100%;
}
.show-links {
    padding-left:0;
    list-style: none;
    display:flex;
    flex-wrap:wrap;
}
.show-links li {
    width:50%;
    padding:15px;
    text-align:center;
}
.show-links a {
    cursor:pointer;
}
.carousel-3d-container figcaption {
    position: absolute;
    background-color: rgba(0, 0, 0, 0.5);
    color: #fff;
    bottom: 0;
    position: absolute;
    bottom: 0;
    padding: 15px;
    font-size: 12px;
    min-width: 100%;
    box-sizing: border-box;
}
.main_media {
    background: #fff;
    width: 100%;
    /* height: 200px; */
    padding-top: 75.53%;
    position: relative;
    /* height: 395px; */
    height: 0;
}
.thumbnails img {
    width:36px;
    height:36px;
    object-fit: cover;
    margin:5px;
}
.detail-content {
    padding:0 15px;
    margin-top:15px;
}
.detail-content h1 {
    font-size:18px;
}
.left-content {
    background:#fff;
    padding: 8px;
    font-size:12px;
    padding: 8px;
}
.right-content {
    background:#F37020;
    color:#fff;
    padding: 8px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 1px;
}
.main_media iframe {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
}
.icon-list {
    list-style: none;
    padding-left: 0;
    display: flex;
    flex-wrap: wrap;
}
.right-content .icon-list li {
    width: 50%;
    padding:0 5px;
}
.left-content .icon-list li {
    width: 50%;
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom:10px;
}
.left-content .icon-list li img {
    width: 30px;
    margin-left: 0;
    margin-right: 5px;
}
.left-content .icon-list li span {
    width: 100px
}
</style>
<style>
/* .carousel-3d-container {
    padding:0 40px;
} */
</style>
<script>
import { Carousel3d, Slide } from 'vue-carousel-3d';
    export default {
        props:{
            items: Array,
            image: String,
            // showme: Object
        },
        components: {
            Carousel3d,
            Slide
        },
        data() {
            return {
                showme: 
                [
                    {
                    "title": "AXIA",
                    "items": [{
                    "car_id": 35495,
                    "vim": "AXIASESILVER",
                    "stock_number": null,
                    "make_id": 54,
                    "model_id": 476,
                    "status": 1,
                    "image": null,
                    "pdf": null,
                    "price": "37515.00",
                    "state_id": 2,
                    "city_id": 4,
                    "merchant_id": 776,
                    "user_id": 562,
                    "created_at": "2021-06-02 06:01:52",
                    "updated_at": "2021-08-18 16:50:59",
                    "type_id": 6,
                    "html_editor": null,
                    "year_make": 2021,
                    "title": "PERODUA AXIA 1.0 SE (AT)",
                    "fuel_type": "petrol",
                    "features": "",
                    "seats": 5,
                    "transmission": "automatic",
                    "mileage": "0",
                    "color": "Glittering Silver",
                    "engine_capacity": 998,
                    "random_code": 45784,
                    "is_sold": 0,
                    "is_airtime_hide": 0,
                    "is_publish": 1,
                    "sp_account": "tanyaje",
                    "make_name": "Perodua",
                    "is_feature": null,
                    "model_name": "AXIA",
                    "model_make_id": 54,
                    "id": 776,
                    "merchant_name": "PERODUA MALAYSIA",
                    "slug": "perodua-malaysia",
                    "merchant_phone_no": "-",
                    "is_default": 1,
                    "merchant_email": "-",
                    "merchant_payment": "0.00",
                    "profile_img": null,
                    "sa_position": null,
                    "whatsapp_url": null,
                    "whatsapp_default_message": null,
                    "waze_url": "https://waze.com/ul?q=%20%20%20",
                    "verified": null,
                    "verified_since": null,
                    "verified_until": null,
                    "address": null,
                    "generate_qr": null,
                    "display_qr": null,
                    "contactMe": null,
                    "showMe": null,
                    "askMe": null,
                    "keepMe": null,
                    "hits": 0,
                    "stat_call": 0,
                    "stat_whatsapp": 0,
                    "stat_showroom_location": 0,
                    "stat_brochure": 0,
                    "stat_price_list": 0,
                    "stat_promotion": 0,
                    "stat_conversion_promotion_redemption": 0,
                    "stat_conversion": 0,
                    "landingpage_version": 0,
                    "landingpage_images": null,
                    "sa_profile_url": null
                    },
                    {
                    "car_id": 35496,
                    "vim": "AXIASTYLESILVER",
                    "stock_number": null,
                    "make_id": 54,
                    "model_id": 476,
                    "status": 1,
                    "image": null,
                    "pdf": null,
                    "price": "37515.00",
                    "state_id": 2,
                    "city_id": 4,
                    "merchant_id": 776,
                    "user_id": 562,
                    "created_at": "2021-06-02 06:01:52",
                    "updated_at": "2021-08-18 16:50:59",
                    "type_id": 6,
                    "html_editor": null,
                    "year_make": 2021,
                    "title": "PERODUA AXIA 1.0 STYLE (AT)",
                    "fuel_type": "petrol",
                    "features": "",
                    "seats": 5,
                    "transmission": "automatic",
                    "mileage": "0",
                    "color": "Glittering Silver",
                    "engine_capacity": 998,
                    "random_code": 38201,
                    "is_sold": 0,
                    "is_airtime_hide": 0,
                    "is_publish": 1,
                    "sp_account": "tanyaje",
                    "make_name": "Perodua",
                    "is_feature": null,
                    "model_name": "AXIA",
                    "model_make_id": 54,
                    "id": 776,
                    "merchant_name": "PERODUA MALAYSIA",
                    "slug": "perodua-malaysia",
                    "merchant_phone_no": "-",
                    "is_default": 1,
                    "merchant_email": "-",
                    "merchant_payment": "0.00",
                    "profile_img": null,
                    "sa_position": null,
                    "whatsapp_url": null,
                    "whatsapp_default_message": null,
                    "waze_url": "https://waze.com/ul?q=%20%20%20",
                    "verified": null,
                    "verified_since": null,
                    "verified_until": null,
                    "address": null,
                    "generate_qr": null,
                    "display_qr": null,
                    "contactMe": null,
                    "showMe": null,
                    "askMe": null,
                    "keepMe": null,
                    "hits": 0,
                    "stat_call": 0,
                    "stat_whatsapp": 0,
                    "stat_showroom_location": 0,
                    "stat_brochure": 0,
                    "stat_price_list": 0,
                    "stat_promotion": 0,
                    "stat_conversion_promotion_redemption": 0,
                    "stat_conversion": 0,
                    "landingpage_version": 0,
                    "landingpage_images": null,
                    "sa_profile_url": null
                    }]
                }],
                screen: 'listing',
                itemSelected: {},
                modelSelected: {},
            }
        },
        computed:{
            showmeJson:function() {
                return this.showme;
            }
        },
        methods: {
            goToLink(type, link) {
                if (type == 'external') {
                    window.open(link, "_blank");
                } else {
                    this.linkSelected = type;
                }
            },
            showModels(item) {
                this.itemSelected = item;
                this.screen = 'listingModel';
                // EventBus.$emit('event-name', data);
            },
            openDetail(model) {
                this.modelSelected = model;
                this.screen = 'detail';
            },
            returnBack() {
                if (this.screen == 'listingModel') {
                    this.screen = 'listing';
                    this.itemSelected = null;
                }
                if (this.screen == 'detail') {
                    this.showModels();
                    this.modelSelected = null;
                }
            }
        },
        mounted() {
            // EventBus.$on('backinShow', () => {
            //      this.returnBack()
            // });
        }
    }
</script>
