<script setup>
import Header from './Header2.vue';
import Footer from './Footer2.vue';
import { computed,ref} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';


const props = defineProps({
    user:Object,products:Object,tarahis:Object,orders_count:Number,companies:Object,role:Object});

const form = useForm({
    q: null,
});
const submit = ()=>{

    form.get(route('guest-product.index'));
};


const siklls = ref([]);
if (props.user && props.user.siklls)
{
    props.user.siklls.forEach(element => {
        if (element.status == 4)
        {
            siklls.value.push(element)
        }
    });
}
</script>
<template>

<Header :companies="props.companies" />
<main class="main">

            <div class="container mb-30">
                <div class="archive-header-2 text-center pt-80 pb-50">

                </div>
                <div class="row flex-row-reverse">
                    <div class="col-lg-4-5">
                        <div class="shop-product-fillter">
                            <div class="totall-product">
                                <p>ما <strong class="text-brand">29</strong> آیتم پیدا کردیم!</p>
                            </div>
                            <div class="sort-by-product-area">

                            </div>
                        </div>
                        <div class="row product-grid">
                            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="shop-product-right.html">
                                                <img class="default-img" src="assets/imgs/shop/product-1-1.jpg" alt="" />
                                                <img class="hover-img" src="assets/imgs/shop/product-1-2.jpg" alt="" />
                                            </a>
                                        </div>
                                        <div class="product-action-1">
                                            <a aria-label="Add To Wishlist" class="action-btn" href="shop-wishlist.html"><i class="fi-rs-heart"></i></a>
                                            <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i class="fi-rs-shuffle"></i></a>
                                            <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                        </div>
                                        <div class="product-badges product-badges-position product-badges-mrg">
                                            <span class="hot">Hot</span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="shop-grid-right.html">Snack</a>
                                        </div>
                                        <h2><a href="shop-product-right.html">Seeds of Change Organic Quinoe</a></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">By <a href="vendor-details-1.html">NestFood</a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
                                                <span>$28.85</span>
                                                <span class="old-price">$32.8</span>
                                            </div>
                                            <div class="add-cart">
                                                <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--product grid-->
                        <div class="pagination-area mt-20 mb-20">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-start">
                                    <li class="page-item">
                                        <a class="page-link" href="#"><i class="fi-rs-arrow-small-right"></i></a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link dot" href="#">...</a></li>
                                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#"><i class="fi-rs-arrow-small-left"></i></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
                        <div class="sidebar-widget widget-store-info mb-30 bg-3 border-0">
                            <div class="vendor-logo mb-30">
                                <!-- <img src="assets/imgs/vendor/vendor-16.png" alt="" /> -->
                                <img v-if="props.user.image && props.user.image.status == 4 " :src="$page.props.ziggy.url+'/storage/'+props.user.image.url" :alt="props.user.user_name" />
                                <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'"  :alt="props.user.user_name" />
                            </div>
                            <div class="vendor-info">
                                <div class="product-category">
                                    <span class="text-muted" v-if="props.role && props.role.id == 1" >{{ props.role.name   }}</span>
                                    <span class="text-muted" v-else-if="props.role &&  props.role.id == 2" >{{ props.role.name }} </span>
                                    <span class="text-muted" v-else-if="props.role &&  props.role.id == 3" >{{ props.role.name }}</span>
                                    <span class="text-muted" v-else-if="props.role &&  props.role.id == 4" >{{ props.role.name }}</span>
                                    <span class="text-muted" v-else >خریدار/کارفرما </span>
                                </div>
                                <h4 class="mb-5"><a href="" class="text-heading">{{ props.user.name_show }}</a></h4>

                                <div class="product-rate-cover mb-15">
                                    <!-- <div class="product-rate d-inline-block">
                                        <div class="product-rating" style="width: 90%"></div>
                                    </div>
                                    <span class="font-small ml-5 text-muted"> (4.0)</span> -->
                                </div>

                                <div class="vendor-des mb-30">
                                    <p class="font-sm text-heading" v-if=" props.user && props.user.profile ">{{ props.user.profile.biography }}</p>
                                </div>
                                <div class="follow-social mb-20" v-if="props.role &&  props.role.id > 2 && props.user && props.user.socials">
                                    <h6 class="mb-15">دنبال کن</h6>
                                    <ul class="social-network" >

                                        <li class="hover-up" v-for="(social,index) in props.user.socials" :key="index">
                                            <a href="#" v-if="social.link" :href="social.link.link" v-html="social.tag"></a>
                                        </li>

                                        <!-- <li class="hover-up">
                                            <a href="#">
                                                <img src="assets/imgs/theme/icons/social-tw.svg" alt="" />
                                            </a>
                                        </li>
                                        <li class="hover-up">
                                            <a href="#">
                                                <img src="assets/imgs/theme/icons/social-fb.svg" alt="" />
                                            </a>
                                        </li>
                                        <li class="hover-up">
                                            <a href="#">
                                                <img src="assets/imgs/theme/icons/social-insta.svg" alt="" />
                                            </a>
                                        </li>
                                        <li class="hover-up">
                                            <a href="#">
                                                <img src="assets/imgs/theme/icons/social-pin.svg" alt="" />
                                            </a>
                                        </li> -->
                                    </ul>
                                </div>

                                <div class="vendor-info">
                                    <ul class="font-sm mb-20" >
                                        <li v-if="props.user && props.user.email" ><img class="mr-5" src="/assets/imgs/theme/icons/icon-location.svg" alt="" /><strong>ایمیل: </strong> <span>{{ props.user.email }}</span></li>
                                        <li v-if="props.user && props.user.tel && props.user.tel > 0 "><img class="mr-5" src="/assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>تماس:</strong><span>{{ props.user.tel }}</span></li>
                                    </ul>
                                    <!-- <a href="vendor-details-1.html" class="btn btn-xs">تماس <i class="fi-rs-arrow-small-right"></i></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
<div class="main">
    <section class="hero-section pt-100 "
                    style="background: url('../img/hero-bg-shape-2.png')no-repeat center center / cover;">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-9 col-lg-7">
                    <div class="page-header-content text-white text-center pt-sm-5 pt-md-5 pt-lg-0">
                        <h1 class="text-white mb-0">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">پروفایل کاربر</font>
                            </font>
                        </h1>
                        <div class="custom-breadcrumb">
                            <ol class="breadcrumb d-inline-block bg-transparent list-inline py-0">
                                <li class="list-inline-item breadcrumb-item"><a href="#">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">صفحه اصلی</font>
                                        </font>
                                    </a></li>
                                <li class="list-inline-item breadcrumb-item"><a href="#">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">صفحات</font>
                                        </font>
                                    </a></li>
                                <li class="list-inline-item breadcrumb-item active">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">پروفایل کاربر</font>
                                    </font>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="background-header" style="height:5rem"></div>
    </section>
    <section class="promo-section ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 col-sm-12 col-lg-5">
                    <div class="team-single-img">
                        <img v-if="props.user.image && props.user.image.status == 4 " :src="$page.props.ziggy.url+'/storage/'+props.user.image.url" :alt="props.user.user_name" class="img-fluid rounded shadow-sm">
                        <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" class="img-fluid rounded shadow-sm" :alt="props.user.user_name" />
                        </div>
                </div>
                <div class="col-md-12 col-sm-12 col-lg-6">
                    <div class="team-single-text">
                        <div class="team-name mb-4">
                            <h4 class="mb-1">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">{{ props.user.name_show }}</font>
                                </font>
                            </h4>
                            <span>
                                <font style="vertical-align: inherit;" >
                                    <!-- <font style="vertical-align: inherit;" v-if="props.role" >{{ props.role.name }}</font>
                                    <font style="vertical-align: inherit;" v-else >خریدار</font> -->
                                </font>
                            </span>
                        </div>
                        <ul class="team-single-info" v-if="props.role &&  props.role.id > 2">
                            <li v-if="props.user && props.user.tel">
                                <strong>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">تلفن: </font>
                                    </font>
                                </strong><span>
                                    <a :href="'tel:'+props.user.tel">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;" v-if="props.user && props.user.tel > 0">{{ props.user.tel }}</font>
                                            <font style="vertical-align: inherit;" v-else>-</font>
                                        </font>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <strong>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">ایمیل: </font>
                                    </font>
                                </strong><span>
                                    <a :href="'mailto:'+props.user.email">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;" v-if="props.user && props.user.email" >{{ props.user.email }}</font>
                                        <font style="vertical-align: inherit;" v-else>-</font>
                                    </font>
                                    </a>
                                </span>
                            </li>
                        </ul>
                        <div class="text-content mt-20">
                            <p v-if="props.user && props.user.profile">
                                <font style="vertical-align: inherit;">
                                    {{ props.user.profile.biography }}
                                </font>
                            </p>

                        </div>
                        <ul class="team-social-list list-inline mt-4" v-if="props.role && props.role.id > 2">
                            <!-- <li v-for="(links,index) in props.user.socials" :key="index" class="list-inline-item" v-html="links.tag"></li> -->
                                <li class="list-inline-item" v-for="(social,index) in props.user.socials" :key="index" >
                                    <a v-if="social.link" :href="social.link.link" v-html="social.tag" class="color-primary"></a>
                                </li>

                            <!-- <li class="list-inline-item">
                                <a href="#" class="color-primary"><span
                                        class="ti-facebook"></span></a>
                                </li>
                            <li class="list-inline-item"><a href="#" class="color-primary"><span
                                        class="ti-instagram"></span></a></li>
                            <li class="list-inline-item"><a href="#" class="color-primary"><span
                                        class="ti-dribbble"></span></a></li>
                            <li class="list-inline-item"><a href="#" class="color-primary"><span
                                        class="ti-linkedin"></span></a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="section-heading">
                        <h5>
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">عناوین و سمت</font>
                            </font>
                        </h5>
                        <div class="section-heading-line-left"></div>
                    </div>
                    <ul class="list-unstyled">
                        <li class="py-2">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="badge badge-primary mr-3"><span class="ti-check"></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;" v-if="props.role &&  props.role.id == 1" >{{ props.role.name }}</font>
                                            <font style="vertical-align: inherit;" v-else-if="props.role && props.role.id == 2" >{{ props.role.name }}</font>
                                            <font style="vertical-align: inherit;" v-else-if="props.role && props.role.id == 3" >تیم مدیریت {{ props.companies.name }}</font>
                                            <font style="vertical-align: inherit;" v-else-if="props.role && props.role.id == 4" >مدیریت {{ props.companies.name }}</font>
                                            <font style="vertical-align: inherit;" v-else >خریدار/کارفرما</font>
                                        </font>
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="section-heading mt-40">
                        <h5>
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">مهارتهای حرفه ای</font>
                            </font>
                        </h5>
                        <div class="section-heading-line-left"></div>
                    </div>
                    <div class="mt-3" v-if="props.user && props.user.siklls.length > 0 ">
                        <div class="progress-item" v-for="sikll,index in siklls" :key="index">
                            <div v-if="sikll.status == 4">
                                <div class="progress-title">
                                    <h6>
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">{{ sikll.subject }}</font>
                                        </font>
                                        <span class="float-right" ><span class="progress-number">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">{{ sikll.number }}</font>
                                                </font>
                                            </span>
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"> درصد</font>
                                            </font>
                                        </span>
                                    </h6>
                                </div>
                                <div class="progress p-1">
                                    <span :style="'width: '+ sikll.number+'%'"><span
                                            class="progress-line"></span>
                                    </span>
                                </div>
                            </div>
                            <div v-else>
                                <h6>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">ثبت نشده {{ index }}</font>
                                    </font>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <h6 v-else>
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">ثبت نشده</font>
                        </font>
                    </h6>
                </div>
            </div>
        </div>
    </section>

</div>


<Footer :companies="props.companies" />
</template>

