<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm ,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,cartTotal:Object,
    notifications:Object,companies:Object,descriptions:Object,alert:Object,wallet:Number,cart:Object
});

const form =  useForm({code:null,price:null,number:null,number_digits:null,min:null,max:null});

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
        title: [errors.value.code ? errors.value.code +'<br>' :'' ,
                errors.value.price ? errors.value.price +'<br>' :'' ,
                errors.value.number ? errors.value.number +'<br>' :'' ,
                errors.value.number_digits ? errors.value.number_digits +'<br>' :'' ,
                errors.value.category ? errors.value.category +'<br>':'',
                errors.value.max ? errors.value.max +'<br>' :'' ,
                errors.value.min ? errors.value.min +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('coupon.create'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{

    if (form.code  && form.price || form.number && form.number_digits  && form.max && form.min )
    {
        form.post(route('coupon.store'),{
            onFinish:() => submitTime()
        })
    }
    else
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
};

</script>
<template>
<Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
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
                        <span v-else >ایجاد</span>
                    </button>
                </td>
            </div>
            <div class="col-sm-12">
                <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
            </div>
        </div>
        <form >
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>بن تخفیف دستی</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کد بن<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.code" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">مبلغ<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy="form.price" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>بن تخفیف رندومی</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label"> تعداد کد بن<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.number" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">تعداد ارقام<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.number_digits" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کمترین رقم<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.min" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">بیشترین رقم<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.max" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
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
