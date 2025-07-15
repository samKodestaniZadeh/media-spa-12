<script setup>
import { computed, ref } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import Header from "@/Pages/Guest/Header.vue";
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
    Inertia.visit(route("register"), {
        only: [props.alert, errors.value, hasErrors.value],
    });
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
                validateUpdate(),
            ],
        });
    }
};

const alert = ref(props.alert);

if (alert.value) {
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
        title: props.alert.title + props.alert.text,
        icon: props.alert.icon,
    });

    alert.value = null;
}

if (hasErrors.value == true) {
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
        title:
            errors.value.name_show ||
            errors.value.name ||
            errors.value.lasst_name ||
            errors.value.email ||
            errors.value.password,
        icon: "error",
    });
}
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
    <Header :companies="props.companies" :results="props.results" :Quickview="Quickview" :menus="props.menus" :cart="props.cart" :menu="props.menu"  />

    <div class="main">
        <section class="hero-section ptb-100 background-img full-screen" style="
                background: url('img/hero-bg-1.jpg') no-repeat center center /
                    cover;
            ">
            <div class="container">
                <div class="row align-items-center justify-content-between pt-5 pt-sm-5 pt-md-5 pt-lg-0">
                    <div class="col-md-5 col-lg-5">
                        <div class="card login-signup-card shadow-lg mb-0">
                            <div class="card-body px-md-5 py-5">
                                <div class="mb-5">
                                    <h6 class="h3">ایجاد حساب کاربری</h6>
                                    <p class="text-muted mb-0">
                                        گزینه های ستاره دار الزامی می باشند.
                                    </p>
                                </div>

                                <form class="login-signup-form" @submit.prevent="submit">
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
                                    <!-- <div class="form-group">
                                        <BreezeLabel class="pb-1" for="tel" value="تلفن همراه" />
                                        <div class="input-group input-group-merge">
                                            <div class="input-icon">
                                                <span class="color-primary">
                                                    <img
                                                        src="https://img.icons8.com/pastel-glyph/23/156d68/iphone-x--v1.png" />
                                                </span>
                                            </div>
                                            <BreezeInput id="tel" type="tel" class="form-control" v-model="form.tel"
                                                autocomplete="tel" placeholder="مثال 09120123456" />
                                        </div>
                                    </div> -->
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
                                                autocomplete="email" placeholder="name@address.com" />
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
                                    <div class="my-4">
                                        <div class="custom-control mb-3">
                                            <input type="checkbox" class="custom-control-input" v-model="form.terms"
                                                id="terms" required />
                                            <label class="custom-control-label" for="terms" >من با
                                                <Link :href="route('privacy.index')
                                                    ">قوانین </Link>موافقم.
                                            </label>
                                        </div>
                                    </div>
                                    <BreezeButton class="btn btn-primary border-radius mt-4 mb-3" :class="{'opacity-25': form.processing}" :disabled="form.processing">
                                        <span v-if="form.processing">پردازش...</span>
                                        <div v-if="form.processing" class="spinner-border spinner-border-sm" role="status">
                                        </div>
                                        <span v-else>ثبت نام</span>
                                    </BreezeButton>
                                </form>
                            </div>
                            <div class="card-footer px-md-5 bg-transparent border-top">
                                <Link :href="route('login')" class="small">قبلاً ثبت نام کرده اید؟</Link>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-6" v-if="props.companies">
                        <div class="hero-content-left text-white">
                            <h1 class="text-white">
                                {{ props.companies.name }}
                            </h1>
                            <p class="lead">
                                برای بهرمندی از خدمات حساب کاربری خود را بسازید.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-img-absolute">
                <img src="img/wave-shap.svg" alt="wave shape" class="img-fluid" />
            </div>
        </section>
    </div>
</template>
