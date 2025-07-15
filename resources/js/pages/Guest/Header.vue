<script setup>
import {onMounted ,onBeforeUnmount} from 'vue';
import {  Link, useForm,usePage } from '@inertiajs/vue3';

const props = defineProps({
    canLogin: Boolean,canRegister: Boolean,laravelVersion: String,phpVersion: String,auth:Object,cartCount:Number,
    auth: Object, menus: Object, alert: Object, flash: String, results:Object, cartTarahis: Object,path:String,
    cartCount: Number, cartPrice: Number, cartDiscount: Number, cartCoupon: Number, cartTotal: Number,
    companies: Object,users:Object,random_coupon: Object
});

const form =  useForm({id:null});

const submitLogout = () =>{
    form.post(route('logout'))
};

onMounted(() => {
    const scriptClass = 'dynamic-script';

    function addJs(address) {
        // حذف اگر همین فایل از قبل وجود داشته باشه (پیشگیری تکراری)
        const exists = document.querySelector(`script[src="${address}"]`);
        if (exists) return;

        const script = document.createElement('script');
        script.src = address;
        script.async = false;
        script.defer = true;
        script.classList.add(scriptClass);
        document.body.appendChild(script);
    }

    const items = [
       "/js/jquery-3.5.0.min.js", // جیکوئری باید زودتر لود شود
        "/js/popper.min.js",
        "/js/bootstrap.min.js",
        "/js/jquery.magnific-popup.min.js",
        "/js/jquery.easing.min.js",
        "/js/jquery.mb.YTPlayer.min.js",
        "/js/wow.min.js",
        "/js/owl.carousel.min.js",
        "/js/jquery.countdown.min.js",
        "/js/validator.min.js",
    ];

    const uniqueItems = [...new Set(items)];
    uniqueItems.forEach(addJs);
});

// موقع ترک این کامپوننت، همه فایل‌های اضافه‌شده رو حذف کن
onBeforeUnmount(() => {
    document.querySelectorAll('script.dynamic-script').forEach(script => {
        script.remove();
    });
});
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
  <main>
    <!--header section start-->
    <header class="header">
        <!--start navbar-->
        <nav class="navbar navbar-expand-lg fixed-top custom-nav white-bg">
            <div class="container">
                <Link class="navbar-brand" :href="route('index')" v-if="props.companies && props.companies.image">
                    <img :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" width="70" height="40" :alt="props.companies.name" class="">
                </Link>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="ti-menu"></span>
                    </button>
                <div v-if="$page.props.auth.user" class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                             <Link class="nav-link page-scroll text-dark" :href="route('index')">صفحه اصلی</Link>
                        </li>
                        <li class="nav-item">
                             <Link class="nav-link page-scroll text-dark" :href="route('dashboard.index')">داشبورد</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link page-scroll text-dark" :href="route('website_design.index','q')+'all' ">طراحی سایت</Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('website_templates.index','q')+'all' " class="nav-link page-scroll text-dark">محصولات</Link>
                        </li>
                        <li class="nav-item">
                             <Link class="nav-link page-scroll text-dark" :href="route('blog.index')">وبلاگ</Link>
                        </li>
                        <li class="nav-item">
                            <Link v-if="$page.props.auth.user" class="menu-link" :href="route('guest-cart.index')">
                                <div v-if="$page.props.cartCount > 0" >
                                <span style="font-size: x-large" class="ti-shopping-cart-full text-dark">{{$page.props.cartCount}} <span class="visually-hidden" ></span></span>
                                </div>
                                <div v-else >
                                 <span style="font-size: x-large" class="ti-shopping-cart text-dark"><span class="visually-hidden"></span></span>
                                </div>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <!-- <Link :href="route('logout')" method="post" class="nav-link page-scroll">خروج</Link> -->
                            <Link href="#" @click.prevent="submitLogout"  class="nav-link page-scroll text-dark">خروج</Link>
                        </li>
                    </ul>
                </div>
                    <div v-else class="collapse navbar-collapse main-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                           <Link class="nav-link page-scroll text-dark" :href="route('index')">صفحه اصلی</Link>
                        </li>
                        <li class="nav-item">
                           <Link class="nav-link page-scroll text-dark" :href="route('login')">ورود</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link page-scroll text-dark" :href="route('register')">ثبت نام</Link>
                            </li>
                        <li class="nav-item">
                            <Link class="nav-link page-scroll text-dark" :href="route('website_design.index', 'q')+'all' ">طراحی سایت</Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('website_templates.index','q')+'all'" class="nav-link page-scroll text-dark">محصولات</Link>
                        </li>
                        <li class="nav-item">
                             <Link class="nav-link page-scroll text-dark" :href="route('blog.index')">وبلاگ</Link>
                        </li>
                        <li class="nav-item">
                            <Link v-if="$page.props.auth.user == null &&  'guest-cart' !== props.path" class="nav-link page-scroll" :href="route('guest-cart.index')">
                                <span v-if="$page.props.cartCount > 0" style="font-size: x-large" class="ti-shopping-cart-full text-dark">{{$page.props.cartCount}}<span class="visually-hidden"></span></span>
                                <span v-else style="font-size: x-large" class="ti-shopping-cart text-dark"><span class="visually-hidden"></span></span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- <article>
        <slot />
    </article> -->
  </main>
</template>
<style>

/* @import '../../../../public/css/bootstrap.min.css';
@import '../../../../public/css/magnific-popup.css';
@import '../../../../public/css/themify-icons.css';
@import '../../../../public/css/animate.min.css';
@import '../../../../public/css/jquery.mb.YTPlayer.min.css';
@import '../../../../public/css/owl.carousel.min.css';
@import '../../../../public/css/owl.theme.default.min.css';
@import '../../../../public/css/style.css';
@import '../../../../public/css/style-rtl.css';
@import '../../../../public/css/responsive.css';
@import '../../../../public/css/mohi.css'; */
[v-cloak] {
  display: none;
}
</style>


