<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm , usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import AsideAdmin2 from '@/Components/AsideAdmin2.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,canResetPassword: Boolean,status: String,users:Object,notifications:Object,
    companies:Object,descriptions:Object,alert:Object,user:Object,wallet:Number,cart:Object
});

var now =  new Date();

const form =  useForm({
    id:props.user.id,
    subject:null,
    number:null,
    siklls:[],
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
        title: [errors.value.id ? errors.value.id +'<br>' :'' ,
                errors.value.subject ? errors.value.subject +'<br>' :'' ,
                errors.value.number ? errors.value.number +'<br>' :'' ,
            ],

        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('sikllAdmin.show',[form.id]),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{

    if(form.subject && form.number || form.siklls.length > 0)
    {
        form.post(route('sikll.store'),{
                onFinish:() => submitTime()
            });
    }
    else
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)

    }

};

const siklls = ref(props.user.siklls);

if (siklls.value) {
    siklls.value.forEach(element => {

        form.siklls.push(element)
    });

}

const submitSikll = ()=>{
    form.post(route('sikllAdmin.store'),{
                onFinish:() => submitTime()
            });
}
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

                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body bg-white">
                        <div class="row gx-5">
                            <AsideAdmin2 :id="form.id" />
                            <div class="col-lg-9">
                                <section class="content-body p-xl-4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row gx-3">
                                                    <template v-for="sikll,index in form.siklls" :key="index">
                                                        <div class="row">
                                                            <div class="col mb-3" >
                                                                <label class="form-label">عنوان<span class="text-danger">*</span></label>
                                                                <input  v-model.lazy="sikll.subject" class="form-control" type="text" placeholder="اینجا تایپ کنید" />
                                                            </div>
                                                            <div class="class col mb-3">
                                                                <label class="form-label">درصد <span class="text-danger">*</span></label>
                                                                <input  v-model.lazy="sikll.number" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name_show" autocomplete="name_show" />
                                                            </div>
                                                            <div class="class col mb-3">
                                                                <label class="form-label">عملیات</label>
                                                                <p class="d-flex" v-if="sikll.subject">
                                                                    <select v-model.lazy="sikll.status" class="form-select">
                                                                            <option value="0">ثبت</option>
                                                                            <option value="1">انتظار</option>
                                                                            <option value="2">بررسی</option>
                                                                            <option value="3">منقضی</option>
                                                                            <option value="4">منتشر</option>
                                                                    </select>
                                                                    <button @click.prevent="submitSikll" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                                                        <span v-if="form.processing">پردازش...</span>
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                                        <span v-else >ارسال</span>
                                                                    </button>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </template>

                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                    </form>
                                    <hr/>
                                </section>
                            </div>

                        </div>

                    </div>

                </div>

            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
