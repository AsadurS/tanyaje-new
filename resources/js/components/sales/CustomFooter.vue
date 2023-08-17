<template>
    <div class="customfooter-wrapper">
        <div class="customfooter">
            <div v-html="templateOutput"></div>
        </div>
    </div>
</template>
<script>
    export default {
        props:{
            template: Array,
            sadata: Object,
            orglogo: String,
            orgname: String,
            orgaddress: String,
        },
        data() {
            return {
                nav: false,
            }
        },
        computed: {
            shortcode() {
                return {
                    org_logo: '<img src="' +( this.orglogo.includes('/images/') ? this.orglogo : '/images/logo/' + this.orglogo) + '" />',
                    org_name:  this.orgname,
                    org_address: this.orgaddress
                }
            },
            templateOutput () {
                if (this.template[0].footer_code) {
                    return this.template[0].footer_code.replace(/{{\s*([\S]+?)\s*}}/g, (full, property) => {
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
        },  
        mounted() {
        }
    }
</script>
