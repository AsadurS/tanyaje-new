<template>
    <div class="showme-child show-makes content">
        <!-- <ul><li v-for="(car, index) in showme" v-bind:key="index">{{car}}</li></ul> -->
        <div class="carousel-wrapper">
            <div class="show-grid">
                <ul class="show-links">
                    <li v-for="(make, index) in makelist" v-bind:key="index">
                    <figure v-on:mouseup="showItems(make)">
                        <img v-if="make.image" :src="make.image">
                        <figcaption>
                            {{make.make_name}}
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
            makelist: Array,
            baselink: String,
            template:Array
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
            showItems(make) {
                window.location.href = this.baselink + '/model/' + make.make_id;
            }
        },
        mounted() {
        }
    }
</script>
