<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { computed, ref,watch,onMounted } from 'vue';
import { Head, useForm ,Link,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header2.vue';
import Footer from "../Guest/Footer2.vue";
import swal from "sweetalert2";

const props = defineProps({
    status: String,
    companies: Object
});

const errors = computed(() => usePage().props.errors);


const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
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
        <Head title="Forgot Password" />
        <main class="main pages">
        <!-- <div v-if="status" class="alert alert-success">
        {{ status }}
        </div> -->
            <div class="page-content pt-150 pb-150">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-6 col-md-12 m-auto">
                            <div class="login_wrap widget-taber-content background-white">
                                <div class="padding_eight_all bg-white">
                                    <div class="heading_s1">
                                        <img class="border-radius-15" src="assets/imgs/page/forgot_password.svg" alt="">
                                        <h2 class="mb-15 mt-15">کلمه عبور فراموش کردی؟</h2>
                                        <p class="mb-30">نگران نباش ما کمکت میکنیم بازیابیش کنی.</p>
                                    </div>
                                    <form method="post" @submit.prevent="submit">
                                        <div class="form-group">
                                            <input type="text"  name="email" placeholder="ایمیل یا تلفن همراه یا نام کاربری" v-model="form.email" >
                                        </div>
                                        <div class="login_footer form-group">
                                            <div class="chek-form">
                                                <input type="text"  name="email" placeholder="Security code *">
                                            </div>
                                            <span class="security-code">
                                                <b class="text-new">8</b>
                                                <b class="text-hot">6</b>
                                                <b class="text-sale">7</b>
                                                <b class="text-best">5</b>
                                            </span>
                                        </div>
                                        <div class="login_footer form-group mb-50">
                                            <div class="chek-form">
                                                <div class="custome-checkbox">
                                                    <!-- <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox12"   />
                                                    <label class="form-check-label" for="exampleCheckbox12"><span>من با قوانین موافقم.</span></label> -->
                                                </div>
                                            </div>
                                            <!-- <a class="text-muted" href="#">Learn more</a> -->
                                            <!-- <Link :href="route('terms-conditions.index')"><i class="text-muted"></i>مطالعه قوانین</Link> -->
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-heading btn-block hover-up" name="login" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    <span v-if="form.processing">پردازش...</span>
                                                    <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></div>
                                                    <span v-else> بازیابی</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

     <Footer :companies="props.companies" :socials="props.socials" :time="props.time" :menus="props.menus" :path="props.path" />
</template>
