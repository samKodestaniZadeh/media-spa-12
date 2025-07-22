<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm ,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import { Inertia } from '@inertiajs/inertia';
import swal from 'sweetalert2';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,users:Object,tarahis:Object,notifications:Object,companies:Object,descriptions:Object,
    alert:Object,wallet:Number,cart:Object
});

const form = useForm({
    id:props.tarahis.id,user_id:props.tarahis.user_id,designer:props.tarahis.designer_id,group:props.tarahis.group,
    type:props.tarahis.type,category:props.tarahis.category,date:props.tarahis.date,text: props.tarahis.text,
    title: props.tarahis.title,price:props.tarahis.price,discount:props.tarahis.discount,total:props.tarahis.total,
    status:props.tarahis.status,entekhab:props.tarahis.company_id,
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
        title: [errors.value.status ? errors.value.status +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('tarahiAdmin.show',props.tarahis.slug),{ only: [errors.value,hasErrors.value,props.alert] })
};

const submit = () => {
    if(form.status == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('tarahiAdmin.store')
            // ,{
            //     onFinish:() => submitTime()
            // }
        );
    }
};
</script>
<template>
<Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
        <div class="row content-header">
                <div class="d-flex col-sm-12">
                    <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                    <table>
                        <thead>
                            <td class="d-flex me-auto">
                                <select v-model.lazy="form.status" class="form-select">
                                        <option value="0">ثبت</option>
                                        <option value="1">انتظار</option>
                                        <option value="2">بررسی</option>
                                        <option value="3"> منقضی</option>
                                        <option value="4">منتشر</option>
                                        <option value="5">واگذار شده</option>
                                        <option value="6">تمام شده</option>
                                        <option value="7">ثبت نظر</option>
                                        <option value="8">بارگذاری فایل</option>
                                </select>
                                <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                    <span v-if="form.processing">پردازش...</span>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                    <span v-else >ویرایش</span>
                                </button>
                            </td>
                        </thead>

                    </table>

                </div>
                <div class="col-sm-12">
                    <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                </div>
            </div>
        <form >
            <div class="row">
                <div class="col-lg-12">
                    <div class="bg-white">
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
                                    <label class="form-label">لغو کننده<span class="text-danger">*</span></label>
                                    <div class="row gx-2" v-if="props.tarahis.user_canceller">
                                        {{ props.tarahis.user_canceller.name_show}}
                                    </div>
                                    <p v-else>تعیین نشده</p>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">طراح<span class="text-danger">*</span></label>
                                    <div class="row gx-2" v-if="props.tarahis.register_designer">
                                        <Link  :href="route('profile.show',[props.tarahis.register_designer.user.user_name])">
                                             {{props.tarahis.register_designer.user.name_show}}
                                        </Link>
                                    </div>
                                    <div class="row gx-2" v-else-if="props.tarahis.company">
                                        <p>{{props.companies.name_show}}</p>
                                    </div>
                                    <p v-else>تعیین نشده</p>
                                </div>
                            </div>
                            <div class="row gx-2">
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">زمان تحویل پروژه <span class="text-danger">*</span></label>
                                    <div class="row gx-2" v-if="form.date">
                                        {{ moment(form.date).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                    </div>
                                    <p class="row gx-2" v-else>تعیین نشده</p>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">مبلغ<span class="text-danger">*</span></label>
                                    <p v-if="props.tarahis.total !== null">{{(props.tarahis.total).toLocaleString("fa-IR")}}</p>
                                    <p v-else>تعیین نشده</p>
                                </div>
                            </div>
                            <div class="row gx-2">
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">عنوان<span class="text-danger">*</span></label>
                                    <p>{{props.tarahis.title}}</p>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <label class="form-label">فایل پیوست </label>
                                    <p v-if="props.tarahis.file">
                                        <a :href="route('download.edit',props.tarahis.file.id)" method="put">نمایش فایل پیوست</a>
                                    </p>
                                    <p v-else> ندارد</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label">توضیحات کامل درباره سفارش <span class="text-danger">*</span></label>
                                <div v-html="props.tarahis.text"></div>
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
