<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm ,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

var now =  new Date();

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    users:Object,transactions:Object,transaction:Object,statuses:Object,ids:Object,prices:Object,wallet:Number,
    notifications:Object,banknames:Object,karbars:Object,companies:Object,descriptions:Object,alert:Object,
    walletUser:Number,cart:Object
});

const form =  useForm({id:props.transactions.id,transaction:props.transactions.transaction,price:props.transactions.price,
    date:props.transactions.date,bankname:props.transactions.bank_name,accountName:props.transactions.account_name,
    user:props.transactions.paymentable_id,status:props.transactions.status,cart_number:props.transactions.cart_number
    ,code_p:props.transactions.code_p,code_e:props.transactions.code_e,user:props.transactions.user.id
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
        title: [errors.value.transaction ? errors.value.transaction +'<br>' :'' ,
                errors.value.price ? errors.value.price +'<br>' :'' ,
                errors.value.date ? errors.value.date +'<br>' :'' ,
                errors.value.bankname ? errors.value.bankname +'<br>' :'' ,
                errors.value.accountName ? errors.value.accountName +'<br>':'',
                errors.value.user ? errors.value.user +'<br>' :'' ,
                errors.value.status ? errors.value.status +'<br>' :'',],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('paymentAdmin.edit',props.transactions.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{

    if(form.transaction == null || form.price == null || form.date == null || form.bankname == null || form.accountName == null
        || form.user == null || form.status == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('paymentAdmin.store'),{
            onFinish:() => submitTime()
        })
    }
};
</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
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
                            <option value="2">بررسی</option>
                            <option value="3"> منقضی</option>
                            <option value="4">انجام</option>
                    </select>
                    <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                        <span v-if="form.processing">پردازش...</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                        <span v-else >ایجاد</span>
                    </button>
                </td>
            </div>
            <div class="col-sm-12">
                <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
            </div>
        </div>
        <form >
            <div class="row">
                <div class="col-lg-6">
                    <div class="mt-4 bg-white">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نوع تراکنش<span class="text-danger">*</span></label>
                                            <select v-model="form.transaction" class="form-select" name="transaction" id="transaction" autocomplete="transaction">
                                                <option>برداشت</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">تاریخ<span class="text-danger">*</span></label>
                                            <date-picker v-model="form.date" format="YYYY-MM-DD HH:mm:ss" display-format="dddd jDD jMMMM jYYYY"   color="#1ABC9C" :max="now" type="datetime"></date-picker>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">شماره کارت مبدا<span class="text-danger">*</span></label>
                                            <input type="text" v-model.lazy="form.cart_number" placeholder="اینجا تایپ کنید" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">مبلغ<span class="text-danger">*</span></label>
                                            <input type="text" v-model.lazy="form.price" placeholder="اینجا تایپ کنید" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کد پیگیری<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.code_p" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">شماره ارجاع<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.code_e" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label"> موجودی کیف پول</label>
                                            <p style="font-size: 18px;">
                                                {{ (props.walletUser).toLocaleString("fa-IR") }}ریال
                                            </p>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کاربر<span class="text-danger">*</span></label>
                                            <select v-model="form.user" class="form-select" name="transaction" id="transaction" autocomplete="transaction">
                                                <option v-if="props.karbars.length > 0 " v-for="(karbar,index) in props.karbars" :key="index" :value="karbar.id">{{karbar.user_name}}</option>
                                                <option v-else>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <Footer :companies="props.companies" />
</main>
</template>
