<template>
    <div class="content">
        <div class="animate__animated animate__fadeIn">
        <div class="verifyContent" v-if="verified == 1">
            <!-- <div v-html="template[0].template_code"></div> -->
            <div class="sa_photo">
                <img v-if="sadata.profile_img.includes('/images/')" :src="sadata.profile_img">
                <img v-if="sadata.profile_img && !sadata.profile_img.includes('/images/')" :src="'/images/sale-advisor/'+sadata.profile_img">
            </div>
            <div class="verifyRightContent">
                <div :class="'verificationBar verify-'+sadata.verified" 
                :style="[ sadata.verified == 1 ? { 'background-color': template[0].colour2 } : { 'background-color': '#ff0000' } ]">
                    <span v-if="sadata.verified">Verified</span>
                    <span v-if="sadata.verified_since">by Tanyaje since {{sadata.verified_since.split(" ")[0]}}</span>
                </div>
                <div class="saDetails">
                    <table>
                        <tr>
                            <td><strong>Name: </strong></td>
                            <td>{{sadata.merchant_name}}</td>
                        </tr>
                        <tr v-if="sadata.sa_position">
                            <td><strong>Position: </strong></td>
                            <td>{{sadata.sa_position}}</td>
                        </tr>
                        <tr v-if="sadata.merchant_emp_id">
                            <td><strong>ID No.: </strong></td>
                            <td>{{sadata.merchant_emp_id}}</td>
                        </tr>
                        <tr v-if="sadata.merchant_phone_no">
                            <td><strong>Contact: </strong></td>
                            <td>{{sadata.merchant_phone_no}}</td>
                        </tr>
                        <tr v-if="sadata.merchant_email">
                            <td><strong>Email: </strong></td>
                            <td>{{sadata.merchant_email}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <div class="verifyContent unverified animate__animated animate__fadeIn" v-if="verified == 0">    
            <div class="sa_photo">
                <img v-if="sadata.profile_img.includes('/images/')" :src="sadata.profile_img">
                <img v-if="sadata.profile_img && !sadata.profile_img.includes('/images/')" :src="'/images/sale-advisor/'+sadata.profile_img">
            </div>
        </div>
        <div class="normalContent animate__animated animate__fadeIn" v-if="verified == 0">
            <p class="sa_name">{{sadata.merchant_name}}</p>
            <p class="sa_position">{{sadata.sa_position}}</p>
            <div class="unverifiedContent">
                <span>NOT ACTIVE</span>
                <span v-if="sadata.verified_until"> SINCE {{sadata.verified_until.split(" ")[0]}}</span>
            </div>
        </div>
        <div class="collapseWrapper animate__animated animate__fadeIn  animate__delay-05s" v-if="sadata.verified">
            <div class="collapseTitle" :style="'background:'+ template[0].colour2">
                Company Information
                <div class="collapseArrow" @click="companyShow = !companyShow">
                    <svg :class="(companyShow)? 'up' :'' " width="18" height="18" viewBox="0 0 48 48"  xmlns="http://www.w3.org/2000/svg"><path d="M14 20l10 10 10-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg>
                </div>
            </div>
            <transition name="smooth-expand">
                <div class="collapseContent" v-if="companyShow">
                    <div>
                         <table>
                            <tr v-if="orgdata_company_name">
                                <td><strong>Company Name: </strong></td>
                                <td>{{orgdata_company_name}}</td>
                            </tr>
                            <tr v-if="orgdata_brn_no">
                                <td><strong>Register No.: </strong></td>
                                <td>{{orgdata_brn_no}}</td>
                            </tr>
                            <tr v-if="orgdata_address">
                                <td><strong>Address: </strong></td>
                                <td>{{orgdata_address}}</td>
                            </tr>
                            <tr v-if="orgdata_phone">
                                <td><strong>Contact: </strong></td>
                                <td>{{orgdata_phone}}</td>
                            </tr>
                            <tr v-if="orgdata_email">
                                <td><strong>Email: </strong></td>
                                <td>{{orgdata_email}}</td>
                            </tr>
                            <tr v-if="orgdata_website">
                                <td><strong>Website: </strong></td>
                                <td>{{orgdata_website}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </transition>
        </div>
        <div class="collapseWrapper animate__animated animate__fadeIn animate__delay-05s" v-if="sadata.verified">
            <div class="collapseTitle" :style="'background:'+ template[0].colour2">
                Bank Information
                <div class="collapseArrow" @click="bankShow = !bankShow">
                    <svg :class="(bankShow)? 'up' :'' " width="18" height="18" viewBox="0 0 48 48"  xmlns="http://www.w3.org/2000/svg"><path d="M14 20l10 10 10-10z"/><path d="M0 0h48v48h-48z" fill="none"/></svg>
                </div>
            </div>
            <transition name="smooth-expand">
                <div class="collapseContent" v-if="bankShow">
                    <div>
                        <table>
                            <thead>
                                <th></th>
                                <th>Account Name</th>
                                <th>Account No.</th>
                            </thead>
                            <tbody>
                                    <tr v-if="orgdata_bank1 && orgdata_bank1.image">
                                        <td>
                                            <img v-if="orgdata_bank1.image.includes('/images/')" :src="orgdata_bank1.image">
                                            <img v-if="!orgdata_bank1.image.includes('/images/')" :src="'/images/logo/'+orgdata_bank1.image" style="max-width:64px">
                                        </td>
                                        <td>{{orgdata_bank1.acc_name}}</td>
                                        <td>{{orgdata_bank1.acc_no}}</td>
                                    </tr>
                                    <tr v-if="orgdata_bank2 && orgdata_bank2.image">
                                        <td>
                                            <img v-if="orgdata_bank2.image.includes('/images/')" :src="orgdata_bank2.image">
                                            <img v-if="!orgdata_bank2.image.includes('/images/')" :src="'/images/logo/'+orgdata_bank2.image" style="max-width:64px">
                                        </td>
                                        <td>{{orgdata_bank2.acc_name}}</td>
                                        <td>{{orgdata_bank2.acc_no}}</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </transition>
        </div>
        <div class="bottomVerifyContent animate__animated animate__fadeIn animate__delay-2s">
            <p>FOR FURTHER VERIFICATION, KINDLY CONTACT THE OFFICE THROUGH BELOW</p>
            <ul>
                <li v-if="orgdata_corpphone"><a :href="'tel:'+orgdata_corpphone"><img src="/new/images/sales/tanyaje_call.png"></a></li>
                <li v-if="orgdata_phone"><a :href="'https://wa.me/'+orgdata_phone"><img src="/new/images/sales/tanyaje_whatsapp.png"></a></li>
                <li v-if="orgdata_email"><a :href="'mailto:'+orgdata_email"><img src="/new/images/sales/tanyaje_email.png"></a></li>
            </ul>
        </div>
    </div>
</template>
<style scoped>
.content img {
    margin:0 auto;
    display:block;
    width: 100%;
}
.sa_photo {
    width:130px;
    height:130px;
    border-radius: 130px;
    overflow: hidden;
    margin:0 auto;
    position: relative;
}
.verifiedIcon {
    position: absolute;
    bottom: 18px;
    left: 0;
    right: 0;
    max-width: 80px;
    margin: 0 auto;
}
.normalContent {
    text-align: center;
}
.sa_name {
    font-weight:800;
    max-width:180px;
    margin:0 auto;
}
.verifyContent {
    display: flex;
    flex-wrap:wrap;
}
.sa_photo {
    margin-left:15px!important;
    margin-right:0!important;
    width:140px!important;
    height: 140px!important;
    position: relative;
    z-index: 2;
    box-shadow: 0 0 10px 0px rgba(0,0,0,0.4);
}
.verifyRightContent {
    width:calc(100% - 110px);
    background:#fff;
    font-size:12px;
    margin-left:-70px;
    padding-left:80px;
    padding-top:25px;
    position: relative;
}
.verifyRightContent td {
    padding-right:15px;
    vertical-align: top;
    font-size: 10px;
}
.verifyRightContent td:nth-child(2){
    word-break: break-all;
}
.verificationBar {
    position: absolute;
    top: 0px;
    left:0;
    background:#eee;
    width: 100%;
    color:#fff;
    padding: 4px 0 4px 53px;
    text-transform: uppercase;
    font-weight: 600;
    font-size: 10px;
}
.collapseWrapper {
    padding:0 20px;
    margin-top:30px;
}
.collapseTitle {
    display:flex;
    justify-content: space-between;
    color:#fff;
    text-transform: uppercase;
    padding:2px 10px;
    font-size:12px;
    font-weight:600;
    letter-spacing: 1px;

}
.collapseTitle svg {
    fill: #fff;
}
.collapseTitle svg.up {
    transform: rotate(180deg);
}
.collapseContent {
    background:#fff;
    /* padding:10px; */
    font-size:12px;
    /* min-height:270px; */
    max-height:400px;
    overflow:hidden;
}
.collapseContent > * {
    padding:10px;
}
.smooth-expand-enter-active, .smooth-expand-leave-active {
    transition: max-height .5s;
}
.smooth-expand-enter, .smooth-expand-leave-to {
    max-height: 0;
}
.collapseContent td, .collapseContent th {
    padding-right:10px;
}
.verifyContent.unverified {
    display:block;
    margin:0 auto;
}

.verifyContent.unverified .sa_photo {
    margin: 0 auto!important;
}
.unverifiedContent {
    background:#ff0000;
    max-width:220px;
    margin: 0 auto;
    color:#fff;
    font-weight:700;
    padding: 8px 15px;
    line-height: 1.4;
}
.unverifiedContent {
    background:#ff0000;
    max-width:220px;
    margin: 0 auto;
    color:#fff;
    font-weight:700;
    padding: 8px 15px;
    line-height: 1.4;
}
.bottomVerifyContent {
    text-align: center;
    font-weight:700;
    max-width:400px;
    padding:0 30px;
    font-size:12px;
    margin: 80px auto 30px;
}
.bottomVerifyContent ul {
    list-style: none;
    padding-left: 0;
    display:flex;
    max-width:300px;
    padding:0 30px;
    font-size:12px;
    margin: 0px auto;
    justify-content: center;
}
.bottomVerifyContent ul li {
    padding: 0 10px;
    width:33.33%
}
@media (max-width:992px) {
    .verificationBar {
        padding-left:80px;
    }
}
@media (max-width:380px) {
    .verificationBar {
        padding-left:80px;
    }
    .sa_photo {
        width: 110px!important;
        height: 110px!important;
    }
    .verifyRightContent {
        width: calc(100% - 76px);
    }
}
@media (min-width:320px) and (max-width:340px) {
    .verifyRightContent {
        padding-top: 48px;
    }
}
</style>
<script>
    export default {
        props:{
            image: String,
            template: Array,
            sadata: Object,
            orgdata_company_name: String,
            orgdata_brn_no: String,
            orgdata_corpphone: String,
            orgdata_phone: String,
            orgdata_email: String,
            orgdata_bank1: Object,
            orgdata_bank2: Object,
            orgdata_website: String,
            orgdata_address: String,
            verified: Number
        },
        data() {
            return {
                companyShow: true,
                bankShow: false,
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
