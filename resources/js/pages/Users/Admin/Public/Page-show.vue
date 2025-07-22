<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,users:Object,pages:Object,notifications:Object,companies:Object,descriptions:Object,
    alert:Object,wallet:Number,cart:Object
});
const ApiKey = ref('cfw3yx4hh06riwl1qwbq3fwcmjr80c5v0z2ki1fid7agx2ow');
const form = useForm({
    route: props.pages.route,
    title:props.pages.title,
    text:props.pages.data,
    id:props.pages.id,
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
        title: [errors.value.route ? errors.value.route +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('page.show',props.pages.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () => {
     if(form.route == null || form.title == null || form.text == null || form.id == null){
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }else{
        form.put(route('page.update',form.id),{
            onFinish:() => submitTime()
        });
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
                            <div class="mt-4">
                                <label class="form-label">عنوان<span class="text-danger">*</span></label>
                                <div class="row gx-2">
                                    <input v-model.lazy.trim="form.title" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                </div>
                            </div>
                            <div class="mt-4 ">
                                <!-- rows="5" cols="30" -->
                                <label class="form-label">کد<span class="text-danger">*</span></label>
                                <!-- <textarea v-model.lazy="form.text" placeholder="اینجا تایپ کنید" class="form-control" ></textarea> -->
                                <Editor :api-key="ApiKey"  :init="{menubar: false }" v-model.lazy.trim="form.text" />
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
