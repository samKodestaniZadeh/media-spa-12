<script setup>

import {onMounted,ref,watch,onBeforeUnmount } from 'vue';
import {  Link, useForm } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';

const props = defineProps({
    canLogin: Boolean,canRegister: Boolean,laravelVersion: String,phpVersion: String,menu: Object,
    auth: Object, menus: Object, alert: Object, flash: String, results:Object, cartTarahis: Object,
    companies: Object,users:Object,random_coupon: Object,Quickview:Object,cart:Object,path:String,
});

const form = useForm({ id:null,model:null,
    name: null, lasst_name: null, email: null, tel: null, price: null, text: null,
    group: null, type: null, title: null, file: null, q:null,category:null,entekhab:null
});

const emit = defineEmits(['EventSubmitQuickview','EventSubmitCart','EventSubmitTarahiFilter','EventSubmitBlogFilter']);
const chenge = (result)=> {
    emit('EventSubmitQuickview',result)

}

const submitCart = (id) => {
   emit('EventSubmitCart',id)
}

const submitTarahiFilter = () => {
   emit('EventSubmitTarahiFilter', form.q )
}

const submitBlogFilter = () => {
   emit('EventSubmitBlogFilter', form.q )
}

onMounted(() => {
  const scriptClass = 'dynamic-script';

  function addJs(address) {
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
    "/assets/js/vendor/jquery-3.6.0.min.js",
    "/assets/js/vendor/bootstrap.bundle.min.js",
    "/assets/js/plugins/slick.js",
    "/assets/js/plugins/jquery.syotimer.min.js",
    "/assets/js/plugins/wow.js",
    "/assets/js/plugins/jquery-ui.js",
    "/assets/js/plugins/perfect-scrollbar.js",
    "/assets/js/plugins/magnific-popup.js",
    "/assets/js/plugins/select2.min.js",
    "/assets/js/plugins/waypoints.js",
    "/assets/js/plugins/counterup.js",
    "/assets/js/plugins/jquery.countdown.min.js",
    "/assets/js/plugins/images-loaded.js",
    "/assets/js/plugins/isotope.js",
    "/assets/js/plugins/scrollup.js",
    "/assets/js/plugins/jquery.vticker-min.js",
    "/assets/js/plugins/jquery.theia.sticky.js",
    "/assets/js/plugins/jquery.elevatezoom.js",
    "/assets/js/main.js",
    "/assets/js/shop.js",
    "/assets/js/invoice/jspdf.min.js",
    "/assets/js/invoice/invoice.js"
  ];

  const uniqueItems = [...new Set(items)];
  uniqueItems.forEach(addJs);
});

onBeforeUnmount(() => {
  // حذف اسکریپت‌ها
  document.querySelectorAll('script.dynamic-script').forEach(script => {
    script.remove();
  });

  // پاکسازی scrollUp
  const scrollUp = document.getElementById('scrollUp')
  if (scrollUp) scrollUp.remove()

  // پاکسازی zoomContainer و zoomWindow
  document.querySelectorAll('.zoomContainer, .zoomWindow').forEach(el => el.remove())

  // حذف دیتاهای attach شده توسط elevateZoom
  const mainImage = document.getElementById('mainImage')
  if (mainImage && typeof $(mainImage).removeData === 'function') {
    $(mainImage).removeData('elevateZoom')
  }
})

const validate = (text)=>{
    swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }}).fire({
        title: text,
        icon:'error',
    })
}

const menus = ref([]);

if (props.menu) {

    props.menu.forEach(element => {
        if (element.sections.length > 0)
        {
            element.sections.forEach(section => {
                if(section.name == 'products')
                {
                    menus.value.push(element)
                }
            });
        }

    });
}
const menusTarahi = ref([]);

if (props.menu) {

    props.menu.forEach(element => {
        if (element.sections.length > 0)
        {
            element.sections.forEach(section => {
                if(section.name == 'tarahis')
                {
                    menusTarahi.value.push(element)
                }
            });
        }

    });
}
const menusBlog = ref([]);

if (props.menu) {

    props.menu.forEach(element => {
        if (element.sections.length > 0)
        {
            element.sections.forEach(section => {
                if(section.name == 'blogs')
                {
                    menusBlog.value.push(element)
                }
            });
        }

    });
}

const submit = ()=>{

    if (form.q) {
        form.get(route('website_templates.index')
        // ,{
        //     onFinish:() => submitTime()
        // }
        )
    }
    else
    {
        let text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
};

</script>
<template v-cloak>

    <head>
        <link :href="$page.props.ziggy.url+'/assets/css/plugins/animate.min.css'" rel="stylesheet" type="text/css"/>
        <link :href="$page.props.ziggy.url+'/assets/css/main.css'" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/assets/css/mohi.css'">
    </head>
    <!-- Modal -->
     <!-- <div class="modal fade custom-modal" id="onloadModal" tabindex="-1" aria-labelledby="onloadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                        <div class="deal" style="background-image: url('assets/imgs/banner/popup-1.png')">
                            <div class="deal-top">
                                <h6 class="mb-10 text-brand-2">Deal of the Day</h6>
                            </div>
                            <div class="deal-content detail-info">
                                <h4 class="product-title"><a class='text-heading' href='/shop-product-right'>Organic fruit for your family's health</a></h4>
                                <div class="clearfix product-price-cover">
                                    <div class="product-price primary-color float-left">
                                        <span class="current-price text-brand">$38</span>
                                        <span>
                                            <span class="save-price font-md color3 ml-15">26% Off</span>
                                            <span class="old-price font-md ml-15">$52</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="deal-bottom">
                                <p class="mb-20">Hurry Up! Offer End In:</p>
                                <div class="deals-countdown pl-5" data-countdown="2025/03/25 00:00:00">
                                    <span class="countdown-section"><span class="countdown-amount hover-up">03</span><span class="countdown-period"> days </span></span><span class="countdown-section"><span class="countdown-amount hover-up">02</span><span class="countdown-period"> hours </span></span><span class="countdown-section"><span class="countdown-amount hover-up">43</span><span class="countdown-period"> mins </span></span><span class="countdown-section"><span class="countdown-amount hover-up">29</span><span class="countdown-period"> sec </span></span>
                                </div>
                                <div class="product-detail-rating">
                                    <div class="product-rate-cover text-end">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width: 90%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted"> (32 rates)</span>
                                    </div>
                                </div>
                                <a class='btn hover-up' href='/shop-grid-right'>Shop Now <i class="fi-rs-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Quick view -->
        <div class="modal fade custom-modal" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true" v-if ="props.results" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                <div class="detail-gallery">
                                    <span class="zoom-icon"><i class="fi-rs-search"></i></span>

                                    <div class="product-image-slider" >
                                        <figure class="border-radius-10" v-for="(result ,index) in props.results.data" :key="index"  >
                                            <img v-if="props.Quickview" :src="$page.props.ziggy.url+'/storage/'+props.Quickview.image.url" alt="product image" />
                                            <img v-else-if="result.image && result.image.status == 4 || result.image &&  result.image.status == 5" :src="$page.props.ziggy.url+'/storage/'+result.image.url" alt="product image" />

                                        </figure>
                                        <!-- <figure class="border-radius-10">
                                            <img src="assets/imgs/shop/product-16-1.jpg" alt="product image" />
                                        </figure>
                                        <figure class="border-radius-10">
                                            <img src="assets/imgs/shop/product-16-3.jpg" alt="product image" />
                                        </figure>
                                        <figure class="border-radius-10">
                                            <img src="assets/imgs/shop/product-16-4.jpg" alt="product image" />
                                        </figure>
                                        <figure class="border-radius-10">
                                            <img src="assets/imgs/shop/product-16-5.jpg" alt="product image" />
                                        </figure>
                                        <figure class="border-radius-10">
                                            <img src="assets/imgs/shop/product-16-6.jpg" alt="product image" />
                                        </figure>
                                        <figure class="border-radius-10">
                                            <img src="assets/imgs/shop/product-16-7.jpg" alt="product image" />
                                        </figure> -->
                                    </div>

                                    <div class="slider-nav-thumbnails">
                                        <div v-for="(result ,index) in props.results.data" :key="index">
                                            <img @click="chenge(result)" :src="$page.props.ziggy.url+'/storage/'+result.image.url" alt="product image" />
                                        </div>
                                        <!-- <div><img src="assets/imgs/shop/thumbnail-4.jpg" alt="product image" /></div>
                                        <div><img src="assets/imgs/shop/thumbnail-5.jpg" alt="product image" /></div>
                                        <div><img src="assets/imgs/shop/thumbnail-6.jpg" alt="product image" /></div>
                                        <div><img src="assets/imgs/shop/thumbnail-7.jpg" alt="product image" /></div>
                                        <div><img src="assets/imgs/shop/thumbnail-8.jpg" alt="product image" /></div>
                                        <div><img src="assets/imgs/shop/thumbnail-9.jpg" alt="product image" /></div> -->
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12" v-if="props.Quickview">
                                <div class="detail-info pr-30 pl-30">
                                    <span class="stock-status out-stock" v-if="props.Quickview.discount"> تخفیف </span>

                                    <h3 class="title-detail"><a class='text-heading' href="">{{ props.Quickview.name }}</a></h3>
                                    <div class="product-detail-rating">
                                        <div class="product-rate-cover text-end">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" v-if="props.Quickview.ratings_avg_rating" :style="'width:' + props.Quickview.ratings_avg_rating*20 + '%' "></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted" v-if="props.Quickview.ratings_avg_rating"> ({{ props.Quickview.ratings_avg_rating }})</span>
                                            <span class="font-small ml-5 text-muted" v-else > (0.0)</span>
                                        </div>
                                    </div>
                                    <div class="clearfix product-price-cover">
                                        <div class="product-price primary-color float-left">
                                            <span class="current-price text-brand" v-if="props.Quickview.discount">{{(props.Quickview.price-(props.Quickview.price*props.Quickview.discount.percent/100)).toLocaleString("fa-IR")}}</span>
                                            <span class="current-price text-brand" v-else >{{(props.Quickview.price).toLocaleString("fa-IR")}}</span>
                                            <span>
                                                <span class="save-price font-md color3 ml-15" v-if="props.Quickview.discount">{{props.Quickview.discount.percent}}% تخفیف </span>
                                                <span class="old-price font-md ml-15" v-if="props.Quickview.discount">{{ (props.Quickview.price).toLocaleString("fa-IR") }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="detail-extralink mb-30">
                                        <!-- <div class="detail-qty border radius">
                                            <a href="#" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                            <span class="qty-val">1</span>
                                            <a href="#" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                        </div> -->
                                        <div class="product-extra-link2">
                                            <button type="submit" @click.prevent="submitCart(props.Quickview.id)"  class="button button-add-to-cart"><i class="fi-rs-shopping-cart"></i>خرید</button>
                                        </div>
                                    </div>
                                    <div class="font-xs">
                                        <ul>
                                            <li class="mb-5">فروشنده: <span class="text-brand">{{ props.Quickview.user.name_show }}</span></li>
                                            <li class="mb-5">MFG:<span class="text-brand"> {{  moment(props.Quickview.created_at).locale("fa", fa).format('jYYYY/jM/jD') }}</span></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <header class="header-area header-style-1 header-height-2">
        <div class="mobile-promotion"><span>تا حالا از <strong>تخفیفات روزانه</strong> استفاده کردی؟<strong>بهتره تا تموم نشدن</strong> ازشون استفاده کنی</span></div>
        <div class="header-top header-top-ptb-1 d-none d-lg-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-4">
                        <div class="header-info">
                            <ul>
                                <li v-if="$page.props.auth.user == null"><Link :href="route('login')">ورود</Link></li>
                                <li><Link :href="route('privacy.index')">حریم خصوصی</Link></li>
                                <li><Link :href="route('faq.index')">سوالات متداول</Link></li>
                                <li><Link :href="route('terms-conditions.index')">قوانین و مقررات</Link></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-4">
                        <div class="text-center">
                            <div id="news-flash" class="d-inline-block">
                                <ul>
                                    <li>تا حالا از بن تخفیف روزانه استفاده کردی!؟ </li>
                                    <li>بهتره تا تموم نشدن</li>
                                    <li>ازشون استفاده کنی</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="header-info header-info-right">
                            <ul>
                                <li v-if="props.companies && props.companies.phone">کمک میخوایی ؟ شماره تماس : <strong class="text-brand"> {{ props.companies.phone }}+</strong></li>
                                <li>
                                    <a class="language-dropdown-active" href="#"><i class="fi-rs-angle-small-down"></i> فارسی</a>

                                </li>
                                <li>
                                    <a class="language-dropdown-active" href="#"><i class="fi-rs-angle-small-down"></i> ریال</a>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
            <div class="container">
                <div class="header-wrap">
                    <div class="logo logo-width-1">
                        <!-- <a href='/'><img src="assets/imgs/theme/logo.svg" alt="logo"></a> -->
                        <Link :href="route('index')"  v-if="props.companies && props.companies.image && props.companies.image.status == 4">
                            <img :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" class="" height="40" width="60" :alt="props.companies.name_show" />
                        </Link>
                    </div>
                    <div class="header-right"  >
                        <div class="search-style-2">
                            <form @submit.prevent="submit" v-if="$page.props.ziggy.location == 'http://localhost:8000/website_templates'">
                                <select class="select-active">
                                    <option>کل</option>
                                </select>
                                <input v-if="$page.props.ziggy.location == 'http://localhost:8000/website_templates' " type="search" v-model="form.q"  placeholder="اسم قالب مورد نظر خود را جستجو نمایید.">

                            </form>
                            <form @submit.prevent="submitTarahiFilter" v-if="$page.props.ziggy.location == 'http://localhost:8000/website_design'">
                                <select class="select-active">
                                    <option>کل</option>
                                </select>

                                <input v-if="$page.props.ziggy.location == 'http://localhost:8000/website_design' " type="search" v-model="form.q"  placeholder="عنوان پروژه مورد نظر خود را جستجو نمایید.">
                            </form>
                            <form @submit.prevent="submitBlogFilter" v-if="$page.props.ziggy.location == 'http://localhost:8000/blog'">
                                <select class="select-active">
                                    <option>کل</option>
                                </select>

                                <input v-if="$page.props.ziggy.location == 'http://localhost:8000/blog' " type="search" v-model="form.q"  placeholder="عنوان بلاگ مورد نظر خود را جستجو نمایید.">
                            </form>
                        </div>
                        <div class="header-action-right">
								<div class="header-action-2">
									<div class="search-location">
										<!-- <form action="#">
											<select class="select-active">
												<option>مکان شما</option>
												<option>Alabama</option>
												<option>Alaska</option>
												<option>Arizona</option>
												<option>Delaware</option>
												<option>Florida</option>
												<option>Georgia</option>
												<option>Hawaii</option>
												<option>Indiana</option>
												<option>Maryland</option>
												<option>Nevada</option>
												<option>New Jersey</option>
												<option>New Mexico</option>
												<option>New York</option>
											</select>
										</form> -->
									</div>
									<!-- <div class="header-action-icon-2">
										<a href='/shop-compare'>
											<img class="svgInject" alt="Nest" src="assets/imgs/theme/icons/icon-compare.svg" />
											<span class="pro-count blue">3</span>
										</a>
										<a href='/shop-compare'><span class="lable ml-0">مقایسه کن</span></a>
									</div> -->
									<!-- <div class="header-action-icon-2">
										<a href='/shop-wishlist'>
											<img class="svgInject" alt="Nest" src="assets/imgs/theme/icons/icon-heart.svg" />
											<span class="pro-count blue">6</span>
										</a>
										<a href='/shop-wishlist'><span class="lable">لیست انتخاب شده</span></a>
									</div> -->
									<div class="header-action-icon-2">
										<a class='mini-cart-icon' href="#">
											<img alt="" :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-cart.svg'" />
											<span class="pro-count blue" v-if="props.cart &&  props.cart.count > 0">{{ (props.cart.count).toLocaleString("fa-IR") }}</span>
										</a>
										<a href="#"><span class="lable">سبد خرید</span></a>
										<div class="cart-dropdown-wrap cart-dropdown-hm2" >
											<ul v-if="props.cart">
												<li v-for="(product,index) in props.cart.products" :key="index">
													<div class="shopping-cart-img">
														<a href="#">
                                                            <img v-if="product.product.image && product.product.image.status == 4 || 5" :alt="product.product.nam" :src="$page.props.ziggy.url+'/storage/'+product.product.image.url" />
                                                        </a>
													</div>
													<div class="shopping-cart-title">
														<h4><Link :href="route('website_templates.show',[product.product.slug])">{{ product.product.name }}</Link></h4>
														<h4><span>{{(1).toLocaleString("fa-IR")}} × </span>{{ (product.product.price).toLocaleString("fa-IR") }}</h4>
													</div>
													<div class="shopping-cart-delete">
														<!-- <a href="#"><i class="fi-rs-cross-small"></i></a> -->
													</div>
												</li>

											</ul>
											<div class="shopping-cart-footer" v-if="props.cart && props.cart.total > 0">
												<div class="shopping-cart-total" v-if="props.cart">
													<h4 class="d-flex">کل <span class="ms-auto">{{ (props.cart.count).toLocaleString("fa-IR") }}</span></h4>
												</div>
												<div class="shopping-cart-button">
													<Link :href="route('cart.index')">سبد</Link>
													<Link :href="route('shop-checkout.index')">تسویه</Link>
												</div>
											</div>
                                            <div class="shopping-cart-footer" v-else >

												<div class="shopping-cart-button">
                                                    سبد خرید شما خالی است.
												</div>
											</div>

										</div>
									</div>

									<div class="header-action-icon-2" v-if="$page.props.auth.user !== null">
										<a href=''>
											<img class="svgInject" alt="" :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-user.svg'" />
										</a>
										<a  href=''><span class="lable ml-0">حساب</span></a>
										<div class="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
											<ul v-if="$page.props.auth.user !== null">
												<li><Link :href="route('dashboard.index')"><i class="fi fi-rs-user mr-10"></i>داشبورت</Link></li>
												<li><Link :href="route('order.index')"><i class="fi fi-rs-location-alt mr-10"></i>شفارشات من</Link></li>
												<li><Link :href="route('favorite.index')"><i class="fi fi-rs-heart mr-10"></i>لیست اتخاب من</Link></li>
												<!-- <li><a href=""><i class="fi fi-rs-settings-sliders mr-10"></i>تنظیمات</a></li> -->
												<li><Link :href="route('logout')" method="post" ><i class="fi fi-rs-sign-out mr-10"></i>خروج</Link></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom header-bottom-bg-color sticky-bar">
            <div class="container">
                <div class="header-wrap header-space-between position-relative">
                    <div class="logo logo-width-1 d-block d-lg-none">
                        <!-- <a href='/'><img :src="$page.props.ziggy.url +'/assets/imgs/theme/logo.svg'" alt="logo"></a> -->
                         <Link :href="route('index')"  v-if="props.companies && props.companies.image && props.companies.image.status == 4">
                            <img :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" class="" :alt="props.companies.name_show" />
                        </Link>
                    </div>
                    <div class="header-nav d-none d-lg-flex">
                        <!-- <div class="main-categori-wrap d-none d-lg-block">
                            <a class="categories-button-active " href="#">
                                <span class="fi-rs-apps"></span> <span class="et"> کل </span> دسته بندی ها
                                <i class="fi-rs-angle-down"></i>
                            </a>
                            <div class="categories-dropdown-wrap categories-dropdown-active-large font-heading">
                                <div class="d-flex categori-dropdown-inner">
                                    <ul>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-1.svg" alt="">Milks and Dairies</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-2.svg" alt="">Clothing & beauty</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-3.svg" alt="">Pet Foods & Toy</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-4.svg" alt="">Baking material</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-5.svg" alt="">Fresh Fruit</a></li>
                                    </ul>
                                    <ul class="end">
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-6.svg" alt="">Wines & Drinks</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-7.svg" alt="">Fresh Seafood</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-8.svg" alt="">Fast food</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-9.svg" alt="">Vegetables</a></li>
                                        <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/category-10.svg" alt="">Bread and Juice</a></li>
                                    </ul>
                                </div>
                                <div class="more_slide_open" style="display: none;">
                                    <div class="d-flex categori-dropdown-inner">
                                        <ul>
                                            <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/icon-1.svg" alt="">Milks and Dairies</a></li>
                                            <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/icon-2.svg" alt="">Clothing & beauty</a></li>
                                        </ul>
                                        <ul class="end">
                                            <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/icon-3.svg" alt="">Wines & Drinks</a></li>
                                            <li><a href='/shop-grid-right'> <img src="assets/imgs/theme/icons/icon-4.svg" alt="">Fresh Seafood</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="more_categories"> <span class="icon"></span> <span class="heading-sm-1">Show more...</span></div>
                            </div>
                        </div> -->
                        <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                            <nav>
                                <ul>

                                        <li>
                                            <Link :href="route('index')">صفحه اصلی</Link>
                                        </li>
                                        <li>
                                            <Link :href="route('website_templates.index','q')+'all' ">محصولات<i class="fi-rs-angle-down"></i></Link>
                                            <ul class="sub-menu" v-if="menus" v-for="(menu,index) in menus" :key="index">
                                                <li v-for="(men,index) in menu.children" :key="index" >
                                                        <Link :href="route('website_templates.index','type')+men.id+'#result'"  >{{menu.name +' '+ men.name}}</Link>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <Link :href="route('website_design.index','q')+'all' ">طراح وبسایت<i class="fi-rs-angle-down"></i></Link>
                                            <ul class="sub-menu" v-if="menusTarahi" v-for="(menu,index) in menusTarahi" :key="index">
                                                <li v-for="(men,index) in menu.children" :key="index" >
                                                        <Link :href="route('website_design.index','type')+men.id+'#result'"  >{{menu.name +' '+ men.name}}</Link>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <Link :href="route('blog.index')">بلاگ<i class="fi-rs-angle-down"></i></Link>
                                            <ul class="sub-menu" v-if="menusBlog" v-for="(menu,index) in menusBlog" :key="index">
                                                <li  >
                                                        <Link :href="route('blog.index','type')+menu.name+'#result'"  >{{menu.name}}</Link>
                                                </li>
                                            </ul>
                                        </li>
                                    <li class="position-static"><a href="#">مگا منو <i class="fi-rs-angle-down"></i></a>
                                        <ul class="mega-menu">
                                            <li class="sub-mega-menu sub-mega-menu-width-22">
                                                <a class="menu-title" href="">محصولات</a>
                                                <ul v-if="menus" v-for="(menu,index) in menus" :key="index">
                                                    <li v-for="(men,index) in menu.children" :key="index" >
                                                        <Link :href="route('website_templates.index','type')+men.id+'#result'"  >{{menu.name +' '+ men.name}}</Link>
                                                    </li>

                                                </ul>
                                            </li>
                                            <li class="sub-mega-menu sub-mega-menu-width-22">
                                                <a class="menu-title" href="">طراحی وسایت</a>
                                                <ul v-if="menusTarahi" v-for="(menu,index) in menusTarahi" :key="index">
                                                    <li v-for="(men,index) in menu.children" :key="index" >
                                                        <Link :href="route('website_design.index','type')+men.id+'#result'"  >{{menu.name +' '+ men.name}}</Link>
                                                    </li>

                                                </ul>
                                            </li>
                                            <li class="sub-mega-menu sub-mega-menu-width-22">
                                                <a class="menu-title" href="#">بلاگ</a>
                                                <ul v-if="menusBlog" v-for="(menu,index) in menusBlog" :key="index">
                                                    <li>
                                                        <Link :href="route('blog.index','type')+menu.name+'#result'">{{menu.name +' '}}</Link>
                                                    </li>

                                                </ul>
                                            </li>
                                            <li class="sub-mega-menu sub-mega-menu-width-34">
                                                <div class="menu-banner-wrap">
                                                    <a href='#'><img :src="$page.props.ziggy.url +'/assets/imgs/banner/banner-menu.png'" alt="Nest"></a>
                                                    <div class="menu-banner-content">
                                                        <h4>معاملات داغ</h4>
                                                        <h3>از دست ندهید<br> محصولات پرطرفدار</h3>
                                                        <div class="menu-banner-price">
                                                            <span class="new-price text-success">تخفیفات</span>
                                                        </div>
                                                        <div class="menu-banner-btn">
                                                            <a href='#'>الان بخر</a>
                                                        </div>
                                                    </div>
                                                    <div class="menu-banner-discount">
                                                        <h3>
                                                            <span>25%</span>
                                                            آفر
                                                        </h3>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="hotline d-none d-lg-flex" v-if="props.companies">
                        <img :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-headphone.svg'" alt="hotline">
                        <p>{{ props.companies.phone }} + <span>مرکز پشتیبانی 17/7</span></p>
                    </div>
                    <div class="header-action-icon-2 d-block d-lg-none">
                        <div class="burger-icon burger-icon-white">
                            <span class="burger-icon-top"></span>
                            <span class="burger-icon-mid"></span>
                            <span class="burger-icon-bottom"></span>
                        </div>
                    </div>
                    <div class="header-action-right d-block d-lg-none">
                        <div class="header-action-2">
                            <!-- <div class="header-action-icon-2">
                                <a href='/shop-wishlist'>
                                    <img alt="" :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-heart.svg'">
                                    <span class="pro-count white">4</span>
                                </a>
                            </div> -->
                            <div class="header-action-icon-2" >
                                <a class='mini-cart-icon' href="#" >
                                    <img alt="" :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-cart.svg'">
                                    <span class="pro-count white" v-if="props.cart &&  props.cart.count > 0">{{ (props.cart.count).toLocaleString("fa-IR") }} </span>
                                </a>
                                <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                    <ul v-if="props.cart" >
                                        <li v-for="(product,index) in props.cart.products" :key="index">
                                            <div class="shopping-cart-img">
                                                <a href='#'>
                                                    <img v-if="product.product.image && product.product.image.status == 4 || 5" :alt="product.product.nam" :src="$page.props.ziggy.url+'/storage/'+product.product.image.url" />
                                                </a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><Link :href="route('website_templates.show',[product.product.slug])">{{ product.product.name }}</Link></h4>
                                                <h3><span>{{(1).toLocaleString("fa-IR")}} × </span>{{ (product.product.price).toLocaleString("fa-IR") }}</h3>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <!-- <a href="#"><i class="fi-rs-cross-small"></i></a> -->
                                            </div>
                                        </li>

                                    </ul>
                                    <div class="shopping-cart-footer" v-if="props.cart && props.cart.total > 0">
                                        <div class="shopping-cart-total" v-if="props.cart">
                                            <h4>کل <span>{{ (props.cart.count).toLocaleString("fa-IR") }}</span></h4>
                                        </div>
                                        <div class="shopping-cart-button">
                                            <Link :href="route('cart.index')">سبد</Link>
                                            <Link :href="route('shop-checkout.index')">تسویه</Link>
                                        </div>
                                    </div>
                                    <div class="shopping-cart-footer" v-else >

                                        <div class="shopping-cart-button">
                                            سبد خرید شما خالی است.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="mobile-header-active mobile-header-wrapper-style">
        <div class="mobile-header-wrapper-inner">
            <div class="mobile-header-top">
                <div class="mobile-header-logo">
                    <Link :href="route('index')"  v-if="props.companies && props.companies.image && props.companies.image.status == 4">
                        <!-- <img :src="$page.props.ziggy.url +'/assets/imgs/theme/logo.svg'" alt="logo" /> -->
                          <img :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" class="" :alt="props.companies.name_show" />
                    </Link>
                     <!-- <Link :href="route('index')"  v-if="props.companies && props.companies.image && props.companies.image.status == 4">
                            <img :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" class="" height="40" width="60" :alt="props.companies.name_show" />
                        </Link> -->
                </div>
                <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                    <button class="close-style search-close">
                        <i class="icon-top"></i>
                        <i class="icon-bottom"></i>
                    </button>
                </div>
            </div>
            <div class="mobile-header-content-area">
                <div class="mobile-search search-style-3 mobile-header-border">
                    <form @submit.prevent="submit" v-if="$page.props.ziggy.location == 'http://localhost:8000/website_templates'">
                        <input v-if="$page.props.ziggy.location == 'http://localhost:8000/website_templates' " type="search" v-model="form.q"  placeholder="اسم قالب مورد نظر خود را جستجو نمایید." />
                        <button type="submit"><i class="fi-rs-search"></i></button>
                    </form>
                </div>
                <div class="mobile-menu-wrap mobile-header-border">
                    <!-- mobile menu start -->
                    <nav>
                        <ul class="mobile-menu font-heading">
                            <li class="menu-item-has-children">
                                <Link :href="route('index')">صفحه اصلی</Link>
                            </li>
                            <li class="menu-item-has-children">
                                <Link :href="route('website_templates.index','q')+'all' " >محصولات</Link>
                                <ul class="dropdown" v-if="menus" v-for="(menu,index) in menus" :key="index">
                                    <li v-for="(men,index) in menu.children" :key="index">
                                        <Link  :href="route('website_templates.index','type')+men.id+'#result'" >{{menu.name +' '+ men.name}}</Link>
                                    </li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children">
                                <Link :href="route('website_design.index','q')+'all' ">طراح وبسایت</Link>
                                <ul class="dropdown"  v-if="menusTarahi" v-for="(menu,index) in menusTarahi" :key="index">
                                    <li v-for="(men,index) in menu.children" :key="index">
                                        <Link :href="route('website_templates.index','type')+men.id+'#result'">{{menu.name +' '+ men.name}} </Link>
                                    </li>

                                </ul>
                            </li>
                            <li class="menu-item-has-children">
                                <Link :href="route('blog.index')">بلاگ</Link>
                                <ul class="dropdown" v-if="menusBlog" v-for="(menu,index) in menusBlog" :key="index">
                                    <li v-for="(men,index) in menu.children" :key="index">
                                        <Link :href="route('blog.index','type')+menu.name+'#result'"  >{{menu.name}}</Link>
                                    </li>

                                </ul>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="#">مگا منو </a>
                                <ul class="dropdown">
                                    <li class="menu-item-has-children">
                                        <a href="">محصولات</a>
                                        <ul class="dropdown" v-if="menus" v-for="(menu,index) in menus" :key="index">
                                            <li v-for="(men,index) in menu.children" :key="index" >
                                                <Link :href="route('website_templates.index','type')+men.id+'#result'" >{{menu.name +' '+ men.name}}</Link>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#">طراحی وسایت</a>
                                        <ul class="dropdown" v-if="menusTarahi" v-for="(menu,index) in menusTarahi" :key="index">
                                            <li v-for="(men,index) in menu.children" :key="index">
                                                <Link :href="route('website_design.index','type')+men.id+'#result'"  >{{menu.name +' '+ men.name}}</Link>
                                            </li>

                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="#">بلاگ</a>
                                        <ul class="dropdown" v-if="menusBlog" v-for="(menu,index) in menusBlog" :key="index" >
                                            <li>
                                                <Link :href="route('blog.index','type')+menu.name+'#result'">{{menu.name +' '}}</Link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                    <!-- mobile menu end -->
                </div>
                <div class="mobile-header-info-wrap">
                    <div class="single-mobile-header-info" v-if="$page.props.auth.user == null">
                        <Link :href="route('login')"><i class="fi-rs-user"></i>ورود </Link>
                    </div>
                    <div class="single-mobile-header-info" v-if="props.companies && props.companies.phone" >
                        <Link href="#"><i class="fi-rs-headphones"></i>{{ props.companies.phone }}+ </Link>
                    </div>
                </div>
                <div class="mobile-social-icon mb-50">
                    <h6 class="mb-15" v-if="props.socials">ما را دنبال کنید</h6>
                    <template v-for="social in props.socials" :key="social.id">
                        <a v-if="social.link"  :href="social.link"  :title="social.name" v-html="social.tag" ></a>
                    </template>


                    <!-- <a href="#"><img :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-facebook-white.svg'" alt="" /></a>
                    <a href="#"><img :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-twitter-white.svg'" alt="" /></a>
                    <a href="#"><img :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-instagram-white.svg'" alt="" /></a>
                    <a href="#"><img :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-pinterest-white.svg'" alt="" /></a>
                    <a href="#"><img :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-youtube-white.svg'" alt="" /></a> -->
                </div>
                <div class="site-copyright" v-if="props.companies" >©کلیه حقوق مادی و معنوی {{ props.companies.name_show }} محفوظ است.</div>
            </div>
        </div>
    </div>
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


