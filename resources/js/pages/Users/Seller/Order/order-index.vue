<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Bar from '@/Components/BarChart.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({orders:Object,users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,
cartTotal:Object,notifications:Object,ids:Object,statuses:Object,prices:Object,companies:Object,descriptions:Object,farvardin:Object,
ordibehesht:Object,khordad:Object,tir:Object,mordad:Object,shahriver:Object,mehr:Object,aban:Object,azar:Object,dey:Object,bahman:Object,
esfand:Object,times:Object|String,subjects:Object|String});


const order_farvardin = ref([]);
var a_farvardin = [];
var b_farvardin = [];
props.farvardin.data.forEach(element => {
    order_farvardin.value = element + +order_farvardin.value
    a_farvardin.push(element.orderable_id)
    b_farvardin.push(element.order.id)
    // console.log(element.user.id)
});


const products_farvardin = a_farvardin.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_farvardin = b_farvardin.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_ordibehesht = ref([]);
var a_ordibehesht = [];
var b_ordibehesht = [];
props.ordibehesht.data.forEach(element => {
    order_ordibehesht.value = element + +order_ordibehesht.value
    a_ordibehesht.push(element.orderable_id)
    b_ordibehesht.push(element.order.id)
    // console.log(element.order.id)
});


const products_ordibehesht = a_ordibehesht.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_ordibehesht = b_ordibehesht.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});


const order_khordad = ref([]);
var a_khordad = [];
var b_khordad = [];
props.khordad.data.forEach(element => {
    order_khordad.value = element + +order_khordad.value
    a_khordad.push(element.orderable_id)
    b_khordad.push(element.order.id)
    // console.log(element.user.id)
});


const products_khordad = a_khordad.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_khordad = b_khordad.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_tir = ref([]);
var a_tir = [];
var b_tir = [];
props.tir.data.forEach(element => {
    order_tir.value = element + +order_tir.value
    a_tir.push(element.orderable_id)
    b_tir.push(element.order.id)
    // console.log(element.user.id)
});


const products_tir = a_tir.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_tir = b_tir.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_mordad = ref([]);
var a_mordad = [];
var b_mordad = [];
props.mordad.data.forEach(element => {
    order_mordad.value = element + +order_mordad.value
    a_mordad.push(element.orderable_id)
    b_mordad.push(element.order.id)
    // console.log(element.user.id)
});


const products_mordad = a_mordad.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_mordad = b_mordad.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_shahrivar = ref([]);
var a_shahrivar = [];
var b_shahrivar = [];
props.shahriver.data.forEach(element => {
    order_shahrivar.value = element + +order_shahrivar.value
    a_shahrivar.push(element.orderable_id)
    b_shahrivar.push(element.order.id)
    // console.log(element.user.id)
});


const products_shahrivar = a_shahrivar.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_shahrivar = b_shahrivar.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_mehr = ref([]);
var a_mehr = [];
var b_mehr = [];
props.mehr.data.forEach(element => {
    order_mehr.value = element + +order_mehr.value
    a_mehr.push(element.orderable_id)
    b_mehr.push(element.order.id)
    // console.log(element.user.id)
});


const products_mehr = a_mehr.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_mehr = b_mehr.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_aban = ref([]);
var a_aban = [];
var b_aban = [];
props.aban.data.forEach(element => {
    order_aban.value = element + +order_aban.value
    a_aban.push(element.orderable_id)
    b_aban.push(element.order.id)
    // console.log(element.user.id)
});


const products_aban = a_aban.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_aban = b_aban.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_azar = ref([]);
var a_azar = [];
var b_azar = [];
props.azar.data.forEach(element => {
    order_azar.value = element + +order_azar.value
    a_azar.push(element.orderable_id)
    b_azar.push(element.order.id)
    // console.log(element.user.id)
});


const products_azar = a_azar.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_azar = b_azar.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_dey = ref([]);
var a_dey = [];
var b_dey = [];
props.dey.data.forEach(element => {
    order_dey.value = element + +order_dey.value
    a_dey.push(element.orderable_id)
    b_dey.push(element.order.id)
    // console.log(element.user.id)
});


const products_dey = a_dey.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_dey = b_dey.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});


const order_bahman = ref([]);
var a_bahman = [];
var b_bahman = [];
props.bahman.data.forEach(element => {
    order_bahman.value = element + +order_bahman.value
    a_bahman.push(element.orderable_id)
    b_bahman.push(element.order.id)
    // console.log(element.user.id)
});


const products_bahman = a_bahman.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_bahman = b_bahman.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const order_esfand = ref([]);
var a_esfand = [];
var b_esfand = [];
props.esfand.data.forEach(element => {
    order_esfand.value = element + +order_esfand.value
    a_esfand.push(element.orderable_id)
    b_esfand.push(element.order.id)
    // console.log(element.user.id)
});


const products_esfand = a_esfand.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});
const factors_esfand = b_esfand.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

const BarChart = ({
    data: {
    labels: [
        "فروردین",
        "اردیبهشت",
        "خرداد",
        "تیر",
        "مرداد",
        "شهریور",
        "مهر",
        "آبان",
        "آذر",
        "دی",
        "بهمن",
        "اسفند",
    ],
    datasets: [
        {
            label: "فروش",
            tension: 0.3,
            fill: true,
            backgroundColor: "rgba(44, 120, 220, 0.2)",
            borderColor: "rgba(44, 120, 220)",
            data:[props.farvardin.data.length,props.ordibehesht.data.length,props.khordad.data.length,
            props.tir.data.length,props.mordad.data.length,props.shahriver.data.length,props.mehr.data.length,
            props.aban.data.length,props.azar.data.length,props.dey.data.length,props.bahman.data.length,
            props.esfand.data.length],
        },
        {
            label: "فاکتور",
            tension: 0.3,
            fill: true,
            backgroundColor: "rgba(4, 209, 130, 0.2)",
            borderColor: "rgb(4, 209, 130)",
            data: [factors_farvardin.length,factors_ordibehesht.length,factors_khordad.length,factors_tir.length,
            factors_mordad.length,factors_shahrivar.length,factors_mehr.length,factors_aban.length,factors_azar.length,
            factors_dey.length,factors_bahman.length,factors_esfand.length],
        },
        {
            label: "محصول",
            tension: 0.3,
            fill: true,
            backgroundColor: "rgba(380, 200, 230, 0.2)",
            borderColor: "rgb(380, 200, 230)",
            data: [products_farvardin.length,products_ordibehesht.length,products_khordad.length,
            products_tir.length,products_mordad.length,products_shahrivar.length,products_mehr.length,
            products_aban.length,products_azar.length,products_dey.length,products_bahman.length,
            products_esfand.length],
        },
    ],
},
options: {
    plugins: {
        legend: {
            labels: {
                usePointStyle: true,
            },
        },
    },
},


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
        title: [errors.value.menu ? errors.value.menu +'<br>' :'' ,
                errors.value.recepiant ? errors.value.recepiant +'<br>' :'' ,
                errors.value.subject ? errors.value.subject +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.email ? errors.value.email +'<br>':'',
                errors.value.name ? errors.value.name +'<br>' :'' ,
                errors.value.lasst_name ? errors.value.lasst_name +'<br>':'',],
        icon:'error',
    })

}

const form = useForm({
    date:null,
    date2:null,
    subject:props.subjects?props.subjects:null,
    time:props.times !== 'All'? props.times:null,
});

const submitTime = ()=>{
    Inertia.visit(route('orderSeller.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () => {
    if(form.subject == null  && form.time == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.get(route('orderSeller.index'),{
            onFinish:() => submitTime()
        });
    }
}

const submitAmar = () => {
    if( form.date == null && form.date2 == null){
         let text
        text = 'تاریخ را انتخاب نمایید.'
        validate(text)
    }
    else
    {
        form.get(route('orderSeller.index'),{
            onFinish:() => submitTime()
        });
    }
}

var order_count = [];

props.orders.data.forEach(element => {
    if(element.orderable_type == 'App\\Models\\Product')
    {
        order_count =  +order_count + element.total
    }
});

var order_comison_tax = [];
props.orders.data.forEach(element => {
    if(element.orderable_type == 'App\\Models\\Product')
    {
        order_comison_tax =  +order_comison_tax + element.comison
        order_comison_tax =  +order_comison_tax + element.tax
        order_comison_tax =  +order_comison_tax + element.complications
    }
});

var product_count = [];
props.orders.data.forEach(element => {
    product_count =  +product_count + element.orderable_id
});


var id_product = [];

props.orders.data.forEach(element => {
    if(element.orderable_type == 'App\\Models\\Product')
    {
        id_product.push(element.orderable_id)
    }
});

var product_count = id_product.filter ((value, index, array) => {
    return array.indexOf (value) == index;
});

</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
        <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <h2 class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></h2>
                        <td class="me-auto">
                            <!-- <Link :href="route('discountVisitor.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link> -->
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <p v-if="props.descriptions" v-html="props.descriptions.text"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card card-body mb-4">
                            <article class="icontext">
                                <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-monetization_on"></i></span>
                                <div class="text">
                                    <h6 class="mb-1 card-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">درآمد کل</font></font></h6>
                                    <span v-if="order_count > 0"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (order_count).toLocaleString("fa-IR") }}</font></font></span>
                                    <span v-else ><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (0).toLocaleString("fa-IR") }}</font></font></span>
                                    <span class="text-sm"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ریال</font></font></span>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-body mb-4">
                            <article class="icontext">
                                <span class="icon icon-sm rounded-circle bg-success-light"><i class="text-success material-icons md-local_shipping"></i></span>
                                <div class="text">
                                    <h6 class="mb-1 card-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">کارمزد و مالیات</font></font></h6>
                                    <span v-if="order_comison_tax > 0"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (order_comison_tax).toLocaleString("fa-IR") }}</font></font></span>
                                    <span v-else><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (0).toLocaleString("fa-IR") }}</font></font></span>
                                    <span class="text-sm"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ریال</font></font></span>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-body mb-4">
                            <article class="icontext">
                                <span class="icon icon-sm rounded-circle bg-info-light"><i class="text-info material-icons md-shopping_basket"></i></span>
                                <div class="text">
                                    <h6 class="mb-1 card-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">درآمد خالص</font></font></h6>
                                    <span v-if="order_count"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (order_count-order_comison_tax).toLocaleString("fa-IR") }}</font></font></span>
                                    <span v-else><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (0).toLocaleString("fa-IR") }}</font></font></span>
                                    <span class="text-sm"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ریال</font></font></span>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card card-body mb-4">
                            <article class="icontext">
                                <span class="icon icon-sm rounded-circle bg-warning-light"><i class="text-warning material-icons md-qr_code"></i></span>
                                <div class="text">
                                    <h6 class="mb-1 card-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">محصولات</font></font></h6>
                                    <span v-if="product_count"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (product_count.length).toLocaleString("fa-IR") }}</font></font></span>
                                    <span v-else><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{ (0).toLocaleString("fa-IR") }}</font></font></span>
                                    <span class="text-sm"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">عدد</font></font></span>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class=" mb-4">
                            <article class="bg-white">
                                <div class="d-flex">
                                    <h5 class="card-title mt-2 me-2"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">آمار فروش</font></font></h5>
                                    <div class="me-auto mt-3 ms-2">
                                        <div class="d-flex">
                                            <p class="ms-2">از :</p>
                                            <div>
                                            <date-picker v-model="form.date"  type="year" format="YYYY" display-format="jYYYY"  color="#1ABC9C" ></date-picker>
                                            </div>
                                            <p class="me-2">تا :</p>
                                            <div class="me-1">
                                                <date-picker @change="submitAmar" v-model="form.date2"  type="year" format="YYYY" display-format="jYYYY"  color="#1ABC9C" ></date-picker>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                    <Bar :data="BarChart.data" class="mt-2"  :options="BarChart.options" id="myChart" height="331" width="827" style="display: block; box-sizing: border-box; height: 331px; width: 827.8px;" />
                            </article>
                        </div>
                    </div>
                </div>
                   <div class="bg-white mb-4"  v-if="props.orders.total > 0">
                        <header class="card-header">
                            <div class="row align-items-center">
                                <div class="col col-check flex-grow-0">
                                    <div class="form-check ms-2">
                                        <input class="form-check-input" type="checkbox" value="">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12 ms-auto mb-md-0 mb-3">

                                    <select class="form-select" v-model.lazy="form.subject" @change="submit">
                                        <option value="All"> کل فروش</option>
                                        <option value="0">فروش بدون تخفیف</option>
                                        <option value="1">فروش تخفیف دار</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-6">
                                    <!-- <input type="date" v-model.lazy="form.time" class="form-control" @change="submit"> -->
                                    <date-picker v-model="form.time" format="YYYY-MM-DD" display-format="dddd jDD jMMMM jYYYY"  color="#1ABC9C" type="date" @change="submit"></date-picker>
                                </div>

                                <div class="col-md-2 col-6">

                                </div>
                            </div>
                        </header>

                        <div class="card-body" v-if="props.orders">
                            <table v-if="props.orders.total > 0 " class="table table-responsive">
                                <thead>
                                    <tr class="col">
                                        <th scope="col">شناسه فروش</th>
                                        <th scope="col">قیمت</th>
                                        <th scope="col">تعداد</th>
                                        <th scope="col">تخفیف</th>
                                        <th scope="col">بن تخفیف</th>
                                        <th scope="col">مالیات و کارمزد</th>
                                        <th scope="col">درآمد</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(order,index) in props.orders.data" :key="index">
                                        <td>{{(order.order.id).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.price).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.count).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.discount).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.coupon).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.comison+order.tax+order.complications).toLocaleString("fa-IR")}}</td>
                                        <td>{{(order.total-order.comison-order.tax-order.complications).toLocaleString("fa-IR")}}</td>
                                        <td>
                                            <!-- {{(order.total).toLocaleString("fa-IR")}} -->
                                            {{ moment(order.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td v-if="order.t_id > 0">{{order.t_id}}</td>
                                        <td v-else>پرداخت از کیف پول </td>
                                    </tr>
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
                                </tbody>
                            </table>
                        </div>
                   </div>
                   <div v-else>
                        <p>گزینه ای یافت نشد.</p>
                    </div>
            </section >

            <Footer :companies="props.companies" />
    </main>
</template>

