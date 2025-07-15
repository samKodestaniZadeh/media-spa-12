<script setup>

import { computed,ref} from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import DatePicker from 'vue3-persian-datetime-picker';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users:Object,tickets:Object,times:Object|String,statuses:Object|String,subjects:Object|String,
    cartNumber:Number,cartPrice:Number,cartCount:Number,cartDiscount:Number,cartCoupon:Number,menus:Object,
    cartTotal:Number,notifications:Object,companies:Object,descriptions:Object,wallet:Number,cart:Object
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
    Inertia.visit(route('supportAdmin.index'),{ only: [errors.value,hasErrors.value,props.alert] })
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
        form.get(route('supportAdmin.index'),{
            onFinish:() => submitTime()
        });
    }

};
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
                            <Link :href="route('supportAdmin.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="mb-4 bg-white" v-if="props.tickets && props.tickets.total > 0">
                    <header class="card-header">
                        <div class="row align-items-center">
                            <div class="col col-check flex-grow-0">
                                <div class="form-check ms-2">
                                    <input class="form-check-input" type="checkbox" value="">
                                </div>
                            </div>
                            <div class="col-md-3 col-12 ms-auto mb-md-0 mb-3">

                                <select class="form-select" v-model.lazy="form.subject" @change="submit">
                                    <option value="All"> همه عنوان ها</option>
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
                                    <option value="0">ثبت شده</option>
                                    <option value="1">در انتظار پاسخ</option>
                                    <option value="2"> مشاهده شده</option>
                                    <option value="3">بسته شده</option>
                                    <option value="4">پاسخ داده شده</option>

                                </select>
                            </div>
                        </div>
                    </header>
                    <div class="card-body" >
                        <div class="table-responsive">
                            <table v-if="props.tickets.total > 0" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="ticket in props.tickets.data" :key="ticket.id">
                                        <td>{{(ticket.id).toLocaleString("fa-IR")}}</td>
                                        <td>{{ticket.subject.name}}</td>
                                        <td>
                                            {{ moment(ticket.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td>
                                            <span v-if="ticket.status == 0" class="badge badge-pill badge-soft-info">ثبت شده</span>
                                            <span v-if="ticket.status == 1" class="badge badge-pill badge-soft-warning"> در انتظار پاسخ</span>
                                            <span v-if="ticket.status == 2"  class="badge badge-pill badge-soft-secondary">مشاهده شده</span>
                                            <span v-if="ticket.status == 3" class="badge badge-pill badge-soft-danger">بسته شده</span>
                                            <span v-if="ticket.status == 4" class="badge badge-pill badge-soft-success">پاسخ داده شده</span>

                                        </td>
                                        <td class="text-end">
                                            <Link :href="route('supportAdmin.show',[ticket.id])" class="btn btn-primary btn-sm rounded font-sm">نمایش</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-5" v-if="props.tickets.total > 9 ">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-start">
                                        <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                        v-for="link in props.tickets.links" :key="link.id" >
                                        <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                        v-html="link.label" ></Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>گزینه ای یافت نشد.</p>
                </div>
            </section>
        <Footer :companies="props.companies" />
</main>
</template>

