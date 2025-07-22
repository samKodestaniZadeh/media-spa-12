<script setup>

import { computed, ref,watch,onMounted } from 'vue';
import Footer from './Footer2.vue';
import Header from './Header2.vue';
import Seo from '@/Components/Seo.vue';
import { Head,useForm, usePage,router,Link } from '@inertiajs/vue3';
import swal from 'sweetalert2';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    auth: Object,
    cartCount: Number,
    discounts: Object,
    time: String,
    alert: Object,
    menus: Object,
    coupon_count: Number,
    companies: Object,
    socials: Object,
    path: String,
    canLogin: Boolean,canRegister: Boolean,laravelVersion: String,phpVersion: String,auth:Object,results:Object,
    time:String,coupon_count:Number,companies:Object,users_count:Number,querystring:String,menu: Object,
    products_count:Number,comments_count:Number,tarahis_count:Number,alert: Object,orders:Object,
    usersOrders:Object,cart:Object,webDesigns:Object,blogs:Object
});


const submitCart = (id) => {
  form.id = id;
  form.model = 'App\\Models\\Product';
  form.post(route('cart.store'));
};

watch(() => props.alert, (val) => {
  if (val) {
    if (val.title) {
      swal.fire(val.title, val.text, val.icon);
    } else {
      swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', swal.stopTimer);
          toast.addEventListener('mouseleave', swal.resumeTimer);
        }
      }).fire({
        title: val.text,
        icon: val.icon,
      });
    }
  }
});

watch(() => errors.value, (val) => {
  if (val && Object.keys(val).length > 0) {
    Object.values(val).forEach((errMsg) => {
      swal
        .mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", swal.stopTimer);
            toast.addEventListener("mouseleave", swal.resumeTimer);
          },
        })
        .fire({
          title: errMsg,
          icon: "error",
        });
    });
  }
});

if (hasErrors.value == true) {
    swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', swal.stopTimer);
            toast.addEventListener('mouseleave', swal.resumeTimer);
        },
    }).fire({
        title: [
            errors.value.menu ? errors.value.menu + '<br>' : '',
            errors.value.recepiant ? errors.value.recepiant + '<br>' : '',
            errors.value.subject ? errors.value.subject + '<br>' : '',
            errors.value.text ? errors.value.text + '<br>' : '',
            errors.value.email ? errors.value.email + '<br>' : '',
            errors.value.name ? errors.value.name + '<br>' : '',
            errors.value.lasst_name ? errors.value.lasst_name + '<br>' : '',
        ],
        icon: 'error',
    });
}
const form = useForm({
    menu: null,
    recepiant: null,
    subject: null,
    text: null,
    email: null,
    name: null,
    lasst_name: null,
    id: null,
    type:null,
    model:null,
});

const validate = (text) => {
    swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', swal.stopTimer);
            toast.addEventListener('mouseleave', swal.resumeTimer);
        },
    }).fire({
        title: text,
        icon: 'error',
    });
};

const menus = ref([]);

if (props.menus && props.menus.length > 0) {
    props.menus.forEach((element) => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach((route) => {
                if (route.name == props.path) {
                    element.sections.forEach((section) => {
                        if (section.name == 'supports') {
                            menus.value.push(element);
                        }
                    });
                }
            });
        }
    });
}

const menu = ref([]);

const recepiant = () => {
    if (menu.value.length > 0) {
        menu.value.splice(0);
    }
    (form.subject = null),
        menus.value.forEach((element) => {
            if (form.recepiant == element && element.children.length > 0) {
                element.children.forEach((child) => {
                    if (child.routes.length > 0) {
                        child.routes.forEach((route) => {
                            if (route.name == props.path) {
                                if (child.sections.length > 0) {
                                    child.sections.forEach((section) => {
                                        if (section.name == 'supports') {
                                            menu.value.push(child);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
            }
        });
};


const submit = () => {
    if (props.auth.user !== null) {
        if (form.recepiant && form.subject && form.text) {
            form.post(route('support.store'));
        } else {
            let text;
            text = 'موارد ستاره دار الزامی است.';
            validate(text);
        }
    } else {
        if (form.recepiant && form.subject && form.text && form.email && form.name && form.lasst_name) {
            form.post(route('guest-support.store'));
        } else {
            let text;
            text = 'موارد ستاره دار الزامی است.';
            validate(text);
        }
    }
};

const Quickview = ref(null);
const submitQuickview = (result) =>{
    Quickview.value = result

}

const discounts = ref([]);
if (props.discounts.data) {
    props.discounts.data.forEach(discount => {
        discounts.value.push(discount)
    });

}
const titleSeo = 'بزرگترین مرجع طراحی سایت و قالب در ایران';
const descriptionSeo = 'خلاصه‌ای از محتوای صفحه برای نمایش در گوگل.';
const keywordsSeo ='گوگل دیگه ازش استفاده نمی‌کنه، ولی بعضی موتورهای داخلی ممکنه هنوز بخوننش';
const robotsSeo = 'ه موتور جستجو می‌گه آیا این صفحه ایندکس بشه یا نه.';
</script>
<template>
    <Seo  :title="titleSeo" :description="descriptionSeo" :keywords="keywordsSeo" :robots="robotsSeo" />
    <Head title="index" />
    <Header :companies="props.companies" :results="props.results" :Quickview="Quickview" :menus="props.menus" :cart="props.cart" :menu="props.menu"
        @event-submit-quickview="submitQuickview"  @event-submit-cart="submitCart"
     />
    <!-- <p v-if="typeof window === 'undefined'">✅ SSR رندر شد (سمت سرور)</p>
    <p v-else>👀 این فقط در مرورگر دیده میشه</p> -->
    <!--End header-->
    <main class="main">
        <section class="home-slider position-relative mb-30">
            <div class="container">
                <div class="home-slide-cover mt-30">
                    <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                        <div class="single-hero-slider single-animation-wrap" style="background-image: url(assets/imgs/slider/slider-1.png)">
                            <div class="slider-content">
                                <h1 class="display-2 mb-40">
                                    محصولات شگفت <br />
                                    انگیز را از دست ندهید
                                </h1>
                                <!-- <p class="mb-65">جهت اطلاع از آخرین اخباردر خبرنامه ثبت نام کنید</p>
                                <form class="form-subcriber d-flex">
                                    <input type="email" placeholder="آدرس ایمیل شما" />
                                    <button class="btn" type="submit">اشتراک</button>
                                </form> -->
                            </div>
                        </div>
                        <div class="single-hero-slider single-animation-wrap" style="background-image: url(assets/imgs/slider/slider-2.png)">
                            <div class="slider-content">
                                <h1 class="display-2 mb-40">
                                    طرح تخفیف<br />
                                    روزانه را از دست ندهید
                                </h1>
                                <!-- <p class="mb-65">جهت اطلاع از آخرین اخباردر خبرنامه ثبت نام کنید</p>
                                <form class="form-subcriber d-flex">
                                    <input type="email" placeholder="آدرس ایمیل شما" />
                                    <button class="btn" type="submit">اشتراک</button>
                                </form> -->
                            </div>
                        </div>
                    </div>
                    <div class="slider-arrow hero-slider-1-arrow"></div>
                </div>
            </div>
        </section>
        <!--End hero slider-->
        <section class="product-tabs section-padding position-relative" v-if="props.results.length > 0">
            <div class="container">
                <div class="section-title style-2 wow animate__animated animate__fadeIn">
                    <h3>محصول </h3>
                    <ul class="nav nav-tabs links" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <Link class="show-all" :href="route('website_templates.index','q')+'all' " > نمایش <i class="fi-rs-angle-left"></i> </Link>
                        </li>

                    </ul>
                </div>
                <!--End nav-tabs-->
                <div class="tab-content" id="myTabContent" >
                    <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                        <div class="row product-grid-4" v-if="props.results">
                            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6"  v-for="(result ,index) in props.results" :key="index">
                                <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s"  >
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <Link :href="route('website_templates.show',[result.slug])" v-if="result.image && result.image.status == 4 || 5">
                                                <img class="default-img" :src="$page.props.ziggy.url+'/storage/'+result.image.url" alt="" />
                                                <img class="hover-img" :src="$page.props.ziggy.url+'/storage/'+result.image.url" alt="" />
                                            </Link>
                                            <Link :href="route('website_templates.show',[result.slug])"  v-else-if="props.companies">
                                                <img class="default-img" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" alt="" />
                                                <img class="hover-img" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" alt="" />
                                            </Link>
                                        </div>
                                        <!-- <div class="product-action-1">
                                            <a aria-label="واچ لیست" class="action-btn" href="/shop-wishlist"><i class="fi-rs-heart"></i></a>
                                            <a aria-label="مقایسه" class="action-btn" href="/shop-compare"><i class="fi-rs-shuffle"></i></a>
                                            <a @click.prevent="submitQuickview(result)" aria-label="نمایش سریع" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"
                                                ><i class="fi-rs-eye"></i
                                            ></a>
                                        </div> -->
                                        <div class="product-badges product-badges-position product-badges-mrg" v-if="result.discount" >
                                            <span class="hot">{{result.discount.percent}}% تخفیف </span>
                                        </div>
                                    </div>
                                    <div class="product-content-wrap" >
                                        <div class="product-category">
                                            <template v-for="(menu,index) in result.menus" :key="index">
                                                <template v-for="(section,index) in menu.sections" :key="section.id">
                                                    <Link href="" v-if="section.name == 'products'" >{{ menu.name + ' '}}</Link>
                                                </template>
                                            </template>

                                        </div>
                                        <h2><Link :href="route('website_templates.show',[result.slug])">{{result.name}}</Link></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block" >
                                                <div class="product-rating" v-if="result.ratings_avg_rating" :style="'width:' + result.ratings_avg_rating*20 + '%' "></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted" v-if="result.ratings_avg_rating" > ({{ result.ratings_avg_rating }})</span>
                                            <span class="font-small ml-5 text-muted" v-else > (0.000)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">فروشنده <Link :href="route('guest-profile.show', result.user.user_name)">({{ result.user.name_show }})</Link></span>
                                        </div>
                                        <div class="product-card-bottom" >
                                            <div class="product-price" v-if="result.discount">
                                                <span>{{(result.price-(result.price*result.discount.percent/100)).toLocaleString("fa-IR")}}</span>
                                                <span class="old-price">{{(result.price).toLocaleString("fa-IR")}}</span>
                                            </div>
                                            <div class="product-price" v-else>
                                                <span>{{(result.price).toLocaleString("fa-IR")}}</span>
                                                <!-- <span class="old-price">$32.8</span> -->
                                            </div>
                                            <div class="add-cart">
                                                <Link class="add" href="" @click.prevent="submitCart(result.id)" ><i class="fi-rs-shopping-cart mr-5"></i>خرید </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end product card-->
                        </div>
                        <!--End product-grid-4-->
                    </div>
                    <!--En tab one-->
                </div>
                <!--End tab-content-->
            </div>
        </section>
        <!--Products Tabs-->
        <section class="section-padding pb-5" v-if="props.webDesigns.length > 0">
                <div class="container">
                    <div class="section-title wow animate__animated animate__fadeIn" data-wow-delay="0">
                        <h3 class="">پروژه</h3>
                        <Link class="show-all" :href="route('website_design.index','q')+'all' " > نمایش <i class="fi-rs-angle-left"></i> </Link>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6" v-for="(result ,index) in props.webDesigns" :key="index" >
                            <div  class="product-cart-wrap style-2 wow animate__animated animate__fadeInUp" data-wow-delay="0" >
                                <div class="product-img-action-wrap">
                                    <div class="product-img" >
                                        <Link :href="route('website_design.show',[result.slug])" v-if="result.image && result.image.status == 4 || result.image &&  result.image.status == 5">
                                            <img class="h-300" :src="$page.props.ziggy.url+'/storage/'+result.image.url" alt=""  />
                                        </Link>
                                        <Link :href="route('website_design.show',[result.slug])"  v-else-if="props.companies">
                                            <img class="h-300" src="/storage/images/logo.jpg" alt=""  />
                                        </Link>
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="deals-countdown-wrap">
                                        <div class="deals-countdown" :data-countdown="result.expired_at"></div>
                                    </div>
                                    <div class="deals-content">
                                        <h2><Link :href="route('website_design.show',[result.slug])">{{result.title}}</Link></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" v-if="result.ratings_avg_rating" :style="'width:' + result.ratings_avg_rating*20 + '%' "></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted" v-if="result.ratings_avg_rating" > ({{ result.ratings_avg_rating }})</span>
                                            <span class="font-small ml-5 text-muted" v-else > (0.000)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">کارفرما <Link :href="route('guest-profile.show', result.user.user_name)">({{ result.user.name_show }})</Link></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price"  v-if="result.discount">
                                                <span>{{(result.price-(result.price*result.discount.percent/100)).toLocaleString("fa-IR")}}</span>
                                                <span class="old-price">{{(result.price).toLocaleString("fa-IR")}}</span>
                                            </div>
                                            <div class="product-price" v-else>
                                                <span>{{(result.price).toLocaleString("fa-IR")}}</span>
                                                <!-- <span class="old-price">$32.8</span> -->
                                            </div>
                                            <div class="add-cart">
                                                <Link class="add" :href="route('website_design.show', [result.slug])" ><i class="mr-5"></i>جزییات </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <!--Products Tabs-->
        <!--End hero slider-->
        <section class="product-tabs section-padding position-relative" v-if="props.blogs.length > 0">
            <div class="container">
                <div class="section-title style-2 wow animate__animated animate__fadeIn">
                    <h3>وبلاگ  </h3>
                    <ul class="nav nav-tabs links" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <Link class="show-all" :href="route('blog.index') " > نمایش <i class="fi-rs-angle-left"></i> </Link>
                        </li>

                    </ul>
                </div>
                <!--End nav-tabs-->
                <div class="tab-content" id="myTabContent" >
                    <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                        <div class="row product-grid-4">
                            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6"  v-for="(result ,index) in props.blogs" :key="index">
                                <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s"  >
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <Link :href="route('blog.show',[result.slug])" v-if="result.image && result.image.status == 4 || 5">
                                                <img class="default-img" :src="$page.props.ziggy.url+'/storage/'+result.image.url" alt="" />
                                                <img class="hover-img" :src="$page.props.ziggy.url+'/storage/'+result.image.url" alt="" />
                                            </Link>
                                            <Link :href="route('blog.show',[result.slug])"  v-else-if="props.companies">
                                                <img class="default-img" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" alt="" />
                                                <img class="hover-img" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" alt="" />
                                            </Link>
                                        </div>

                                        <div class="product-badges product-badges-position product-badges-mrg" >
                                            <!-- <span class="hot"></span> -->
                                        </div>
                                    </div>
                                    <div class="product-content-wrap" >
                                        <div class="product-category">
                                            <template v-for="(menu,index) in result.menus" :key="index">
                                                <template v-for="(section,index) in menu.sections" :key="section.id">
                                                    <a href="" v-if="section.name == 'blogs'" >{{ menu.name + ' '}}</a>
                                                </template>
                                            </template>

                                        </div>
                                        <h2><Link :href="route('blog.show',[result.slug])">{{result.title}}</Link></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block" >
                                                <div class="product-rating" v-if="result.ratings_avg_rating" :style="'width:' + result.ratings_avg_rating*20 + '%' "></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted" v-if="result.ratings_avg_rating" > ({{ result.ratings_avg_rating }})</span>
                                            <span class="font-small ml-5 text-muted" v-else > (0.000)</span>
                                        </div>
                                        <div>
                                            <span class="font-small text-muted">نویسنده <Link :href="route('guest-profile.show', result.user.user_name)">({{ result.user.name_show }})</Link></span>
                                        </div>
                                        <div class="product-card-bottom" >
                                            <div class="product-price" v-if="result.discount">
                                                <span>{{(result.price-(result.price*result.discount.percent/100)).toLocaleString("fa-IR")}}</span>
                                                <span class="old-price">{{(result.price).toLocaleString("fa-IR")}}</span>
                                            </div>
                                            <div class="product-price" v-else>

                                                <!-- <span class="old-price">$32.8</span> -->
                                            </div>
                                            <div class="add-cart">
                                                <Link class="add" :href="route('blog.show',[result.slug])" ><i class=" mr-5"></i>جزئیات </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end product card-->
                        </div>
                        <!--End product-grid-4-->
                    </div>
                </div>
                <!--End tab-content-->
            </div>
        </section>

    </main>
    <Footer :companies="props.companies" :socials="props.socials" :time="props.time" :menus="props.menus" :path="props.path" />
</template>
