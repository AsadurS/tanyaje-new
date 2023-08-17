<template>
    <div class="content">
        <!-- <div class="keep-wrapper-top" v-html="templateTopOutput"></div> -->
        <div class="keep-wrapper">
            <ul class="keep-links">
                <li v-for="(link, index) in keepme" v-bind:key="index" class="animate__animated animate__fadeIn">
                    <a target="_blank" :href="'/promotions?utm_source='+ sadata.merchant_name +'&utm_medium=promotion_button&utm_campaign=sa-landing&sid='+sadata.id">
                        <img v-if="link.main_image.includes('/images/')" :src="link.main_image">
                        <img v-if="!link.main_image.includes('/images/')" :src="'/images/promotion/'+link.main_image">
                    </a>
                </li>
            </ul>
            <div class="text-center">
                <button v-if="buttonVisibility" class="btn btn-primary" @click="loadMore" :style="'background:'+ template[0].colour2">Load More</button>
            </div>
        </div>
        <!-- <div class="keep-wrapper-bottom" v-html="templateBottomOutput"></div> -->
    </div>
</template>
<script>
    export default {
        props:{
            sadata: Object,
            image: String,
            keepme: Array,
            template: Array,
        },
        computed: {
            // loadedLinks: function() {
            //     return this.links.slice(0, this.show)
            // }
        },
        data() {
            return {
                show: 8,
            }
        },
        computed: {
            shortcode() {
                return {
                    // org_logo: '<img src="' +( this.orglogo.includes('/images/') ? this.orglogo : '/images/logo/' + this.orglogo) + '" />'
                }
            },
            templateTopOutput () {
                if(this.template[0].keepme_code) {
                    if (this.template[0].keepme_code.includes("<p>{{top}}<\/p>")) {
                        return this.template[0].keepme_code.match(/\<p>{{top}}<\/p>([^)]+)\<p>{{top_end}}<\/p>/)[1] 
                    } else {
                        return this.template[0].keepme_code.match(/\{{top}}([^)]+)\{{top_end}}/)[1] 
                    }
                }
            },
            templateBottomOutput () {
                if(this.template[0].keepme_code) {
                    if (this.template[0].keepme_code.includes("<p>{{bottom}}<\/p>")) {
                        return this.template[0].keepme_code.match(/\<p>{{bottom}}<\/p>([^)]+)\<p>{{bottom_end}}<\/p>/)[1] 
                    } else {
                        return this.template[0].keepme_code.match(/\{{bottom}}([^)]+)\{{bottom_end}}/)[1] 
                    }
                }
            },
            buttonVisibility: function() {
                if ( this.keepme.length <= this.show ) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        methods: {
            loadMore() {
                this.show = this.show + 4;
            }
        },
        mounted() {
        }
    }
</script>
