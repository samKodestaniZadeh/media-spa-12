<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm ,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,users:Object,tarahis:Object,notifications:Object,wallet:Number,
    companies:Object,descriptions:Object,reqDesigner:Object,registerDesigner:Object,alert:Object
});

const form = useForm({
    expired: null,
    id:null,
    file:null,
    tarahi:null
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

const submit = (id) => {
    form.id = id
     if(form.expired == null || form.id == null)
     {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('tarahiDesigner.store'));
    }
};
const submitFile = (id,tarahi) => {
    form.id = id
    form.tarahi = tarahi
     if(form.file == null || form.id == null)
     {
         let text
        text = 'لطفا فایل پروژه را بارگذاری نمایید.'
        validate(text)
    }
    else
    {
        form.post(route('tarahiDesigner.store'),{
            onFinish:() => submitTime()
        });
    }
};
const submitTime = ()=>{
    Inertia.visit(route('tarahiDesigner.show',props.tarahis.slug),{ only: [props.alert,errors.value,hasErrors.value] })
}

const alert = ref(props.alert);

if (alert.value)
{
    if (alert.value.title)
    {

        swal.fire(
        props.alert.title,
        props.alert.text,
        props.alert.icon,
        )

    }
    else
    {
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
            title: props.alert.text,
            icon:props.alert.icon,
        })
    }

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
        title: [errors.value.expired ? errors.value.expired +'<br>' :'' ,
                errors.value.file ? errors.value.file +'<br>' :'' ,],
        icon:'error',
    })
}
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
                <td class="me-auto">
                    <button v-if="props.tarahis.reqdesigner_id == null " @click.prevent="submit(props.tarahis.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                        <span v-if="form.processing">پردازش...</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                        <span v-else >ارسال</span>
                    </button>
                    <button v-if="props.tarahis.status == 5 && props.registerDesigner || props.tarahis.status == 8 && props.registerDesigner " @click.prevent="submitFile(props.registerDesigner.id,props.tarahis.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
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
        <form >
            <div class="row">
                <div class="col-lg-12">
                    <div class="bg-white">
                        <div class="card-header">
                            <h4>اطلاعات پروژه</h4>
                        </div>
                        <div class="card-body">
                            <div class="row gx-2">
                                <div class="col-lg-6">
                                    <label class="form-label">دسته بندی<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-6 d-flex">
                                    <p v-for="(menu,index) in props.tarahis.menus" :key="index">{{menu.name + ''}}</p>
                                </div>
                            </div>
                            <div class="row gx-2">
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">عنوان<span class="text-danger">*</span></label>
                                    <p>{{props.tarahis.title}}</p>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">مبلغ پروژه<span class="text-danger">*</span></label>
                                    <p v-if="props.tarahis && props.tarahis.total">{{(props.tarahis.total).toLocaleString("fa-IR")}}</p>
                                </div>
                            </div>
                            <div class="row gx-2">
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">زمان تحویل پروژه <span class="text-danger">*</span></label>
                                    <div class="row gx-2" v-if="props.tarahis">
                                        {{ moment(props.tarahis.date).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">فایل پیوست<span class="text-danger">*</span></label>
                                    <p v-if="props.tarahis.file">
                                        <a :href="route('download.edit',props.tarahis.file.id)" method="put">نمایش</a>
                                    </p>
                                    <p v-else>ندارد</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label">توضیحات کامل درباره سفارش <span class="text-danger">*</span></label>
                                <div v-html="props.tarahis.text"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-2" v-if="props.reqDesigner && props.reqDesigner.file">
                    <div class="bg-white" v-if="props.tarahis.reqdesigner_id == null">
                        <div class="card-header">
                            <h4> ارسال پیشنهاد</h4>
                        </div>
                        <div class="card-body" >
                            <div class="row gx-2">
                                <div class="col-lg-12">
                                    <div class="mt-4">
                                        <label class="form-label">تحویل پروژه<span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.expired" class="form-control" placeholder="تعداد روز را وارد نمایید مثال : 10">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-else>
                        <p>شما قبلا درخواست فرستاده اید.</p>
                    </div>
                </div>
                <div class="col-lg-6 mt-2" v-if="props.tarahis.status == 5  && props.registerDesigner ||
                props.tarahis.status == 8 && props.registerDesigner">
                    <div class="bg-white">
                        <div class="card-header">
                            <h4> بارگذاری فایل پروژه</h4>
                        </div>
                        <div class="card-body" >
                            <div class="row gx-2">
                                <div class="col-lg-12">
                                    <label for="file">لطفا فایل پروژه را که جهت ارائه آماده کرده اید را بارگذاری نمایید.</label>
                                    <input  class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file"  accept="zip/rar/*"/>
                                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">{{ form.progress.percentage }}%</progress>
                                    <a v-if="props.reqDesigner && props.reqDesigner.file && form.progress == null" :href="route('download.edit',props.reqDesigner.file.id)" method="put">نمایش فایل پیوست</a>
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
