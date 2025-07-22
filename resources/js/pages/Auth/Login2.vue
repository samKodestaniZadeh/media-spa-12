<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header2.vue';
import Footer from '../Guest/Footer2.vue';
import swal from 'sweetalert2';

const props = defineProps({
  canResetPassword: Boolean,
  status: String,
  cartCount: Number,
  companies: Object,
  alert: Object
});

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const alert = ref(props.alert);

const form = useForm({
  email: '',
  password: '',
  remember: false
});

const showToast = (icon, title) => {
  swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', swal.stopTimer)
      toast.addEventListener('mouseleave', swal.resumeTimer)
    }
  }).fire({
    icon,
    title
  });
};

const submit = () => {
  if (!form.email || !form.password) {
    return showToast('error', 'موارد ستاره دار الزامی است.');
  }

  form.post(route('login'), {
    onSuccess: () => {
      if (usePage().props.alert) {
        showToast(usePage().props.alert.icon, usePage().props.alert.title + ' ' + usePage().props.alert.text);
      }
    },
    onError: () => {
      if (hasErrors.value) {
        showToast('error', 'ایمیل یا رمز عبور اشتباه است.');
      }
    }
  });
};

if (alert.value) {
  showToast(alert.value.icon, alert.value.title + ' ' + alert.value.text);
  alert.value = null;
}

</script>

<template>
  <Header  :companies="props.companies" :results="props.results" :Quickview="Quickview" :menus="props.menus" :cart="props.cart" :menu="props.menu" />
  <Head title="Log in" />
  <main class="main pages">
            <!-- <div class="page-header breadcrumb-wrap">
                <div class="container">
                    <div class="breadcrumb">
                        <Link :href="route('index')" rel='nofollow'>صفحه اصلی<i class="fi-rs-home mr-5"></i></Link>
                        <span></span> صفحات <span></span> حساب من
                    </div>
                </div>
            </div> -->
            <div class="page-content pt-150 pb-150">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                            <div class="row">
                                <!-- <div class="col-lg-6 pr-30 d-none d-lg-block">
                                    <img class="border-radius-15" src="assets/imgs/page/login-1.png" alt="" />
                                </div> -->
                                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">{{ status }}</div>
                                <div class="col-lg-6 col-md-8">
                                    <div class="login_wrap widget-taber-content background-white">
                                        <div class="padding_eight_all bg-white">
                                            <div class="heading_s1">
                                                <h1 class="mb-5">ورود</h1>
                                                <p class="mb-30">حساب کاربری نداری? <Link :href="route('register')">ایجاد کن</Link></p>
                                            </div>
                                            <form method="post" @submit.prevent="submit">
                                                <div class="form-group">
                                                    <input type="text" required="" name="email" placeholder="ایمیل یا تلفن همراه یا نام کاربری"  v-model="form.email" autocomplete="username" />
                                                </div>
                                                <div class="form-group">
                                                    <input required="" type="password" name="password" placeholder="Your password *" v-model="form.password" autocomplete="current-password" />
                                                </div>
                                                <div class="login_footer form-group">
                                                    <div class="chek-form">
                                                        <input type="text"  name="email" placeholder="Security code *" />
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
                                                            <BreezeCheckbox class="form-check-input" name="remember" v-model:checked="form.remember" />
                                                            <label class="form-check-label" for="exampleCheckbox1"><span>مرا بخاطر بسپار</span></label>
                                                        </div>
                                                    </div>
                                                    <Link class="text-muted" v-if="canResetPassword" :href="route('password.request')">رمز خود را فراموش کرده اید؟</Link>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-heading btn-block hover-up" name="login" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                       <span v-if="form.processing">پردازش...</span>
                                                        <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></div>
                                                        <span v-else>ورود</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6 pr-30 d-none d-lg-block">
                                    <div class="card-login mt-115">
                                        <a href="#" class="social-login facebook-login">
                                            <img src="assets/imgs/theme/icons/logo-facebook.svg" alt="" />
                                            <span>ادامه با فیس بوک</span>
                                        </a>
                                        <a href="/auth/google" class="social-login google-login">
                                            <img src="assets/imgs/theme/icons/logo-google.svg" alt="" />
                                            <span>ادامه با جیمیل</span>
                                        </a>
                                        <a href="#" class="social-login apple-login">
                                            <img src="assets/imgs/theme/icons/logo-apple.svg" alt="" />
                                            <span>ادامه با اپل</span>
                                        </a>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer :companies="props.companies" :socials="props.socials" :time="props.time" :menus="props.menus" :path="props.path" />
</template>
