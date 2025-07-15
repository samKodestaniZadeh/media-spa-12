<script setup>
import { computed,ref} from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({users:Object,orders:Object,notifications:Object,companies:Object});
</script>
<template>

    <head>
        <link :href="$page.props.ziggy.url+'/assets/frontend/assets/css/main.css'" rel="stylesheet" type="text/css"/>
    </head>
    <div class="invoice invoice-content invoice-6">
            <div class="back-top-home hover-up mt-30 ml-30">
                <Link class="hover-up" :href="route('dashboard.index')"><i class="fi-rs-home mr-5"></i> داشبورت</Link>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="invoice-inner">
                            <div class="invoice-info" id="invoice_wrapper">
                                <div class="invoice-header">
                                    <div class="invoice-icon">
                                        <img :src="$page.props.ziggy.url+'/assets/frontend/assets/imgs/theme/icons/icon-invoice.svg'" class="img-fluid" alt="">
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="logo">
                                                <Link :href="route('index')" class="mr-20">
                                                    <img v-if="props.companies && props.companies.image" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" alt="logo" style="height: 5rem;" />
                                                </Link>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <h2 class="mb-0">صورت حساب</h2>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="text" v-if="props.companies">{{ props.companies.name }}<br />
                                                <abbr title="Phone">تلفن:</abbr> {{props.companies.phone}}<br />
                                                <abbr title="Phone">پست الکترونیک:</abbr> {{props.companies.email}}<br />
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <strong v-if="props.orders.user.profile.gender == 'آقا'">جناب آقای {{props.orders.user.profile.gender}}ی </strong>
                                            <strong v-else-if="props.orders.user.profile.gender == 'خانم'"> سرکار خانم {{props.orders.user.profile.gender}} </strong>
                                            <strong class="text-brand" v-if="props.orders">{{ props.orders.user.name_show }}</strong>
                                            <br />
                                            <abbr title="Email">پست الکترونیک: </abbr>
                                            <a href="#" class="__cf_email__" data-cfemail="442a222b043321263e6a272b296a3428">{{ props.orders.user.email }}</a>
                                            <br />
                                            <abbr title="Email" v-if="props.orders.user && props.orders.user.tel">تلفن : </abbr>
                                            <a href="#" class="__cf_email__" data-cfemail="442a222b043321263e6a272b296a3428" v-if="props.orders.user && props.orders.user.tel">
                                                {{ props.orders.user.tel }}
                                            </a>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="row mt-20">
                                       <div class="col-12"> <div class="hr mb-10"></div></div>
                                        <div class="col-lg-4">
                                           <strong class="text-brand" v-if="props.orders"> شماره فاکتور:</strong> {{ props.orders.id }}
                                        </div>
                                         <div class="col-lg-4">
                                           <strong class="text-brand" v-if="props.orders"> تاریخ صدور:</strong> {{ moment(props.orders.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </div>
                                        <div class="col-lg-4">
                                           <strong class="text-brand"> روش پرداخت:</strong> کیف پول
                                        </div>
                                        <div class="col-12"><div class="hr mt-10"></div></div>
                                    </div>
                                </div>

                                <div class="invoice-center">
                                    <div class="table-responsive">
                                        <table class="table table-striped invoice-table">
                                            <thead class="bg-active">
                                                <tr>
                                                    <th>محصول / خدمات</th>
                                                    <th class="text-center">فروشنده / طراح</th>
                                                    <th class="text-center">قیمت واحد</th>
                                                    <th class="text-center">تعداد</th>
                                                    <th class="text-center">جمع</th>
                                                    <th class="text-center">تخفیف</th>
                                                    <th class="text-center">مالیات</th>
                                                    <th class="text-right">کل</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item,index in props.orders.sub_order" :key="index">
                                                    <td >
                                                        <div class="item-desc-1 d-flex">
                                                            <small  v-for="menu,index in item.orderable.menus" :key="index">
                                                                <span v-if="menu.section == 'products'">{{ menu.name }} </span>
                                                            </small>
                                                            <span v-if="item.orderable_type == 'App\\Models\\Product'"> {{ item.orderable.name}} </span>
                                                            <span v-else-if="item.orderable_type == 'App\\Models\\Tarahi'"> {{ item.orderable.title}} </span>
                                                            <span v-else-if="item.orderable_type == 'App\\Models\\WebDesign'"> {{ item.orderable.name}} </span>
                                                        </div>
                                                    </td>
                                                    <td v-if="item.orderable_type == 'App\\Models\\Product'" class="text-center">{{ item.orderable.user.name_show }}</td>
                                                    <td v-else-if="item.orderable_type == 'App\\Models\\Tarahi'" class="text-center">{{ item.orderable.register_designer.user.name_show }}</td>
                                                    <td v-if="item.orderable_type == 'App\\Models\\WebDesign'" class="text-center">{{ item.orderable.user.name_show }}</td>
                                                    <td class="text-center">{{ (item.price).toLocaleString("fa-IR") }} ریال</td>
                                                    <td class="text-center">{{ (item.count).toLocaleString("fa-IR") }} عدد</td>
                                                    <td class="text-center">{{ (item.total).toLocaleString("fa-IR") }} ریال</td>
                                                    <td class="text-center">{{ (item.discount).toLocaleString("fa-IR") }} ریال</td>
                                                   <td class="text-center"  >
                                                    <!-- <template v-for="role,index in item.orderable.user.roles" :key="index" > -->
                                                        <span v-if=" item.comison == 0">{{ (item.tax).toLocaleString("fa-IR") }} ریال</span>
                                                        <span v-else >{{ (0).toLocaleString("fa-IR") }} ریال</span>
                                                    <!-- </template> -->
                                                    </td>
                                                    <td class="text-right" >
                                                        <!-- <span v-if="item.comison  > 0"> {{ item.comison }}</span> -->
                                                        <span v-if=" item.comison == 0">{{ (item.col).toLocaleString("fa-IR") }} ریال</span>
                                                        <span v-if="item.comison > 0" >{{ (item.col+item.comison+item.tax).toLocaleString("fa-IR") }} ریال</span>
                                                    </td>
                                                    <!-- <td class="text-right" v-eles-if="item.comison > 0" >{{ (item.col+item.comison+item.tax).toLocaleString("fa-IR") }} ریال</td> -->
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-end f-w-600">جمع</td>
                                                    <td class="text-right">{{(props.orders.price).toLocaleString("fa-IR")}} ریال</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-end f-w-600">تخفیف</td>
                                                    <td class="text-right">{{ (props.orders.discount).toLocaleString("fa-IR") }} ریال</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-end f-w-600">بن تخفیف</td>
                                                    <td class="text-right">{{ (props.orders.coupon).toLocaleString("fa-IR") }} ریال</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-end f-w-600">جمع کل</td>
                                                    <td class="text-right f-w-600">{{ (props.orders.col).toLocaleString("fa-IR") }} ریال</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-end f-w-600"> پرداخت شده</td>
                                                    <td class="text-right f-w-600">{{ (props.orders.payment).toLocaleString("fa-IR") }} ریال</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-end f-w-600">مانده</td>
                                                    <td class="text-right f-w-600">{{ (props.orders.col-props.orders.payment).toLocaleString("fa-IR") }} ریال</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="invoice-bottom pb-80">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-15">اطلاعات فاکتور</h6>
                                            <p class="font-sm">
                                                <strong>تاریخ چاپ:</strong> {{ moment(new Date()).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}<br />
                                            </p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <h6 class="mb-15">مبلغ قابل پرداخت</h6>
                                            <h3 class="mt-0 mb-0 text-brand">{{ (props.orders.col-props.orders.payment).toLocaleString("fa-IR") }} ریال</h3>
                                            <p class="mb-0 text-muted">بدون مالیات</p>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-12">
                                            <div class="hr mt-30 mb-30"></div>
                                            <p class="mb-0 text-muted"><strong>توجه:</strong>این فاکتور بصورت اینترنتی صادر شده است.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-btn-section clearfix d-print-none">
                                <a href="javascript:window.print()" class="btn btn-lg btn-custom btn-print hover-up">
                                    <img :src="$page.props.ziggy.url+'/assets/frontend/assets/imgs/theme/icons/icon-print.svg'" alt="" /> چاپ
                                </a>
                                <a id="invoice_download_btn" class="btn btn-lg btn-custom btn-download hover-up">
                                    <img :src="$page.props.ziggy.url+'/assets/frontend/assets/imgs/theme/icons/icon-download.svg'" alt="" /> دانلود
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <component :is="'script'" :src="$page.props.ziggy.url+'/assets/frontend/assets/js/invoice/invoice.js'"></component>
        <component :is="'script'" :src="$page.props.ziggy.url+'/assets/frontend/assets/js/vendor/modernizr-3.6.0.min.js'"></component>
        <component :is="'script'" :src="$page.props.ziggy.url+'/assets/frontend/assets/js/vendor/jquery-3.6.0.min.js'"></component>

        <component :is="'script'" :src="$page.props.ziggy.url+'/assets/frontend/assets/js/invoice/jspdf.min.js'"></component>

</template>
<style>

</style>
