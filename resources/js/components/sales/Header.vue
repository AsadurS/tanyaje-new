<template>
    <div class="header-wrapper">
        <nav class="top-bar d-flex justify-content-between" :style="'background:'+ template[0].colour1">
            <button @click="nav=!nav" v-if="sadata.verified !== 0">
                <svg height="24px" viewBox="0 -53 384 384" width="24px" xmlns="http://www.w3.org/2000/svg"><path d="m368 154.667969h-352c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h352c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/><path d="m368 32h-352c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h352c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/><path d="m368 277.332031h-352c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h352c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/></svg>
            </button>
            <div class="title" v-if="breadcrumb.link">
                <a @click="headerBack()"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 31.418 31.418"><path d="M26.585 3v25.418a3.002 3.002 0 01-4.883 2.335L5.949 18.044a2.999 2.999 0 010-4.67L21.703.665a3.004 3.004 0 013.178-.372A3.003 3.003 0 0126.585 3z"/></svg></a>
                <span v-html="breadcrumb.title"></span></div>
            <button onclick="openShare()" v-if="sadata.verified !== 0"><svg height="24px" viewBox="-21 0 512 512" width="24px" xmlns="http://www.w3.org/2000/svg"><path d="m453.332031 85.332031c0 38.292969-31.039062 69.335938-69.332031 69.335938s-69.332031-31.042969-69.332031-69.335938c0-38.289062 31.039062-69.332031 69.332031-69.332031s69.332031 31.042969 69.332031 69.332031zm0 0"/><path d="m384 170.667969c-47.0625 0-85.332031-38.273438-85.332031-85.335938 0-47.058593 38.269531-85.332031 85.332031-85.332031s85.332031 38.273438 85.332031 85.332031c0 47.0625-38.269531 85.335938-85.332031 85.335938zm0-138.667969c-29.417969 0-53.332031 23.9375-53.332031 53.332031 0 29.398438 23.914062 53.335938 53.332031 53.335938s53.332031-23.9375 53.332031-53.335938c0-29.394531-23.914062-53.332031-53.332031-53.332031zm0 0"/><path d="m453.332031 426.667969c0 38.289062-31.039062 69.332031-69.332031 69.332031s-69.332031-31.042969-69.332031-69.332031c0-38.292969 31.039062-69.335938 69.332031-69.335938s69.332031 31.042969 69.332031 69.335938zm0 0"/><path d="m384 512c-47.0625 0-85.332031-38.273438-85.332031-85.332031 0-47.0625 38.269531-85.335938 85.332031-85.335938s85.332031 38.273438 85.332031 85.335938c0 47.058593-38.269531 85.332031-85.332031 85.332031zm0-138.667969c-29.417969 0-53.332031 23.9375-53.332031 53.335938 0 29.394531 23.914062 53.332031 53.332031 53.332031s53.332031-23.9375 53.332031-53.332031c0-29.398438-23.914062-53.335938-53.332031-53.335938zm0 0"/><path d="m154.667969 256c0 38.292969-31.042969 69.332031-69.335938 69.332031-38.289062 0-69.332031-31.039062-69.332031-69.332031s31.042969-69.332031 69.332031-69.332031c38.292969 0 69.335938 31.039062 69.335938 69.332031zm0 0"/><path d="m85.332031 341.332031c-47.058593 0-85.332031-38.269531-85.332031-85.332031s38.273438-85.332031 85.332031-85.332031c47.0625 0 85.335938 38.269531 85.335938 85.332031s-38.273438 85.332031-85.335938 85.332031zm0-138.664062c-29.417969 0-53.332031 23.933593-53.332031 53.332031s23.914062 53.332031 53.332031 53.332031c29.421875 0 53.335938-23.933593 53.335938-53.332031s-23.914063-53.332031-53.335938-53.332031zm0 0"/><path d="m135.703125 245.761719c-7.425781 0-14.636719-3.863281-18.5625-10.773438-5.824219-10.21875-2.238281-23.253906 7.980469-29.101562l197.949218-112.851563c10.21875-5.867187 23.253907-2.28125 29.101563 7.976563 5.824219 10.21875 2.238281 23.253906-7.980469 29.101562l-197.953125 112.851563c-3.328125 1.898437-6.953125 2.796875-10.535156 2.796875zm0 0"/><path d="m333.632812 421.761719c-3.585937 0-7.210937-.898438-10.539062-2.796875l-197.953125-112.851563c-10.21875-5.824219-13.800781-18.859375-7.976563-29.101562 5.800782-10.238281 18.855469-13.84375 29.097657-7.976563l197.953125 112.851563c10.21875 5.824219 13.800781 18.859375 7.976562 29.101562-3.945312 6.910157-11.15625 10.773438-18.558594 10.773438zm0 0"/></svg>
            </button>
            <div class="hide backdrop"></div>
            <div class="shareModal hide">
                <button onclick="closeShare()"><svg height="24px" viewBox="0 0 329.26933 329" width="24px" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
                </button>
                <h2>Share this to:</h2>
                <div class="addthis_inline_share_toolbox"></div>
            </div>
            
            <transition name="slide-fade">
                <ul v-if="nav">
                    <li><a :href="link_landing">Back to Main</a></li>
                    <li v-if="sadata.verified == 1"><a :href="link_verify" >Verify Me</a></li>
                    <li v-if="sadata.showMe == 1"><a :href="link_showme">{{this.template[0].a360_title || '360&deg; Showcase' }}</a></li>
                    <li v-if="sadata.askMe == 1"><a :href="link_askme">{{this.template[0].askme_title || 'Ask Me'}}</a></li>
                    <li v-if="sadata.keepMe == 1"><a :href="link_keepme">{{this.template[0].promotion_title || 'Promotion'}}</a></li>
                    <li v-if="sadata.campaign == 1"><a :href="link_campaign">{{this.template[0].campaign_title || 'Campaign'}}</a></li>
                </ul>
            </transition>
        </nav>
        <div :class="'header verify-' + verify">
            <div v-html="templateOutput"></div>
            <div v-if="verify == 1">
                <div class="verifiedIcon"><img src="/new/images/sales/verified-icon.png?v=2"></div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:{
            image: String,
            link_landing: String,
            link_verify: String,
            link_showme: String,
            link_askme: String,
            link_keepme: String,
            link_campaign: String,
            template: Array,
            breadcrumb: Object,
            verify: Number,
            pageType: String,
            sadata: Object,
            orglogo: String,
        },
        data() {
            return {
                nav: false,
            }
        },
        computed: {
            shortcode() {
                return {
                    org_logo: '<img src="' +( this.orglogo.includes('/images/') ? this.orglogo : '/images/logo/' + this.orglogo) + '" />'
                }
            },
            templateOutput () {
                return this.template[0].header_code.replace(/{{\s*([\S]+?)\s*}}/g, (full, property) => {
                    return escape(this.shortcode[property])
                })
                
                function escape (str) {
                    if (str == null) {
                    return ''
                    }
                    
                    return String(str)
                }
            }
        },
        methods: {
            assignShortcode() {

            },
            emitShare() {
                EventBus.$emit('showSelected', payload);
            },
            handleEvent (data) {
                console.log('test')
            },
            headerBack(){
                if (this.pageType=='showme') {
                    // EventBus.$emit('backinShow');
                } else {
                    window.location.href=this.breadcrumb.link;
                }
            }
        },  
        mounted() {
            // this.headerBack()
            // EventBus.$on('event-name', handleEvent(data))
        }
    }
</script>
