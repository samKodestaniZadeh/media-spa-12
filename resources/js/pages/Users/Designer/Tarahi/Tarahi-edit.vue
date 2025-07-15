<script setup>
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage} from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    orders:Object,users:Object,cartPrice:Number,cartCount:Number,wallet:Number,
    cartDiscount:Object,cartCoupon:Object,cartTotal:Number,notifications:Object,ids:Object,
    statuses:Object,prices:Object,companies:Object,descriptions:Object,dark: String,asidemini:String,
    path:String
});

const form = useForm({
    price: null,
    status:null,
    id:null,
});


const asidemini = ref([props.asidemini]);
const dark = ref([props.dark]);
const offcanvas = ref([]);
const show = ref([]);

const darkmode = () => {
    if( dark.value == '')
    {
        dark.value = 'dark'
    }
    else
    {
        dark.value = ''
    }
}

const minimize = () => {
    if(  asidemini.value == '' && offcanvas.value == 'offcanvas-active')
    {
        offcanvas.value = ''
        show.value = ''
    }
    else if(asidemini.value == '')
    {
        asidemini.value = 'aside-mini'
    }
    else
    {
        asidemini.value = ''

    }

}

const offcanvas_aside = () => {

    if( offcanvas.value == '')
    {
        offcanvas.value = 'offcanvas-active'
        show.value = 'show'
    }
    else
    {
        offcanvas.value = ''
        show.value = ''
    }


}

</script>
<template>
    <Head2 />
    <body >
        <div class="screen-overlay"></div>
        <Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <!-- <Link :href="route('product.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link> -->
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4"  v-if="props.orders.total > 0">
                        <div class="card-body" v-if="props.orders">
                            <table v-if="props.orders.total > 0 " class="table table-responsive">
                                <thead>
                                    <tr class="col">
                                        <th scope="col">شناسه</th>
                                        <th scope="col">قیمت</th>
                                        <th scope="col">تعداد</th>
                                        <th scope="col">تخفیف</th>
                                        <th scope="col">بن تخفیف</th>
                                        <th scope="col">جمع</th>
                                        <th scope="col">کمیسیون</th>
                                        <th scope="col">مالیات</th>
                                        <th scope="col">در آمد</th>
                                        <th scope="col">تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(order,index) in props.orders.data" :key="index">
                                        <td>{{(order.id).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.price).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.count).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.discount).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.coupon).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.price*order.count-(order.discount+order.coupon)).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.comison).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.tax+order.complications).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.total).toLocaleString("fa-IR")}}</td>
                                        <td>
                                            {{ moment(order.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-5" v-if="props.orders.total > 9 ">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-start">
                                        <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                        v-for="link in props.orders.links" :key="link.id" >
                                        <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                        v-html="link.label" ></Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                </div>
                <div v-else>
                        <p>گزینه ای یافت نشد.</p>
                    </div>
            </section >
            <Footer :companies="props.companies" />
        </main>
    </body>
</template>
