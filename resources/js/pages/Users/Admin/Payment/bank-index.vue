<script setup>

import { computed } from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { Head, Link, useForm , usePage} from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users:Object,banks:Object,transaction:Object,notifications:Object,companies:Object,descriptions:Object,wallet:Number,
    cart:Object,
});

const form = useForm({
    transaction: null,
    status:null,
    id:null,
    price:null,
});

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
                        <table>
                            <thead >
                                <td class="me-auto">
                                    <!-- <Link :href="route('paymentAdmin.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link> -->
                                </td>
                            </thead>
                        </table>

                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4" v-if="props.banks && props.banks.total > 0">
                    <div class="card-body" >
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead >
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">نام</th>
                                        <th scope="col">نام کاربر</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(bank,index) in props.banks.data" :key="index">
                                        <td>{{(bank.id).toLocaleString("fa-IR")}}</td>
                                        <td>{{bank.menu.name}}</td>
                                        <td>{{bank.user.user_name}}</td>
                                        <td>
                                            {{ moment(bank.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td>
                                            <span v-if="bank.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                            <span v-if="bank.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                            <span v-if="bank.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                            <span v-if="bank.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                            <span v-if="bank.status == 4" class="badge badge-pill badge-soft-success">انجام</span>
                                        </td>
                                        <td>
                                            <Link class="btn btn-sm btn-primary" :href="route('bankAdmin.show',[bank.id])">نمایش</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-5" v-if="props.banks.total > 9">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-start">
                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                            v-for="link in props.banks.links" :key="link.id" >
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

