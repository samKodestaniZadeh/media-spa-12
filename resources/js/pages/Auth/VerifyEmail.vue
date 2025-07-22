<script setup>
<<<<<<< HEAD
import { computed } from 'vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header.vue';

const props = defineProps({
=======
import { computed,watch } from 'vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/pages/Users/Buyer/header.vue';
import Footer from "@/pages/Users/Buyer/footer.vue";
import swal from "sweetalert2";

const props = defineProps({
    cart:Object,wallet:Number,alert: Object, users: Object, orders: Object, notifications: Object,
    dark: String,companies:Object,descriptions:Object,asidemini:String,path:String,roles:Object,
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
    status: String
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

<<<<<<< HEAD
=======
watch(() => props.status, (val) => {

  if (val) {
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
        title: val,
        icon: "success", // چون پیام موفقیته
      });
  }

});


>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
<<<<<<< HEAD
<Header/>
        <Head title="Email Verification" />
<div class="main">

    <section class="hero-section full-screen gray-light-bg">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">

                <div class="col-12 col-md-7 col-lg-6 col-xl-8 d-none d-lg-block">


                    <div class="bg-cover vh-100 ml-n3 background-img" style="background-image: url(img/hero-bg-3.jpg);">
                        <div class="position-absolute login-signup-content">
                            <div class="position-relative text-white col-md-12 col-lg-7">
                                <!-- <h2 class="text-white">رمز را فراموش کرده اید؟ <br> نگران نباشید</h2>
                                <p class="lead">صورت خود را همیشه به سمت آفتاب نگه دارید - و سایه ها پشت سر شما قرار می گیرند. به طور مداوم طاقچه های کاملاً تحقیق شده را دنبال کنید در حالی که سیستم عامل های به موقع. وظیفه قابل اطمینان موازی کاتالیزورهای بهینه برای تغییر پس از کاتالیزورهای متمرکز برای تغییر.</p> -->
                                 <div class="mb-4 text-sm text-gray-600">
                                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                                </div>

                                <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent" >
                                    A new verification link has been sent to the email address you provided during registration.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-5 col-lg-6 col-xl-4 px-lg-6">
                    <div class="login-signup-wrap px-4 px-lg-5 my-5">

                        <h1 class="text-center mb-1">
                            تایید حساب کاربری
                        </h1>

                        <!-- <form class="login-signup-form">
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-icon">
                                        <span class="ti-email color-primary"></span>
                                    </div>
                                    <input type="email" class="form-control" placeholder="name@address.com">
                                </div>
                            </div>

                            <button class="btn btn-lg btn-block solid-btn border-radius mt-4 mb-3">
                                بازیابی رمز
                            </button>


                            <div class="text-center">
                                <small class="text-muted text-center">
                                    رمز عبور خود را به خاطر می آورید؟ <a href="login.html">ورود</a>.
                                </small>
                            </div>

                        </form> -->
                        <form @submit.prevent="submit" class="login-signup-form">
                            <div class="form-group">
                                <BreezeButton class="btn btn-primary  solid-btn border-radius mt-4 mb-3" :disabled="form.processing">
                                    Resend Verification Email
                                </BreezeButton>
                            </div>
                            <Link :href="route('logout')" method="post" as="button" class="btn btn-danger  border-radius mt-4 mb-3">Log Out</Link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
=======
<Header :cart="props.cart"  :roles="props.roles" :alert="props.alert" :users="props.users" :wallet="props.wallet"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <Head title="Email Verification" />
        <section class="content-main mt-80 mb-80">
                <div class="card mx-auto card-login">
                    <div class="card-body">
                        <h4 class="card-title mb-4"> تایید حساب کاربری</h4>
                        <form @submit.prevent="submit">

                            <!-- form-group form-check .// -->
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    <span v-if="form.processing">پردازش...</span>
                                    <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></div>
                                    <span v-else>ارسال ایمیل تایید</span>
                                </button>
                            </div>
                            <!-- form-group// -->
                        </form>
                    </div>
                </div>
            </section>
<Footer :companies="props.companies" :socials="props.socials" :time="props.time" :menus="props.menus" :path="props.path" />
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
</template>
