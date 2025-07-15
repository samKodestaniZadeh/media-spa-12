<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import swal from 'sweetalert2';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { Inertia } from '@inertiajs/inertia'

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({products:Object,users:Object,notifications:Object,statuses:Object,alert: Object,
    names:Object,companies:Object,descriptions:Object,userPrice:Number,cart:Object
});

const form = useForm({
    id: null,
    type:null
});

const submitTime = ()=>{

Inertia.visit(route('favorite.index'),{ only: [props.alert,props.products] })
}
const submitRemove = (id,type) => {
    form.id = id
    form.type = type
    form.post(route('favorite.store'), {
        onFinish: () => submitTime(),
    });

};

const alert = ref(props.alert);

if (alert.value)
{
    if (alert.value.title)
    {

        swal.fire(
        props.alert.title,
        props.alert.text,
        props.alert.icon,
        )

    }
    else
    {
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
            title: props.alert.text,
            icon:props.alert.icon,
        })
    }

    alert.value = null
};

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
<Header :cart="props.cart" :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :userPrice="props.userPrice"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
        <section class="content-main" >
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
                <!-- <BreezeValidationErrors class="col-sm-4 fixed-bottom alert alert-warning alert-dismissible fade show mt-3 ms-auto" /> -->
                <div class="card mb-4" v-if="props.products.total > 0">
                    <div class="card-body">
                        <div class="row" >
                            <div class="col-xl-3 col-lg-6 col-md-6" v-for="(product,index) in props.products.data" :key="index">
                                <div class="card card-product-grid">
                                    <Link v-if="product.favoritable_type == 'App\\Models\\Product'" :href="route('website_templates.show',product.favoritable.slug)" class="img-wrap">
                                        <img v-if="product.favoritable.image && product.favoritable.image.status == 4 || product.favoritable.image.status == 5" :src="$page.props.ziggy.url+'/storage/'+product.favoritable.image.url" class="card-img h-100" :alt="product.favoritable.name">
                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="card-img h-100" :alt="product.name">
                                    </Link>
                                    <Link v-else-if="product.favoritable_type == 'App\\Models\\Tarahi'" :href="route('website_design.show',product.favoritable.slug)" class="img-wrap">
                                        <img v-if="product.favoritable.image && product.favoritable.image.status == 4 || product.favoritable.image.status == 5" :src="$page.props.ziggy.url+'/storage/'+product.favoritable.image.url" class="card-img h-100" :alt="product.favoritable.name">
                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="card-img h-100" :alt="product.name">
                                    </Link>
                                    <div class="info-wrap">
                                        <a href="" class="title" v-if="product.favoritable_type == 'App\\Models\\Product'">نام محصول:</a>
                                        <a href="" class="title" v-else-if="product.favoritable_type == 'App\\Models\\Tarahi'">نام پروژه:</a>
                                        <div class="price mt-1 text-start text-nofull" v-if="product.favoritable_type == 'App\\Models\\Product'">{{ product.favoritable.name }}</div>
                                        <div class="price mt-1 text-start text-nofull" v-else-if="product.favoritable_type == 'App\\Models\\Tarahi'">{{ product.favoritable.title }}</div>
                                    </div>
                                    <div class="price mt-1 text-start mb-1 ms-1">
                                        <button href="#" @click.prevent="submitRemove(product.favoritable_id,product.favoritable_type)" class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-delete_forever"></i>
                                            <span v-if="form.processing && form.id == product.favoritable_id && form.type == product.favoritable_type">پردازش...</span>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                            v-if="form.processing && form.id == product.favoritable_id && form.type == product.favoritable_type"></span>
                                            <span v-else>حذف</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5" v-if="props.products.total > 9">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-start">
                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                v-for="link in props.products.links" :key="link.id" >
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

            <Footer :companies="props.companies" />
    </main>
</template>
