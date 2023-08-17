<template>
    <div class="content">
        <div class="show-cta">
            <div class="main_media">
                <img :src="campaign.campaign_image">
            </div>
            <!-- <div class="thumbnails">
                <div v-for="(thumb,index) in item.images" v-bind:key="index">
                    <img :src="thumb">
                </div>
            </div> -->
            <div class="detail-content">
                <h2><strong>{{campaign.campaign_name}}</strong></h2>
                <div v-html="campaign.description"></div>
                <div v-if="!interest" style="text-align:center"><button class="form-button" @click="dynamicInterest()" :style="'background:'+ campaign.response_button_color+'; color:'+campaign.response_text_color">I'm interested</button></div>
                <div v-if="interest"><input class="form-control" type="text" v-model="form.name" placeholder="NAME:"></div>
                <div v-if="interest" class="input-with-prefix"><span class="input-prefix">+60</span><input type="phone" class="form-control" v-model="form.contact" v-on:keypress="isLetterOrNumber($event)" placeholder=""></div>
                <div v-if="interest"><input class="form-control" v-model="form.email" placeholder="EMAIL:" type="email"></div>
                <div v-if="interest" style="text-align:center">
                    <div v-if="submitted">Please wait a moment...</div>
                    <img v-if="submitted" :src="loadinganim">
                </div>
                <div v-if="interest" style="text-align:center">
                    <button :disabled="!validated" class="form-button" @click="linkTo(true, 'campaign_response')" :style="'background:'+ campaign.response_button_color+'; color:'+campaign.response_text_color">{{campaign.response_text || 'TELL ME MORE' }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.show-cta .main_media img {
    width: 100%;
    height: auto;
}
.show-cta .detail-content {
    padding:15px;
    background: #fff;
}
.show-cta .detail-content h2 {
    font-size:18px;
}

.show-cta .form-control {
    margin-bottom: 10px;
    border-radius: 0;
}
.form-button {
    border:0;
    margin-top: 30px;
    margin-bottom:30px;
    padding:8px 12px;
    color:#fff;
}
</style>
<script>
    export default {
        props:{
            campaign: Object, 
            template: Array,
            sadata: Object,
            loadinganim: String,
        },
        data() {
            return {
                interest: false,
                submitted: false,
                form: {
                    name: '',
                    contact: '',
                    email:'',
                }
            }
        },
        computed:{
            newLink: function(){
                return  this.sadata.whatsapp_url+'?text='+this.sadata.whatsapp_message+'%0a %0a*'+this.campaign.campaign_name+'*%0a %0aName: '+this.form.name+'%0aContact: %2b60'+this.form.contact+'%0aEmail: '+this.form.email;
            },
            newLinkShort: function(){
                return  this.sadata.whatsapp_url+'?text='+this.sadata.whatsapp_message+'%0a %0a*'+this.campaign.campaign_name+'*%0a ';
            },
            validEmail: function () {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(this.form.email);
            },
            validated() {
                if (!this.submitted) {
                    if (this.form.contact.length >= 9 || this.validEmail) {
                        return true
                    } else {
                        return false
                    }
                } else {
                    return false
                }
            },
        },
        methods: {
            isLetterOrNumber(e) {
                let char = String.fromCharCode(e.keyCode);
                if (/^[0-9]+$/.test(char)) return true;
                else e.preventDefault();
            },
            dynamicInterest() {
                var that = this;
                if (this.campaign.interest_form == 1) {
                    this.interested();
                } else {    
                    $.ajax({
                        url: "/eventtracker",
                        type:"POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            "event": 'campaign_interest',
                        },
                        complete:function(response){
                            that.linkTo(true, 'interested');
                        },
                        async: false
                    });
                }
            },
            interested(){
                $.ajax({
                    url: "/eventtracker",
                    type:"POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        "event": 'campaign_interest',
                    },
                    complete:function(response){
                    },
                    async: false
                });
                this.interest = true;
            },
            linkTo(link, event, tab){
                this.submitted = true;
                var that = this;
                console.log('track '+ event);
                setTimeout(() => {
                    $.ajax({
                        url: "/eventtracker",
                        type:"POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            "event": event,
                            "form_response": this.form,
                        },
                        complete:function(response){
                            if (link == true) {
                                if (that.campaign.interest_form == 1 ) {
                                window.location.href  = that.newLink;
                                } else {
                                window.location.href  = that.newLinkShort;
                                }
                            }
                        },
                        async: false
                    });
                }, 200);
                
            },
        },
        mounted() {
        }
    }
</script>
