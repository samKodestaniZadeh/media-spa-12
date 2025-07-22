<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm , usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { Inertia } from '@inertiajs/inertia';
import swal from 'sweetalert2';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    newsletters:Object,users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,
    cartTotal:Object,companies:Object,descriptions:Object,alert:Object,wallet:Number,cart:Object
});

const form =  useForm({id:props.newsletters.id,subject:props.newsletters.subject,text:props.newsletters.text,
    status:props.newsletters.status,route:props.newsletters.route,p_route:props.newsletters.p_route
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
        title: [
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.subject ? errors.value.subject +'<br>' :'' ,
                errors.value.route ? errors.value.route +'<br>' :'' ,
                ],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('newsletterAdmin.edit',props.newsletters.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{

    if(form.id == null || form.subject == null || form.text == null || form.status == null || form.route == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('newsletterAdmin.store'),{
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
                <td class="d-flex me-auto">
                    <select v-model.lazy="form.status" class="form-select">
                            <option value="0">ثبت</option>
                            <option value="1">انتظار</option>
                            <option value="2">بررسی</option>
                            <option value="3"> منقضی</option>
                            <option value="4">منتشر</option>
                    </select>
                    <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                        <span v-if="form.processing">پردازش...</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                        <span v-else >ویرایش</span>
                    </button>
                </td>
            </div>
            <div class="col-sm-12">
                <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
            </div>
        </div>
            <div class="row">
                <form class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>اطلاعات پایه</h4>
                            </div>
                            <div class="card-body">
                                    <div class="mt-1">
                                        <label for="product_name" class="form-label">عنوان<span class="text-danger">*</span></label>
                                        <input v-model.lazy="form.subject" type="text" placeholder="اینجا تایپ کنید" class="form-control" id="product_name" />
                                    </div>
                                    <div class="mt-1">
                                        <label for="product_name" class="form-label">نام روت<span class="text-danger">*</span></label>
                                        <input v-model.lazy="form.route" type="text" placeholder="اینجا تایپ کنید" class="form-control" id="product_name" />
                                    </div>
                                    <div class="mt-1">
                                        <label for="product_name" class="form-label">پسوند روت</label>
                                        <input v-model.lazy="form.p_route" type="text" placeholder="اینجا تایپ کنید" class="form-control" id="product_name" />
                                    </div>
                                    <div class="mt-4">
                                        <label class="form-label">توضیحات <span class="text-danger">*</span></label>
                                        <textarea v-model.lazy="form.text" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea>
                                    </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    </section>
    <Footer :companies="props.companies" />
</main>
</template>
