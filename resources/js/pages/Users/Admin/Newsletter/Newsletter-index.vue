<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Aside from '@/Components/AsideAdmin.vue';
import { Inertia } from '@inertiajs/inertia';
import swal from 'sweetalert2';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users:Object,newsletters:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,
    cartTotal:Object,names:Object,ids:Object,statuses:Object,companies:Object,descriptions:Object,alert:Object,
    wallet:Number,cart:Object
});

const form = useForm({
    name: null,
    status:null,
    id:null,
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
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.subject ? errors.value.subject +'<br>' :'' ,
                errors.value.route ? errors.value.route +'<br>' :'' ,
                ],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('newsletterAdmin.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}

</script>
<template>
<Header :cart="props.cart"  :roles="props.roles" :alert="props.alert" :users="props.users" :wallet="props.wallet"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

<main class="main-wrap rtl">
        <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <Link :href="route('newsletterAdmin.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body" >
                        <div class="row gx-5">
                            <Aside class="col-lg-3 border-end" />
                                <div class="col-lg-9">
                                    <section class="content-body p-xl-4">
                                            <div class="table-responsive" v-if="props.newsletters.total > 0">
                                                <table v-if="props.newsletters.total > 0" class="table table-hover">
                                                    <thead>
                                                        <tr class="col">
                                                            <th scope="col"> شناسه</th>
                                                            <th scope="col">نام</th>
                                                            <th scope="col">تاریخ</th>
                                                            <th scope="col">بروزرسانی</th>
                                                            <th scope="col">وضعیت</th>
                                                            <th scope="col">عملیات</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        <tr v-for="(newsletter,index) in props.newsletters.data" :key="index">
                                                            <td >{{(newsletter.id).toLocaleString("fa-IR")}}</td>
                                                            <td >{{newsletter.subject}}</td>
                                                            <td>
                                                                {{ moment(newsletter.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                            </td>
                                                            <td>
                                                                {{ moment(newsletter.updated_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                            </td>
                                                            <td>
                                                                <span v-if="newsletter.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                                                <span v-if="newsletter.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                                                <span v-if="newsletter.status == 2"  class="badge badge-pill badge-soft-secondary"> بررسی</span>
                                                                <span v-if="newsletter.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                                                <span v-if="newsletter.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                            </td>
                                                            <td class="text-end">
                                                                <div class="dropdown">
                                                                    <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                                    <div class="dropdown-menu">
                                                                        <Link class="dropdown-item" :href="route('newsletterAdmin.edit',[newsletter.id])">ویرایش اطلاعات</Link>
                                                                        <Link class="dropdown-item" :href="route('newsletterAdmin.update',[newsletter.id])" method="put" as="button" @finish="submitTime">ارسال</Link>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="mt-5" v-if="props.newsletters.total > 9">
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination justify-content-start">
                                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                            v-for="link in props.newsletters.links" :key="link.id" >
                                                            <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                            v-html="link.label" ></Link>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                            <p v-else>گزینه ای یافت نشد.</p>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
            </section >

            <Footer :companies="props.companies" />
    </main>
</template>
