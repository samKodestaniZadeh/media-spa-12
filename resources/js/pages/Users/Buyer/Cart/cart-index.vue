<script setup>
import {computed, ref} from 'vue';
import { Inertia } from '@inertiajs/inertia'
import {useForm,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';



const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    auth: Object,products: Object,tarahis: Object,cartNumber: Number,wallet:Number,
    cartPrice: Number,cartCount: Number,cartDiscount: Number,cartCoupon: Number,cartTotal: Number,
    codes: Object,alert: Object,flash: String,users: Object,notifications: Object,cart: Object,
    random_coupon: Object,cartTax:Number,cartComplications:Number,companies:Object,cartComison:Number,
    descriptions:Object,dark: String,token:String,path:String,
});

const form = useForm({
    id: null, count: null, coupon: null, cartPrice: null, cartCount: null, cartDiscount: null, cartTotal: null,
    cartWallet: props.auth.user.wallet, dargah: null, text: null, date: null, type: null,model:null,
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
        title: props.alert.title + props.alert.text,
        icon:props.alert.icon,
    })

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
        title: [errors.value.coupon ? errors.value.coupon +'<br>' :'' ,
                errors.value.count ? errors.value.count +'<br>' :'' ,
                errors.value.cartPrice ? errors.value.cartPrice +'<br>' :'' ,
                errors.value.cartCount ? errors.value.cartCount +'<br>' :'' ,
                errors.value.cartDiscount ? errors.value.cartDiscount +'<br>' :'' ,
                errors.value.cartTotal ? errors.value.cartTotal +'<br>' :'' ,
                errors.value.cartWallet ? errors.value.cartWallet +'<br>' :'' ,
                errors.value.dargah ? errors.value.dargah +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.date ? errors.value.date +'<br>' :'' ,
                errors.value.type ? errors.value.type +'<br>' :'' ,
            ],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('cart.index')|| route('coupon.store'),{ only: [props.alert,props.cartCount,props.codes,errors.value,hasErrors.value] })
}

const submitRemove = (id,model) => {
    form.id = id,
    form.model = model,
    form.type = 'del',
    form.post(route('cart.store'),{
        onFinish: () => cartTime()
    })
};


const submitRemoveAll = (id) => {
    form.id = id
    form.delete(route('cart.destroy','all'),{
        onFinish: () => submitTime()
    })
};

const submitCoupon = () => {
    if (form.coupon !== null) {
        props.codes.forEach(code => {
            form.post(route('coupon.store'),{
                onFinish: ()=> submitTime()
            })
        });
    }
    else
    {
        let text = 'کد تخفیف را وارد نمایید.'
        validate(text)
    }
};

const products = ref([props.products]);


const submit = () => {
    form.cartPrice = props.cartPrice,
    form.cartCount = props.cartCount,
    form.cartDiscount = props.cartDiscount,
    form.cartTotal = props.cartTotal

    if (form.dargah == 'wallet')
    {
        form.post(route('order.store'),{
                    onFinish: ()=> submitTime()
                })
        // if (props.users.profile)
        // {
        //     if (props.cartTotal <= props.users.profile.wallet) {
        //         form.post(route('order.store'),{
        //             onFinish: ()=> submitTime()
        //         })
        //     } else {
        //         let text = 'موجودی کیف پول کافی نمی باشد. '
        //         validate(text)
        // }
        // } else {
        // let text = 'موجودی کیف پول کافی نمی باشد. '
        //     validate(text)
        // }
    }
    else if (form.dargah == 'sepehr' || form.dargah == 'behpardakht')
    {
        form.post(route('order.store'))
    }
    else
    {
        let text = 'لطفا یکی از گزینه های پرداخت را انتخاب نمایید. '
        validate(text)
    }

};

</script>
<template>
    <Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies"
            :path="props.path" />
    <body :class="{dark}">
    <div class="screen-overlay"></div>
    <main class="main-wrap rtl">

        <section class="content-main">
            <div class="row content-header">
                <div class="d-flex col-sm-12">
                    <h2 class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></h2>
                    <td class="me-auto">
                        <!-- <button v-if="props.cartCount > 0" @click="submitRemoveAll" href="#" method="delete"
                                as="button" class="btn btn-sm btn-primary">حذف سبد
                        </button> -->
                    </td>
                </div>
                <div class="col-sm-12">
                    <p v-if="props.descriptions" v-html="props.descriptions.text"></p>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <form>
                            <div class="card">
                                <header class="card-header" >
                                    <form>
                                        <div class="row gx-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </symbol>
                                                <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                                </symbol>
                                                <symbol id="exclamation-triangle-fill" fill="currentColor"
                                                        viewBox="0 0 16 16">
                                                    <path
                                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                </symbol>
                                            </svg>
                                            <div class="alert alert-info d-flex align-items-center" role="alert" v-if="props.random_coupon">
                                                <svg class="bi flex-shrink-0 ms-2" width="24" height="24" role="img"
                                                     aria-label="Success:">
                                                    <use xlink:href="#check-circle-fill"/>
                                                </svg>
                                                    بن تخفیف شما:<p>{{ props.random_coupon.code }}</p>
                                            </div>

                                        </div>
                                    </form>
                                </header>
                                <div class="card-body">
                                    <article class="itemlist" v-if="props.cartCount > 0">
                                        <div class="row align-items-center" v-for="(product,index ) in props.products" :key="index">
                                            <div class="col-lg-4 col-sm-4 col-8 flex-grow-1 col-name">
                                                <a class="itemside" href="#">
                                                    <div class="left">
                                                        <img v-if="product['product'].image" :src="$page.props.ziggy.url+'/storage/'+product['product'].image.url" class="img-sm img-thumbnail" alt="مورد">
                                                    </div>
                                                    <div class="info">
                                                        <h6 class="mb-0">{{ product['product'].name }}</h6>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-4 col-price"><span>{{ (product['product'].price).toLocaleString("fa-IR") }} ریال</span></div>
                                            <div class="col-lg-2 col-sm-2 col-4 col-action text-end">
                                                <a href="#" @click.prevent="submitRemove(product.product.id,product.model)" class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-delete_forever"></i>حذف</a>
                                            </div>
                                        </div>
                                    </article>
                                    <article v-else>سبد خرید شما خالی است.</article>
                                </div>

                                <div class="row d-flex">
                                    <div class="col-sm-6 me-1" style="width: 18rem;">
                                        <div class="card">
                                            <ul class="list-group list-group-flush m-2">
                                                <li class="list-group-item">بن تخفیف</li>
                                                <p class="mt-4"> چنانچه بن تخفیفی دارید پس از وارد نمودن کد دکمه ثبت را
                                                    فشار دهید.</p>
                                                <input name="coupon" v-model="form.coupon" class="form-control"
                                                       placeholder="بن تخفیف را اینجا وارد نمایید">
                                            </ul>
                                            <div class="card-footer mt-4">
                                                <button @click.prevent="submitCoupon"
                                                        class="form-control btn btn-sm btn-primary col-sm-4">ثبت
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ms-1 me-auto" style="width: 18rem;">
                                        <div class="card">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">سفارش شما</li>
                                                <div class="m-2">
                                                    <div class="d-flex">تعداد:<h5 class="me-auto">
                                                        {{ (cartCount).toLocaleString("fa-IR") }}</h5></div>
                                                    <div class="d-flex">جمع:<h5 class="me-auto">
                                                        {{ (cartPrice).toLocaleString("fa-IR") }}</h5></div>
                                                    <div class="d-flex">تخفیف:<h5 class="me-auto">
                                                        {{ (cartDiscount).toLocaleString("fa-IR") }}</h5></div>
                                                    <div class="d-flex" v-if="cartCoupon">بن تخفیف:<h5 class="me-auto">
                                                        {{ (cartCoupon).toLocaleString("fa-IR") }}</h5></div>
                                                        <div class="d-flex" v-else>بن تخفیف:<h5 class="me-auto">
                                                        {{ (0).toLocaleString("fa-IR") }}</h5></div>
                                                    <div class="d-flex">قابل پرداخت:<h5 class="me-auto"
                                                                                        style="font-size: 1.3rem ">
                                                        {{ (cartTotal).toLocaleString("fa-IR") }}</h5></div>
                                                    <div class="d-flex">
                                                        <label class="form-check-label" for="flexRadioDefault1"> کیف پول</label>
                                                        <input class="form-check-input me-auto" v-model="form.dargah" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="wallet"></div>
                                                    <div class="d-flex">
                                                        <label class="form-check-label" for="flexRadioDefault1">درگاه ملت</label>
                                                        <input class="form-check-input me-auto" v-model="form.dargah" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="behpardakht"></div>
                                                    <div class="d-flex">
                                                        <label class="form-check-label" for="flexRadioDefault1">درگاه صادرات</label>
                                                        <input class="form-check-input me-auto" v-model="form.dargah" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="sepehr">
                                                    </div>
                                                </div>
                                            </ul>
                                            <div class="card-footer">
                                                <button @click.prevent="submit" v-if="form.dargah == 'wallet' || form.dargah == null"
                                                        class="form-control btn btn-sm btn-primary col-sm-4">
                                                        <span v-if="form.processing">پردازش...</span>
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                        <span v-else >پرداخت</span>
                                                </button>
                                                <form v-else-if="form.dargah == 'behpardakht'" :action="route('order.store')" method="POST" >
                                                    <input type="hidden" name="_token" :value="token" />
                                                    <input type="hidden" name="wallet" value="behpardakht" />
                                                    <input type="hidden" name="cartCount" :value="props.cartCount" />
                                                    <input type="hidden" name="cartPrice" :value="props.cartPrice" />
                                                    <input type="hidden" name="cartTotal" :value="props.cartTotal" />
                                                    <button type="submit" class="form-control btn btn-sm btn-primary col-sm-4">پرداخت</button>
                                                </form>
                                                <form v-else-if="form.dargah == 'sepehr'" :action="route('order.store')" method="POST" >
                                                    <input type="hidden" name="_token" :value="token" />
                                                    <input type="hidden" name="wallet" value="sepehr" />
                                                    <input type="hidden" name="cartCount" :value="props.cartCount" />
                                                    <input type="hidden" name="cartPrice" :value="props.cartPrice" />
                                                    <input type="hidden" name="cartTotal" :value="props.cartTotal" />
                                                    <button type="submit" class="form-control btn btn-sm btn-primary col-sm-4">پرداخت</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <Footer :companies="props.companies" />
    </main>
    </body>
</template>
<style>
.position {
    background-position: left 0.75rem center !important
}

</style>
