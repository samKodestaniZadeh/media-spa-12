<script setup>
import { computed, ref,watch } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import Header from "@/Pages/Guest/Header2.vue";
import Footer from "../Guest/Footer2.vue";
import swal from "sweetalert2";
import { Inertia } from "@inertiajs/inertia";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    alert: Object,
    companies: Object,
});

const form = useForm({
    name_show: null,
    name: null,
    lasst_name: null,
    tel: null,
    email: null,
    password: null,
    password_confirmation: null,
    terms: false,
    person:0,
});

const validateUpdate = () => {
        Inertia.visit(route("register"),
        {
            only: [props.alert, errors.value, hasErrors.value],
        }
    );
};

const validate = (text) => {
    swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", swal.stopTimer);
            toast.addEventListener("mouseleave", swal.resumeTimer);
        },
    }).fire({
        title: text,
        icon: "error",
    });
};

const submit = () => {
    if (
        form.name_show == null ||
        form.name == null ||
        form.lasst_name == null ||
        form.email == null ||
        form.password == null ||
        form.password_confirmation == null
    ) {
        let text = "موارد ستاره دار الزامی است.";
        validate(text);
    } else {
        form.post(route("register"), {
            onFinish: () => [
                form.reset("password", "password_confirmation"),
                //validateUpdate(),
            ],
        });
    }
};



watch(() => props.alert, (val) => {
  if (val) {
    if (val.title) {
      swal.fire(val.title, val.text, val.icon);
    } else {
      swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', swal.stopTimer);
          toast.addEventListener('mouseleave', swal.resumeTimer);
        }
      }).fire({
        title: val.text,
        icon: val.icon,
      });
    }
  }
});

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
const step = ref('step');
const selectStep = () => {
    if (step.value == 'step') {
            step.value = 'step2'
    }
    else {
            step.value = 'step'
    }

};
</script>
<template>
    <Head title="Register" />
    <Header :alert="props.alert" :companies="props.companies" />

 <main class="main pages">
            <!-- <div class="page-header breadcrumb-wrap">
                <div class="container">
                    <div class="breadcrumb">
                        <a href="index.html" rel="nofollow">Home<i class="fi-rs-home mr-5"></i></a>
                        <span></span> Pages <span></span> My Account
                    </div>
                </div>
            </div> -->
            <div class="page-content pt-150 pb-150">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                            <div class="row">
                                <div class="col-lg-6 col-md-8">
                                    <div class="login_wrap widget-taber-content background-white">
                                        <div class="padding_eight_all bg-white">
                                            <div class="heading_s1">
                                                <h1 class="mb-5">ایجاد حساب کاربری</h1>
                                                <p class="mb-30">قبلا حساب کاربری ساختی? <Link :href="route('login')">ورود</Link></p>
                                            </div>
                                            <form method="post" @submit.prevent="submit">
                                                <div class="form-group">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="name_show" value="انتخاب اشخاص" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-user color-primary"></span>
                                            </div>
                                            <select v-model="form.person" @change="selectStep" class="form-control ltr">
                                                <option class="rtl" value="0">شخص</option>
                                                <option class="rtl" value="1">شرکت</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="step == 'step'">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="name_show" value="نام نمایشی" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-user color-primary"></span>
                                            </div>
                                            <BreezeInput id="name_show" type="text" class="form-control"
                                                v-model="form.name_show" autocomplete="name_show"
                                                placeholder="مثال فروشگاه مدیا" />
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="step == 'step'">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="name" value="نام" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-user color-primary"></span>
                                            </div>
                                            <BreezeInput id="name" type="text" class="form-control" v-model="form.name"
                                                autocomplete="name" placeholder="اینجا تایپ کنید " />
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="step == 'step'">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="lasst_name" value="نام خانوادگی" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-user color-primary"></span>
                                            </div>
                                            <BreezeInput id="lasst_name" type="text" class="form-control"
                                                v-model="form.lasst_name" autocomplete="lasst_name"
                                                placeholder="اینجا تایپ کنید" />
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="step == 'step2'">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="name_show" value="نام نمایشی(شرکت)" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-user color-primary"></span>
                                            </div>
                                            <BreezeInput id="name_show" type="text" class="form-control"
                                                v-model="form.name_show" autocomplete="name_show"
                                                placeholder="مثال فروشگاه مدیا" />
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="step == 'step2'">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="name" value="نام مدیرعامل" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-user color-primary"></span>
                                            </div>
                                            <BreezeInput id="name" type="text" class="form-control" v-model="form.name"
                                                autocomplete="name" placeholder="اینجا تایپ کنید " />
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="step == 'step2'">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="lasst_name" value="نام خانوادگی مدیرعامل" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-user color-primary"></span>
                                            </div>
                                            <BreezeInput id="lasst_name" type="text" class="form-control"
                                                v-model="form.lasst_name" autocomplete="lasst_name"
                                                placeholder="اینجا تایپ کنید" />
                                        </div>
                                    </div>
                                                <div class="form-group">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="email" value="پست الکترونیک" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-email color-primary"></span>
                                            </div>
                                            <BreezeInput id="email" type="email" class="form-control" v-model="form.email"
                                                autocomplete="email" placeholder="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="password" value="کلمه عبور" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-lock color-primary"></span>
                                            </div>
                                            <BreezeInput id="password" type="password" class="form-control"
                                                v-model="form.password" autocomplete="new-password"
                                                placeholder="رمز ورود خود را وارد نمایید" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="d-flex">
                                            <BreezeLabel class="pb-1" for="password_confirmation" value="تکرار کلمه عبور" />
                                            <span class="text-danger me-1">*</span>
                                        </div>
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="ti-lock color-primary"></span>
                                            </div>
                                            <BreezeInput id="password_confirmation" type="password" class="form-control"
                                                v-model="form.password_confirmation
                                                    " autocomplete="new-password"
                                                placeholder="تکرار رمز ورود خود را وارد نمایید " />
                                        </div>
                                    </div>


                                                <!-- <div class="login_footer form-group">
                                                    <div class="chek-form">
                                                        <input type="text" required="" name="email" placeholder="Security code *" />
                                                    </div>
                                                    <span class="security-code">
                                                        <b class="text-new">8</b>
                                                        <b class="text-hot">6</b>
                                                        <b class="text-sale">7</b>
                                                        <b class="text-best">5</b>
                                                    </span>
                                                </div> -->
                                                <!-- <div class="payment_option mb-50">
                                                    <div class="custome-radio">
                                                        <input class="form-check-input" required="" type="radio" name="payment_option" id="exampleRadios3" checked="" />
                                                        <label class="form-check-label" for="exampleRadios3" data-bs-toggle="collapse" data-target="#bankTranfer" aria-controls="bankTranfer">I am a customer</label>
                                                    </div>
                                                    <div class="custome-radio">
                                                        <input class="form-check-input" required="" type="radio" name="payment_option" id="exampleRadios4" checked="" />
                                                        <label class="form-check-label" for="exampleRadios4" data-bs-toggle="collapse" data-target="#checkPayment" aria-controls="checkPayment">I am a vendor</label>
                                                    </div>
                                                </div> -->
                                                <div class="login_footer form-group mb-50">
                                                    <div class="chek-form">
                                                        <div class="custome-checkbox">
                                                            <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox12"  v-model="form.terms"  />
                                                            <label class="form-check-label" for="exampleCheckbox12"><span>من با قوانین موافقم.</span></label>
                                                        </div>
                                                    </div>
                                                    <Link :href="route('terms-conditions.index')"><i class="fi-rs-book-alt mr-5 text-muted"></i>مطالعه قوانین</Link>
                                                </div>
                                                <div class="form-group mb-30">
                                                    <button type="submit" class="btn btn-fill-out btn-block hover-up font-weight-bold" name="login" :class="{'opacity-25': form.processing}" :disabled="form.processing">
                                                        <span v-if="form.processing">پردازش...</span>
                                                        <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status">
                                                        </div>
                                                        <span v-else>ثبت نام</span>
                                                    </button>
                                                </div>
                                                <p class="font-xs text-muted"><strong>توجه داشته باشید:</strong>اطلاعات شخصی شما برای پشتیبانی از تجربه شما در سراسر این وب‌سایت، مدیریت دسترسی به حساب کاربری شما و سایر اهدافی که در سیاست حفظ حریم خصوصی ما شرح داده شده است، استفاده خواهد شد.</p>
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
                                        <a href="#" class="social-login google-login">
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
