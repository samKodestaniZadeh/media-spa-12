<script setup>

import Header from './Header.vue';
import Footer from './Footer.vue';
import { computed ,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Input from '@/Components/Input.vue';
import swal from 'sweetalert2';
import ExtensionOption from '@/Components/ExtensionOptions.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({product:Object,count:Number,auth:Object,cartCount:Number,time:String,
alert:Object,flash:Object,product_count:Number,product_order:Number,companies:Object});

if(props.alert){

 swal.fire(
  props.alert.title,
  props.alert.text,
  props.alert.icon,
)
props.alert=null
};
const form = useForm({
    id:null,

});
const submitFavorite = (id) => {
    form.id = id
    form.post(route('favorite.store'))
};
const submitCart =(id) => {
    form.id = id
    form.post(route('cart.store'))
}
const favorite = ref();
props.product.favorite.forEach(element => {
    if(props.auth.user && element.user_id == props.auth.user.id)
    {
        favorite.value = element
    }
});
</script>
<template>
<Header :companies="props.companies" />
<section class="hero-section pt-100 background">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-9 col-lg-7">
                <div class="page-header-content text-white text-center pt-sm-5 pt-md-5 pt-lg-0">
                    <h1 class="text-white mb-0">قالب ها</h1>
                    <div class="custom-breadcrumb">
                        <ol class="breadcrumb d-inline-block bg-transparent list-inline py-0">
                            <li class="list-inline-item breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="list-inline-item breadcrumb-item"><a href="#">صفحات</a></li>
                            <li class="list-inline-item breadcrumb-item active">دانلود و خرید</li>
                            <li class="list-inline-item breadcrumb-item active">قالب ها</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="background-header" style="height:5rem"></div>
</section>
<!--hero section end-->
<div class="container-fluid mb-5">
    <ExtensionOption :product="props.product" :product_count="props.product_count" :product_order="props.product_order"
     :cart="props.cart" :time="props.time" :count="props.count" :alert="props.alert" :flash="props.flash" :auth="props.auth"
     :companies="props.companies" />
<div class="row mt-5">
    <div class="col-sm-8">
        <div class="card text-left">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <Link class="nav-link color-succss" :href="route('website_templates.show',props.product.slug)">توضیحات</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link color-succss" :href="route('guest_comment.show',props.product.slug)">پرسش و پاسخ</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link active" href="#">پشتیبانی</Link>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <h3>خریداران محترم</h3>
                <h5>توجه فرمایید</h5>
                <p v-if="props.companies">محصولات ارائه شده در {{ props.companies.name }} قبل از اینکه برای فروش گذاشته شوند بررسی می‌گردند</p>
                <p > فایل های راهنمای نصب در فایل دانلودی شما موجود است</p>
                <p >طراح موظف است تا ایرادات امنیتی و خطاهای موجود در محصول خود را رفع نماید، اما در مورد ویرایش یا مواردی اضافی هیچ مسئولیتی ندارد</p>
                <div class="alert alert-warning text-center" role="alert">
                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                    </svg>
                    پشتیبانی از طریق  تیکت پشتیبانی  انجام می شود.
                  </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="card card-body mb-4">
                                <article class="icontext text-center align-items-center">
                                    <img style="margin-top: 0.7rem" src="https://img.icons8.com/material/60/3498DB/add-ticket--v1.png"/>
                                    <br>
                                    <Link v-if="props.auth.user" :href="route('support.create')" class="btn btn-link" tabindex="-1" role="button" aria-disabled="true">ارسال تیکت</Link>
                                    <Link v-else href="#" class="btn btn-link disabled" tabindex="-1" role="button" aria-disabled="true">ارسال تیکت</Link>
                                </article>
                            </div>

                        </div>
                    </div>
            </div>
        </div>
        <div class="card-footer">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header">
                مشخصات فروشنده
            </div>
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">نام کاربر</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <Link class="color-succss" :href="route('profile.show',[props.product.user.user_name])">{{props.product.user.name_show}}</Link>
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">تعداد محصولات</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">{{count}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3 mb-3">
            <div class="card-header">
                مشخصات محصول:
            </div>
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">ورژن</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">{{product.version}}</p>
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">انتشار</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">
                            {{ moment(props.product.created_at).locale("fa", fa).format('jYYYY/jM/jD') }}
                        </p>
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">بروز رسانی</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">
                            {{ moment(props.product.updated_at).locale("fa", fa).format('jYYYY/jM/jD') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<Footer class="mt-5" :companies="props.companies" />
</template>
