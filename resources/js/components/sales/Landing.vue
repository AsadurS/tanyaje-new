<template>
    <div class="content">
        <div class="normalContent">
            <!-- <div v-html="templateOutput"></div> -->
            <div v-if="sadata.verified !== 0" :class="'sa_photo withVerifiedMask   animate__animated animate__fadeInUp verify-'+sadata.verified" @click="goToLink(link_verify)">
                <img v-if="sadata.profile_img.includes('/images/')" :src="sadata.profile_img">
                <img v-if="sadata.profile_img && !sadata.profile_img.includes('/images/')" :src="'/images/sale-advisor/'+sadata.profile_img">
                <div class="verifiedIcon" v-if="sadata.verified == 1"><img src="/new/images/sales/verified.png"></div>
            </div>
            <div v-if="sadata.verified == 0" :class="'sa_photo withVerifiedMask   animate__animated animate__fadeInUp verify-'+sadata.verified">
                <img v-if="sadata.profile_img.includes('/images/')" :src="sadata.profile_img">
                <img v-if="sadata.profile_img && !sadata.profile_img.includes('/images/')" :src="'/images/sale-advisor/'+sadata.profile_img">
            </div>
            <p class="sa_name  animate__animated animate__fadeInDown ">{{sadata.merchant_name}}</p>
            <p class="sa_position  animate__animated animate__fadeInDown ">{{sadata.sa_position}}</p>
            <div class="awards   animate__animated animate__fadeInDown " v-html="sadata.landingpage_images"></div>
            <div v-if="sadata.landingpage_version == 1" class="address">
                <h2>{{orgname}}</h2>
                <p>{{orgaddress}}</p>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:{
            image: String,
            template: Array,
            sadata: Object,
            link_verify: String,
            orgaddress: String,
            orgname: String,
        },
        data() {
            return {
                
            }
        },
        computed: {
            shortcode() {
                var codeObj = {
                    sa_name: this.sadata.merchant_name,
                    sa_position: this.sadata.sa_position,
                    sa_images: this.sadata.landingpage_images,
                }
                // codeObj.sa_photo = '<a href="'+this.link_verify+'" class="sa_photo withVerifiedMask animate__animated animate__fadeInUp verify-'+this.sadata.verified+'">'
                // + '<img src="' + ( this.sadata.profile_img.includes('/images/') ? this.sadata.profile_img : '/images/sale-advisor/' + this.sadata.profile_img) + '" />'
                // + (this.sadata.verified !== 2 ? '<div class="verifiedIcon"><img src="/new/images/sales/verified.png"></div>' : '')
                // + '</a>'
                // return codeObj;
            },
            templateOutput () {
                if (this.template[0].template_code) {
                    return this.template[0].template_code.replace(/{{\s*([\S]+?)\s*}}/g, (full, property) => {
                        return escape(this.shortcode[property])
                    })
                    
                    function escape (str) {
                        if (str == null) {
                        return ''
                        }
                        
                        return String(str)
                    }
                }
            }
        },
        methods: {
            goToLink(link) {
                if (this.sadata.verified !== 2) {
                    window.location.href = link;
                }
                // if (type == 'external') {
                //     window.open(link, "_blank");
                // } else {
                //     this.linkSelected = type;
                // }
            },
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
