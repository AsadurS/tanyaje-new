<template>
    <div class="content">
        <div class="show-detail">
            <div class="main_media" >
                <!-- <div id="spin-car-section" class="spin-car-section"></div> -->
                <iframe v-if="item.sp_account !== 'no_sp_account'" :src="'https://spins.spincar.com/'+item.sp_account+'/'+item.vim.toLowerCase()" width="100%" ></iframe>
                <img v-if="item.sp_account == 'no_sp_account'" :src="selectedImage">
            </div>
            <div class="thumbnails" v-if="item.no_spincar_img && item.no_spincar_img.length > 0">
                <div v-for="(thumb,index) in item.no_spincar_img" v-bind:key="index" @click="swapImage(thumb)">
                    <img :src="thumb">
                </div>
            </div>
            <div class="detail-content">
                <div class="row">
                    <div class="col-7" style="padding-right:0">
                        <div class="left-content">
                            <div class="description" v-html="descriptionWithBr"></div>
                            <br/>
                            <ul class="icon-list" v-if="item.item_type_name == 'Cars'">
                                <li v-if="item.color">
                                    Color: 
                                    <span>{{item.color}}</span>
                                </li>
                                <li v-if="item.type_name">
                                   Car Type: 
                                    <span>{{item.type_name}}</span>
                                </li>
                                <li v-if="item.mileage">
                                    Mileage: 
                                    <span>{{item.mileage}}</span>
                                </li>
                                <li v-if="item.engine_capacity">
                                    Engine Capacity: 
                                    <span>{{item.engine_capacity}}</span>
                                </li>
                                <li v-if="item.fuel_type">
                                    Fuel Type: 
                                    <span>{{item.fuel_type}}</span>
                                </li>
                                <li v-if="item.year_make">
                                    Year of Make: 
                                    <span>{{item.year_make}}</span>
                                </li>
                                <li v-if="item.seats">
                                    Seats: 
                                    <span>{{item.seats}}</span>
                                </li>
                                <li v-if="item.transmission">
                                    Transmission: 
                                    <span>{{item.transmission}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="right-content" :style="'background:'+ template[0].colour4">
                            <h1>{{item.title}}</h1>
                            <p class="price">RM{{item.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}}</p>
                            <hr />
                            <ul class="icon-list">
                                <li v-if="brochure">
                                    <a @click="linkTo(brochure, 'brochure', '')">
                                    <img src="/new/images/sales/icon-showme-brochure.png">
                                    </a>
                                </li>
                                <li v-if="pricelist">
                                    <a @click="linkTo(pricelist, 'pricelist', '')">
                                    <img src="/new/images/sales/icon-showme-pricelist.png">
                                    </a>
                                </li>
                                <!-- <li><a href="#"><img src="/new/images/sales/icon_showbtn3.jpg"></a></li> -->
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
    background: url('/new/images/sales/loading.svg') center center no-repeat #fff;
}
.thumbnails {
    display:flex;
    overflow-x: auto;
}
.thumbnails img {
    width:64px;
    height:64px;
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
    padding: 12px;
    font-size:12px;
    border-radius: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
.right-content {
    color:#fff;
    padding: 10px;
    border-radius: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
.main_media > iframe, .main_media > img {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
}
.icon-list {
    /* list-style: none; */
    padding-left: 0;
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 0;
    padding-left:20px;
}
.right-content .icon-list {
    list-style: none;
    padding-left: 0;
}
.right-content hr:not([size]) {
    height:2px;
} 
.right-content .icon-list li {
    width: 50%;
    padding:0 5px;
}
.left-content .icon-list li {
    width: 100%;
    /* display: flex; */
    flex-direction: row;
    align-items: center;
    margin-bottom:10px;
}
.left-content .icon-list li img {
    width: 20px;
    margin-left: 0;
    margin-right: 5px;
}
.left-content .icon-list li span {
    width: 100px
}
.price {
    font-weight:600;
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
            image: String,
            item: Object,
            orgbrochure: String,
            orgpricelist: String,
            template: Array,
            askme: Array,
        },
        components: {
            Carousel3d,
            Slide
        },
        data() {
            return {
                selectedImage: '',
            }
        },
        computed:{
            pricelist: function() {
                if (this.askme) {
                    if (this.askme.filter(doc => doc.link == 'Pricelist' && doc.name.toUpperCase() == this.item.model_name).length > 0) {
                        if (!this.askme.filter(doc => doc.link == 'Pricelist' && doc.name.toUpperCase() == this.item.model_name)[0].attachment.includes('/images/')) {
                            return '/images/organisation/documents/'+this.askme.filter(doc => doc.link == 'Pricelist' && doc.name.toUpperCase() == this.item.model_name)[0].attachment
                        } else {
                            return this.askme.filter(doc => doc.link == 'Pricelist' && doc.name.toUpperCase() == this.item.model_name)[0].attachment;
                        }
                    }
                }
            },
            brochure: function() {
                if (this.askme) {
                    if (this.askme.filter(doc => doc.link == 'Brochure' && doc.name.toUpperCase() == this.item.model_name).length > 0) {
                        if (!this.askme.filter(doc => doc.link == 'Brochure' && doc.name.toUpperCase() == this.item.model_name)[0].attachment.includes('/images/')) {
                            return '/images/organisation/documents/'+this.askme.filter(doc => doc.link == 'Brochure' && doc.name.toUpperCase() == this.item.model_name)[0].attachment;
                        } else {
                            return this.askme.filter(doc => doc.link == 'Brochure' && doc.name.toUpperCase() == this.item.model_name)[0].attachment;
                        }
                    }
                }
            },
            descriptionWithBr: function() {
                if (this.item.html_editor) {
                    const description = this.item.html_editor;
                    return description.replace(/\r*\n/g, '<br>')
                }
            }
        },
        methods: {
            linkTo(link, event, tab){
                $.ajax({
                    url: "/eventtracker",
                    type:"POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        "event": event
                    },
                    success:function(response){
                        window.location.href  = link;
                        // if (tab == 'newtab') {
                        //     window.open(link, "_blank");
                        // } else {
                        //     window.location.href  = link;
                        // }
                    },
                    async: false
                });
                // 
            },
            swapImage(image) {
                this.selectedImage = image;
            },
        },
        mounted() {
            this.selectedImage = this.item.image;
        }
    }
</script>
