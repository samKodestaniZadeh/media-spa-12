<script setup>

import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';
import {Head, Link, useForm} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users: Object, all:Number,second:Number,minute:Number,hour:Number,day:Number,week:Number,
    month:Number,year:Number,cartPrice: Object, cartCount: Object, cartDiscount: Object,
    cartCoupon: Object, cartTotal: Object, names: Object, ids: Object, statuses: Object,
    companies:Object,descriptions:Object,wallet:Number,cart:Object
});
const form = useForm({
    name: null,
    status: null,
    id: null,
});

const submitFilter = () => {
    if (form.status == null && form.id == null && form.name == null) {
        errors.value.errors = 'لطفا یکی از موارد را جهت فیلتر انتخاب نمایید.'
    } else {
        form.get(route('productModir.search'));
    }
};
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
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table  class="table table-hover">
                            <thead>
                            <tr class="col">
                                <th scope="col"> ردیف</th>
                                <th scope="col">بازدید</th>
                                <th scope="col">تعداد</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{(1).toLocaleString("fa-IR")}}</td>
                                    <td>لحظه</td>
                                    <td>{{ props.second}}</td>
                                </tr>
                                <tr>
                                    <td>{{(2).toLocaleString("fa-IR")}}</td>
                                    <td>دقیقه</td>
                                    <td>{{ props.minute}}</td>
                                </tr>
                                <tr>
                                    <td>{{(3).toLocaleString("fa-IR")}}</td>
                                    <td>ساعت</td>
                                    <td>{{ props.hour}}</td>
                                </tr>
                                <tr>
                                    <td>{{(4).toLocaleString("fa-IR")}}</td>
                                    <td>روز</td>
                                    <td>{{ props.day}}</td>
                                </tr>
                                <tr>
                                    <td>{{(5).toLocaleString("fa-IR")}}</td>
                                    <td>هفته</td>
                                    <td>{{ props.week}}</td>
                                </tr>
                                <tr>
                                    <td>{{(6).toLocaleString("fa-IR")}}</td>
                                    <td>ماه</td>
                                    <td>{{ props.month}}</td>
                                </tr>
                                <tr>
                                    <td>{{(7).toLocaleString("fa-IR")}}</td>
                                    <td>سال</td>
                                    <td>{{ props.year}}</td>
                                </tr>
                                <tr>
                                    <td>{{(8).toLocaleString("fa-IR")}}</td>
                                    <td>کل</td>
                                    <td>{{ props.all}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <Footer :companies="props.companies" />
    </main>
</template>
