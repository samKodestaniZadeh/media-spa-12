<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import Aside from '@/Components/Aside.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,canResetPassword: Boolean,status: String,users:Object,ostans:Object,shahrs:Object,
    notifications:Object,companies:Object,descriptions:Object,alert:Object,wallet:Number,cart:Object
});

var now =  new Date();

const form =  useForm({
    notification:props.users.profile?props.users.profile.notification == 1:null,
    mobile:props.users.profile?props.users.profile.mobile == 1:null,
    email:props.users.profile?props.users.profile.email == 1:null,
});

const validate = (text)=>{
    swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }}).fire({
        title: text,
        icon:'error',
    })
}

const alert = ref(props.alert);

if (alert.value) {

    swal.fire(
        alert.value.title,
        alert.value.text,
        alert.value.icon,
    )

    alert.value = null
};

if (hasErrors.value == true) {

    swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }}).fire({
        title: [errors.value.user_name ? errors.value.user_name +'<br>' :'' ,
                errors.value.name ? errors.value.name +'<br>' :'' ,
                errors.value.lasst_name ? errors.value.lasst_name +'<br>' :'' ,
                errors.value.name_show ? errors.value.name_show +'<br>' :'' ,
                errors.value.tel ? errors.value.tel +'<br>':'',
                errors.value.birth ? errors.value.birth +'<br>' :'',
                errors.value.gender ? errors.value.gender +'<br>' :'',
                errors.value.biography ? errors.value.biography +'<br>' :'',],

        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('network.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{

    form.post(route('network.store'),{
            onFinish:() => submitTime()
    });
};

const ostans = ref(props.ostans);
const shahrs = ref();

const change = () =>{

    if(props.shahrs == form.ostan)
    {
        shahrs.value = props.shahrs
    }

};
</script>
<template>
<Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                <span v-if="form.processing">پردازش...</span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                <span v-else >ارسال</span>
                            </button>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body bg-white">
                        <div class="row gx-5">
                            <Aside />
                            <div class="col-lg-9">
                                <section class="content-body p-xl-4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row gx-3">
                                                    <label class="form-label">دریافت وقایع از طریق:</label>
                                                    <div class="col-lg-3 mb-3">
                                                        <!-- <label class="form-label">کد ملی</label>
                                                        <input  v-model.number.lazy="form.national_code" class="form-control" type="text" placeholder="اینجا تایپ کنید" /> -->
                                                        <label class="flex items-center">
                                                            <BreezeCheckbox name="notification" v-model:checked="form.notification" />
                                                            <span class="ml-2 text-sm text-gray-600"> نتیفیکیشن</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-3 mb-3">
                                                        <!-- <label class="form-label">تلفن همراه</label>
                                                        <input  v-model.lazy="form.tel" class="form-control" type="text" placeholder="09123456789" name="tel" autocomplete="tel" /> -->
                                                        <label class="flex items-center">
                                                            <BreezeCheckbox name="email" v-model:checked="form.email" />
                                                            <span class="ml-2 text-sm text-gray-600"> ایمیل</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <label class="flex items-center">
                                                            <BreezeCheckbox name="mobile" v-model:checked="form.mobile" />
                                                            <span class="ml-2 text-sm text-gray-600"> تلفن همراه</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <!-- <button @click.prevent="submit" class="btn btn-primary" type="submit">ذخیره</button> -->
                                    </form>
                                    <hr class="my-5" />
                                    <!-- <div class="row" style="max-width: 920px">
                                        <div class="col-md">
                                            <article class="box mb-3 bg-light">
                                                <h6>کلمه عبور</h6>
                                                <small class="text-muted d-block" style="width: 70%">با کلیک روی اینجا می توانید گذرواژه خود را بازنشانی یا تغییر دهید</small>
                                                <Link  class="btn btn-light btn-sm rounded font-md"  method="post" :href="route('password.request')">تغییر</Link>
                                            </article>
                                        </div>

                                        <div class="col-md">
                                            <article class="box mb-3 bg-light">
                                                <h6>حذف حساب</h6>
                                                <small class="text-muted d-block" style="width: 70%">هنگامی که حساب خود را حذف می کنید ، دیگر بازگشتی وجود ندارد.</small>
                                                <a class="btn btn-light rounded btn-sm font-md" href="#">غیرفعال</a>
                                            </article>
                                        </div>

                                    </div> -->

                                </section>

                            </div>

                        </div>

                    </div>

                </div>

            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
