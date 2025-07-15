<script setup>

import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header.vue';

const props = defineProps({
    email: String,
    token: String
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</template>
