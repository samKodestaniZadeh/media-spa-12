<script setup>

import { computed,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import DatePicker from 'vue3-persian-datetime-picker';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({users:Object,orders:Object,orderables:Object,notifications:Object,companys:Object});

// const form =  useForm({
//     id:null
// });


// const submit = () =>{
//     form.post(route('profile.store'))
// };

</script>
<template>
<head>

    <link rel="stylesheet" :href="$page.props.ziggy.url+'/assets/css/vendors/material-icon-round.css'" type="text/css">
    <meta charset="utf-8">
    <!-- <title>فاکتور - صورتحساب شماره : {{ str_pad($orders.id, 6, '0', STR_PAD_LEFT) }}</title> -->

</head>
<body>
    <div class="content-header d-flex">
            <a href="javascript:history.back()"><i class="material-icons md-arrow_forward"></i> بازگشت </a>
            <a class="btn btn-secondary print me-auto" href="javascript:window.print()"><i class="icon material-icons md-print"></i></a>
        </div>
    <section class="container" >

        <!-- style="background-color: color"  -->
       <!-- <div class="head-1">
            <img width="60" style="float: right" src="asset('img/logo-color-1x.png')" alt="company-logo" />
        </div> -->
        <div class="head-4">
            <strong style="font-size: 20px">صورت حساب فروش کالا و خدمات</strong>
        </div>
         <div class="head-3">

            <strong> شماره سریال: {{ props.orders.id }}</strong>
        </div>
    </section>
    <section>
        <div class="ipanel">
                <h4 class="header">مشخصات فروشنده</h4>
            <div class="details mb-4">
                <div class="col-1">
                    <span>نام شخص حقیقی / حقوقی: </span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0">{{item.name}}</strong>
                    <strong v-else>-</strong>
                </div>
                <!-- <div class="col-2">
                    <span class="panel-col-text"> شماره ثبت/شماره ملی:</span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0">{{item.national_number}}</strong>
                    <strong v-else>-</strong>
                </div>
                <div class="col-3">
                    <span class="panel-col-text">شماره اقتصادی فروشنده:</span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0">{{item.economical_number}}</strong>
                    <strong v-else>-</strong>
                </div>
                <div class="col-2">
                    <span class="panel-col-text">شهر:</span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0">{{item.shahr}}</strong>
                    <strong v-else>-</strong>
                </div>
                <div class="col-3">
                    <span class="panel-col-text">کد ده رقمی فروشنده:</span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0">{{item.postal_code}}</strong>
                    <strong v-else>-</strong>
                </div>
                <div class="col-12">
                    <span class="panel-col-text">نشانی کامل:</span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0" >  استان: {{item.ostan}}</strong>
                    <strong v-else>-</strong>
                </div>
                <div class="col-1">
                    <span>نشانی دقیق فروشنده: </span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0">{{item.address}} </strong>
                    <strong v-else>-</strong>
                </div>
                <div class="col-2">
                    <span>شماره تلفن / نمابر: </span>
                    <strong v-for="(item,index) in props.companys.data" :key="index" v-if="props.companys.total > 0">{{item.phone}}-{{item.mobile}} </strong>
                    <strong v-else>-</strong>
                </div> -->
            </div>
        </div>
    </section>
<br>

<section>
    <div class="ipanel">
                <h4 class="header">مشخصات خریدار</h4>
            <div class="details mb-4">
                <div class="col-1">
                    <span>نام شخص حقیقی / حقوقی: </span>
                    <strong>
                    <strong v-if="props.users.profile.gender == 'آقا'">جناب {{props.users.profile.gender}}ی </strong>
                    <strong v-else> سرکار {{props.users.profile.gender}} </strong>
                  {{props.orders.user.name}} {{props.orders.user.lasst_name}} </strong><br>
                </div>
                <div class="col-2">
                    <span class="panel-col-text"> شماره ثبت/شماره ملی:</span>
                    <strong>{{props.orders.user.national_code}}</strong>
                </div>
                <div class="col-3">
                    <span class="panel-col-text">شماره اقتصادی خریدار:</span>
                    <strong> </strong>
                </div>
                <div class="col-2">
                    <span class="panel-col-text">شهر:</span>
                    <strong>{{props.users.profile.shahr}} </strong>
                </div>
                <div class="col-3">
                    <span class="panel-col-text">کد ده رقمی خریدار:</span>
                    <strong> </strong>
                </div>
                <div class="col-12">
                    <span class="panel-col-text">نشانی کامل:</span>
                    <strong>  استان:{{props.users.profile.ostan}} </strong>
                </div>
                <div class="col-1">
                    <span>نشانی دقیق خریدار: </span>
                    <strong>{{props.users.profile.address}}</strong>
                </div>
                <div class="col-2">
                    <span>شماره تلفن / نمابر: </span>
                    <strong>{{props.orders.user.tel}} </strong>
                </div>
            </div>
        </div>
</section>
<br>
<section>
    <div class="ipanel-2">
            <h4 class="header">مشخصات کالا یا خدمات مورد معامله</h4>
    </div>
</section>
<table style="margin-top:205px !important ; margin-right:1px">
    <thead>
    <tr style="background-color: #d2d2d2">
        <th>ردیف</th>
        <th>کد کالا</th>
        <th>شرح کالا / خدمات</th>
        <th>تعداد / مقدار</th>
        <th>مبلغ واحد(ریال)</th>
        <th>مبلغ کل(ریال)</th>
        <th>تخفیف(ریال)</th>
        <th>مبلغ کل پس از تخفیف(ریال)</th>
        <th>مبلغ کارمزد(ریال)</th>
        <th>جمع مالیات و عوارض(ریال)</th>
        <th>جمع مبلغ کل بعلاوه جمع مالیات و عوارض(ریال)</th>
    </tr>
    </thead>
    <tbody>

        <tr v-for="item,index in props.orderables" :key="index">
            <td>{{ (index+1).toLocaleString("fa-IR") }}</td>
            <td>{{ (item.orderable.id).toLocaleString("fa-IR")}}</td>
            <td>{{ item.orderable.group }} {{ item.orderable.type }} {{ item.orderable.name }}</td>
            <td>{{ (item.count).toLocaleString("fa-IR") }}</td>
            <td>{{ (item.price).toLocaleString("fa-IR") }}</td>
            <td>{{ (item.price).toLocaleString("fa-IR") }}</td>
            <td>{{ (item.discount).toLocaleString("fa-IR") }}</td>
            <td>{{(item.price-item.discount).toLocaleString("fa-IR")}}</td>
            <td>{{(item.comison).toLocaleString("fa-IR")}}</td>
            <td>{{(item.tax+item.complications).toLocaleString("fa-IR")}}</td>
            <td>{{ (item.total).toLocaleString("fa-IR") }}</td>
        </tr>
    <!-- @empty
        <tr>
            <td colspan="6">هیچ محصولی وجود ندارد!</td>
        </tr>
    @endforelse -->
    </tbody>
    <tbody>
        <tr>
            <td colspan="5">جمع</td>
            <td>{{(props.orders.price).toLocaleString("fa-IR") }}</td>
            <td>{{ (props.orders.discount+props.orders.coupon).toLocaleString("fa-IR") }}</td>
            <td>{{(props.orders.price-(props.orders.discount+props.orders.coupon)).toLocaleString("fa-IR") }}</td>
            <td>{{ (props.orders.comison).toLocaleString("fa-IR") }}</td>
            <td>{{ (props.orders.tax+props.orders.complications).toLocaleString("fa-IR")  }}</td>
            <td>{{ (props.orders.total).toLocaleString("fa-IR")  }}</td>
        </tr>
    </tbody>
</table>
<br>

<section>

    <span class="panel-col-text">توضیحات:</span>
    <p style="font-size:10px;text-align: justify; padding: 0 15px"> بدون مهر و امضا فاقد اعتبار می باشد. </p> <br>

        <div class="foot-1">
            <span class="panel-col-text">مبلغ قابل پرداخت:</span>
            <strong>{{(props.orders.total).toLocaleString("fa-IR") }}</strong> <br>
            <!-- +props.orders.comison -->
            <hr style="width: 300px; float: right; text-align: right">
            <strong style="font-size: 12px">شرایط و نحوه فروش: </strong>
            <input type="checkbox" class="check-box"> <span>پرداخت نقدی</span>
            <!-- <input type="checkbox" class="check-box"> <span>پرداخت غیر نقدی</span> -->
        </div>

</section>
<br>
<section>
    <div class="col-12" style="text-align: center;vertical-align: middle;">
        مهر و امضا
    </div>
</section>
<htmlpagefooter name="page-footer">
    <section class="container-footer" >
        <!-- {{ $sale->company->title ?? '' }} {{ $sale->company->name ?? '' }} | {{ $sale->company->address ?? '' }} - {{ $sale->company->tel ?? '' }} -->
    </section>
</htmlpagefooter>
</body>
</template>
<style>
        @page {
            footer: page-footer;
        }
        body {
            direction: rtl;
            text-align: right;
            font-family: 'fa';
            font-size: 12px;
        }
        strong {
            font-size: 10px;
        }
        table th {
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0 !important;
        }
        table th {
            width: auto;
            height: 30px;
            font-weight: bold;
        }
        table td{
            font-size: 10px;
        }
         table, td, th {
             padding: 8px;
             border: 1px solid black;
             text-align: center;
             vertical-align: middle;
         }
        .container {
            display: flex;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px !important;
        }
        .container-footer {
            font-size: 8px;
            display: flex;
            border-radius: 10px;
            padding: 5px;
        }
        .head-1 {
            position: relative;
            width: 20%;
            float: right;
            z-index: 1010101010;
            text-align: center;
            vertical-align: middle;
        }
        .head-2 {
            position: relative;
            width: 20%;
            float: left;
            z-index: 1010101010;
            text-align: center;
            vertical-align: middle;
        }
        .head-3 {
            position: relative;
            width: 60%;
            float: left;
            z-index: 1010101010;
            text-align: left;
            vertical-align: middle;
            margin-left: 3rem;
        }
        .head-4 {
            position: relative;
            width: 100%;
            float: right;
            z-index: 1010101010;
            text-align: left;
            vertical-align: middle;
        }
        .col-1 {
            position: relative;
            width: 44%;
            float: right;
            text-align: right;
            z-index: 1010101010;
        }
        .col-1 strong{
            font-size: 10px;
        }
        .col-2 {
            position: relative;
            width: 28%;
            float: left;
            z-index: 1010101010;
            text-align: right;
        }
        .col-2 strong{
            font-size: 10px;
        }
        .col-3 {
            position: relative;
            width: 28%;
            float: left;
            z-index: 1010101010;
            text-align: right;
        }
        .col-3 strong{
            font-size: 10px;
        }
        .col-12{
            position: relative;
            width: 100%;
            z-index: 1010101010;
            text-align: right;
        }
        .col-12 strong{
            font-size: 10px;
        }
        .ipanel {
            margin-bottom: -15px;
            position: relative;
            width: 100%;
            float: right;
            z-index: 1010101010;
            text-align: center;
            vertical-align: middle;
            border: 1.5px solid #404040;
            border-radius: 5px;
        }
        .ipanel .details {
            padding: 1rem;

        }
        .ipanel-2 {
            margin-bottom: -12px;
            position: relative;
            width: 100%;
            float: right;
            z-index: 1010101010;
            text-align: center;
            vertical-align: middle;
            /* border: 1.5px solid #404040; */
            border-radius: 5px;
        }
        .ipanel-2 .details {
            padding: 1rem;

        }
        .header {
            background-color: #d2d2d2;
            margin-top: 0 !important;
        }
        .foot-1 {
            position: relative;
            width: 70%;
            float: right;
            text-align: right;
            z-index: 1010101010;
        }
        .foot-1 strong{
            font-size: 10px;
        }
        .foot-2 {
            position: relative;
            width: 30%;
            float: left;
            z-index: 1010101010;
            text-align: right;
        }
        .foot-2 strong{
            font-size: 10px;
        }
        .check-box{
            font-size: 15px;
            color: black;
        }
        .me-auto {
            margin-right: auto !important;
        }
        .ms-auto {
            margin-left: auto !important;
        }
        .d-flex {
            display: -webkit-box !important;
            display: -ms-flexbox !important;
            display: flex !important;
        }
        .m-0 {
        margin: 0 !important;
        }
        .mt-0,
        .my-0 {
        margin-top: 0 !important;
        }
        .mr-0,
        .mx-0 {
        margin-left: 0 !important;
        }
        .mb-0,
        .my-0 {
        margin-bottom: 0 !important;
        }
        .ml-0,
        .mx-0 {
        margin-right: 0 !important;
        }
        .m-1 {
        margin: .25rem !important;
        }
        .mt-1,
        .my-1 {
        margin-top: .25rem !important;
        }
        .mr-1,
        .mx-1 {
        margin-left: .25rem !important;
        }
        .mb-1,
        .my-1 {
        margin-bottom: .25rem !important;
        }
        .ml-1,
        .mx-1 {
        margin-right: .25rem !important;
        }
        .m-2 {
        margin: .5rem !important;
        }
        .mt-2,
        .my-2 {
        margin-top: .5rem !important;
        }
        .mr-2,
        .mx-2 {
        margin-left: .5rem !important;
        }
        .mb-2,
        .my-2 {
        margin-bottom: .5rem !important;
        }
        .ml-2,
        .mx-2 {
        margin-right: .5rem !important;
        }
        .m-3 {
        margin: 1rem !important;
        }
        .mt-3,
        .my-3 {
        margin-top: 1rem !important;
        }
        .mr-3,
        .mx-3 {
        margin-left: 1rem !important;
        }
        .mb-3,
        .my-3 {
        margin-bottom: 1rem !important;
        }
        .ml-3,
        .mx-3 {
        margin-right: 1rem !important;
        }
        .m-4 {
        margin: 1.5rem !important;
        }
        .mt-4,
        .my-4 {
        margin-top: 1.5rem !important;
        }
        .mr-4,
        .mx-4 {
        margin-left: 1.5rem !important;
        }
        .mb-4,
        .my-4 {
        margin-bottom: 1.5rem !important;
        }
        .ml-4,
        .mx-4 {
        margin-right: 1.5rem !important;
        }
        .m-5 {
        margin: 3rem !important;
        }
        .mt-5,
        .my-5 {
        margin-top: 3rem !important;
        }
        .mr-5,
        .mx-5 {
        margin-left: 3rem !important;
        }
        .mb-5,
        .my-5 {
        margin-bottom: 3rem !important;
        }
        .ml-5,
        .mx-5 {
        margin-right: 3rem !important;
        }

    </style>
