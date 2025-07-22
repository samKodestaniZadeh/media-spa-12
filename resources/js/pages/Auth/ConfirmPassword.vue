<script setup>
<<<<<<< HEAD
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';

import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header.vue';
=======

import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/pages/Users/Buyer/header.vue';
import Footer from "@/pages/Users/Buyer/footer.vue";


const props = defineProps({
    cart:Object,wallet:Number,alert: Object, users: Object, orders: Object, notifications: Object,
    dark: String,companies:Object,descriptions:Object,asidemini:String,path:String,roles:Object,
});
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b

const form = useForm({
    password: '',
});

const submit = () => {
<<<<<<< HEAD
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    })
=======
    form.post(route('password.confirm'),
        {
            onFinish: () => form.reset(),
        }
    )
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
};
</script>

<template>
<<<<<<< HEAD
<Header />
<div class="main">
     <Head title="Confirm Password" />

    <section class="hero-section full-screen gray-light-bg">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-center">

                <div class="col-12 col-md-7 col-lg-6 col-xl-8 d-none d-lg-block">


                    <div class="bg-cover vh-100 ml-n3 background-img" style="background-image: url(img/hero-bg-3.jpg);">
                        <div class="position-absolute login-signup-content">
                            <div class="position-relative text-white col-md-12 col-lg-7">
                                 <div class="mb-4 text-sm text-gray-600">
                                        This is a secure area of the application. Please confirm your password before continuing.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-5 col-lg-6 col-xl-4 px-lg-6">
                    <div class="login-signup-wrap px-4 px-lg-5 my-5">

                        <h1 class="text-center mb-1">
                            تایید کلمه عبور
                        </h1>

                        <form @submit.prevent="submit" class="login-signup-form">
                            <div class="form-group">
                                <BreezeLabel class="pb-1" for="password" value="Password" />
                                <BreezeInput id="password" type="password" class="form-control" v-model="form.password" required autocomplete="current-password"  />
                            </div>

                            <div class="flex justify-end mt-4">
                                <BreezeButton class="btn btn-primary border-radius mt-4 mb-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    <span v-if="form.processing">پردازش...</span>
                                    <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></div>
                                    <span v-else>تایید</span>
                                </BreezeButton>
                            </div>
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

     <Head title="Confirm Password" />
        <section class="content-main mt-80 mb-80">
                <div class="card mx-auto card-login">
                    <div class="card-body">
                        <h4 class="card-title mb-4">تایید کلمه عبور</h4>
                        <form @submit.prevent="submit">

                            <!-- form-group// -->
                            <div class="mb-3">
                                <input class="form-control" placeholder="کلمه عبور خود را اینجا وارد نمایید." type="password" v-model="form.password" autocomplete="current-password" />
                            </div>

                            <!-- form-group form-check .// -->
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    <span v-if="form.processing">پردازش...</span>
                                    <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></div>
                                    <span v-else>تایید</span>
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
