<script setup>
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
    status: String
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

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


const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
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
</template>
