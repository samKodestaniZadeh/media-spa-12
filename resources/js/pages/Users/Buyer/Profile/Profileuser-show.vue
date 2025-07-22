<script setup>
import { computed,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';


const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    users:Object,user:Object,companies:Object,orders_count:Number,userPrice:Number,
    user_products:Object,descriptions:Object,user_tarahis:Object,user_designers:Object,
    cart:Object
});

const orders = ref([]);

props.user.orders.forEach(order => {

    orders.value = +orders.value + ((order.price*order.count)-order.discount)
});
const orders_products = ref([]);

if (props.user.orders_product) {
    props.user.orders_product.forEach(element => {
        orders_products.value.push(element.price*element.count-element.comison-
        element.tax - element.complications)
    });
}
const orders_counts = ref([]);
if (props.user.orders_product) {

        orders_counts.value = props.user.orders_product.length

}
</script>
<template>
<Header :cart="props.cart" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
    <main class="main-wrap rtl">
            <section class="content-main">
                <div class="content-header">
                    <div>
                        <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                        <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-brand-2" style="height: 150px"></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl col-lg flex-grow-0" style="flex-basis: 230px">
                                <div class="img-thumbnail shadow w-100 bg-white position-relative text-center" style="height: 190px; width: 200px; margin-top: -120px">
                                    <img v-if="props.user.image && props.user.image.status == 4 " :src="$page.props.ziggy.url+'/storage/'+props.user.image.url" class="center-xy img-fluid" :alt="props.user.user_name" />
                                    <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" class="center-xy img-fluid" :alt="props.user.user_name" />
                                </div>
                            </div>

                            <div class="col-xl col-lg">
                                <h3 v-if="props.user">{{props.user.name_show}}</h3>
                                <p v-if="props.user.profile && props.user.profile.status == 4">{{props.user.profile.biography}}</p>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="row g-4">
                            <div class="col-md-12 col-lg-4 col-xl-2">
                                <article class="box">
                                    <p class="mb-0 text-muted">تعداد فروش:</p>
                                    <h5 class="text-success text-start">{{ (orders_counts).toLocaleString("fa-IR") }}</h5>
                                    <p class="mb-0 text-muted">درآمد خالص:</p>
                                    <h5 class="text-success mb-0 text-start" v-if="orders_products > 0">
                                        {{ (orders_products).toLocaleString("fa-IR") }}
                                    </h5>
                                    <h5 class="text-success mb-0 text-start" v-else >
                                        {{(0).toLocaleString("fa-IR")}}
                                    </h5>
                                </article>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card mb-4" v-if="props.user_products.total > 0" >
                    <div class="card-body">
                        <h3 class="card-title">محصولات</h3>
                        <div class="row" >
                            <div class="col-xl-2 col-lg-3 col-md-6" v-for="(product,index) in props.user_products.data" :key="index">
                                <div class="card card-product-grid">
                                    <Link :href="route('website_templates.show',product.slug)" class="img-wrap">
                                        <img v-if="product.image && product.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+product.image.url" class="card-img h-100" :alt="product.name">
                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="card-img h-100" :alt="product.name">
                                    </Link>
                                    <div class="info-wrap">
                                        <a href="" class="title">نام محصول:</a>
                                        <div class="price mt-1 text-start">{{ product.name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5" v-if="props.user_products.total > 9">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-start">
                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                v-for="link in props.user_products.links" :key="link.id" >
                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                v-html="link.label" ></Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="card mb-4" v-if="props.user_tarahis.total > 0" >
                    <div class="card-body">
                        <h3 class="card-title">پروژه ها</h3>
                        <div class="row" >
                            <div class="col-xl-2 col-lg-3 col-md-6" v-for="(tarahi,index) in props.user_tarahis.data" :key="index">
                                <div class="card card-product-grid">
                                    <Link :href="route('website_design.show',tarahi.slug)" class="img-wrap">
                                        <img v-if="tarahi.image && tarahi.image.status == 4" :src="'/./storage/'+tarahi.image.url" class="card-img h-100" :alt="tarahi.name">
                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="card-img h-100" :alt="tarahi.name">
                                    </Link>
                                    <div class="info-wrap">
                                        <a href="" class="title">نام محصول:</a>
                                        <div class="price mt-1 text-start">{{ tarahi.title }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5" v-if="props.user_tarahis.total > 9">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-start">
                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                v-for="link in props.user_tarahis.links" :key="link.id" >
                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                v-html="link.label" ></Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="card mb-4" v-if="props.user_designers.total > 0 && props.user_designers.data[0].tarahi_register" >
                    <div class="card-body">
                        <h3 class="card-title">خدمات طراحی وب سایت</h3>
                        <div class="row" >
                            <div class="col-xl-2 col-lg-3 col-md-6" v-for="(designer,index) in props.user_designers.data" :key="index">
                                <div class="card card-product-grid">
                                    <Link v-if="designer.tarahi_register" :href="route('website_design.show',designer.tarahi_register.slug)" class="img-wrap">
                                        <img v-if="designer.tarahi_register.image && designer.tarahi_register.image.status == 4" :src="'/./storage/'+designer.tarahi_register.image.url" class="card-img h-100" :alt="designer.tarahi_register.name">
                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="card-img h-100" :alt="designer.tarahi_register.name">
                                    </Link>
                                    <div class="info-wrap" v-if="designer.tarahi_register">
                                        <a href="" class="title">نام پروژه:</a>
                                        <div class="price mt-1 text-start">{{ designer.tarahi_register.title }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5" v-if="props.user_designers.total > 9">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-start">
                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                v-for="link in props.user_designers.links" :key="link.id" >
                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                v-html="link.label" ></Link>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </section>
            <Footer :companies="props.companies"/>
    </main>
</template>
