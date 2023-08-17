<template>
    <div class="content">
        <!-- <ul><li v-for="(car, index) in showme" v-bind:key="index">{{car}}</li></ul> -->
        <div class="carousel-wrapper">
            <carousel-3d :width="300" :height="200" :disable3d="true" :space="310">
                <slide v-for="(model, index) in modellist" v-bind:key="index" :index="index" >
                    <figure v-on:mouseup="showModels(model.model_id)">
                        <img v-if="model.image == null && model.item_type_name == 'Cars' && model.sp_account !== 'no_sp_account'" :src="'https://manager.spincar.com/web-preview/walkaround-thumb/'+model.sp_account+'/'+model.vim.toLowerCase()+'/md'">
                        <img v-if="model.image == null && model.item_type_name == 'Cars' && model.sp_account == 'no_sp_account' && model.newimage !== ''" :src="model.newimage">
                        <img v-if="model.image !== null && model.item_type_name == 'Cars'" :src="model.image">
                        <img v-if="model.item_type_name !== 'Cars' && model.car_image !== ''" :src="model.car_image">
                        <img v-if="model.item_type_name !== 'Cars' && model.image !== ''" :src="model.image">
                        <!-- <figcaption>
                            {{item.name}}
                        </figcaption> -->
                    </figure>
                </slide>
            </carousel-3d>
            <div class="show-grid">
                <ul class="show-links">
                    <li v-for="(model, index) in modellist" v-bind:key="index">
                    <figure v-on:mouseup="showModels(model.model_id)">
                        <img v-if="model.image == null && model.item_type_name == 'Cars' && model.sp_account !== 'no_sp_account'" :src="'https://manager.spincar.com/web-preview/walkaround-thumb/'+model.sp_account+'/'+model.vim.toLowerCase()+'/md'">
                        <img v-if="model.image == null && model.item_type_name == 'Cars' && model.sp_account == 'no_sp_account' && model.newimage !== ''" :src="model.newimage">
                        <img v-if="model.image !== null && model.item_type_name == 'Cars'" :src="model.image">
                        <img v-if="model.item_type_name !== 'Cars' && model.car_image !== ''" :src="model.car_image">
                        <img v-if="model.item_type_name !== 'Cars' && model.image !== ''" :src="model.image">
                            <figcaption>
                                {{model.model_name}}
                            </figcaption>
                        </figure>
                    </li>
                </ul>
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
    padding: 5px;
    font-size:12px;
}
.right-content {
    background:rgb(12, 177, 75);
    color:#fff;
    padding: 5px;
}
.main_media iframe {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
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
            modellist: Array,
            baselink: String,
        },
        components: {
            Carousel3d,
            Slide
        },
        data() {
            return {
            }
        },
        methods: {
            showModels(model_id) {
                // Disabled link to variants for now
                // window.location.href = this.baselink + '/variants/' + model_id;
                window.location.href = this.baselink + '/model/' + model_id + '/items';
            }
        },
        mounted() {
        }
    }
</script>
