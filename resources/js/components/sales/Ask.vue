<template>
    <div class="content">
        <div class="link-wrapper" v-if="!linkSelected">
            <div class="collapseWrapper  animate__animated animate__fadeInUp askMeAccordion">
                <div class="collapseTitle" :style="'background:'+ template[0].colour2">
                    {{ template[0].askme_title || 'ASK ME' }}
                    <div class="collapseArrow" @click="askmeShow = !askmeShow">
                        <svg :class="(askmeShow)? 'up' :'' " width="18" height="18" viewBox="0 0 48 48"  xmlns="http://www.w3.org/2000/svg"><path d="M14 20l10 10 10-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg>
                    </div>
                </div>
                <transition name="smooth-expand">
                    <div class="collapseContent" v-if="askmeShow">
                        <ul class="ask-links">
                            <li v-if="brochureCount > 0 ">
                                <a @click="linkTo(brochureLink, 'brochure', '')">
                                    <img v-if="orgbrochurelogo.includes('/images/')" :src="orgbrochurelogo">
                                    <img v-if="!orgbrochurelogo.includes('/images/')" :src="'/images/organisation/brochure/'+orgbrochurelogo">
                                    <label>Brochure</label>
                                </a>
                            </li>
                            <li v-if="pricelistCount > 0">
                                <a @click="linkTo(pricelistLink, 'pricelist', '')">
                                    <img v-if="orgpricelistlogo.includes('/images/')" :src="orgpricelistlogo">
                                    <img v-if="!orgpricelistlogo.includes('/images/')" :src="'/images/organisation/pricelist/'+orgpricelistlogo">
                                    <label>Price List</label>
                                </a>
                            </li>
                            <li v-if="extraBrochureCount > 0 ">
                                <a @click="linkTo(extraBrochureLink, 'brochure', '')">
                                    <img v-if="orgextrabrochurelogo.includes('/images/')" :src="orgextrabrochurelogo">
                                    <img v-if="!orgextrabrochurelogo.includes('/images/')" :src="'/images/organisation/brochure/'+orgextrabrochurelogo">
                                    <label>{{orgextrabrochurelabel}}</label>
                                </a>
                            </li>
                            <li v-if="extraPricelistCount > 0" >
                                <a @click="linkTo(extraPricelistLink, 'pricelist', '')">
                                    <img v-if="orgextrapricelistlogo.includes('/images/')" :src="orgextrapricelistlogo">
                                    <img v-if="!orgextrapricelistlogo.includes('/images/')" :src="'/images/organisation/pricelist/'+orgextrapricelistlogo">
                                    <label>{{orgextrapricelistlabel}}</label>
                                </a>
                            </li>
                            <li v-if="orgpromotion">
                                <a @click="linkTo(orgpromotionLink, 'promotion', 'newtab')" target="_blank">
                                    <img v-if="orgpromotionlogo.includes('/images/')" :src="orgpromotionlogo">
                                    <img v-if="!orgpromotionlogo.includes('/images/')" :src="'/images/organisation/promotion/'+orgpromotionlogo">
                                    <label>Promotion</label>
                                </a>
                            </li>
                            <li v-for="(link, index) in linksExternal" v-bind:key="index">
                                <a @click="goToLink(link.link)" target="_blank">
                                    <img v-if="link.logo.includes('/images/')" :src="link.logo">
                                    <img v-if="!link.logo.includes('/images/')" :src="'/images/organisation/documents/'+link.logo">
                                    <label>{{link.name}}</label>
                                </a>
                            </li>
                        </ul>
                        <!-- <ul class="ask-links">
                            <li v-for="(link, index) in linksExternal" v-bind:key="index">
                                <a @click="goToLink(link.type, link.url)"><img :src="link.icon"></a>
                            </li>
                        </ul> -->
                    </div>
                </transition>
            </div>
        </div>
        <div class="collapseWrapper   animate__animated animate__fadeInUp toolsAccordion">
            <div class="collapseTitle" :style="'background:'+ template[0].colour2">
                TOOLS
                <div class="collapseArrow" @click="toolsShow = !toolsShow">
                    <svg :class="(toolsShow)? 'up' :'' " width="18" height="18" viewBox="0 0 48 48"  xmlns="http://www.w3.org/2000/svg"><path d="M14 20l10 10 10-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg>
                </div>
            </div>
            <transition name="smooth-expand">
                <div class="collapseContent" v-if="toolsShow">
                    <ul class="ask-links">
                        <li v-for="(link, index) in linksTools" v-bind:key="index">
                            <a @click="goToLink(link.link)" target="_blank">
                                <img v-if="link.logo.includes('/images/')" :src="link.logo">
                                <img v-if="link.logo.includes('/images/')" :src="'/images/organisation/documents/'+link.logo">
                                <label>{{link.name}}</label>
                            </a>
                        </li>
                    </ul>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
import { Carousel3d, Slide } from 'vue-carousel-3d';
    export default {
        props:{
            askme: Array,
            orgbrochurelogo: String,
            orgpricelistlogo: String,
            orgpromotionlogo: String,
            orgextrabrochurelogo: String,
            orgextrapricelistlogo: String,
            orgextrabrochurelabel: String,
            orgextrapricelistlabel: String,
            orgbrochure: String,
            orgpricelist: String,
            orgpromotion: String,
            template: Array,
            baselink: String
        },
        components: {
            Carousel3d,
            Slide
        },
        computed: {
            brochureCount:function(){
                return this.askme.filter(doc => doc.link == 'Brochure').length;
            },
            brochurePosition:function(){
                return this.askme.filter(doc => doc.link == 'Brochure').is_askme;
            },
            pricelistCount:function(){
                return this.askme.filter(doc => doc.link == 'Pricelist').length;
            },
            pricelistPosition:function(){
                return this.askme.filter(doc => doc.link == 'Pricelist').is_askme;
            },
            extraBrochureCount:function(){
                return this.askme.filter(doc => doc.link == 'Extra Brochure').length;
            },
            extraBrochurePosition:function(){
                return this.askme.filter(doc => doc.link == 'Extra Brochure').is_askme;
            },
            extraPricelistCount:function(){
                return this.askme.filter(doc => doc.link == 'Extra Pricelist').length;
            },
            extraPricelistPosition:function(){
                return this.askme.filter(doc => doc.link == 'Extra Pricelist').is_askme;
            },
            brochureLink: function() {
                return this.baselink + '/brochure';
            },
            pricelistLink: function() {
                return this.baselink + '/pricelist';
            },
            extraBrochureLink: function() {
                return this.baselink + '/extrabrochure';
            },
            extraPricelistLink: function() {
                return this.baselink + '/extrapricelist';
            },
            orgpromotionLink: function() {
                if ( !this.orgpromotion.includes('/images/') ) {
                    return '/images/organisation/promotion/'+this.orgpromotion
                } else {
                    return this.orgpromotion
                }
            },
            linksExternal: function() {
                return this.askme.filter(link => 
                    link.is_askme == '1' && link.link !== 'Brochure' && link.link !== 'Pricelist' && link.link !== 'Extra Brochure' && link.link !== 'Extra Pricelist' 
                )
            },
            linksTools: function() {
                return this.askme.filter(link => 
                    link.is_askme == '2' && link.link !== 'Brochure' && link.link !== 'Pricelist' && link.link !== 'Extra Brochure' && link.link !== 'Extra Pricelist' 
                )
            }
        },
        data() {
            return {
                linkSelected: null,
                askmeShow: true,
                toolsShow: true,
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
            goToLink(link) {
                window.location.href  = link;
                // window.open(link, "_blank");
                // if (type == 'external') {
                //     window.open(link, "_blank");
                // } else {
                //     this.linkSelected = type;
                // }
            },
        },
        mounted() {
        }
    }
</script>
