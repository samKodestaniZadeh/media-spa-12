<script setup>
import { computed} from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { usePage } from '@inertiajs/vue3';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Aside from '@/Components/AsideAdmin.vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({users:Object,banknames:Object,ids:Object,statuses:Object,subjects:Object,
cartNumber:Number,cartPrice:Number,cartCount:Number,cartDiscount:Number,cartCoupon:Number,
cartTotal:Number,notifications:Object,companies:Object,descriptions:Object});

const form = useForm({
    subject: null,
    status:null,
    id:null,
});
const submit = () => {
    form.get(route('support.search'));

};
</script>
<template>
    <Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <div class="screen-overlay"></div>
        <main class="main-wrap rtl" >
            <section class="content-main">
                <div class="content-header">
                    <div>
                        <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                        <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                    </div>
                    <Link :href="route('bankname.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                </div>
                <div class="card mb-4" v-if="props.banknames.total > 0">
                    <div class="card-body" >
                        <div class="row gx-5">
                        <Aside class="col-lg-3 border-end" />
                            <div class="col-lg-9">
                                <section class="content-body p-xl-4">
                                    <div class="table-responsive">
                                        <table v-if="props.banknames.total > 0" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">شناسه</th>
                                                    <th scope="col">ایجاد کننده</th>
                                                    <th scope="col">نام</th>
                                                    <th scope="col">تاریخ</th>
                                                    <th scope="col">وضعیت</th>
                                                    <th scope="col">عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr v-for="bankname in props.banknames.data" :key="bankname.id">
                                                    <td>{{(bankname.id).toLocaleString("fa-IR")}}</td>
                                                    <td>{{bankname.user.user_name}}</td>
                                                    <td>{{bankname.name}}</td>
                                                    <td>
                                                        {{ moment(bankname.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                    </td>
                                                    <td>
                                                        <span v-if="bankname.status == 0"  class="badge badge-pill badge-soft-info"> ثبت</span>
                                                        <span v-if="bankname.status == 1" class="badge badge-pill badge-soft-warning">ایجاد</span>
                                                        <span v-if="bankname.status == 2"  class="badge badge-pill badge-soft-secondary">  بررسی</span>
                                                        <span v-if="bankname.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                                        <span v-if="bankname.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                    </td>

                                                    <td class="text-end">
                                                        <div class="dropdown">
                                                            <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                            <div class="dropdown-menu">
                                                                <Link :href="route('bankname.edit',[bankname.id])" class="dropdown-item">ویرایش</Link>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                    <div class="mt-5" v-if="props.banknames.total > 9 ">
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination justify-content-start">
                                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                            v-for="link in props.banknames.links" :key="link.id" >
                                                            <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                            v-html="link.label" ></Link>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
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

