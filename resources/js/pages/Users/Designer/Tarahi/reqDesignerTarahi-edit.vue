<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia'

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({auth:Object,users:Object,tarahis:Object,notifications:Object,menus:Object,
    companies:Object,descriptions:Object,reqDesigner:Object,alert:Object});

const form = useForm({
    expired: props.reqDesigner.expired,
    price:props.reqDesigner.price,
    id:props.reqDesigner.id,
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

const submitTimer = ()=>{
    Inertia.visit(route('reqDesigner.edit',form.id),{ only: [props.alert,errors.value,hasErrors.value] })
}

const alert = ref(props.alert);

if (alert.value) {

    swal.fire({
        title: props.alert.title ,
        text: props.alert.text,
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
        title:[ errors.value.price ? errors.value.price +'<br>' :'' ,
                errors.value.expired ? errors.value.expired +'<br>' :''],
        icon:'error',
    })

}

const submit = () => {
     if(form.price == null && form.id == null &&  form.expired == null)
     {
         let text = 'لطفا تمامی موارد الزامی را پر نمایید سپس مجدد فرم را ارسال نمایید.'
         validate(text)
    }
    else
    {
        form.post(route('reqDesigner.store'),{
            onFinish:() => submitTimer()
        });
    }
};


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
        <form >
            <div class="row">
                <div class="col-lg-12">
                    <div class="bg-white">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="row gx-2">
                                <div class="col-sm-6 col-6">
                                    <div class="mt-4">
                                        <label class="form-label">تعداد روز<span class="text-danger">*</span></label>
                                        <input v-model.lazy="form.expired" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-sm-6 col-6">
                                    <div class="mt-4">
                                        <label class="form-label">مبلغ<span class="text-danger">*</span></label>
                                        <input v-model.lazy="form.price" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                    </div>
                                    <label class="form-label mt-2" v-if="form.price > 0">ضمانت اجرایی مبلغ پیشنهادی</label>
                                    <p v-if="props.companies && form.price > 0" class="mt-2">
                                        {{ (form.price*props.companies.design_damage).toLocaleString("fa-IR") }}ریال
                                    </p>
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
