<script setup>

import { computed, ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,
    cartCoupon:Object,cartTotal:Object,notifications:Object,results:Object,companies:Object,
    descriptions:Object,subjects:Object|String,menus:Object,alert:Object});

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
        title: [errors.value.expired ? errors.value.expired +'<br>' :'' ,
                errors.value.percent_min ? errors.value.percent_min +'<br>' :'' ,
                errors.value.subject ? errors.value.subject +'<br>' :'' ,
                errors.value.percent_max ? errors.value.percent_max +'<br>' :'' ,
                errors.value.results ? errors.value.results +'<br>':'',],
        icon:'error',
    })

}

const form =  useForm({expired:null,percent_min:null,
    percent_max:null,results:[],subject:props.subjects?props.subjects:null});

const submitTime = ()=>{
    Inertia.visit(route('discountVisitor.create'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () => {

    if(form.subject == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.get(route('discountVisitor.create'),{
            onFinish:() => submitTime()
        });
    }

};
const submitStore = () => {
    form.post(route('discountVisitor.store'),{
        onFinish:() => submitTime()
    });
}
</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
        <div class="row content-header">
            <div class="d-flex col-sm-12">
                <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                <td class="me-auto">
                    <Link @click="submitStore" class="btn btn-primary btn-sm rounded font-sm">
                        <span v-if="form.processing">پردازش...</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                        <span v-else >ایجاد</span>
                    </Link>
                </td>
            </div>
            <div class="col-sm-12">
                <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
            </div>
        </div>
        <form >
            <div class="row">
                <div class="col-lg-6">
                    <div class="mt-4 bg-white">
                        <div class="card-header d-flex">
                            <h4>تخفیف</h4>
                            <div class="me-auto">
                                <select v-model.lazy="form.subject" @change="submit" class="form-select">
                                    <option value="All"> همه محصولات</option>
                                    <template v-for="menu,index in props.menus" :key="index">
                                        <option v-for="child,index in menu.children" :key="index" :value="child.id">{{ child.name }}</option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                                <div class="table-responsive" v-if="props.results.total > 0">
                                    <article class="itemlist">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="col">
                                                    <th scope="col">
                                                        انتخاب
                                                    </th>
                                                    <th scope="col">نام</th>
                                                    <th scope="col">دسته بندی</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(result,index) in props.results.data" :key="index" >
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" v-model="form.results"  :value="result.id">
                                                        </div>
                                                    </td>
                                                    <td >
                                                        <div class="left">
                                                        <img v-if="result.image" :src="$page.props.ziggy.url +'/storage/' +result.image.url" class="img-sm img-thumbnail" :alt="result.name">
                                                        </div>
                                                        <div class="info">
                                                            <h6 class="mb-0"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{result.name}}</font></font></h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ result.type.name }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </article>
                                </div>
                                <p v-else>محصولی یافت نشد.</p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mt-4 bg-white">
                        <div class="card-header">
                            <h4>تخفیف رندومی</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کمترین درصد<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.percent_min" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">بیشترین درصد<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.percent_max" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">تاریخ انقضا<span class="text-danger">*</span></label>
                                            <date-picker v-model="form.expired"   format="YYYY-MM-DD HH:mm:ss" display-format="dddd jDD jMMMM jYYYY"  color="#1ABC9C"  type="datetime" ></date-picker>
                                        </div>
                                    </div>
                                </div>
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
