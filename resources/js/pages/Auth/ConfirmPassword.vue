<script setup>

import { Head, useForm } from '@inertiajs/vue3';
import Header from '@/pages/Users/Buyer/header.vue';
import Footer from "@/pages/Users/Buyer/footer.vue";


const props = defineProps({
    cart:Object,wallet:Number,alert: Object, users: Object, orders: Object, notifications: Object,
    dark: String,companies:Object,descriptions:Object,asidemini:String,path:String,roles:Object,
});

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'),
        {
            onFinish: () => form.reset(),
        }
    )
};
</script>

<template>
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
</template>
