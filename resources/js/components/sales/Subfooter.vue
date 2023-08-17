<template>
    <nav :class="'subbottom-bar ' + 'template-'+template" >
        <div class="information-buttons" v-if="size == 'default'">
            <div class="heading">For more information click the icon below </div>
            <ul class="button-list">
                <li>
                    <a href="#">
                        <img :src="image_callnow" alt="CALL NOW" />
                        CALL NOW
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img :src="image_callnow" alt="WHATSAPP" />
                        WHATSAPP
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img :src="image_callnow" alt="DIRECTIONS" />
                        DIRECTIONS
                    </a>
                </li>
            </ul>
        </div>
        <div class="information-buttons shrinked" v-if="size == 'shrink'">
            <div class="agent">
                <div class="agent-avatar">
                    <img :src="sa_avatar">
                </div>
                <div class="agent-name">Tan Wei Leong</div>
                <div class="agent-title">Sales Head</div>
            </div>
            <ul class="button-list">
                <li>
                    <a href="#">
                        <img :src="image_callnow" alt="CALL NOW" />
                        CALL NOW
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img :src="image_callnow" alt="WHATSAPP" />
                        WHATSAPP
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img :src="image_callnow" alt="DIRECTIONS" />
                        DIRECTIONS
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>
<style scoped>
.subbottom-bar {
    margin-top:auto;
    z-index: 2;
    position: relative;
}
.subbottom-bar .heading {
    margin-bottom:10px;
    padding-left:10px;
}
.information-buttons {
    padding:15px 0;
    background:#A0A5A9;
}
.button-list {
    padding-left:0;
    list-style: none;
    display: flex;
    justify-content: space-evenly;
    margin-bottom:0;
}
.button-list li {
    width:33.33%;
}
.button-list li a {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 10px;
}
.button-list img {
    margin-bottom: 10px;
    display: block;
    width: 38px;
    height: 38px;
    border:2px solid #000;
    border-radius: 30px;
    padding: 5px;
}
.button-list li a {
    text-decoration: none;
    color:#000;
}
.subbottom-bar .shrinked {
    display: flex;
}
.subbottom-bar .shrinked a {
    font-size:0!important;
}
.subbottom-bar .shrinked .agent {
    width:40%;
    padding-left:15px;
    margin-top:-30px;
    font-size:12px;
}
.subbottom-bar .shrinked .button-list {
    width:60%;
}
.subbottom-bar .agent .agent-avatar {
    width: 48px;
    height: 48px;
    border-radius:38px;
    background:#fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.4);
    overflow: hidden;
} 
.subbottom-bar .agent .agent-avatar img {
    width:48px;
    height:48px;
    object-fit: cover;
}
</style>
<script>
    export default {
        props:{
            template: String,
            size: {
                default: 'default',
                type: String,
            },
            image_callnow: String,
            sa_avatar: String,
        },
        data() {
            return {
                
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
