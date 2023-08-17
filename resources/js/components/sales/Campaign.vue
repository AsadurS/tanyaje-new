<template>
    <div class="content">
        <!-- <div class="keep-wrapper-top" v-html="templateTopOutput"></div> -->
        <div class="campaign-wrapper">
            <ul class="campaign-links" v-if="campaignlist.length !== 0">
                <li v-for="(link, index) in campaignlist" v-bind:key="index" class="animate__animated animate__fadeIn">
                    <a @click="linkTo(link.campaign_id, 'campaign_click')">
                        <img v-if="link.campaign_image.includes('/images/')" :src="link.campaign_image">
                        <img v-if="!link.campaign_image.includes('/images/')" :src="'/images/promotion/'+link.campaign_image">
                    </a>
                </li>
            </ul>
            <div class="text-center" v-if="campaignlist.length !== 0">
                <button v-if="buttonVisibility" class="btn btn-primary" @click="loadMore" :style="'background:'+ template[0].colour2">Load More</button>
            </div>
            <p v-if="campaignlist.length == 0">There is no campaign currently.</p>
        </div>
        <!-- <div class="keep-wrapper-bottom" v-html="templateBottomOutput"></div> -->
    </div>
</template>
<script>
    export default {
        props:{
            sadata: Object,
            campaignlist: Array,
            template: Array,
            baseurl: String,
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
            buttonVisibility: function() {
                if ( this.campaignlist.length <= this.show ) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        methods: {
            loadMore() {
                this.show = this.show + 4;
            },
            linkTo(campaign_id, event, tab){
                console.log('track '+ event);
                var that = this;
                $.ajax({
                    url: "/eventtracker",
                    type:"POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        "event": event
                    },
                    complete:function(response){
                        if (campaign_id !== '') {
                            window.location.href  = that.baseurl+'/campaign/'+campaign_id;
                        }
                    },
                    async: false
                });
                
            },
        },
        mounted() {
        }
    }
</script>
