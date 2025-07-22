<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,cartTotal:Object,
    notifications:Object,menus:Object,companies:Object,descriptions:Object,alert:Object,wallet:Number,
    menu:Object,cart:Object
});

const form =  useForm({
    id:props.menu.id,
    parent_id:props.menu.parent_id,
    name:props.menu.name,
    status:props.menu.status
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
        title: [errors.value.name ? errors.value.name +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('menu.show',form.id),{ only: [errors.value,hasErrors.value,props.alert] })
}
const submit = () =>{
    if( form.name == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('menu.store'),{
            onFinish:() => submitTime()
        })
    }
};

</script>
<template>
<Header :cart="props.cart" :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
        <div class="row content-header">
            <div class="d-flex col-sm-12">
                <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                <div class="d-flex me-auto">
                    <select v-model.lazy="form.status" class="form-select">
                            <option value="0">ثبت</option>
                            <option value="1">انتظار</option>
                            <option value="2">بررسی</option>
                            <option value="3">منقضی</option>
                            <option value="4">منتشر</option>
                    </select>

                    <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                        <span v-if="form.processing">پردازش...</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                        <span v-else >ارسال</span>
                    </button>
                </div>
            </div>
            <div class="col-sm-12">
                <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
            </div>
        </div>

        <form action="">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy="form.name" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">شناسه والد</label>
                                            {{ form.parent_id }}
                                            <select class="form-select" v-model.lazy="form.parent_id">
                                                <option v-if="props.menus && props.menus.length > 0" v-for="menu,index in props.menus" :key="index" :value="menu.id">
                                                    <font style="vertical-align: inherit;">{{menu.name}}(<font style="vertical-align: inherit;" v-if="menu.sections" v-for="section,index in menu.sections" :key="index"> قسمت:{{ section.name }}</font>)</font>
                                                </option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
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
