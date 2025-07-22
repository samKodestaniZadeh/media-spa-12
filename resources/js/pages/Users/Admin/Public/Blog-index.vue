<script setup>
import { computed,ref} from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Aside from '@/Components/AsideAdmin.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users:Object,blogs:Object,ids:Object,statuses:Object,subjects:Object,wallet:Number,
    cartNumber:Number,cartPrice:Number,cartCount:Number,cartDiscount:Number,cartCoupon:Number,
    cartTotal:Number,notifications:Object,companies:Object,descriptions:Object,alert:Object
    ,cart:Object
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
        title: [errors.value.title_en ? errors.value.title_en +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('blogAdmin.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}
</script>
<template>
    <Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <div class="screen-overlay"></div>
        <main class="main-wrap rtl" >
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <Link :href="route('blogAdmin.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body" >
                        <div class="row gx-5">
                        <!-- <Aside class="col-lg-3 border-end" /> -->
                            <div class="col-lg-12">
                                <section class="content-body p-xl-4">
                                    <div class="table-responsive" v-if="props.blogs.total > 0">
                                        <table v-if="props.blogs.total > 0" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">شناسه</th>
                                                    <th scope="col">ایجاد کننده</th>
                                                    <th scope="col">عنوان</th>
                                                    <th scope="col">تاریخ</th>
                                                    <th scope="col">وضعیت</th>
                                                    <th scope="col">عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr v-for="menu in props.blogs.data" :key="menu.id">
                                                    <td>{{(menu.id).toLocaleString("fa-IR")}}</td>
                                                    <td v-if="menu.user">{{menu.user.user_name}}</td>
                                                    <td>{{menu.title}}</td>
                                                    <td>
                                                        {{ moment(menu.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                    </td>
                                                    <td>
                                                        <span v-if="menu.status == 0"  class="badge badge-pill badge-soft-info"> ثبت</span>
                                                        <span v-if="menu.status == 1" class="badge badge-pill badge-soft-warning">ایجاد</span>
                                                        <span v-if="menu.status == 2"  class="badge badge-pill badge-soft-secondary">  بررسی</span>
                                                        <span v-if="menu.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                                        <span v-if="menu.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="dropdown">
                                                            <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                            <div class="dropdown-menu">
                                                                <Link :href="route('blog.show',[menu.slug])" class="dropdown-item">نمایش</Link>
                                                                <Link :href="route('blogAdmin.show',[menu.id])" class="dropdown-item">نمایش جزئیات</Link>
                                                                <Link class="dropdown-item text-danger" :href="route('blogAdmin.destroy',[menu.id])" method="delete" as="button" @finish="submitTime()">حذف</Link>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                    <div class="mt-5" v-if="props.blogs.total > 9 ">
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination justify-content-start">
                                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                            v-for="link in props.blogs.links" :key="link.id" >
                                                            <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                            v-html="link.label" ></Link>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else>
                                        <p>گزینه ای یافت نشد.</p>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <Footer :companies="props.companies" />
</main>
</template>

