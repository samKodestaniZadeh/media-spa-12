<script setup>

import Header from './Header2.vue';
import Footer from './Footer2.vue';
import { computed, ref,watch } from 'vue';
import { Link, useForm, usePage ,router} from '@inertiajs/vue3';
import swal from 'sweetalert2';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import 'vue3-carousel/dist/carousel.css'
import CommentReply from '@/Components/CommentReply.vue';
import Editor from '@tinymce/tinymce-vue';
const ApiKey = ref('cfw3yx4hh06riwl1qwbq3fwcmjr80c5v0z2ki1fid7agx2ow');

const props = defineProps({
    product: Object, auth: Object, cart: Object, time: String, count: Number,alert: Object,
    flash: String, product_count: Number, product_order: Number, coupon_count: Number,
    companies: Object,product_averageRating:String,product_usersRated:Number,carousels:Object,
    users:Object,
});


const page = usePage()
const errors = computed(() => page.props?.errors || {})
const rawFavorite = computed(() => page.props.product.favorite)
const favorite = ref(null);
watch(rawFavorite, (val) => {
    favorite.value = null
        val.forEach(element => {

                if (element.favoritable_id == page.props.product.id && props.auth.user && props.auth.user.id == element.user_id) {
                    favorite.value = element
                }
                else
                {
                    favorite.value = null
                }
        });
})




const form = useForm({
    id: null,
    type:null,
    model:null,
    text:null,
    parent_id:null,
    user_id:null,
    product_id:null,
});
const submitFavorite = (product) => {
  form.id = product.id
  form.type = 'App\\Models\\Product'

  form.post(route('favorite.store')

)}

const submitCart = (id) => {
  form.id = id;
  form.model = 'App\\Models\\Product';
  form.post(route('cart.store'));
};


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

watch(errors, (newErrors) => {
  const errorMessages = Object.values(newErrors)
    .flat()
    .map(msg => `${msg}<br>`)
    .join('')

  if (errorMessages) {
    swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
      }
    }).fire({
      title: errorMessages,
      icon: 'error',
    })
  }
}, { immediate: true })


props.product.favorite.forEach(element => {
    if (props.auth.user && element.user_id == props.auth.user.id) {
        favorite.value = element
    }
});
const settings={
      itemsToShow: 1,
      snapAlign: 'center',
};

const breakpoints={
      // 700px and up
      700: {
        itemsToShow: 1,
        snapAlign: 'center',
      },
      // 1024 and up
      1024: {
        itemsToShow: 1,
        snapAlign: 'start',
      },
}

const menus = ref([]);

if (props.product.menus.length > 0) {

    props.product.menus.forEach(element => {
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

const prerequisites = ref([]);

if (props.product.menus.length > 0) {
    props.product.menus.forEach(element => {

            if (element.sections.length > 0)
            {
                element.sections.forEach(section => {
                    if (section.name == 'prerequisites') {
                        prerequisites.value.push(element)
                    }

                });
            }

    });
}

const additional_facilities = ref([]);
if (props.product.menus.length > 0) {
    props.product.menus.forEach(element => {
        if(element.sections.length > 0)
        {
            element.sections.forEach(section => {
                if (section.name == 'additional_facilities') {
                    additional_facilities.value.push(element)
                }
            });
        }
    });
}

const browsers = ref([]);
if (props.product.menus.length > 0) {
    props.product.menus.forEach(element => {
        if (element.sections.length > 0) {
            element.sections.forEach(section => {
                if (section.name == 'browsers') {
                    browsers.value.push(element)
                }
            });
        }

    });
}

const tests = ref([]);

if (props.product.menus.length > 0) {
    props.product.menus.forEach(element => {
        if (element.sections.length > 0)
        {
            element.sections.forEach(section =>  {
                if (section.name == 'tests') {
                    tests.value.push(element)
                }
            })
        }
    });
}

const submitComment = () => {

    form.user_id=props.product.user.id
    form.product_id=props.product.id
    if(form.text == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('comment.store'))
    }

}

const submitReply = (id) => {
    form.parent_id = id
    form.user_id=props.product.user.id
    form.product_id=props.product.id

    if(form.text == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('comment.store'));
    }
}

</script>
<template>
    <Header :companies="props.companies" :results="props.results"  :menus="props.menus" :cart="props.cart" />
    <main class="main">

            <div class="container mb-30">
                <div class="row">
                    <div class="col-xl-10 col-lg-12 m-auto">
                        <div class="product-detail accordion-detail">
                            <div class="row mb-50 mt-30">
                                <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                    <div class="detail-gallery">
                                        <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                        <!-- MAIN SLIDES -->
                                        <div class="product-image-slider">
                                            <figure class="border-radius-10">
                                                <img :src="$page.props.ziggy.url + '/storage/' + props.product.image.url" alt="product image" />
                                            </figure>

                                        </div>
                                        <!-- THUMBNAILS -->

                                    </div>
                                    <!-- End Gallery -->
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="detail-info pr-30 pl-30">
                                        <span class="stock-status out-stock" v-if="props.product.discount"> تخفیف </span>
                                        <h2 class="title-detail">{{ props.product.name }}</h2>
                                        <div class="product-detail-rating">
                                            <div class="product-rate-cover text-end">
                                                <div class="product-rate d-inline-block">
                                                    <div class="product-rating" v-if="props.product.ratings_avg_rating"
                                                    :style="'width:' + props.product.ratings_avg_rating * 20 + '%'"></div>
                                                </div>
                                                <span class="font-small ml-5 text-muted" v-if="props.product.ratings_avg_rating"> ({{ props.product.ratings_avg_rating }})</span>
                                                <span class="font-small ml-5 text-muted" v-else> (0.0000)</span>
                                            </div>
                                        </div>
                                        <div class="clearfix product-price-cover">
                                            <div class="product-price primary-color float-left" v-if="props.product.discount">
                                                <span class="current-price text-brand" >
                                                    {{
                                                                (props.product.price - (props.product.price * props.product.discount.percent) / 100).toLocaleString(
                                                                    'fa-IR',
                                                                )
                                                            }}
                                                </span>
                                                <span>
                                                    <span class="save-price font-md color3 ml-15" v-if="props.product.discount">{{props.product.discount.percent}}% تخفیف</span>
                                                    <span class="old-price font-md ml-15">{{ props.product.price.toLocaleString('fa-IR') }}</span>
                                                </span>
                                            </div>

                                            <div class="product-price primary-color float-left" v-else>
                                                <span class="current-price text-brand" >
                                                    {{
                                                                (props.product.price).toLocaleString(
                                                                    'fa-IR',
                                                                )
                                                            }}
                                                </span>

                                            </div>
                                        </div>
                                        <div class="short-desc mb-30">
                                            <p class="font-lg">
                                                <template v-for="(menu, index) in props.product.menus" :key="index">
                                                    <template v-for="(section, index) in menu.sections" :key="section.id">
                                                        <span href="" v-if="section.name == 'products'">{{ menu.name + ' ' }}</span>
                                                    </template>
                                                </template>
                                            </p>
                                        </div>
                                        <div class="attr-detail attr-size mb-30">
                                            <!-- <strong class="mr-10">Size / Weight: </strong>
                                            <ul class="list-filter size-filter font-small">
                                                <li><a href="#">50g</a></li>
                                                <li class="active"><a href="#">60g</a></li>
                                                <li><a href="#">80g</a></li>
                                                <li><a href="#">100g</a></li>
                                                <li><a href="#">150g</a></li>
                                            </ul> -->
                                        </div>
                                        <div class="detail-extralink mb-50">
                                            <div class="detail-qty border radius">
                                                <!-- <a href="#" class="qty-down"><i class="fi-rs-angle-small-down"></i></a> -->
                                                <span class="qty-val">1</span>
                                                <!-- <a href="#" class="qty-up"><i class="fi-rs-angle-small-up"></i></a> -->
                                            </div>
                                            <div class="product-extra-link2">
                                                <button type="submit" class="button button-add-to-cart" @click.prevent="submit( props.product.id)"><i class="fi-rs-shopping-cart"></i>خرید</button>
                                                <a aria-label="Add To Wishlist" class="action-btn hover-up " :class="favorite && favorite.favoritable_id == props.product.id ? 'text-brand' : ''" href="" @click.prevent="submitFavorite(props.product)"><i class="fi-rs-heart"></i></a>
                                                <!-- <a aria-label="Compare" class="action-btn hover-up" href=""><i class="fi-rs-shuffle"></i></a> -->
                                            </div>
                                        </div>
                                        <div class="font-xs">
                                            <ul class="mr-50 float-start">
                                                <li class="mb-5">ورژن: <span class="text-brand">{{ props.product.version }}</span></li>
                                                <li class="mb-5">انتشار:<span class="text-brand"> {{ moment(product.created_at).locale("fa", fa).format('jYYYY/jM/jD') }}</span></li>
                                            </ul>
                                            <ul class="float-start">
                                                <li class="mb-5">بروز رسانی: <a href="">{{ moment(product.updated_at).locale("fa", fa).format('jYYYY/jM/jD') }}</a></li>
                                                <li class="mb-5">تگ: <a href="" rel="tag">Snack</a>, <a href="" rel="tag">Organic</a>, <a href="" rel="tag">Brown</a></li>

                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Detail Info -->
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="tab-style3">
                                    <ul class="nav nav-tabs text-uppercase">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description" >توضیحات</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="Vendor-info-tab" data-bs-toggle="tab" href="#Vendor-info">فروشنده</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews">بازخورد </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content shop_info_tab entry-main-content">
                                        <div class="tab-pane fade show active" id="Description">
                                            <div v-html="props.product.text">

                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="Vendor-info">
                                            <div class="vendor-logo d-flex mb-30">
                                                <img v-if="props.product.user && props.product.user.image" :src="$page.props.ziggy.url + '/storage/' + props.product.user.image.url" alt="" />
                                                <img v-else :src="$page.props.ziggy.url + '/storage/images/default-user.png'" alt="" />
                                                <div class="vendor-name ml-15">
                                                    <h6>
                                                        <Link :href="route('profile.show', [props.product.user.user_name])">{{ props.product.user.name_show }}</Link>
                                                    </h6>
                                                    <div class="product-rate-cover text-end" v-if="props.product.ratings_avg_rating > 0 ">
                                                        <div class="product-rate d-inline-block">
                                                            <div class="product-rating" :style="'width:' + props.product.ratings_avg_rating * 20 + '%'"></div>
                                                        </div>
                                                        <span class="font-small ml-5 text-muted"> ({{ props.product.ratings_avg_rating }})</span>
                                                    </div>
                                                    <div class="product-rate-cover text-end" v-else>
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" style="width: 0%"></div>
                                                    </div>
                                                    <span class="font-small ml-5 text-muted"> (0.000)</span>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="d-flex mb-55">
                                                <!-- <div class="mr-30">
                                                    <p class="text-brand font-xs">Rating</p>
                                                    <h4 class="mb-0">92%</h4>
                                                </div>
                                                <div class="mr-30">
                                                    <p class="text-brand font-xs">ارسال به موقع</p>
                                                    <h4 class="mb-0">100%</h4>
                                                </div>
                                                <div>
                                                    <p class="text-brand font-xs">پاسخ چت</p>
                                                    <h4 class="mb-0">89%</h4>
                                                </div> -->
                                            </div>
                                            <p v-if="props.product.user && props.product.user.profile">{{ props.product.user.profile.biography }}</p>
                                        </div>
                                        <div class="tab-pane fade" id="Reviews">
                                            <!--Comments-->
                                            <div class="comments-area">
                                                <div class="row">
                                                     <div class="col-lg-8">
                                                        <h4 class="mb-30">پرسش و پاسخ</h4>
                                                        <div class="comment-list" style="position: relative;">
                                                            <template v-for="(comment,index) in props.product.comments" :key="index">
                                                                <div class="single-comment justify-content-between d-flex mb-30"  >
                                                                        <div class="user justify-content-between d-flex">
                                                                            <div class="thumb text-center">
                                                                                <img  v-if="comment.user.image  && comment.user.image.url && comment.user.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+comment.user.image.url" :alt="comment.user.name_show" />
                                                                                <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="comment.user.name_show" >
                                                                                <a href="" class="font-heading text-brand">{{comment.user.name_show}}</a>
                                                                            </div>
                                                                            <div class="desc">
                                                                                <div class="d-flex justify-content-between mb-10">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <span class="font-xs text-muted">{{ moment(comment.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}</span>
                                                                                    </div>
                                                                                    <!-- <div class="product-rate d-inline-block">
                                                                                        <div class="product-rating" style="width: 100%"></div>
                                                                                    </div> -->
                                                                                </div>
                                                                                <p class="mb-10"> <span v-html="comment.text"> </span>
                                                                                    <a v-if="props.auth.user"
                                                                                    data-bs-toggle="collapse" :href="'#multiCollapseExample'+comment.id"
                                                                                    aria-expanded="false" :aria-controls="'multiCollapseExample'+comment.id"
                                                                                    class="reply">پاسخ</a>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                 <!--comment form-->
                                                                 <div class="comment-form collapse " :id="'multiCollapseExample' + comment.id" >
                                                                    <h4 class="mb-15">ارسال پاسخ</h4>
                                                                    <div class="product-rate d-inline-block mb-30"></div>
                                                                    <div class="row" >
                                                                        <div class="col-lg-8 col-md-12" >
                                                                            <form class="form-contact comment_form" id="commentForm">
                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <!-- <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="Write Comment"></textarea> -->
                                                                                            <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text" />
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <button type="submit" @click.prevent="submitReply(comment.id)" class="button button-contactForm" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                                                        <span v-if="form.processing">پردازش...</span>
                                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                                                        <span v-else >ارسال</span>
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        <!-- <div class="comment-respond mt-5" v-else>
                                                                            <p class="comment-notes">برای ارسال نظر وارد حساب کاربری خود شوید.</p>
                                                                        </div> -->
                                                                    </div>
                                                                </div>
                                                                <CommentReply :comments="comment.replies" :comment_id ="comment.id"  :product="props.product"  />
                                                            </template>

                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-lg-4">
                                                        <h4 class="mb-30">نظرات</h4>
                                                        <div class="d-flex mb-30">
                                                            <div class="product-rate d-inline-block mr-15">
                                                                <div class="product-rating" style="width: 90%"></div>
                                                            </div>
                                                            <h6>4.8 out of 5</h6>
                                                        </div>
                                                        <div class="progress">
                                                            <span>5 star</span>
                                                            <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>4 star</span>
                                                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>3 star</span>
                                                            <div class="progress-bar" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">45%</div>
                                                        </div>
                                                        <div class="progress">
                                                            <span>2 star</span>
                                                            <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%</div>
                                                        </div>
                                                        <div class="progress mb-30">
                                                            <span>1 star</span>
                                                            <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                                                        </div>
                                                        <a href="#" class="font-xs text-muted">How are ratings calculated?</a>
                                                    </div> -->
                                                </div>

                                            </div>
                                            <!--comment form-->
                                            <div class="comment-form  " >
                                                <h4 class="mb-15">ارسال نظر</h4>
                                                <div class="product-rate d-inline-block mb-30"></div>
                                                <div class="row" >
                                                    <div class="col-lg-8 col-md-12" v-if="props.auth.user" >
                                                        <form class="form-contact comment_form" id="commentForm">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <!-- <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="Write Comment"></textarea> -->
                                                                        <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text" />
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit" @click.prevent="submitComment" class="button button-contactForm" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                                    <span v-if="form.processing">پردازش...</span>
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                                    <span v-else >ارسال</span>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="comment-respond mt-5" v-else>
                                                        <p class="comment-notes">برای ارسال نظر وارد حساب کاربری خود شوید.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-60">
                                <div class="col-12">
                                    <h2 class="section-title style-1 mb-30">محصولات مرتبط</h2>
                                </div>
                                <div class="col-12">
                                    <div class="row related-products">
                                        <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                            <div class="product-cart-wrap hover-up">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="shop-product-right.html" tabindex="0">
                                                            <img class="default-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-2-1.jpg'" alt="" />
                                                            <img class="hover-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-2-2.jpg'" alt="" />
                                                        </a>
                                                    </div>

                                                    <div class="product-badges product-badges-position product-badges-mrg">
                                                        <span class="hot">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap">
                                                    <h2><a href="shop-product-right.html" tabindex="0">Ulstra Bass Headphone</a></h2>
                                                    <div class="rating-result" title="90%">
                                                        <span> </span>
                                                    </div>
                                                    <div class="product-price">
                                                        <span>$238.85 </span>
                                                        <span class="old-price">$245.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                            <div class="product-cart-wrap hover-up">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="shop-product-right.html" tabindex="0">
                                                            <img class="default-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-3-1.jpg'" alt="" />
                                                            <img class="hover-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-4-2.jpg'" alt="" />
                                                        </a>
                                                    </div>

                                                    <div class="product-badges product-badges-position product-badges-mrg">
                                                        <span class="sale">-12%</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap">
                                                    <h2><a href="shop-product-right.html" tabindex="0">Smart Bluetooth Speaker</a></h2>
                                                    <div class="rating-result" title="90%">
                                                        <span> </span>
                                                    </div>
                                                    <div class="product-price">
                                                        <span>$138.85 </span>
                                                        <span class="old-price">$145.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                            <div class="product-cart-wrap hover-up">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="shop-product-right.html" tabindex="0">
                                                            <img class="default-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-4-1.jpg'" alt="" />
                                                            <img class="hover-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-4-2.jpg'" alt="" />
                                                        </a>
                                                    </div>

                                                    <div class="product-badges product-badges-position product-badges-mrg">
                                                        <span class="new">New</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap">
                                                    <h2><a href="shop-product-right.html" tabindex="0">HomeSpeak 12UEA Goole</a></h2>
                                                    <div class="rating-result" title="90%">
                                                        <span> </span>
                                                    </div>
                                                    <div class="product-price">
                                                        <span>$738.85 </span>
                                                        <span class="old-price">$1245.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-4 col-12 col-sm-6 d-lg-block d-none">
                                            <div class="product-cart-wrap hover-up mb-0">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="shop-product-right.html" tabindex="0">
                                                            <img class="default-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-5-1.jpg'" alt="" />
                                                            <img class="hover-img" :src="$page.props.ziggy.url +'/assets/imgs/shop/product-3-2.jpg'" alt="" />
                                                        </a>
                                                    </div>

                                                    <div class="product-badges product-badges-position product-badges-mrg">
                                                        <span class="hot">Hot</span>
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap">
                                                    <h2><a href="shop-product-right.html" tabindex="0">Dadua Camera 4K 2024EF</a></h2>
                                                    <div class="rating-result" title="90%">
                                                        <span> </span>
                                                    </div>
                                                    <div class="product-price">
                                                        <span>$89.8 </span>
                                                        <span class="old-price">$98.8</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <Footer :companies="companies" />
</template>
<style>
.carousel__item {
  min-height: 200px;
  width: 100%;

  font-size: 20px;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.carousel__slide {
  padding: 10px;
}

.carousel__prev,
.carousel__next {
  box-sizing: content-box;
  border: 5px solid white;
}
.form-floating1 {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  margin-top: 10px;
  background: white;
  z-index: 10;

}

</style>
