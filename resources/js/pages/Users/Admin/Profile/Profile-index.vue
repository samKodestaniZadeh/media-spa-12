<script setup>

import { computed} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    user:Object,orders:Object,users:Object,cartPrice:Number,cartCount:Number,cartDiscount:Number,
    cartCoupon:Number,cartTotal:Number,notifications:Object,ids:Object,statuses:Object,user_names:Object,
    companies:Object,descriptions:Object,wallet:Number,cart:Object
});

const form = useForm({
    id:null,wallet:null,cartWallet:null,cartCount:null,cartPrice:null,cartDiscount:null,cartCoupon:null,cartTotal:null,
    status:null,user_name:null
});


const submit = (price,discount,coupon,total,price_wallet,id)=>{

     form.wallet = 'on',
     form.id = id,
     form.cartWallet = price_wallet
     form.cartCount = 1,
     form.cartPrice = price,
     form.cartDiscount = discount,
     form.cartCoupon = coupon,
     form.cartTotal = total

    if(props.users.profile.wallet >= (total-price_wallet))
    {
        form.post(route('order.store'))
    }
    else
    {
        errors.value.errors = 'موجودی کافی نمی باشد.لطفا پس از شارژ مجدددرخواست نمایید. '
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

                    </td>
                </div>
                <div class="col-sm-12">
                    <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                </div>
            </div>
                <div class="card mb-4"  v-if="props.user.total > 0">
                    <div class="card-body" >
                        <div class="table-responsive">
                            <table v-if="props.user.total > 0" class="table table-hover">
                            <thead>
                                <tr class="col">
                                    <th scope="col">شناسه</th>
                                    <th scope="col">نام کاربری</th>
                                    <th scope="col">وضعیت</th>
                                    <th scope="col">اعمال</th>
                                </tr>
                            </thead>
                            <tbody >

                                <tr v-for="(use,index) in props.user.data" :key="index">
                                    <td >{{(use.id).toLocaleString("fa-IR")}}</td>
                                    <td >{{use.user_name}}</td>

                                    <td >
                                        <span v-if="use.session">{{ moment(use.session.last_activity).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}</span>
                                        <span v-else-if="use.status == 0" class="badge badge-pill badge-soft-info">ثبت شده </span>
                                        <span v-else-if="use.status == 1" class="badge badge-pill badge-soft-warning">مسدود شده</span>
                                        <span v-else-if="use.status == 2"  class="badge badge-pill badge-soft-secondary">اخیرا</span>
                                        <span v-else-if="use.status == 3" class="badge badge-pill badge-soft-danger">غیر فعال</span>
                                        <span v-else-if="use.status == 4" class="badge badge-pill badge-soft-success">آنلاین</span>
                                    </td>
                                    <td>
                                        <Link class="btn btn-sm btn-primary" :href="route('profileAdmin.show',[use.id])">نمایش</Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        </div>
                    </div>
                </div>
                <p v-else>گزینه ای یافت نشد.</p>
            </section >
        <Footer :companies="props.companies" />
    </main>
</template>
