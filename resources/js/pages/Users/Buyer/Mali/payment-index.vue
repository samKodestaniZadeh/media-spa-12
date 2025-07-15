<script setup>
import { computed } from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { usePage } from '@inertiajs/vue3';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DatePicker from 'vue3-persian-datetime-picker';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users:Object,transactions:Object,ids:Object,statuses:Object,transaction:Object,
    prices:Object,notifications:Object,companies:Object,descriptions:Object,wallet:Number,
    cart:Object
});

const form = useForm({
    transaction: null,
    status:null,
    id:null,
    price:null,
});
const submitFilter = () => {
     if(form.transaction == null && form.status == null && form.id == null && form.price == null){
         errors.value.errors = 'لطفا یکی از موارد را جهت فیلتر انتخاب نمایید.'
    }else{
        form.get(route('payment.search'));
    }


};
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
                        <td class="me-auto">
                            <!-- <button v-if="form.transaction == 'برداشت' " class="btn btn-primary" @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">ثبت</button> -->
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4" v-if="props.transactions.total > 0">
                    <div class="card-body" >
                        <div class="table-responsive">
                            <table v-if="props.transactions.total > 0" class="table table-hover">
                                <thead >
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">نوع تراکنش</th>
                                        <th scope="col">مبلغ</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col" class="text-center">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(transaction,index) in props.transactions.data" :key="index">
                                        <td>{{(transaction.id).toLocaleString("fa-IR")}}</td>
                                        <td>{{transaction.transaction}}</td>
                                        <td>{{(transaction.price).toLocaleString("fa-IR")}}</td>
                                        <td>
                                            <!-- <date-picker v-model="transaction.created_at" :disabled="true" format="YYYY-MM-DD HH:mm:ss" display-format="dddd jDD jMMMM jYYYY"  color="#1ABC9C"  type="datetime" ></date-picker> -->
                                            {{ moment(transaction.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td>
                                            <span v-if="transaction.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                            <span v-if="transaction.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                            <span v-if="transaction.status == 2"  class="badge badge-pill badge-soft-secondary">برسی</span>
                                            <span v-if="transaction.status == 3" class="badge badge-pill badge-soft-danger">منقضی</span>
                                            <span v-if="transaction.status == 4" class="badge badge-pill badge-soft-success">انجام</span>
                                        </td>
                                        <td class="text-end" v-if="transaction.status == 0">
                                            <div class="dropdown">
                                                <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                <div class="dropdown-menu">
                                                    <Link class="dropdown-item" :href="route('payment.edit',[transaction.id])">ویرایش</Link>
                                                    <Link type="button" class="dropdown-item text-danger" :href="route('payment.destroy',[transaction.id])" method="delete" as="delete">لغو</Link>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-5" v-if="props.transactions.total > 9">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-start">
                                        <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                        v-for="link in transactions.links" :key="link.id" >
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

