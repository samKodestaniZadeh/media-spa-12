<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    user:Object,orders:Object,users:Object,cartPrice:Number||String,cartCount:Number||String,cartDiscount:Number||String,wallet:Number,
    cartCoupon:Number||String,cartTotal:Number||String,notifications:Object,statuses:Object|String,subjects:Object|String,alert:Object,companies:Object,
    descriptions:Object,cart:Object
});

const form = useForm({
    subject: props.subjects?props.subjects:null,
    status:props.statuses?props.statuses:null,
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
    if (alert.value.title)
    {
        swal.fire({
            title: props.alert.title ,
            text:props.alert.text,
            icon:props.alert.icon,
        })
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
        title: [errors.value.userName ? errors.value.userName +'<br>' :'' ,
                errors.value.status ? errors.value.status +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('userModir.index'),{ only: [props.alert,errors.value,hasErrors.value] })
}
const submit = ()=>{

    if(form.subject == null || form.status == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.get(route('userModir.index'),{
            onFinish:() => submitTime()
        });
    }

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
                            <!-- <a href="#" class="btn btn-primary"><i class="material-icons md-plus"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ایجاد جدید</font></font></a> -->
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4">
                    <header class="card-header">
                        <div class="row gx-3">
                            <div class="col-lg-4 col-md-6 me-auto">
                                <input v-model.lazy="form.subject" type="text" placeholder="جستجو کردن..." class="form-control" @focusout="submit">
                            </div>
                            <div class="col-lg-2 col-md-3 col-6">
                                <select class="form-select" v-model.lazy="form.status" @change="submit">
                                    <option value="All">همه وضعیت ها</option>
                                    <option value="0">ثبت</option>
                                    <option value="1">انتظار</option>
                                    <option value="2">بررسی</option>
                                    <option value="3">منقضی</option>
                                    <option value="4">منتشر</option>
                                    <option value="6">تمام شده</option>
                                </select>
                            </div>
                        </div>
                    </header>
                    <!-- card-header end// -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">کاربر</font></font></th>
                                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">نقش</font></font></th>
                                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">افتتاح حساب</font></font></th>
                                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">آخرین ورود</font></font></th>
                                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">وضعیت</font></font></th>
                                        <th class="text-end"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">عملیات</font></font></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(use,index) in props.user.data" :key="index">
                                        <td width="40%">
                                            <a href="#" class="itemside">
                                                <div class="left">
                                                    <img v-if="use.image && use.image.url" :src="$page.props.ziggy.url+'/storage/'+use.image.url" class="img-sm img-avatar" :alt="use.show_name">
                                                    <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" class="img-sm img-avatar" :alt="use.show_name">
                                                </div>
                                                <div class="info pl-3">
                                                    <h6 class="mb-0 title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ use.user_name }}</font></font></h6>
                                                    <small class="text-muted"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">شناسه کاربری: {{use.id}}</font></font></small>
                                                </div>
                                            </a>
                                        </td>
                                        <td >
                                            <template v-for="(role,index) in use.roles" :key="index"  class="d-flex" >
                                                <font style="vertical-align: inherit;" ><font style="vertical-align: inherit;"> {{ role.name + ' ' }}</font></font>
                                            </template>
                                        </td>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ moment(use.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}</font></font></td>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ moment(use.updated_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}</font></font></td>
                                        <td>
                                            <span v-if="use.status == 0" class="badge badge-pill badge-soft-info">ثبت </span>
                                            <span v-if="use.status == 1" class="badge badge-pill badge-soft-warning">مسدود</span>
                                            <span v-if="use.status == 2"  class="badge badge-pill badge-soft-secondary">اخیرا</span>
                                            <span v-if="use.status == 3" class="badge badge-pill badge-soft-danger"> غیرفعال</span>
                                            <span v-if="use.status == 4" class="badge badge-pill badge-soft-success">آنلاین</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                <div class="dropdown-menu">
                                                    <Link class="dropdown-item" :href="route('userModir.show',[use.id])">ویرایش اطلاعات</Link>
                                                    <!-- <Link class="dropdown-item" :href="route('paymentModir.show',[use.id])">گردش حساب مالی</Link> -->
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <!-- table-responsive.// -->
                        </div>
                    </div>
                    <!-- card-body end// -->
                </div>
                <!-- card end// -->
                <div class="mt-5" v-if="props.user.total > 9">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-start">
                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                            v-for="link in props.user.links" :key="link.id" >
                            <Link class="page-link" :href="link.url == null ? '#' : link.url"
                            v-html="link.label" ></Link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </section>
        <Footer :companies="props.companies" />
    </main>
</template>
