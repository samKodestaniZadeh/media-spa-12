<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia'


const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,time:String,products:Object,alert:Object,link:Object,dark: String,wallet:Number,
    id:Object,newfilename:Object,filename:Object,orders:Object,users:Object,cart:Object,request:Object,token:String,notifications: Object,
    companies:Object,descriptions:Object,
});

const form =  useForm({id:null,link:null,order:null});



const validate = (text)=>{
    swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }}).fire({
        title: text,
        icon:'error',
    })
}


if (hasErrors.value == true) {

    swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }}).fire({
        title: errors.value.link,
        icon:'error',
    })

}


</script>
<template>
<Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
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
                <form>
                   <div class="card">
                        <header class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-6 mb-lg-0 mb-15">
                                    <br />
                                    <small class="text-muted">شناسه سفارش: {{props.orders.id}}</small>
                                </div>
                                <div class="col-lg-6 col-md-6 ms-auto text-md-start">
                                    <Link class="btn btn-primary" :href="route('factor.show',[props.orders.id])">فاکتور</Link>
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
                                                    <th scope="col"> خدمات / محصول</th>

                                                    <th scope="col">عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr v-for="(order,index ) in props.orders.sub_order" :key="index" >
                                                    <td>
                                                        <a class="itemside" href="#">
                                                            <div class="info" v-if="order.orderable_type == 'App\\Models\\Product'">{{order.orderable.name}}</div>
                                                            <div class="info" v-else-if="order.orderable_type == 'App\\Models\\Tarahi'" >{{order.orderable.title}}</div>
                                                            <div class="info" v-if="order.orderable_type == 'App\\Models\\WebDesign'">{{order.orderable.name}}</div>
                                                        </a>
                                                    </td>

                                                    <td v-if="order.orderable_type == 'App\\Models\\Product'">

                                                        <form class="col-lg-4 col-md-4 " :action="route('download.store')" method="POST" >
                                                            <input type="hidden" name="_token" :value="token" />
                                                            <input type="hidden" name="id" :value="order.order_id">
                                                            <button type="submit" class="form-control btn btn-sm btn-primary">دانلود</button>
                                                        </form>
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
            <Footer :companies="props.companies" />
    </main>
</template>
