<template>
    <div class="askme-child ask-extra-pricelist content">
        <div class="carousel-wrapper">
            <div class="show-grid">
                <ul class="show-links">
                    <li v-for="(doc, index) in askmeFiltered" v-bind:key="index">
                    <figure v-on:mouseup="getFile(doc.attachment)">
                        <img v-if="doc.logo.includes('/images/')" :src="doc.logo">
                        <img v-if="!doc.logo.includes('/images/')" :src="'/images/organisation/documents/'+doc.logo">
                            <figcaption>
                                {{doc.name}}
                            </figcaption>
                        </figure>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import { Carousel3d, Slide } from 'vue-carousel-3d';
    export default {
        props:{
            askme: Array,
            doctype: String,
        },
        components: {
            Carousel3d,
            Slide
        },
        computed: {
            askmeFiltered: function() {
                return this.askme.filter(doc => doc.link == this.doctype);
            }
        },
        data() {
            return {
            }
        },
        methods: {
            getFile(file_name) {
                // window.location.href = '/images/organisation/documents/' + file_name;
                if (file_name.includes('/images/')) {
                    window.open(file_name, "_blank");
                } else {
                    window.open('/images/organisation/documents/' + file_name, "_blank");
                }
            }
        },
        mounted() {
        }
    }
</script>
