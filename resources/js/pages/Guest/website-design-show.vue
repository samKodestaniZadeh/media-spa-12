<script setup>

import Header from './Header2.vue';
import Footer from './Footer2.vue';
import { computed, ref,watch } from 'vue';
import { Link, useForm ,usePage } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Editor from '@tinymce/tinymce-vue';
import 'vue3-carousel/dist/carousel.css'
import CommentReply from '@/Components/CommentReply.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import StarRating from 'vue-star-rating';
const ApiKey = ref('cfw3yx4hh06riwl1qwbq3fwcmjr80c5v0z2ki1fid7agx2ow');

const page = usePage()
const errors = computed(() => page.props?.errors || {})

const props = defineProps({
    tarahis: Object, cart: Object, time: String, count: Number, menus: Object,auth:Object,
    alert: Object, flash: String, tarahi_count: Number, tarahi_order: Number ||String, coupon_count: Number,
    companies: Object, reqdesigners: Object,carousels:Object,users:Object,tarahi_usersRated:Object,
    tarahi_averageRating : Number, tarahi_timesRated : Number,companies2: Object
});



const rawFavorite = computed(() => page.props.tarahis.favorite)
const favorite = ref(null);

const results = ref(props.tarahis);

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

watch(rawFavorite, (val) => {


    favorite.value = null
        val.forEach(element => {


        if (element.favoritable_id == page.props.tarahis.id && props.auth.user && props.auth.user.id == element.user_id) {
            favorite.value = element

        }
        else
        {
            favorite.value = null

        }

    });


})

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

const form = useForm({
    id:null,
    name:null,
    expired: null,
    price:null,
    tarahi_id:null,
    type:null,
    text:null,
    user_id:null,
});


const submitFavorite = (tarahi) => {

        form.id = tarahi.id
        form.type = 'App\\Models\\Tarahi'
        form.post(route('favorite.store'))

};

const submit = () => {
    form.tarahi_id = props.tarahis.id
        if(form.expired == null && form.id == null)
        {
            let text
            text = 'مورد ستاره دار الزامی است.'
            validate(text)
        }
        else
        {
            form.post(route('reqDesigner.store'));
        }


};
const submitlogin = () => {

    if (props.auth.user)
    {
        emit('EventSubmit',form.expired,form.price)
    }
    else
    {
        let text
        text = 'لطفا وارد حساب کاربری خود شوید.'
        validate(text)
    }
};


props.tarahis.favorite.forEach(element => {
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

const role = ref();
if (props.users) {
    props.users.roles.forEach(element => {

    if (element.id == 3) {
        role.value = element
    }
    });
}

const show = ref(true);

const submitComment = () => {

    form.user_id = results.value.user.id,
    form.tarahi_id = results.value.id
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
    form.user_id= results.value.user_id
    form.tarahi_id= results.value.id

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
                                        <figure class="border-radius-10" v-if="props.companies && props.companies.image">
                                            <img :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" alt="product image" />
                                        </figure>

                                    </div>
                                    <!-- THUMBNAILS -->

                                </div>
                                <!-- End Gallery -->
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="detail-info pr-30 pl-30">
                                    <span class="stock-status out-stock" v-if="results && results.discount"> تخفیف </span>
                                    <h2 class="title-detail">{{ results.title }}</h2>
                                    <div class="product-detail-rating">
                                        <div class="product-rate-cover text-end">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" v-if="results.ratings_avg_rating"
                                                :style="'width:' + results.ratings_avg_rating * 20 + '%'"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted" v-if="results.ratings_avg_rating"> ({{ results.ratings_avg_rating }})</span>
                                            <span class="font-small ml-5 text-muted" v-else> (0.0000)</span>
                                        </div>
                                    </div>
                                    <div class="clearfix product-price-cover">
                                        <div class="product-price primary-color float-left" v-if="results.discount">
                                            <span class="current-price text-brand" >
                                                {{
                                                            (results.price - (results.price * results.discount.percent) / 100).toLocaleString(
                                                                'fa-IR',
                                                            )
                                                        }}
                                            </span>
                                            <span>
                                                <span class="save-price font-md color3 ml-15" v-if="results.discount">{{results.discount.percent}}% تخفیف</span>
                                                <span class="old-price font-md ml-15">{{ results.price.toLocaleString('fa-IR') }}</span>
                                            </span>
                                        </div>

                                        <div class="product-price primary-color float-left" v-else>
                                            <span class="current-price text-brand" >
                                                {{
                                                            (results.price).toLocaleString(
                                                                'fa-IR',
                                                            )
                                                        }}
                                            </span>

                                        </div>
                                    </div>
                                    <div class="short-desc mb-30">
                                        <p class="font-lg">
                                            <template v-for="(menu, index) in results.menus" :key="index">
                                                    <template v-for="(section, index) in menu.sections" :key="section.id">
                                                        <span href="" v-if="section.name == 'tarahis'">{{ menu.name + ' ' }}</span>
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
                                            <button  v-if="props.auth.user" href="#" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" title="ارسال پیشنهاد به این پروژه" class="button button-add-to-cart" ><i class=""></i>پیشنهاد</button >
                                            <button  v-else  @click.prevent="submitlogin" href="#" data-bs-toggle="tooltip" data-bs-placement="bottom"  class="button button-add-to-cart" ><i class=""></i>پیشنهاد</button >
                                            <a aria-label="Add To Wishlist" class="action-btn hover-up " :class="favorite && favorite.favoritable_id == props.tarahis.id ? 'text-brand' : ''" href="" @click.prevent="submitFavorite(props.tarahis)"><i class="fi-rs-heart"></i></a>
                                            <!-- <a aria-label="Compare" class="action-btn hover-up" href=""><i class="fi-rs-shuffle"></i></a> -->
                                        </div>
                                    </div>
                                    <div class="font-xs">
                                        <ul class="mr-50 float-start">
                                            <li class="mb-5">ورژن: <span class="text-brand">{{ results.version }}</span></li>
                                            <li class="mb-5">انتشار:<span class="text-brand"> {{ moment(results.created_at).locale("fa", fa).format('jYYYY/jM/jD') }}</span></li>
                                        </ul>
                                        <ul class="float-start">
                                            <li class="mb-5">بروز رسانی: <a href="">{{ moment(results.updated_at).locale("fa", fa).format('jYYYY/jM/jD') }}</a></li>
                                            <li class="mb-5">تگ: <a href="" rel="tag">Snack</a>, <a href="" rel="tag">Organic</a>, <a href="" rel="tag">Brown</a></li>

                                        </ul>
                                    </div>
                                </div>
                                <!-- Detail Info -->

                            </div>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false" >
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">ارسال پیشنهاد</h5>
                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button> -->
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex">
                                                <p>آیا میخواهید به پروژه
                                                <strong style="font-size: 18px;" v-if="results">{{results.title}}</strong>
                                                پیشنهاد ارسال نمایید؟
                                                توجه داشته باشید در صورت پذیرش پیشنهاد شما از طرف کارفرما می بایست مبلغ
                                                <strong style="font-size: 18px;" v-if="props.companies2 && results && results.price ">
                                                    {{ (results.price*props.companies2.design_damage).toLocaleString("fa-IR") }}
                                                </strong>
                                                <strong style="font-size: 18px;" v-else>
                                                    {{ (form.price*props.companies2.design_damage).toLocaleString("fa-IR") }}
                                                </strong>
                                                ریال بابت ضمانت اجرای پروژه واریز نمایید؛
                                                    در صورت موفقیت اتمام پروژه مبلغ ضمانت پروژه به شما عودت داده خواهد شد.
                                                    ضمنا میتوانید مبلغ پیشنهادی خود را به کارفرما اعلام نمایید،
                                                    درصورت پذیرش کارفرما مبلغ ضمانت پروژه بسته به مبلغ جدید تغییر خواهد کرد.
                                                </p>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="bg-white">
                                                    <div class="card-body">
                                                        <div class="row gx-2">
                                                            <div class="col-lg-12">
                                                                <form @submit.prevent="" >
                                                                    <label class="form-label">تحویل پروژه<span class="text-danger">*</span></label>
                                                                    <input type="text" v-model="form.expired" class="form-control mt-2" placeholder="تعداد روز را وارد نمایید مثال : 10">
                                                                </form>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <form >
                                                                    <label class="form-label">مبلغ پیشنهادی</label>
                                                                    <input type="text" v-model="form.price" class="form-control mt-2" placeholder="مبلغ پیشنهادی را وارد نمایید مثال : 100000ریال">
                                                                </form>
                                                                <label class="form-label mt-2" v-if="form.price > 0">ضمانت اجرایی مبلغ پیشنهادی</label>
                                                                <p v-if="props.companies && form.price > 0" class="mt-2">
                                                                    {{ (form.price*props.companies2.design_damage).toLocaleString("fa-IR") }}ریال
                                                                </p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" href="#" class="btn btn-primary" data-dismiss="modal" @click.prevent="submit"
                                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >
                                                <span v-if="form.processing">پردازش...</span>
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                <span v-else >ثبت</span>
                                            </button>
                                            <!-- <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" @click="!show">انصراف</button> -->

                                        </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="product-info">
                            <div class="tab-style3">
                                <ul class="nav nav-tabs text-uppercase">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description" >توضیحات</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="Vendor-info-tab" data-bs-toggle="tab" href="#Vendor-info">کارفرما</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews">بازخورد </a>
                                    </li>
                                </ul>
                                <div class="tab-content shop_info_tab entry-main-content">
                                    <div class="tab-pane fade show active" id="Description">
                                        <div v-html="results.text">

                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="Vendor-info">
                                        <div class="vendor-logo d-flex mb-30">
                                            <img v-if="results.user && results.user.image" :src="$page.props.ziggy.url + '/storage/' + results.user.image.url" alt="" />
                                            <img v-else :src="$page.props.ziggy.url + '/storage/images/default-user.png'" alt="" />
                                            <div class="vendor-name ml-15">
                                                <h6>
                                                    <Link :href="route('profile.show', [results.user.user_name])">{{ results.user.name_show }}</Link>
                                                </h6>
                                                <div class="product-rate-cover text-end" v-if="props.tarahi_timesRated > 0 ">
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" :style="'width:' + props.tarahi_timesRated * 20 + '%'"></div>
                                                    </div>
                                                    <span class="font-small ml-5 text-muted"> ({{ props.tarahi_timesRated }})</span>
                                                </div>
                                                <div class="product-rate-cover text-end" v-else>
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" style="width: 0%"></div>
                                                    </div>
                                                    <span class="font-small ml-5 text-muted"> (0.000)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="contact-infor mb-50" >
                                            <li v-if="results.user && results.user.profile"><img :src="$page.props.ziggy.url +'/assets/imgs/theme/icons/icon-location.svg'" alt="" /><strong>آدرس: </strong> <span>{{ results.user.profile.ostan }}</span></li>

                                        </ul>
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
                                        <p v-if="results.user && results.user.profile">{{ results.user.profile.biography }}</p>
                                    </div>
                                    <div class="tab-pane fade" id="Reviews">
                                        <!--Comments-->
                                        <div class="comments-area">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <h4 class="mb-30">پرسش و پاسخ</h4>
                                                    <div class="comment-list" style="position: relative;">
                                                        <template v-for="(comment,index) in results.comments" :key="index">
                                                            <div class="single-comment justify-content-between d-flex mb-30"  >
                                                                <div class="user justify-content-between d-flex">
                                                                    <div class="thumb text-center">
                                                                        <img  v-if="comment.user.image && comment.user.image.url && comment.user.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+comment.user.image.url" :alt="comment.user.name_show" />
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
                                                            <CommentReply :comments="comment.replies" :comment_id ="comment.id"  :product="props.tarahis"  />
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
    <Footer :companies="props.companies" />
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
.sr-only{
    left:0 !important
}
</style>

