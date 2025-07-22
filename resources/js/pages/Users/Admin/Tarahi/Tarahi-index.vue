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
    tarahis:Object,users:Object,cart:Object,wallet:Number,notifications:Object,names:Object,ids:Object,statuses:Object,companies:Object,
    descriptions:Object,cart:Object
});

const form = useForm({
    name: null,
    status:null,
    id:null,
});

</script>
<template>
<Header :cart="props.cart" :wallet="props.wallet" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
        <section class="content-main">
            <div class="row content-header">
                <div class="d-flex col-sm-12">
                    <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                    <td class="me-auto">
                        <!-- <Link :href="route('tarahiAdmin.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link> -->
                    </td>
                </div>
                <div class="col-sm-12">
                    <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                </div>
            </div>
                   <div class="card mb-4"  v-if="props.tarahis.total > 0">
                    <div class="card-body" v-if="props.tarahis">
                            <table v-if="props.tarahis.total > 0 " class="table table-responsive">
                            <thead>
                                <tr class="col">
                                    <th scope="col">شناسه سفارش</th>
                                    <th scope="col">مجری</th>
                                    <th scope="col">عنوان</th>
                                    <th scope="col">کارفرما</th>
                                    <th scope="col">تاریخ</th>
                                    <th scope="col">بروزرسانی</th>
                                    <th scope="col">وضعیت</th>
                                    <th scope="col">عملیات</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr v-for="(tarahi,index) in props.tarahis.data" :key="index">
                                    <td >{{(tarahi.id).toLocaleString("fa-IR")}}</td>
                                    <td v-if="tarahi.company">{{props.companies.name_show}}</td>
                                    <td v-else>فریلنسر</td>
                                    <td >{{tarahi.title}}</td>
                                    <td v-if="tarahi.user">
                                        <Link  :href="route('profile.show',[tarahi.user.user_name])"> {{tarahi.user.user_name}}</Link>
                                    </td>
                                    <td >
                                        {{ moment(tarahi.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                    </td>
                                    <td >
                                        {{ moment(tarahi.updated_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                    </td>
                                    <td>
                                        <span v-if="tarahi.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                        <span v-if="tarahi.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                        <span v-if="tarahi.status == 2"  class="badge badge-pill badge-soft-secondary">در حال واگذاری</span>
                                        <span v-if="tarahi.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                        <span v-if="tarahi.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                        <span v-if="tarahi.status == 5" class="badge badge-pill badge-soft-pink">واگذار شده</span>
                                        <span v-if="tarahi.status == 6" class="badge badge-pill badge-soft-dark">تمام شده</span>
                                        <span v-if="tarahi.status == 7" class="badge badge-pill badge-soft-info">ثبت نظر</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown" v-if="tarahi.status !== 3">
                                            <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                            <div class="dropdown-menu">
                                                <Link class="dropdown-item" :href="route('tarahiAdmin.show',[tarahi.id])">نمایش</Link>
                                                <Link class="dropdown-item" :href="route('website_design.show',[tarahi.slug])">نمایش جزئیات</Link>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-5" v-if="props.tarahis.total > 9">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-start">
                                        <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                        v-for="link in props.tarahis.links" :key="link.id" >
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
