<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import DatePicker from 'vue3-persian-datetime-picker';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    orders:Object,users:Object,products:Object,wallet:Number,alert:Object,notifications:Object,
    companies:Object,descriptions:Object,menus:Object,times:{type: [Object, String],default: () => ({})},statuses:{type: [Object, String],default: () => ({})},
    subjects:{type: [Object, String],default: () => ({})},cart:Object
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
        title: [errors.value.menu ? errors.value.menu +'<br>' :'' ,
                errors.value.recepiant ? errors.value.recepiant +'<br>' :'' ,
                errors.value.subject ? errors.value.subject +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.email ? errors.value.email +'<br>':'',
                errors.value.name ? errors.value.name +'<br>' :'' ,
                errors.value.lasst_name ? errors.value.lasst_name +'<br>':'',],
        icon:'error',
    })

}

const form = useForm({
    subject:props.subjects?props.subjects:null,
    status:props.statuses?props.statuses:null,
    time:props.times !== 'All'? props.times:null,
});

const submitTime = ()=>{
    Inertia.visit(route('product.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () => {

    if(form.subject == null && form.status == null && form.time == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.get(route('product.index'),{
            onFinish:() => submitTime()
        });
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
                            <tr class="me-auto">
                                <Link :href="route('product.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="mb-4 bg-white"  v-if="props.products.total > 0">
                    <header class="card-header">
                        <div class="row align-items-center">
                            <div class="col col-check flex-grow-0">
                                <div class="form-check ms-2">
                                    <input class="form-check-input" type="checkbox" value="">
                                </div>
                            </div>
                            <div class="col-md-3 col-12 ms-auto mb-md-0 mb-3">

                                <select class="form-select" v-model.lazy="form.subject" @change="submit">
                                    <option value="All"> همه دسته بندی ها</option>
                                    <template v-for="menu,index in props.menus" :key="index">
                                        <option v-for="child,index in menu.children" :key="index" :value="child.id">{{ child.name }}</option>
                                    </template>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <!-- <input type="date" v-model.lazy="form.time" class="form-control" @change="submit"> -->
                                <date-picker v-model="form.time" format="YYYY-MM-DD" display-format="dddd jDD jMMMM jYYYY"  color="#1ABC9C" type="date" @change="submit"></date-picker>
                            </div>

                            <div class="col-md-2 col-6">
                                <select class="form-select" v-model.lazy="form.status" @change="submit">
                                    <option value="All">همه وضعیت ها</option>
                                    <option value="0">ثبت</option>
                                    <option value="1">انتظار</option>
                                    <option value="2">بررسی</option>
                                    <option value="3">منقضی</option>
                                    <option value="4">منتشر</option>
                                    <option value="5">متوقف</option>
                                </select>
                            </div>
                        </div>
                    </header>
                    <div class="card-body">
                        <div class="table-responsive">
                            <article class="itemlist">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="col">
                                            <th scope="col">شناسه</th>
                                            <th scope="col">نام</th>
                                            <th scope="col">دسته بندی</th>
                                            <th scope="col">تاریخ</th>
                                            <th scope="col">بروزرسانی</th>
                                            <th scope="col">بازدید</th>
                                            <th scope="col">وضعیت</th>
                                            <th scope="col">عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                            <tr v-for="(product,index) in props.products.data" :key="index">
                                                <td >{{(product.id).toLocaleString("fa-IR")}}</td>
                                                <td >
                                                    <div class="left">
                                                    <img v-if="product.image" :src="$page.props.ziggy.url +'/storage/' +product.image.url" class="img-sm img-thumbnail" :alt="product.name">
                                                    </div>
                                                    <div class="info">
                                                        <h6 class="mb-0"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{product.name}}</span></span></h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ product.type.name }}
                                                </td>
                                                <td>
                                                    {{ moment(product.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                </td>
                                                <td>
                                                    {{ moment(product.updated_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                </td>
                                                <td v-if="product.views_count">
                                                    {{ (product.views_count).toLocaleString("fa-IR") }}
                                                </td>
                                                <td v-else>
                                                    {{ (0).toLocaleString("fa-IR") }}
                                                </td>
                                                <td>
                                                    <span v-if="product.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                                    <span v-if="product.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                                    <span v-if="product.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                                    <span v-if="product.status == 3" class="badge badge-pill badge-soft-danger">منقضی</span>
                                                    <span v-if="product.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                    <span v-if="product.status == 5" class="badge badge-pill badge-soft-warning">متوقف</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                        <div class="dropdown-menu">
                                                            <Link v-if="product.status == 0 || product.status == 3 || product.status == 4" class="dropdown-item" :href="route('product.edit',[product.id])"> ویرایش</Link>
                                                            <Link v-if="product.status == 4" class="dropdown-item" method="PUT" as="button" :href="route('product.update',[product.id])">بروزرسانی</Link>
                                                            <Link v-if="product.status == 4" class="dropdown-item" :href="route('website_templates.show',[product.slug])">نمایش</Link>
                                                            <Link  class="dropdown-item" :href="route('product.show',[product.id])">فروش</Link>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                    </tbody>
                                </table>
                            </article>
                            <div class="mt-5" v-if="props.products.total > 9">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end ltr">
                                        <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                        v-for="link in props.products.links" :key="link.id" >
                                        <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                        v-html="link.label" ></Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <p v-else>گزینه ای یافت نشد.</p>
        </section >
        <Footer :companies="props.companies" />
    </main>
</template>
