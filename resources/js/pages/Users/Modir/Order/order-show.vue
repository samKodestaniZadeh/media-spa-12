<script setup>

import { computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    orders:Object,users:Object,cart:Object,notifications:Object,ids:Object,statuses:Object,prices:Object,companies:Object,
    descriptions:Object,wallet:Number
});

const form = useForm({
    price: null,
    status:null,
    id:null,
});

</script>
<template>
<Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
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
                <div class="card">
                    <header class="card-header">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
                                <span> <i class="material-icons md-calendar_today"></i> <b>
                                    <span style="vertical-align: inherit;"><span style="vertical-align: inherit;" v-for="(order,index) in props.orders.data" :key="index">
                                        {{ moment(order.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                    </span></span></b>
                                </span> <br>
                                <small class="text-muted"><span style="vertical-align: inherit;">
                                    <span style="vertical-align: inherit;" v-for="(order,index) in props.orders.data" :key="index">
                                        شناسه سفارش: {{ (order.id).toLocaleString("fa-IR") }}
                                    </span></span>
                                </small>
                            </div>
                            <div class="col-lg-6 col-md-6 ms-auto text-md-start">
                                <a class="btn btn-secondary print me-auto" href="javascript:window.print()"><i class="icon material-icons md-print"></i></a>
                            </div>
                        </div>
                    </header>
                    <!-- card-header end// -->
                    <div class="card-body">
                        <div class="row mb-50 mt-20 order-info-wrap">
                            <div class="col-md-4">
                                <article class="icontext align-items-start">
                                    <span class="icon icon-sm rounded-circle bg-primary-light">
                                        <i class="text-primary material-icons md-person"></i>
                                    </span>
                                    <div class="text">
                                        <h6 class="mb-1"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">فروشنده/طراح</span></span></h6>
                                        <p class="mb-1" v-for="(order,index) in props.orders.data" :key="index">
                                            <span style="vertical-align: inherit;">
                                                <span style="vertical-align: inherit;" >
                                                    {{order.user.user_name}}</span>
                                                </span><br>
                                                <span style="vertical-align: inherit;"><span style="vertical-align: inherit;">
                                                    {{order.user.email}} </span>
                                                <span style="vertical-align: inherit;" v-if="order.user.tel !== null">
                                                {{ (order.user.tel).toLocaleString("fa-IR") }}
                                            </span></span> <br><span style="vertical-align: inherit;"></span>
                                        </p>
                                        <Link v-for="(order,index) in props.orders.data" :key="index" :href="route('profile.show',order.user.user_name)">
                                            <span style="vertical-align: inherit;"><span style="vertical-align: inherit;">مشاهده نمایه</span>
                                            </span>
                                        </Link>
                                    </div>
                                </article>
                            </div>
                            <!-- col// -->
                            <div class="col-md-4">
                                <article class="icontext align-items-start">

                                </article>
                            </div>
                            <!-- col// -->
                            <div class="col-md-4">
                                <article class="icontext align-items-start">
                                    <span class="icon icon-sm rounded-circle bg-primary-light">
                                        <i class="text-primary material-icons md-person"></i>
                                    </span>
                                    <div class="text">
                                        <h6 class="mb-1"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">خریدار/کارفرما</span></span></h6>
                                        <p class="mb-1" v-for="(order,index) in props.orders.data" :key="index">
                                            <span style="vertical-align: inherit;">
                                                <span style="vertical-align: inherit;" >
                                                    {{order.order.user.user_name}}</span>
                                                </span><br>
                                                <span style="vertical-align: inherit;"><span style="vertical-align: inherit;">
                                                    {{order.order.user.email}} </span>
                                                <span style="vertical-align: inherit;" v-if="order.order.user.tel !== null">
                                                {{ (order.order.user.tel).toLocaleString("fa-IR") }}
                                            </span></span> <br><span style="vertical-align: inherit;"></span>
                                        </p>
                                        <Link v-for="(order,index) in props.orders.data" :key="index" :href="route('profile.show',order.order.user.user_name)">
                                            <span style="vertical-align: inherit;"><span style="vertical-align: inherit;">مشاهده نمایه</span>
                                            </span>
                                        </Link>
                                    </div>
                                </article>
                            </div>
                            <!-- col// -->
                        </div>
                        <!-- row // -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="40%"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">خدمات - محصول</span></span></th>
                                                <th width="10%"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">قیمت واحد</span></span></th>
                                                <th width="10%"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">تعداد</span></span></th>
                                                <th width="10%"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">تخفیف</span></span></th>
                                                <th width="10%"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">ناخالص</span></span></th>
                                                <th width="10%"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">درآمد</span></span></th>
                                                <th width="10%"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">مالیات</span></span></th>
                                                <th width="20%" class="text-end"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">پرداخت</span></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(order,index) in props.orders.data" :key="index">
                                                <td>
                                                    <a class="itemside" href="#">
                                                        <div class="left">
                                                            <img v-if="order.orderable.image" :src="'/storage/'+order.orderable.image.url" width="40" height="40" class="img-xs" :alt="order.orderable.name">
                                                        </div>
                                                        <div class="info">
                                                            <span style="vertical-align: inherit;">
                                                                <span style="vertical-align: inherit;" v-if="order.orderable.name">
                                                                {{order.orderable.name}}
                                                                </span>
                                                                <span style="vertical-align: inherit;" v-else>
                                                                {{order.orderable.title}}
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{(order.price).toLocaleString("fa-IR")}}</span></span></td>
                                                <td><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{(order.count).toLocaleString("fa-IR")}}</span></span></td>
                                                <td><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{(order.discount).toLocaleString("fa-IR")}}</span></span></td>
                                                <td><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{((order.price*order.count)-order.discount).toLocaleString("fa-IR")}}</span></span></td>
                                                <td><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{(order.comison).toLocaleString("fa-IR")}}</span></span></td>
                                                <td><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{(order.tax).toLocaleString("fa-IR")}}</span></span></td>
                                                <td class="text-end"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{(order.total).toLocaleString("fa-IR")}}</span></span></td>
                                            </tr>
                                            <tr>
                                                <td colspan="7">
                                                    <article class="float-end">
                                                        <dl class="dlist">
                                                            <dt><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">جمع :</span></span></dt>
                                                            <dd>
                                                                <span style="vertical-align: inherit;">
                                                                    <span style="vertical-align: inherit;" v-for="(order,index) in props.orders.data" :key="index">
                                                                        {{(order.order.price).toLocaleString("fa-IR")}}
                                                                    </span>
                                                                </span>
                                                            </dd>
                                                        </dl>
                                                        <dl class="dlist">
                                                            <dt><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">تخفیف :</span></span></dt>
                                                            <dd>
                                                                <span style="vertical-align: inherit;">
                                                                    <span style="vertical-align: inherit;" v-for="(order,index) in props.orders.data" :key="index">
                                                                        {{(order.order.discount).toLocaleString("fa-IR")}}
                                                                    </span>
                                                                </span>
                                                            </dd>
                                                        </dl>
                                                        <dl class="dlist">
                                                            <dt><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">بن تخفیف :</span></span></dt>
                                                            <dd>
                                                                <span style="vertical-align: inherit;">
                                                                    <span style="vertical-align: inherit;" v-for="(order,index) in props.orders.data" :key="index">
                                                                        {{(order.order.coupon).toLocaleString("fa-IR")}}
                                                                    </span>
                                                                </span>
                                                            </dd>
                                                        </dl>
                                                        <dl class="dlist">
                                                            <dt><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">کل :</span></span></dt>
                                                            <dd>
                                                                <b class="h5">
                                                                    <span style="vertical-align: inherit;">
                                                                        <span style="vertical-align: inherit;" v-for="(order,index) in props.orders.data" :key="index">
                                                                            {{(order.order.total).toLocaleString("fa-IR")}}
                                                                        </span>
                                                                    </span>
                                                                </b>
                                                            </dd>
                                                        </dl>
                                                        <dl class="dlist">
                                                            <dt class="text-muted"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">وضعیت:</span></span></dt>
                                                            <dd>
                                                                <span class="badge rounded-pill alert-success text-success"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">پرداخت انجام شد</span></span></span>
                                                            </dd>
                                                        </dl>
                                                    </article>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </section>
            <Footer :companies="props.companies" />
    </main>
</template>
