<script setup>

import { computed} from 'vue';
import { Head, Link, useForm ,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    webDesigns:Object,users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,wallet:Number,
    cartTotal:Object,notifications:Object,names:Object,ids:Object,statuses:Object,notifications: Object,companies:Object,
    descriptions:Object,cart:Object
});

const form = useForm({
    name: null,
    status:null,
    id:null,
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
                    <td class="me-auto">
                        <Link :href="route('webdesign.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                    </td>
                </div>
                <div class="col-sm-12">
                    <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                </div>
            </div>
                   <div class="card mb-4"  v-if="props.webDesigns.total > 0">
                    <div class="card-body" v-if="props.webDesigns">
                            <table v-if="props.webDesigns.total > 0 " class="table table-responsive">
                            <thead>
                                <tr class="col">
                                    <th scope="col">شناسه</th>
                                    <th scope="col">نام</th>
                                    <th scope="col">دسته بندی</th>
                                    <th scope="col">تاریخ</th>
                                    <th scope="col">وضعیت</th>
                                    <th scope="col">عملیات</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr v-for="(product,index) in props.webDesigns.data" :key="index">
                                    <td >{{(product.id).toLocaleString("fa-IR")}}</td>
                                    <td >
                                        <div class="left">
                                            <img v-if="product.image" :src="$page.props.ziggy.url +'/storage/' +product.image.url" class="img-sm img-thumbnail" :alt="product.name">
                                        </div>
                                        <div class="info">
                                            <h6 class="mb-0"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{product.name}}</font></font></h6>
                                        </div>
                                    </td>
                                    <td>
                                        {{ product.type.name }}
                                    </td>
                                    <td>
                                        {{ moment(product.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                    </td>
                                    <td>
                                        <span v-if="product.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                        <span v-if="product.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                        <span v-if="product.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                        <span v-if="product.status == 3" class="badge badge-pill badge-soft-danger">منقضی</span>
                                        <span v-if="product.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                        <span v-if="product.status == 5" class="badge badge-pill badge-soft-warning">متوقف</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                            <div class="dropdown-menu">
                                                <Link  class="dropdown-item" :href="route('webdesign.show',[product.id])"> ویرایش</Link>
                                                <Link  class="dropdown-item" :href="route('website_design.index',['q']) +'all'">نمایش</Link>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-5" v-if="props.webDesigns.total > 9">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-start">
                                        <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                        v-for="link in props.webDesigns.links" :key="link.id" >
                                        <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                        v-html="link.label" ></Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                    </div>
                   </div>
                   <p v-else>گزینه ای یافت نشد.</p>
            </section >
        <Footer :companies="props.companies" />
    </main>
</template>
