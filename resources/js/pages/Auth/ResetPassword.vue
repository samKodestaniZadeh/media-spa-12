<script setup>

import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
<<<<<<< HEAD
import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header.vue';

const props = defineProps({
    email: String,
    token: String
});

=======
import { computed,watch } from 'vue';
import { Head, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header2.vue';
import Footer from '@/pages/Guest/Footer2.vue';
import swal from "sweetalert2";

const props = defineProps({
    email: String,
    token: String,
    status: String,
});

const errors = computed(() => usePage().props.errors);

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
<<<<<<< HEAD
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
<template>
    <Header />
<Head title="Reset Password" />
<div class="main">
    <section class="hero-section full-screen gray-light-bg">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-md-7 col-lg-6 col-xl-8 d-none d-lg-block">
                    <div class="bg-cover vh-100 ml-n3 background-img" style="background-image: url(img/hero-bg-3.jpg);">
                        <div class="position-absolute login-signup-content">
                            <div class="position-relative text-white col-md-12 col-lg-7">
                                <h2 class="text-white">تغیر رمز عبور شما</h2>
                                <p class="lead">صورت خود را همیشه به سمت آفتاب نگه دارید - و سایه ها پشت سر شما قرار می گیرند. به طور مداوم طاقچه های کاملاً تحقیق شده را دنبال کنید در حالی که سیستم عامل های به موقع. وظیفه قابل اطمینان موازی کاتالیزورهای بهینه برای تغییر پس از کاتالیزورهای متمرکز برای تغییر.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-lg-6 col-xl-4 px-lg-6">
                    <div class="login-signup-wrap px-4 px-lg-5 my-5">
                        <h1 class="text-center mb-1">
                            تغییر رمز عبور
                        </h1>
                        <p class="text-muted text-center mb-5">
                            رمز جدید را وارد کنید
                        </p>
                        <BreezeValidationErrors class="mb-4" />
                        <form @submit.prevent="submit" class="login-signup-form">
                            <div class="form-group">
                                <BreezeLabel for="email" value="Email"  class="pb-1"/>
                                <BreezeInput id="email" type="email" class="form-control" v-model="form.email" required  autocomplete="username" />
                            </div>
                            <div class="form-group">
                                <BreezeLabel for="password" value="Password" class="pb-1"/>
                                <BreezeInput id="password" type="password" class="form-control" v-model="form.password" required autocomplete="new-password" />
                            </div>
                            <div  class="form-group">
                                <BreezeLabel for="password_confirmation" value="Confirm Password" class="pb-1"/>
                                <BreezeInput id="password_confirmation" type="password" class="form-control" v-model="form.password_confirmation" required autocomplete="new-password" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <BreezeButton class="btn btn-lg btn-block solid-btn border-radius mt-4 mb-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Reset Password
                                </BreezeButton>
                            </div>
                        </form>
=======
    form.post(route('password.store'),
        {
            onFinish: () => form.reset('password', 'password_confirmation'),
        }
    );
};

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
</script>
<template>
    <Header />
    <Head title="Reset Password" />
    <main class="main pages">
        <div class="page-content pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-8 col-md-12 m-auto">
                        <div class="row">
                            <div class="heading_s1">
                                <img class="border-radius-15" src="assets/imgs/page/reset_password.svg" alt="">
                                <h2 class="mb-15 mt-15">رمز عبور جدید</h2>
                                <p class="mb-30">لطفا یک رمز عبور جدید وارد نمایید.</p>
                            </div>
                            <div class="col-lg-6 col-md-8">
                                <div class="login_wrap widget-taber-content background-white">
                                    <div class="padding_eight_all bg-white">
                                        <form method="post" @submit.prevent="submit">
                                            <div class="form-group">
                                                <input type="password" required="" name="email" v-model="form.password" placeholder="کلمه عبور جدید خود را اینجا وارد نمایید. *">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" required="" name="email" v-model="form.password_confirmation" placeholder="تکرار کلمه عبور جدید خود را اینجا وارد نمایید.">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-heading btn-block hover-up" name="login"  :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    <span v-if="form.processing">پردازش...</span>
                                                    <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></div>
                                                    <span v-else> بازنشانی رمز عبور</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 pl-50">
                                <h6 class="mb-15">رمز عبور باید:</h6>
                                <p>بین ۹ تا ۶۴ کاراکتر باشد</p>
                                <p>حداقل دو مورد از موارد زیر را وارد کنید:</p>
                                <ol class="list-insider">
                                    <li>یک کاراکتر بزرگ</li>
                                    <li>یک کاراکتر کوچک</li>
                                    <li>یک عدد</li>
                                    <li>یک کاراکتر ویژه</li>
                                </ol>
                            </div>
                        </div>

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
    </section>
</div>
=======
    </main>
<Footer />
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
</template>
