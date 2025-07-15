<script setup>
import { computed,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';


const props = defineProps({products:Object,companies:Object});

const form = useForm({
    id: null,
});
const style = ref('desktop');
const submitCart =(id) => {
    form.id = id
    form.post(route('cart.store'))
}

</script>
<template v-cloak>
    <head>
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
    </head>
    <section class="hero-section " style="background: url('../img/hero-bg-shape-2.png')no-repeat center center / cover">
<header class="header">
        <nav class="navbar navbar-expand-lg fixed-top custom-nav white-bg">
            <div class="container">
                <Link class="navbar-brand" :href="route('index')" v-if="props.companies">
                    <img v-if="props.companies.image" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" width="70" height="40" :alt="props.companies.name_show" class="">
                </Link>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="ti-menu"></span>
                    </button>
                <div v-if="$page.props.auth.user" class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                             <Link class="nav-link page-scroll"  :href="route('website_templates.show',[props.products.slug])">خرید و دانلود</Link>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" id="navbarBlogPage" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link page-scroll dropdown-toggle"> ریسپانسیو </a>
                            <div aria-labelledby="navbarBlogPage" class="dropdown-menu submenu">
                                <a @click="style ='desktop'" class="dropdown-item">
                                    <img src="https://img.icons8.com/material/24/null/monitor--v1.png"/>
                                </a>
                                <a @click="style ='tablet'" class="dropdown-item">
                                    <img src="https://img.icons8.com/material/24/null/windows8-tablet--v1.png"/>
                                </a>
                                <a @click="style ='mobile'" class="dropdown-item">
                                    <img src="https://img.icons8.com/material/24/null/iphone.png"/>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                    <div v-else class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                           <Link class="nav-link page-scroll" :href="route('index')">خرید و دانلود</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
        <div class="preview " :class="[style]">
            <iframe :src="props.products.demo_link" > </iframe>
        </div>
        </section>
</template>
<style>
[v-cloak] {
  display: none;
}
</style>
