<template>
    <div class=" animate__animated animate__fadeIn ">
        <nav class="subbottom-bar" v-if="!simplifymenu">
            <carousel-3d :width="120" :height="150" :space="200" :disable3d="true" :inverse-scaling="600">
                <slide v-for="(link, index) in links.filter(link => link.visibility == 1)" v-bind:key="index" :index="index">
                    <figure v-on:mouseup="linkTo(link.link)">
                        <img :src="link.image">
                        <figcaption v-html="link.name">
                        </figcaption>
                    </figure>
                </slide>
            </carousel-3d>
        </nav>
        <nav class="bottom-bar">
            <div class="link-buttons">
                <ul class="button-list" v-if="this.sadata.contactMe == 1">
                    <li v-if="template[0].call_icon">
                        <a @click="linkTo('tel:+'+sadata.merchant_phone_no, 'call', 'newtab')">
                            <img v-if="template[0].call_icon.includes('/images/')" :src="template[0].call_icon">
                            <img v-if="!template[0].call_icon.includes('/images/')"  :src="'/images/template/'+template[0].call_icon" alt="Call" />
                        </a>
                    </li>
                    <li v-if="template[0].whatsapp_icon">
                        <a @click="linkTo(sadata.whatsapp_url + '?text='+ sadata.whatsapp_message, 'whatsapp', 'newtab')">
                            <img v-if="template[0].whatsapp_icon.includes('/images/')" :src="template[0].whatsapp_icon">
                            <img v-if="!template[0].whatsapp_icon.includes('/images/')"  :src="'/images/template/'+template[0].whatsapp_icon" alt="Whatsapp" />
                        </a>
                    </li>
                    <li v-if="template[0].direction_icon">
                        <a @click="linkTo(sadata.waze_url, 'waze', 'newtab')">
                            <img v-if="template[0].direction_icon.includes('/images/')" :src="template[0].direction_icon">
                            <img v-if="!template[0].direction_icon.includes('/images/')"  :src="'/images/template/'+template[0].direction_icon" alt="Direction" />
                        </a>
                    </li>
                </ul>
                <div class="address">
                    <h2 v-if="template[0].show_company_with == 'name'">{{orgname}}</h2>
                    <img v-if="template[0].show_company_with == 'logo' && orglogo && !orglogo.includes('/images/')" :src="'/images/logo/'+orglogo">
                    <img v-if="template[0].show_company_with == 'logo' && orglogo && orglogo.includes('/images/')" :src="orglogo">
                    <p>{{orgaddress}}</p>
                </div>
            </div>
        </nav>
        
    </div>
</template>
<script>
    import { Carousel3d, Slide } from 'vue-carousel-3d';
    export default {
        props:{
            template: Array,
            image_callnow: String,
            image1: String,
            image2: String,
            link_showme: String,
            link_askme: String,
            link_keepme: String,
            link_campaign: String,
            template: Array,
            orgaddress: String,
            orgname: String,
            orglogo: String,
            size: {
                default: 'default',
                type: String,
            },
            sadata: Object,
            simplifymenu: { 
                type: Number
            }
        },
        components: {
            Carousel3d,
            Slide
        },
        computed: {
            A360_image: function(){
                if (this.template[0].A360_icon) {
                    if ( !this.template[0].A360_icon.includes('/images/') ) {
                        return '/images/template/'+this.template[0].A360_icon
                    } else {
                        return this.template[0].A360_icon
                    }
                }
            },
            askme_image: function(){
                if (this.template[0].askme_icon) {
                    if ( !this.template[0].askme_icon.includes('/images/') ) {
                        return '/images/template/'+this.template[0].askme_icon
                    } else {
                        return this.template[0].askme_icon
                    }
                }
            },
            promotion_image: function(){
                if (this.template[0].promotion_icon) {
                    if ( !this.template[0].promotion_icon.includes('/images/') ) {
                        return '/images/template/'+this.template[0].promotion_icon
                    } else {
                        return this.template[0].promotion_icon
                    }
                }
            },
            links: function() {
                if (this.template.length > 0) {
                    var obj = []
                    obj = [
                        {
                            link: this.link_showme,
                            image: this.A360_image,
                            visibility: this.sadata.showMe,
                            name: this.template[0].a360_title || '360&deg; Showcase' 
                        },
                        {
                            link: this.link_askme,
                            image: this.askme_image,
                            visibility: this.sadata.askMe,
                            name: this.template[0].askme_title || 'Ask Me'
                        },
                        {
                            link: this.link_keepme,
                            image: this.promotion_image,
                            visibility: this.sadata.keepMe,
                            name: this.template[0].promotion_title || 'Promotion'
                        },
                        {
                            link: this.link_campaign,
                            image: this.template[0].campaign_icon,
                            visibility: this.sadata.campaign,
                            name: this.template[0].campaign_title || 'Campaign'
                        }
                    ]
                    return obj;
                }
            },
        },
        data() {
            return {
                
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
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
