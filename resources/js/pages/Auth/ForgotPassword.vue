<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header.vue';

const props = defineProps({
    status: String
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Header />
        <Head title="Forgot Password" />
<div class="main">
    <section class="hero-section full-screen gray-light-bg">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-md-7 col-lg-6 col-xl-8 d-none d-lg-block">
                    <div class="bg-cover vh-100 ml-n3 background-img" style="background-image: url(img/hero-bg-3.jpg);">
                        <div class="position-absolute login-signup-content">
                            <div class="position-relative text-white col-md-12 col-lg-7">
                                <h2 class="text-white">Forgot your password? <br> No problem.</h2>
                                <p class="lead"> Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-lg-6 col-xl-4 px-lg-6">
                    <div class="login-signup-wrap px-4 px-lg-5 my-5">
                        <h1 class="text-center mb-1">
                            بازیابی رمز
                        </h1>
                        <p class="text-muted text-center mb-5">
                            ایمیل خود را وارد کنید تا پیوند تنظیم مجدد رمز عبور را دریافت کنید.
                        </p>
                        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                            {{ status }}
                        </div>
                        <BreezeValidationErrors class="mb-4" />
                        <form @submit.prevent="submit" class="login-signup-form">
                            <div class="form-group">
                                <BreezeLabel for="email" value="Email" />
                                <BreezeInput id="email" type="email" class="form-control" placeholder="name@address.com" v-model="form.email" required  autocomplete="username" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <BreezeButton class="btn btn-primary solid-btn border-radius mt-4 mb-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Email Password Reset Link
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
