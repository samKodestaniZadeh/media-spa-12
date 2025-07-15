<script setup>

import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import DatePicker from 'vue3-persian-datetime-picker';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    menus:Object,path:String,banks:Object,alert:Object,users:Object,payment:Object,now:String,
    notifications:Object,companies:Object,descriptions:Object,cart:Object
});

const form = useForm({
    id:props.payment.id,
    transaction:null,
    price:props.payment.price,
    bank:null,
    accountName:null,
    date:null,
    user:props.users.id,
    wallet:props.users.profile.wallet,
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
                errors.value.bank ? errors.value.bank +'<br>' :'' ,
                errors.value.accountName ? errors.value.accountName +'<br>' :'' ,
                errors.value.date ? errors.value.date +'<br>':'',
                errors.value.dargah ? errors.value.dargah +'<br>' :'',],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('payment.edit',form.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = ()=>{

    if(form.transaction == null || form.price == null || form.bank == null || form.date == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else if(form.price > props.users.profile.wallet){
            let text
            text = 'موجودی کیف پول کافی نمی باشد.'
           validate(text)
    }
    else
    {
        form.post(route('payment.store'),{
            onFinish:() => submitTime()
        });
    }
}

const menus = ref([]);

if (props.menus && props.menus.length > 0) {

    props.menus.forEach(element => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section => {
                        if(section.name == 'transactions')
                        {
                            element.children.forEach(child => {
                                menus.value.push(child)
                            });
                        }
                    });
                }
            });
        }
    });
}
const banks = ref([])
if (props.banks && props.banks.length > 0)
{
    props.banks.forEach(bank => {
        banks.value.push(bank)
    });
}

</script>
<template>
    <Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <!-- <button v-if="form.transaction == 'برداشت' " class="btn btn-primary" @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">ثبت</button> -->
                            <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary">
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
                    <div class="card-body bg-white">
                        <div class="row gx-5">
                            <aside class="col-lg-3 border-end">
                                <nav class="nav nav-pills flex-lg-column mb-4">
                                    <a class="nav-link active" aria-current="page" href="#">درخواست برداشت</a>
                                </nav>
                            </aside>
                            <div class="col-lg-9">
                                <section class="content-body p-xl-4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row gx-3">
                                                    <div class="col-3 mb-3">
                                                        <label class="form-label">نوع تراکنش</label>
                                                        <select v-model="form.transaction" class="form-select" name="transaction" id="transaction" autocomplete="transaction">
                                                            <option v-if="menus.length > 0" :value="menu" v-for="(menu, index) in menus" :key="index">{{ menu.name }}</option>
                                                            <option v-else disabled>گزینه ای یافت نشد.</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-3 mb-3">
                                                        <label class="form-label">حساب بانک</label>
                                                        <select v-model="form.bank" class="form-select" name="bank" id="" autocomplete="bank">
                                                            <option v-if="banks.length > 0"  v-for="(bank,index) in banks" :key="index" :value="bank">{{bank.bank_name}}-{{bank.shaba_number}}</option>
                                                            <option  v-else disabled> گزینه ای یافت نشد.</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3 mb-3">
                                                        <label class="form-label">تاریخ</label>
                                                        <date-picker v-model="form.date" disable="Friday" format="YYYY-MM-DD HH:mm:ss" display-format="dddd jDD jMMMM jYYYY" color="#1ABC9C" :min="props.now" type="date"></date-picker>
                                                    </div>
                                                    <div class="col-lg-3 mb-3" >
                                                        <label class="form-label">مبلغ</label>
                                                        <input type="text" v-model.lazy="form.price" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <br />
                                    </form>
                                </section>

                            </div>

                        </div>

                    </div>

                </div>

            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
