<script setup>

import Header from './Header2.vue';
import Footer from './Footer2.vue';
import { computed, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import swal from 'sweetalert2';
import ExtensionOption from '@/Components/ExtensionOptions.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import { Carousel, Navigation, Slide } from 'vue3-carousel'
import 'vue3-carousel/dist/carousel.css'
import CommentReply from '@/Components/CommentReply.vue';
import Editor from '@tinymce/tinymce-vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    product: Object, auth: Object, cart: Object, time: String, count: Number,alert: Object,
    flash: String, product_count: Number, product_order: Number, coupon_count: Number,
    companies: Object,product_averageRating:String,product_usersRated:Number,carousels:Object,
    users:Object,cartCount:Number,comments:Object,replies:Object,latestBlogs:Object,
});

const ApiKey = ref('cfw3yx4hh06riwl1qwbq3fwcmjr80c5v0z2ki1fid7agx2ow');

const form = useForm({
    id:null,
    parent_id:null,
    text : null,
    user_id:null,
    product_id:null,
});

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

const alert = ref(props.alert);

if (alert.value) {

swal.fire(
    alert.value.title,
    alert.value.text,
    alert.value.icon,
)

alert.value = null
};

if (hasErrors.value == true) {

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
        title: [errors.value.text ? errors.value.text +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('blog.show',[props.product.slug]),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () => {

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
    form.post(route('comment.store'),{
        onFinish:() => submitTime()
    })
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
    form.post(route('comment.store'),{
        onFinish:() => submitTime()
    });
}
}

const submitFavorite = (id) => {
    form.id = id,
    form.type = 'App\\Models\\Product'
    form.post(route('favorite.store'), {
        onFinish: () => submitTime(),
    });

}


const submitCart = (id) => {
    form.id = id
    form.post(route('cart.store'),{
        onFinish: () => submitTime()
    })
}


const favorite = ref();
if(props.product && props.product.favorite)
{
    props.product.favorite.forEach(element => {
    if (props.auth.user && element.user_id == props.auth.user.id) {
        favorite.value = element
    }
});
}

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

if (props.product && props.product.menus &&props.product.menus.length > 0) {

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

if (props.product && props.product.menus && props.product.menus.length > 0) {
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
if (props.product && props.product.menus && props.product.menus.length > 0) {
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
if (props.product && props.product.menus && props.product.menus.length > 0) {
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

if (props.product && props.product.menus && props.product.menus.length > 0) {
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
</script>
<template>
    <Header :companies="companies" />
<main class="main">
        <div class="page-content mb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 m-auto">
                        <div class="single-page pt-50 pr-30">
                            <div class="single-header style-2">
                                <div class="row">
                                    <div class="col-xl-10 col-lg-12 m-auto">
                                        <h6 class="mb-10"><a href="#" v-if="props.product && props.product.group" >{{ props.product.group.name }}</a></h6>
                                        <h2 class="mb-10">{{ props.product.title }}</h2>
                                        <div class="single-header-meta">
                                            <div class="entry-meta meta-1 font-xs mt-15 mb-15">
                                                <a class="author-avatar" href="#">
                                                    <img class="img-circle" v-if="props.product.user && props.product.user.image" :src="$page.props.ziggy.url+'/storage/'+props.product.user.image.url" :alt="props.product.user.name_show">
                                                    <img class="img-circle" v-else style="width: 64px;height:64px" :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="props.product.user.name_show" >
                                                </a>
                                                <span class="post-by">نویسنده <Link :href="route('profile.show',[props.product.user.user_name])">{{ props.product.user.name_show }}</Link></span>
                                                <span class="post-on has-dot">{{ moment(props.product.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}</span>
                                                <!-- <span class="time-reading has-dot">8 mins read</span> -->
                                            </div>
                                            <!-- <div class="social-icons single-share">
                                                <ul class="text-grey-5 d-inline-block">
                                                    <li class="mr-5"><a href="#"><img src="assets/imgs/theme/icons/icon-bookmark.svg" alt=""></a></li>
                                                    <li><a href="#"><img src="assets/imgs/theme/icons/icon-heart-2.svg" alt=""></a></li>
                                                </ul>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <figure class="single-thumbnail">
                                <img style="margin: auto;" v-if="props.product && props.product.image && props.product.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+props.product.image.url" alt="">
                            </figure>
                            <div class="single-content">
                                <div class="row">
                                    <div class="col-xl-10 col-lg-12 m-auto">
                                        <div v-html="props.product.text"></div>
                                        <!--Comment form-->
                                        <div class="comment-form">
                                            <h3 class="mb-15 text-center mb-30">نظر خود را با ما به اشتراک بگذرید.</h3>
                                            <div class="row">
                                                <div class="col-lg-9 col-md-12  m-auto">
                                                    <form class="form-contact comment_form mb-50" action="#" id="commentForm" v-if="props.auth.user" >
                                                        <div class="row ">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text" placeholder="نظر خود را اینجا تایپ کنید." />
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="form-group">

                                                            <button @click.prevent="submit" class="button button-contactForm" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                                <span v-if="form.processing">پردازش...</span>
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                                <span v-else >ارسال نظر</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                    <div class="form-contact comment_form mb-50" v-else>

                                                        <p class="comments-area">برای ارسال نظر وارد حساب کاربری خود شوید.</p>
                                                    </div>
                                                    <div class="comments-area">
                                                        <h3 class="mb-30">نظرات</h3>
                                                        <div class="comment-list   m-auto">
                                                            <template v-for="(comment,index) in props.product.comments" :key="index">
                                                                <div class=" single-comment justify-content-between d-flex mb-30" >
                                                                    <div class="user justify-content-between d-flex">
                                                                        <div class="thumb text-center">
                                                                            <img  v-if="comment.user.image && comment.user.image.url && comment.user.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+comment.user.image.url" :alt="comment.user.name_show" >
                                                                            <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="comment.user.name_show" >
                                                                            <Link :href="route('profile.show',[comment.user.user_name])" class="font-heading text-brand">{{ comment.user.name_show }}</Link>
                                                                        </div>
                                                                        <div class="desc">
                                                                            <div class="d-flex justify-content-between mb-10">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="font-xs text-muted">{{ moment(comment.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }} </span>
                                                                                </div>
                                                                                <!-- <div class="product-rate d-inline-block">
                                                                                    <div class="product-rating" style="width:80%">
                                                                                    </div>
                                                                                </div> -->
                                                                            </div>
                                                                            <p class="mb-10">
                                                                                <span v-html="comment.text"></span>
                                                                                <a class="reply" data-bs-toggle="collapse" :href="'#multiCollapseExample'+comment.id" aria-expanded="false" :aria-controls="'multiCollapseExample'+comment.id">پاسخ</a>
                                                                            </p>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-lg-9 col-md-12  m-auto collapse" :id="'multiCollapseExample'+comment.id">
                                                                    <form class="form-contact comment_form mb-50 " action="#"  >
                                                                        <div class="row ">
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text" placeholder="نظر خود را اینجا تایپ کنید." />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">

                                                                            <button @click.prevent="submitReply(comment.id)" class="button button-contactForm" type="submit">
                                                                                <span v-if="form.processing">پردازش...</span>
                                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                                                <span v-else > ارسال پاسخ</span>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <CommentReply :comments="comment.replies" :comment_id ="comment.id"  :product="props.product"  />
                                                            </template>

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
</style>
