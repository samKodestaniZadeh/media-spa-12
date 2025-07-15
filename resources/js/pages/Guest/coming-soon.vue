<script setup>

import Header from './Header.vue';
import Footer from './Footer.vue';
import {computed, ref} from 'vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({

    companies:Object,
});


</script>
<template v-cloak>
    <!-- <head>
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/bootstrap.min.css'" type="text/css" />
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/magnific-popup.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/themify-icons.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/animate.min.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/jquery.mb.YTPlayer.min.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/owl.carousel.min.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/owl.theme.default.min.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/style.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/style-rtl.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/responsive.css'">
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/css/mohi.css'">
    </head> -->
    <Header :companies="props.companies" />
    <body>
        <div data-v-8913de20="" class="main">
            <section data-v-8913de20="" class="hero-section ptb-100 background-img full-screen banner-1-bg">
                <div data-v-8913de20="" class="container">
                    <div data-v-8913de20="" class="row align-items-center justify-content-center">
                        <div data-v-8913de20="" class="col-md-9 col-lg-7">
                            <div data-v-8913de20="" class="hero-content-left text-white text-center">
                                <h1 data-v-8913de20="" class="text-white"> بزودی برمیگردیم</h1>
                                <p data-v-8913de20="" class="lead"> ما در حال بروز رسانی هستیم.لطفا صبور باشید </p>

                                <div class="progress">
  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>

</template>
<style>
[v-cloak] {
  display: none;
}
</style>
