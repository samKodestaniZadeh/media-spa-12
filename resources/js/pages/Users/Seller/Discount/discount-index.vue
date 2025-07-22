<script setup>

import { computed, ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({cartPrice: Number, cartCount: Number, cartDiscount: Number, cartCoupon: Number,
    cartTotal: Number, alert: Object, users: Object, orders: Object, notifications: Object,time:String,
    dark: String,companies:Object,descriptions:Object,asidemini:String,path:String,discounts:Object,menus:Object,
    times:Object|String,statuses:Object|String,subjects:Object|String});

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
    Inertia.visit(route('discountVisitor.index'),{ only: [errors.value,hasErrors.value,props.alert] })
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
        form.get(route('discountVisitor.index'),{
            onFinish:() => submitTime()
        });
    }

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
                        <h2 class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></h2>
                        <td class="me-auto">
                            <Link :href="route('discountVisitor.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <p v-if="props.descriptions" v-html="props.descriptions.text"></p>
                    </div>
                </div>
                <div class="mb-4 bg-white"  v-if="props.discounts.total > 0">
                    <header class="card-header">
                        <div class="row align-items-center">
                            <div class="col col-check flex-grow-0">
                                <div class="form-check ms-2">
                                    <input class="form-check-input" type="checkbox" value="">
                                </div>
                            </div>
                            <div class="col-md-3 col-12 ms-auto mb-md-0 mb-3">

                                <select class="form-select" v-model.lazy="form.subject" @change="submit">
                                    <option value="All"> همه محصولات</option>
                                    <option v-for="menu,index in props.menus" :key="index" :value="menu.id">{{ menu.name }}</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <!-- <input type="date" v-model.lazy="form.time" class="form-control" @change="submit"> -->
                                <date-picker v-model="form.time" format="YYYY-MM-DD" display-format="dddd jDD jMMMM jYYYY"  color="#1ABC9C" type="date" @change="submit"></date-picker>
                            </div>

                            <div class="col-md-2 col-6">
                                <select class="form-select" v-model.lazy="form.status" @change="submit">
                                    <option value="All">همه وضعیت ها</option>
                                    <option value="3">منقضی</option>
                                    <option value="4">فعال</option>
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
                                            <th scope="col">محصول/خدمات</th>
                                            <th scope="col">تاریخ</th>
                                            <th scope="col">انقضا</th>
                                            <th scope="col">درصد تخفیف</th>
                                            <th scope="col">وضعیت</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <tr v-for="(discount,index) in props.discounts.data" :key="index">
                                            <td >{{(discount.id).toLocaleString("fa-IR")}}</td>
                                            <td v-if="discount.discountable">
                                                <div class="left">
                                                    <Link v-if="discount.discountable_type == 'App\\Models\\Product'" :href="route('website_templates.show',discount.discountable.slug)" class="img-sm img-thumbnail">
                                                        <img v-if="discount.discountable.image && discount.discountable.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+discount.discountable.image.url" class="img-sm img-thumbnail" :alt="discount.discountable.name">
                                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="img-sm img-thumbnail" :alt="discount.discountable.name">
                                                    </Link>
                                                    <Link v-else-if="discount.discountable_type == 'App\\Models\\Tarahi'" :href="route('website_design.show',discount.discountable.slug)" class="img-sm img-thumbnail">
                                                        <img v-if="discount.discountable.image && discount.discountable.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+discount.discountable.image.url" class="img-sm img-thumbnail" :alt="discount.discountable.name">
                                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="img-sm img-thumbnail" :alt="discount.discountable.name">
                                                    </Link>
                                                </div>
                                                <div class="info" v-if="discount.discountable_type == 'App\\Models\\Product'">
                                                    <h6 class="mb-0"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{discount.discountable.name}}</font></font></h6>
                                                </div>
                                                <div class="info" v-else-if="discount.discountable_type == 'App\\Models\\Tarahi'">
                                                    <h6 class="mb-0"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{discount.discountable.title}}</font></font></h6>
                                                </div>
                                            </td>
                                            <td>
                                                {{ moment(discount.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                            </td>
                                            <td>
                                                {{ moment(discount.expired).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                            </td>
                                            <td>
                                                {{ (discount.percent).toLocaleString("fa-IR") }}
                                            </td>
                                            <td>
                                                <span v-if="props.time > discount.expired" class="badge badge-pill badge-soft-danger">منقضی</span>
                                                <span v-else class="badge badge-pill badge-soft-success">فعال</span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-5" v-if="props.discounts.total > 9">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end ltr">
                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                            v-for="link in props.discounts.links" :key="link.id" >
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
