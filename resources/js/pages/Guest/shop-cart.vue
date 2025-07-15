<script setup>

import Header from '@/Pages/Guest/Header2.vue';
import Footer from '@/Pages/Guest/Footer2.vue';
import {computed,ref,onMounted } from 'vue';
import {Link, useForm,usePage} from '@inertiajs/vue3';
import swal from 'sweetalert2';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    auth: Object, products: Object, tarahis: Object, cartPrice: Number,
    cartCount: Number,cartDiscount: Number, cartCoupon: Number, cartTotal: Number, alert: Object,
    flash: String, random_coupon: Object,cartTax:Number,cartComplications:Number,companies:Object,
    cartComison:Number,path:String,cartCol:Number,cartPayment:Number,cartBalance:Number,cart:Object,
    menus: Object
});

const form = useForm({
    id: null, count: null, coupon: null, cartPrice: props.cartPrice, cartCount: props.cartCount,
    cartDiscount: props.cartDiscount, cartTotal: props.cartTotal, edit: null, type: null,model:null,
});

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
const alert = ref(props.alert);

if (alert.value) {

swal.fire(
    alert.value.title,
    alert.value.text,
    alert.value.icon,
)

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
        title: [errors.value.name ? errors.value.name +'<br>' :'' ,
                errors.value.name_en ? errors.value.name_en +'<br>' :'' ,
                errors.value.group ? errors.value.group +'<br>' :'' ,
                errors.value.type ? errors.value.type +'<br>' :'' ,
                errors.value.category ? errors.value.category +'<br>':'',
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.demo_link ? errors.value.demo_link +'<br>' :'' ,
                errors.value.version ? errors.value.version +'<br>' :'' ,
                errors.value.file ? errors.value.file +'<br>' :'' ,
                errors.value.image ? errors.value.image +'<br>' :'' ,],
        icon:'error',
    })

}



const submitRemove = (id,model) => {
    form.id = id,
    form.model = model,
    form.type = 'del',
    form.post(route('cart.store'),{
        onFinish: () => cartTime()
    })
};

const cartTime = () => {
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
        title:'!محصول از سبد خرید حذف شد.',
        icon:'success'
    })
};

const submitRemoveAll = (id) => {
    form.id = id
    form.delete(route('cart.destroy','all'),{
        onFinish: () => cartTimeAll()
    })

};

const cartTimeAll = () => {
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
        title:'!سبد خرید پاک شد.',
        icon:'success'
    })
};

const submitCoupon = () => {
    if (form.coupon !== null) {
        form.post(route('coupon.store'))
    }
    else
    {
        let errors = 'کد تخفیف را وارد نمایید.'
        validate(errors)
    }
}

const submitOrder = () => {
    if (form.cartCount > 0)
    {
        form.post(route('order.store'))
    }
}

// console.log(props.cart);
// onMounted(() => {

//     function  addJs  (address) {
//         let externalScript = document.createElement('script');
//         externalScript.src = address;
//         document.body.appendChild(externalScript);
//         }

//     let items = [
//       "assets/frontend/assets/js/invoice/invoice.js",
//       "assets/frontend/assets/js/vendor/modernizr-3.6.0.min.js",
//       "assets/frontend/assets/js/vendor/jquery-3.6.0.min.js",
//       "assets/frontend/assets/js/vendor/bootstrap.bundle.min.js",
//       "assets/frontend/assets/js/plugins/slick.js",
//       "assets/frontend/assets/js/plugins/jquery.syotimer.min.js",
//       "assets/frontend/assets/js/plugins/wow.js",
//       "assets/frontend/assets/js/plugins/jquery-ui.js",
//       "assets/frontend/assets/js/plugins/perfect-scrollbar.js",
//       "assets/frontend/assets/js/plugins/magnific-popup.js",
//       "assets/frontend/assets/js/plugins/select2.min.js",
//       "assets/frontend/assets/js/plugins/waypoints.js",
//       "assets/frontend/assets/js/plugins/counterup.js",
//       "assets/frontend/assets/js/plugins/jquery.countdown.min.js",
//       "assets/frontend/assets/js/plugins/images-loaded.js",
//       "assets/frontend/assets/js/plugins/isotope.js",
//       "assets/frontend/assets/js/plugins/scrollup.js",
//       "assets/frontend/assets/js/plugins/jquery.vticker-min.js",
//       "assets/frontend/assets/js/plugins/jquery.theia.sticky.js",
//       "assets/frontend/assets/js/plugins/jquery.elevatezoom.js",
//       "assets/frontend/assets/js/main.js",
//       "assets/frontend/assets/js/shop.js",
//       "assets/frontend/assets/js/invoice/jspdf.min.js",
//     ];
//     for (const i of items) {

//       if (document.getElementById(i) != null)
//       {
//         document.getElementById(i).remove();
//       }
//       addJs(i);
//     }
// })
</script>
<template>

<body >

    <Header :companies="props.companies" :menus="props.menus"/>
    <main class="main">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href='/' rel='nofollow'>صفحه اصلی<i class="fi-rs-home mr-5"></i></a>
                    <span></span> خرید
                    <span></span> سبد خرید
                </div>
            </div>
        </div>
        <div class="container mb-80 mt-50">
            <div class="row">
                <div class="col-lg-8 mb-40">
                    <h1 class="heading-2 mb-10">سبد خرید شما</h1>
                    <div class="d-flex justify-content-between" v-if="props.cartCount > 0">
                        <h6 class="text-body">تعداد<span class="text-brand">{{props.cartCount}}</span> محصول در سبد خرید شما وجود دارد</h6>
                        <h6 class="text-body" ><a href="#" class="text-muted" @click="submitRemoveAll"><i class="fi-rs-trash mr-5"></i>حذف سبد</a></h6>
                    </div>
                    <div class="d-flex justify-content-between" v-else>
                        <h6 class="text-body">محصولی<span class="text-brand"></span>  در سبد خرید شما وجود ندارد</h6>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8" >
                    <div class="table-responsive shopping-summery" v-if="props.cartCount > 0">
                        <table class="table table-wishlist">
                            <thead>
                                <tr class="main-heading">
                                    <th class="custome-checkbox start pl-30">
                                        <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox11" value="">
                                        <label class="form-check-label" for="exampleCheckbox11"></label>
                                    </th>
                                    <th scope="col" colspan="2">محصول</th>
                                    <th scope="col">قیمت واحد</th>
                                    <th scope="col">تعداد</th>
                                    <th scope="col">تخفیف</th>
                                    <th scope="col">مالیات</th>
                                    <th scope="col">پرداختی</th>
                                    <th scope="col" class="end">حذف</th>
                                </tr>
                            </thead>
                            <tbody v-if="props.cartCount > 0">
                                <tr class="pt-30" v-for="(product,index ) in props.products" :key="index">
                                    <td class="custome-checkbox pl-30">
                                        <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox1" value="">
                                        <label class="form-check-label" for="exampleCheckbox1"></label>
                                    </td>
                                    <td class="image product-thumbnail pt-40">
                                        <img v-if="product['product'].image" :src="$page.props.ziggy.url+'/storage/'+product['product'].image.url" alt="#">
                                        <img v-else :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" alt="#">
                                    </td>
                                    <td class="product-des product-name">
                                        <h6 class="mb-5">
                                            <Link class='product-name mb-10 text-heading' v-if="product['model'] == 'App\\Models\\Product'" :href="route('website_templates.show',product['product'].slug)">{{ product['product'].name }}</Link>
                                            <Link class='product-name mb-10 text-heading' v-if="product['model'] == 'App\\Models\\WebDesign'" :href="route('website_design.index','q')+'all'">{{ product['product'].name }}</Link>
                                            <Link class='product-name mb-10 text-heading' v-if="product['model'] == 'App\\Models\\Tarahi'" :href="route('website_design.show',product['product'].slug)">{{ product['product'].title }}</Link>
                                            <Link class='product-name mb-10 text-heading' v-if="product['model'] == 'App\\Models\\ReqDesigner'" :href="route('website_design.show',product['product'].tarahi_register.slug)">{{  'ضمانت پروژه' + product['product'].tarahi_register.title }}</Link>
                                        </h6>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width:0%">
                                                </div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (0)</span>
                                        </div>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h5 class="text-body" v-if="product['product'].price">{{ (product['product'].price).toLocaleString("fa-IR") }}  </h5>
                                        <h5 class="text-body" v-else>{{ (product['total']/product['count']).toLocaleString("fa-IR") }}  </h5>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h5 class="text-body">{{ (product['count']).toLocaleString("fa-IR") }}  </h5>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h5 class="text-body">{{ (product['discount']).toLocaleString("fa-IR") }}   </h5>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h5 class="text-body">{{ (product['tax']).toLocaleString("fa-IR") }}   </h5>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h5 class="text-brand">{{ (product['col']).toLocaleString("fa-IR") }}   </h5>
                                    </td>
                                    <td class="action text-center" data-title="Remove"><a href="#" class="text-body" @click.prevent="submitRemove(index,product.model)"><i class="fi-rs-trash"></i></a></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="divider-2 mb-30"></div>
                </div>
                <div class="col-lg-4" v-if="props.cartCount > 0">
                    <div class="border p-md-4 cart-totals ml-30">
                        <div class="table-responsive">
                            <table class="table no-border">
                                <tbody>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">تعداد</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">{{ (props.cartCount).toLocaleString("fa-IR") }}  </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">جمع</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">{{ (props.cartTotal).toLocaleString("fa-IR") }} ریال </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">تخفیف</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">{{ (props.cartDiscount).toLocaleString("fa-IR") }} ریال </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="col" colspan="2">
                                            <div class="divider-2 mt-10 mb-10"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">مالیات</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">{{ (props.cartTax).toLocaleString("fa-IR") }} ریال  </h5></td> </tr>
                                        <tr>

                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">جمع کل</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">{{ (props.cartCol).toLocaleString("fa-IR") }} ریال </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td scope="col" colspan="2">
                                            <div class="divider-2 mt-10 mb-10"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">قابل پرداخت</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-brand text-end">{{ (props.cartPayment).toLocaleString("fa-IR") }} ریال  </h5></td> </tr>
                                        <tr>

                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">
                                            <h6 class="text-muted">مانده</h6>
                                        </td>
                                        <td class="cart_total_amount">
                                            <h5 class="text-heading text-end">{{ (props.cartBalance).toLocaleString("fa-IR") }} ریال  </h5></td> </tr>
                                        <tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Link :href="route('shop-checkout.index')" class="btn mb-20 w-100">پرداخت<i class="fi-rs-sign-out ml-15"></i></Link>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <Footer  :companies="props.companies" />
</body>
</template>
