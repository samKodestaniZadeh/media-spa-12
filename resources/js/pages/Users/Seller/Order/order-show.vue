<script setup>

import { computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({orders:Object,users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,
cartTotal:Object,notifications:Object,ids:Object,statuses:Object,prices:Object,companies:Object,
descriptions:Object});

const form = useForm({
    price: null,
    status:null,
    id:null,
});

const submitFilter = () => {
     if( form.status == null && form.id == null && form.price == null ){
         errors.value.errors = 'لطفا یکی از موارد را جهت فیلتر انتخاب نمایید.'
    }else{
        form.get(route('order.search'));
    }
};
</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
        <section class="content-main">
                <div class="content-header">
                    <div>
                        <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                        <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                    </div>
                </div>
                   <div class="card mb-4"  v-if="props.orders.total > 0">

                        <div class="card-body">
                            <table v-if="props.orders.total > 0 " class="table table-responsive">
                                <thead>
                                    <tr class="col">
                                        <th scope="col">شناسه خرید</th>
                                        <th scope="col">فروشنده</th>
                                        <th scope="col">محصول</th>
                                        <th scope="col">قیمت</th>
                                        <th scope="col">تعداد</th>
                                        <th scope="col">تخفیف</th>
                                        <th scope="col">خالص</th>
                                        <th scope="col">درآمد</th>
                                        <th scope="col">مالیات</th>
                                        <th scope="col">کل</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(order,index) in props.orders.data" :key="index">
                                        <td >{{(order.order_id).toLocaleString("fa-IR")}}</td>
                                        <td>{{order.user.user_name}}-{{(order.user.id).toLocaleString("fa-IR")}}</td>
                                        <td>{{order.orderable.name}}</td>
                                        <td >{{(order.price).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.count).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.discount).toLocaleString("fa-IR")}}</td>
                                        <td>{{((order.price*order.count)-order.discount).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.comison).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.tax+order.complications).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.total).toLocaleString("fa-IR")}}</td>
                                        <td>
                                            {{ moment(order.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td v-if="order.t_id > 0">{{order.t_id}}</td>
                                        <td v-else>پرداخت از کیف پول </td>
                                    </tr>
                                    <div class="mt-5" v-if="props.orders.total > 9 ">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">
                                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                v-for="link in props.orders.links" :key="link.id" >
                                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                v-html="link.label" ></Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                   </div>
                   <div v-else>
                        <p>گزینه ای یافت نشد.</p>
                    </div>
            </section >

            <Footer :companies="props.companies" />
    </main>
</template>
