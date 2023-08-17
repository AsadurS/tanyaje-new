<template>
    <div class="content">
        <div class="show-grid">
            <div class="pagination-label text-right">
                All 
                {{currentPageCount}} 
                <span v-if="eachPage*currentPage < totalItems">- {{eachPage*currentPage}}</span> 
                <span v-if="eachPage*currentPage > totalItems">- {{totalItems}}</span> 
                of {{totalItems}}
            </div>
            <ul class="show-links">
                <li v-for="(item, index) in currentPageItems" v-bind:key="index" :class="item ? 'visible' : ''">
                    <figure v-on:mouseup="openDetail(item.car_id)" v-if="item">
                        <img v-if="item.sp_account !== 'no_sp_account'" :src="'https://manager.spincar.com/web-preview/walkaround-thumb/'+item.sp_account+'/'+item.vim.toLowerCase()+'/md'">
                        <img v-if="item.sp_account == 'no_sp_account'" :src="item.image">
                        <img class="label" src="/new/images/sales/icon360.png" v-if="item.sp_account">
                        <span v-if="item.status" class="top_left_label" :style="getLabelBg(item.status)">{{getLabelName(item.status)}}</span>
                    </figure>
                    <div class="itemListText" v-if="item">
                        <span class="updated" v-if="item.updated_at">{{item.updated_at.split(" ")[0]}}</span>
                        <h4><span class="yearMake" :style="'background:'+ template[0].colour2">{{item.year_make}}</span> {{item.title}}</h4>
                        <button @click="openDetail(item.car_id)">MORE INFO</button>
                        <div class="carColor"><img src="/new/images/sales/icon_color.jpg"> {{item.color}}</div>
                    </div>
                </li>
            </ul>
            <div class="pagination">
                <button :disabled="currentPage == 1" @click="pagination(1)">&#171;</button>
                <button v-for="(page,index) in paginationVisible" v-bind:key="index" 
                @click="pagination(page)"  
                :disabled="page == currentPage"
                :class="(page == currentPage) ? 'active' : ''" >
                    {{page}}
                </button>
                <button :disabled="currentPage == lastPage" @click="pagination(lastPage)">&#187;</button>
            </div>
            <div class="text-align">
                <a class="button" v-if="viewall" :href="baselink" :style="'background:'+ template[0].colour2">View All</a>
            </div>
        </div>
    </div>
</template>
<style scoped>
.text-align {
    text-align: center;
    margin-bottom: 20px;
}
.text-right {
    text-align: right;
    padding-right: 5px;
}
.button {
    color:#fff;
    background:#F0702F;
    text-decoration: none;
    padding: 4px 30px;
    text-transform: uppercase;
}
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
.show-links li.visible {
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
.content {
    padding: 0 5px;
}
.show-links li.visible {
    padding: 0 10px!important;
    margin-bottom: 15px;
}
.show-links li figure {
    margin-bottom: 0;
    position: relative;
}
.show-links li figure .label {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 40px;
}
.itemListText {
    position: relative;
    padding: 5px;
}
.itemListText {
    border-bottom-left-radius: 6px;
    border-bottom-right-radius: 6px;
    background:#fff;
    padding-bottom: 20px
}
.itemListText .updated {
    font-size: 10px;
    text-align: right;
    display: block;
    color:#666;
}
.itemListText button {
    border: 0;
    background: none;
    padding: 0;
    text-decoration: underline;
    font-size: 12px;
    margin-left:-15px;
}
.itemListText h4 {
    margin-top: 5px;
    font-size:14px;
    font-weight:700;
    display: flex;
    align-items: flex-start;
    text-align: left;
    margin-bottom: 0px;
}
.itemListText h4 .yearMake {
    color:#fff;
    padding: 2px;
    margin-right:5px;
}
.itemListText .carColor {
    display: flex;
    align-items: center;
    font-size: 12px;
    padding-left:40px;
    margin-top: 0px;
    text-align: left;
}
.itemListText .carColor img {
    width:20px;
    margin-right: 5px;
    margin-left: 0;
}
.pagination {
    text-align: center;
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}
.pagination button {
    border: 0;
    background: #959799;
    margin: 0 2px;
}
.pagination button.active {
    background: #ED1C24;
    color:#fff;
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
            itemlist: Array,
            baselink: String,
            viewall: Boolean,
            template: Array,
        },
        components: {
            Carousel3d,
            Slide
        },
        data() {
            return {
                eachPage:12,
                currentPage: 1,
            }
        },
        computed:{
            totalItems: function() {
                return this.itemlist.length;
            },
            totalPages: function() {
                return Math.ceil(this.itemlist.length / this.eachPage);
            },
            lastPage: function() {
                return Math.ceil(this.itemlist.length / this.eachPage);
            },
            currentPageCount: function() {
                if (this.itemlist.length > this.eachPage) {
                    return this.eachPage * this.currentPage - this.eachPage + 1
                } else {
                    return this.itemlist.length;
                }
            },
            paginationVisible: function() {
                if (this.totalPages > 0) {
                    var newArray = [];
                    if (this.currentPage-2 > 0) {
                        newArray.push(this.currentPage-2)
                    }
                    if (this.currentPage-1 > 0) {
                        newArray.push(this.currentPage-1)
                    }
                    newArray.push(this.currentPage)
                    if (this.currentPage+1 <= this.lastPage) {
                        newArray.push(this.currentPage+1)
                    }
                    if (this.currentPage+2 <= this.lastPage) {
                        newArray.push(this.currentPage+2)
                    }
                    return newArray;
                }
            },
            currentPageItems: function() {
                var newArray = [];
                if (this.itemlist.length > this.eachPage) {
                    for (var i = 0; i < this.eachPage; i++) {
                        newArray.push(this.itemlist[this.currentPage*this.eachPage-i-1])
                    }
                } else {
                    for (var i = 0; i < this.itemlist.length; i++) {
                        newArray.push(this.itemlist[i])
                    }
                }
                return newArray;
            },
        },
        methods: {
            openDetail(item_id) {
                // console.log(this.baselink);
                window.location.href = this.baselink + '/item_detail/' + item_id;
            },
            pagination(page) {
                this.currentPage = page;
            },
            getLabelBg(status){
                if (status == 0) 
                    return 'background: #ED1B24; color:#fff'
                if (status == 1) 
                    return 'background: #F37022; color:#fff'
                if (status == 2) 
                    return 'background: #FDCB0A; color:#000'
                if (status == 3) 
                    return 'background: #000000; color:#fff'
            },
            getLabelName(status) {
                if (status == 0) 
                    return 'New Arrivals'
                if (status == 1) 
                    return 'New'
                if (status == 2) 
                    return 'Used'
                if (status == 3) 
                    return 'Recond'
                if (status == 4) 
                    return 'Sold'
            }
        },
        mounted() {
        }
    }
</script>
