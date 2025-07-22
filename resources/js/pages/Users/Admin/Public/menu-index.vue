<script setup>

import { computed} from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { Head, Link, useForm ,usePage} from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Aside from '@/Components/AsideAdmin.vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users:Object,menus:Object,ids:Object,statuses:Object,subjects:Object,wallet:Number,
    cartNumber:Number,cartPrice:Number,cartCount:Number,cartDiscount:Number,cartCoupon:Number,
    cartTotal:Number,notifications:Object,companies:Object,descriptions:Object,cart:Object
});

const form = useForm({
    subject: null,
    status:null,
    id:null,
});

</script>
<template>
    <Header :cart="props.cart" :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <div class="screen-overlay"></div>
        <main class="main-wrap rtl" >
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <Link :href="route('menu.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4" >
                    <div class="card-body" >
                        <div class="row gx-5">
                        <Aside class="col-lg-3 border-end" />
                            <div class="col-lg-9">
                                <section class="content-body p-xl-4">
                                    <div class="table-responsive" v-if="props.menus.total > 0">
                                        <table v-if="props.menus.total > 0" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">شناسه</th>
                                                    <th scope="col">ایجاد کننده</th>
                                                    <th scope="col">قسمت</th>
                                                    <th scope="col">شناسه والد</th>
                                                    <th scope="col">نام</th>
                                                    <th scope="col">تاریخ</th>
                                                    <th scope="col">وضعیت</th>
                                                    <th scope="col">عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr v-for="menu in props.menus.data" :key="menu.id">
                                                    <td>{{(menu.id).toLocaleString("fa-IR")}}</td>
                                                    <td>{{menu.user.user_name}}</td>
                                                    <td v-if="menu.sections.length > 0" >
                                                        <span v-for="section,index in menu.sections" :key="index"> {{section.name+'-'}}</span>

                                                        </td>
                                                    <td v-else> ندارد</td>
                                                    <td>
                                                        <template v-if="menu.parent_id == null">ندارد</template>
                                                        <template v-else-if="menu.parent_id > 0" >{{(menu.parent_id).toLocaleString("fa-IR")}}</template>
                                                    </td>
                                                    <td>{{menu.name}}</td>
                                                    <td>
                                                        {{ moment(menu.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                    </td>
                                                    <td>
                                                        <span v-if="menu.status == 0"  class="badge badge-pill badge-soft-info"> ثبت</span>
                                                        <span v-if="menu.status == 1" class="badge badge-pill badge-soft-warning">ایجاد</span>
                                                        <span v-if="menu.status == 2"  class="badge badge-pill badge-soft-secondary">  بررسی</span>
                                                        <span v-if="menu.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                                        <span v-if="menu.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                    </td>

                                                    <td class="text-end">
                                                        <div class="dropdown">
                                                            <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                            <div class="dropdown-menu">
                                                                <Link :href="route('menu.show',[menu.id])" class="dropdown-item">نمایش جزئیات</Link>
                                                                <Link class="dropdown-item text-danger" :href="route('menu.destroy',[menu.id])" method="delete" as="button">حذف</Link>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="mt-5" v-if="props.menus.total > 9 ">
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination justify-content-start">
                                                    <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                    v-for="link in props.menus.links" :key="link.id" >
                                                    <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                    v-html="link.label" ></Link>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <p>گزینه ای یافت نشد.</p>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <Footer :companies="props.companies" />
</main>
</template>

