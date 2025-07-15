<script setup>

import { computed ,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,users:Object,banks:Object,notifications:Object,wallet:Number,
    companies:Object,descriptions:Object , alert: Object,cart:Object
});

const form = useForm({
    id:props.banks.id,
    userid:props.banks.user_id,
    bankid:props.banks.bank_id,
    accountname:props.banks.account_name,
    accountnumber:props.banks.account_number,
    cartnumber:props.banks.cart_number,
    shabanumber:props.banks.shaba_number,
    status:props.banks.status,
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
        title: [
                errors.value.status ? errors.value.status +'<br>' :'',],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('bankAdmin.show',form.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{

    if(form.status == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('bankAdmin.store'),{
            onFinish:() => submitTime()
        })
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
                        <td class="d-flex me-auto">
                            <select v-model.lazy="form.status" class="form-select">
                                <option value="0">ثبت</option>
                                <option value="1">انتظار</option>
                                <option value="2">مسدود</option>
                                <option value="3">منقضی</option>
                                <option value="4">منتشر</option>
                            </select>
                            <button @click.prevent="submit" class="btn btn-primary me-auto ms-1">
                                <span v-if="form.processing">پردازش...</span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                <span v-else >ویرایش</span>
                            </button>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body d-flex">
                        <div class="col-lg-4 me-1" >
                                <div class="card card-user">
                                    <div class="card">
                                        <span style=" transform: rotate(40deg); margin-top: 15px;" v-if="props.banks.status == 0" class="category position-absolute badge badge-pill bg-primary">ثبت</span>
                                        <span style=" transform: rotate(40deg); margin-top: 25px;" v-if="props.banks.status == 1" class="category position-absolute badge badge-pill bg-warning"> انتظار</span>
                                        <span style=" transform: rotate(40deg); margin-top: 25px;" v-if="props.banks.status == 3" class="category position-absolute badge badge-pill bg-danger"> غیرفعال</span>
                                        <span style=" transform: rotate(40deg); margin-top: 25px;" v-if="props.banks.status == 4" class="category position-absolute badge badge-pill bg-success">فعال</span>
                                        <img class="img-wrap" v-if="props.banks.image" :src="$page.props.ziggy.url+'/storage/'+props.banks.image.url" alt="">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">حساب</h5>
                                        <div class="card-text text-muted">
                                            <p class="m-0">{{props.banks.menu.name}}</p>
                                            <p>شماره حساب:<a href="" class="__cf_email__" data-cfemail="ff929e8d86c6cfbf9a879e928f939ad19c9092">{{form.account_number}}</a></p>
                                            <p>شماره کارت:<a href="" class="__cf_email__" data-cfemail="ff929e8d86c6cfbf9a879e928f939ad19c9092">{{form.cart_number}}</a></p>
                                            <p>شماره شبا: IR  <a href="" class="__cf_email__" data-cfemail="ff929e8d86c6cfbf9a879e928f939ad19c9092">{{form.shaba_number}}</a></p>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
