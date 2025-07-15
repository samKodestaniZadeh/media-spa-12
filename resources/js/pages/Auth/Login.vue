<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Guest/Header.vue';
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
  <Header />
  <Head title="Log in" />
  <div class="main">
    <section class="hero-section ptb-100 background-img full-screen" style="background: url('img/hero-bg-1.jpg') no-repeat center center / cover">
      <div class="container">
        <div class="row align-items-center justify-content-between pt-5 pt-sm-5 pt-md-5 pt-lg-0">
          <div class="col-md-5 col-lg-5">
            <div class="card login-signup-card shadow-lg mb-0">
              <div class="card-body px-md-5 py-5">
                <div class="mb-5">
                  <h5 class="h3">ورود</h5>
                  <p class="text-muted mb-0">برای ادامه وارد حساب خود شوید.</p>
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">{{ status }}</div>

                <form class="login-signup-form" @submit.prevent="submit">
                  <div class="form-group">
                    <BreezeLabel class="pb-1" for="email" value="Email Or UserName Or Mobile" />
                    <div class="input-group input-group-merge">
                      <div class="input-icon">
                        <span class="ti-email color-primary"></span>
                      </div>
                      <BreezeInput id="email" type="text" class="form-control" v-model="form.email" autocomplete="username" />
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="row">
                      <div class="col">
                        <BreezeLabel class="pb-1" for="password" value="Password" />
                      </div>
                      <div class="col-auto">
                        <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                          رمز خود را فراموش کرده اید؟
                        </Link>
                      </div>
                    </div>
                    <div class="input-group input-group-merge">
                      <div class="input-icon">
                        <span class="ti-lock color-primary"></span>
                      </div>
                      <BreezeInput id="password" type="password" class="form-control" v-model="form.password" autocomplete="current-password" />
                    </div>

                    <div class="block mt-4">
                      <label class="flex items-center">
                        <BreezeCheckbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2 text-sm text-gray-600">مرا بخاطر بسپار</span>
                      </label>
                    </div>
                  </div>

                  <BreezeButton class="btn btn-primary border-radius mt-4 mb-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    <span v-if="form.processing">پردازش...</span>
                    <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status"></div>
                    <span v-else>ورود</span>
                  </BreezeButton>
                </form>
              </div>

              <div class="card-footer bg-transparent border-top px-md-5">
                <small>ثبت نام نکرده اید؟</small>
                <Link :href="route('register')" class="small"> ایجاد حساب</Link>
              </div>
            </div>
          </div>

          <div class="col-md-7 col-lg-6" v-if="props.companies">
            <div class="hero-content-left text-white">
              <h1 class="text-white">{{ props.companies.name }}</h1>
              <p class="lead">برای استفاده از خدمات وارد حساب کاربری خود شوید.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="bottom-img-absolute">
        <img src="img/hero-bg-shape-1.svg" alt="wave shape" class="img-fluid">
      </div>
    </section>
  </div>
</template>
