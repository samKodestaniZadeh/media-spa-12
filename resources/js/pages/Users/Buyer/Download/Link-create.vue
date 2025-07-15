<script setup>

import { computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,time:Object,products:Object,flash:Object,link:Object,wallet:Number,
    id:Object,newfilename:Object,filename:Object,orders:Object,users:Object,cartPrice:Object,cartCount:Object,
    cartDiscount:Object,cartCoupon:Object,cartTotal:Object,request:Object,token:String
});

const form =  useForm({id:null,link:null,order:null});

const submit = (id,order_id) =>{
    form.id = id
    form.order = order_id
    form.post(route('link.store'))
};

</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
    <main class="main-wrap rtl">
        <section class="content-main">
                <div class="content-header">
                    <div>
                        <h2 class="content-title card-title">جزئیات سفارش</h2>

                        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد.</p>

                    </div>
                </div>
                <form>
                   <div class="card">
                        <header class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
                                    <br />
                                    <small class="text-muted">شناسه سفارش: {{props.orders[0].order_id}}</small>
                                </div>
                                <div class="col-lg-6 col-md-6 ms-auto text-md-start">
                                    <Link class="btn btn-primary" :href="route('factor.show',[props.orders[0].order_id])">فاکتور</Link>
                                </div>
                            </div>
                        </header>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive ">
                                        <table class="table">
                                            <thead>
                                                <tr >
                                                    <th scope="col"> محصول</th>
                                                    <th scope="col">دامنه</th>
                                                    <th scope="col">عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr v-for="(order,index ) in props.orders" :key="index">
                                                    <td >
                                                        <a class="itemside" href="#">
                                                            <div class="info">{{order.orderable.name}}</div>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <input type="text" v-model.lazy.trim="form.link" style="width: 100%;" placeholder="لطفا آدرس دامنه وب سایت خود را وارد نمایید.مثال : http://media.ir">
                                                    </td>
                                                    <td>
                                                        <Link class="btn btn-primary" @click.prevent="submit(order.orderable.id,order.order_id)">ثبت</Link>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                   </form>
            </section>
    </main>
</template>
