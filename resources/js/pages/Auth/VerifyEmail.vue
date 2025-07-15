<script setup>
import { computed } from 'vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header.vue';

const props = defineProps({
    status: String
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
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
</template>
