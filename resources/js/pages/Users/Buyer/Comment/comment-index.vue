<script setup>

import { computed} from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { Head, Link, useForm, usePage} from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    auth:Object,tickets:Object,ids:Object,statuses:Object,dark: String,wallet:Number,
    users:Object, notifications:Object,companies:Object,descriptions:Object,cartTotal: Number,
    cartCount: Number,cart:Object
});

const form = useForm({
    status:null,
    id:null,
});

</script>
<template>
    <Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <div class="screen-overlay"></div>
        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="me-auto">
                                        <!--  -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4" v-if="props.tickets.total > 0">
                    <div class="card-body" >
                        <div class="table-responsive">
                            <table v-if="props.tickets.total > 0" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody v-for="comment in props.tickets.data" :key="comment.id">
                                    <tr >
                                        <td>{{(comment.id).toLocaleString("fa-IR")}}</td>
                                        <td>
                                            {{ moment(comment.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td>
                                            <span v-if="comment.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                            <span v-if="comment.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                            <span v-if="comment.status == 2"  class="badge badge-pill badge-soft-secondary">برسی</span>
                                            <span v-if="comment.status == 3" class="badge badge-pill badge-soft-danger">منقضی</span>
                                            <span v-if="comment.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                        </td>
                                        <td class="text-end">
                                            <Link v-if="comment.status == 4 && comment.commentable_type == 'App\\Models\\Tarahi'" :href="route('website_design.show',[comment.commentable.slug])" class="btn btn-primary btn-sm rounded font-sm">نمایش</Link>
                                            <Link v-if="comment.status == 4 && comment.commentable_type == 'App\\Models\\Product'" :href="route('website_templates.show',[comment.commentable.slug])" class="btn btn-primary btn-sm rounded font-sm">نمایش</Link>
                                            <Link v-if="comment.status == 4 && comment.commentable_type == 'App\\Models\\Blog'" :href="route('blog.show',[comment.commentable.slug])" class="btn btn-primary btn-sm rounded font-sm">نمایش</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-5" v-if="props.tickets.total > 9" >
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-start">
                                        <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                        v-for="link in props.tickets.links" :key="link.id" >
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

