<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';
import swal from 'sweetalert2';
const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    orders:Object,users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,
    cartTotal:Object,companies:Object,descriptions:Object,alert:Object,menus:Object,wallet:Number,cart:Object
});

const form =  useForm({title:null,tag:null});

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
                ],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('socialAdmin.create'),{ only: [errors.value,hasErrors.value,props.alert] })
}
const submit = () =>{

    if(form.title == null || form.tag == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('socialAdmin.store'),{
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
                                        <select class="form-select" v-model.lazy="form.title">
                                            <option v-if="props.menus && props.menus.length > 0 && props.menus[0].children" v-for="menu,index in props.menus[0].children" :key="index" :value="menu.id">
                                                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{menu.name}}</font></font>
                                            </option>
                                            <option v-else disabled>گزینه ای یافت نشد.</option>
                                        </select>
                                    </div>
                                    <div class="mt-4">
                                        <label class="form-label">تگ<span class="text-danger">*</span></label>
                                        <textarea v-model.lazy="form.tag" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea>
                                    </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    </section>
    <Footer :companies="props.companies"  />
</main>
</template>
