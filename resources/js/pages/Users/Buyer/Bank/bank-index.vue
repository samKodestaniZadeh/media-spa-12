<script setup>

import { computed ,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';

const props = defineProps({
    auth:Object,users:Object,banks:Object,notifications:Object,wallet:Number,
    companies:Object,descriptions:Object , dark: String,cart:Object
});

const form = useForm({
    text:props.auth.user.id,
});

const asidemini = ref([]);
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
    <Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
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
                <div class="">
                    <div class="card-body d-flex">
                        <div class="col-sm-4 me-1">
                                <div class="card card-user">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body">
                                        <div class="card-text text-muted">
                                            <Link :href="route('bank.create')" class="btn btn-sm btn-brand rounded font-sm mt-15">کارت جدید</Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 me-1" v-for="(bank,index) in props.banks.data" :key="index">
                                    <div class="card card-user">
                                        <div class="card">
                                            <span style=" transform: rotate(40deg); margin-top: 15px;" v-if="bank.status == 0" class="category position-absolute badge badge-pill bg-primary">ثبت</span>
                                            <span style=" transform: rotate(40deg); margin-top: 25px;" v-if="bank.status == 1" class="category position-absolute badge badge-pill bg-warning"> انتظار</span>
                                            <span style=" transform: rotate(40deg); margin-top: 25px;" v-if="bank.status == 3" class="category position-absolute badge badge-pill bg-danger"> غیرفعال</span>
                                            <span style=" transform: rotate(40deg); margin-top: 25px;" v-if="bank.status == 4" class="category position-absolute badge badge-pill bg-success">فعال</span>
                                            <img class="img-wrap" :src="$page.props.ziggy.url+'/storage/'+bank.image.url" alt="">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">حساب</h5>
                                            <div class="card-text text-muted">
                                                <p class="m-0">{{bank.bank_name}}</p>
                                                <p>شماره حساب:<a href="" class="__cf_email__" data-cfemail="ff929e8d86c6cfbf9a879e928f939ad19c9092">{{bank.account_number}}</a></p>
                                                <p>شماره کارت:<a href="" class="__cf_email__" data-cfemail="ff929e8d86c6cfbf9a879e928f939ad19c9092">{{bank.cart_number}}</a></p>
                                                <p>شماره شبا: IR  <a href="" class="__cf_email__" data-cfemail="ff929e8d86c6cfbf9a879e928f939ad19c9092">{{bank.shaba_number}}</a></p>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                    </div>
                    <div class="mt-5" v-if="props.banks.total > 2">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-start">
                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                v-for="link in banks.links" :key="link.id" >
                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                v-html="link.label" ></Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
