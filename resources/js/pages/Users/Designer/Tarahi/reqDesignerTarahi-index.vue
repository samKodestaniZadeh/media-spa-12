<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia'

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({tarahi:Object,users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,
    cartTotal:Object,notifications:Object,names:Object,ids:Object,statuses:Object,companies:Object,descriptions:Object,
    reqDesigners:Object,alert:Object});

const form = useForm({
    name: null,
    status:null,
    id:null,
    reqDesigner_id:null,
    dargah:null
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

const submitTimer = ()=>{
    Inertia.visit(route('reqDesigner.index'),{ only: [props.alert] })
}

const alert = ref(props.alert);

if (alert.value) {

    swal.fire({
        title: props.alert.title ,
        text: props.alert.text,
        icon:props.alert.icon,
    })

    alert.value = null
};

</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
        <section class="content-main">
            <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                            <td class="me-auto">
                                <!-- <Link :href="route('product.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link> -->
                            </td>
                        </div>
                        <div class="col-sm-12">
                            <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                        </div>
                    </div>
                    <div class="bg-white mb-4"  v-if="props.reqDesigners.total > 0">
                        <div class="card-body" >
                            <div class="table-responsive">
                                <article class="itemlist">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="col">
                                                <th scope="col">شناسه</th>
                                                <th scope="col">پروژه</th>
                                                <th scope="col">تاریخ</th>
                                                <th scope="col">تحویل پروژه</th>
                                                <th scope="col">وضعیت</th>
                                                <th scope="col">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            <tr v-for="(reqDesigner,index) in props.reqDesigners.data" :key="index">
                                                <td >{{(reqDesigner.id).toLocaleString("fa-IR")}}</td>
                                                <td v-if="reqDesigner.req_designer && reqDesigner.req_designer.status == 4 || reqDesigner.req_designer && reqDesigner.req_designer.status == 6">
                                                    <Link  :href="route('website_design.show',[reqDesigner.req_designer.slug])">
                                                        <div class="left">
                                                            <img v-if="reqDesigner.req_designer.image" :src="$page.props.ziggy.url +'/storage/' +reqDesigner.req_designer.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.req_designer.title">
                                                            <img v-else-if="props.companies" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.req_designer.title">
                                                        </div>
                                                        <div class="info">
                                                            <h6 class="mb-0 text-nofull"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{reqDesigner.req_designer.title}}</font></font></h6>
                                                        </div>
                                                    </Link>
                                                </td>
                                                <td v-else>
                                                    <Link  href="#">
                                                        <div class="left">
                                                            <img v-if="reqDesigner.req_designer.image" :src="$page.props.ziggy.url +'/storage/' +reqDesigner.req_designer.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.req_designer.title">
                                                            <img v-else-if="props.companies" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.req_designer.title">
                                                        </div>
                                                        <div class="info">
                                                            <h6 class="mb-0 text-nofull"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{reqDesigner.req_designer.title}}</font></font></h6>
                                                        </div>
                                                    </Link>
                                                </td>
                                                <td >
                                                    {{ moment(reqDesigner.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                </td>
                                                <td >
                                                    در {{ reqDesigner.expired }} روز
                                                </td>
                                                <td>
                                                    <span v-if="reqDesigner.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                                    <span v-if="reqDesigner.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                                    <span v-if="reqDesigner.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                                    <span v-if="reqDesigner.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                                    <span v-if="reqDesigner.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                    <span v-if="reqDesigner.status == 5" class="badge badge-pill badge-soft-pink">انتخاب شده</span>
                                                </td>
                                                <td class="text-end" v-if="reqDesigner.status !== 3 && reqDesigner.status !== 5 && reqDesigner.id !== reqDesigner.req_designer.reqdesigner_id">
                                                    <div class="dropdown">
                                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                        <div class="dropdown-menu">
                                                            <Link v-if="reqDesigner.status !== 3 && reqDesigner.status !== 5"
                                                                class="dropdown-item"  :href="route('reqDesigner.edit',[reqDesigner.id])" method="GET" >ویرایش جزئیات</Link>
                                                            <Link v-if="reqDesigner.status !== 3 && reqDesigner.status !== 5"
                                                                class="dropdown-item text-danger"  :href="route('reqDesigner.destroy',[reqDesigner.id])" @finish="submitTimer()"   method="delete" >لغو</Link>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-5" v-if="props.reqDesigners.total > 9">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">
                                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                v-for="link in props.reqDesigners.links" :key="link.id" >
                                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                v-html="link.label" ></Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </article>
                            </div>
                        </div>
                   </div>
                   <p v-else>گزینه ای یافت نشد.</p>
            </section >
        <Footer :companies="props.companies" />
    </main>
</template>
