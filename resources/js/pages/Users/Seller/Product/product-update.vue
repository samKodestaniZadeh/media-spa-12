<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue'
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    product:Object,users:Object,cartPrice:Object,cartCount:Object,children:Object,
    cartDiscount:Object,cartCoupon:Object,cartTotal:Object,alert: Object,companies:Object,menus: Object,
    descriptions:Object,path:String,wallet:Number
});

const form =  useForm({update:props.product.id,text:null,version:null,file:null,price:null});

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

const submitTime = ()=>{
    Inertia.visit(route('product.update',form.update),{ method: 'PUT',only: [errors.value,hasErrors.value,props.alert] })
}
const submit = () => {

    if (form.update == null || form.text == null || form.version == null || form.file == null || form.price == null)
    {
            let text
            text = 'موارد ستاره دار الزامی است.'
           validate(text)
    }
    else
    {
        form.post(route('product.store'),{
            // onFinish:() => submitTime()
        })
    }
};

</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
        <div class="row content-header">
            <div class="d-flex col-sm-12">
                <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                <td class="me-auto">

                    <button  @click.prevent="submit" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing" class="btn btn-md rounded font-sm hover-up me-auto">
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
                    <div class="col-lg-12">
                        <div class="card" >
                            <div class="card-header">
                                <h4>اطلاعات پایه</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="card-body col-sm-6">
                                        <label class="form-label">فایل <span class="text-danger">*</span></label>
                                        <div class="input-upload">
                                        <input class="form-control" type="file" @input="form.file = $event.target.files[0]"
                                                id="file" accept="zip/rar/*" />
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                {{ form.progress.percentage }}%
                                            </progress>
                                        </div>

                                    </div>
                                    <div class="card-body col-sm-6">
                                        <label class="form-label">نسخه محصول <span class="text-danger">*</span></label>
                                        <div class="row gx-2">
                                            <input v-model.lazy="form.version" placeholder="اینجا تایپ کنید" type="text"
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                        <label class="form-label">قیمت پیشنهادی محصول <span class="text-danger">*</span></label>
                                        <input v-model.lazy="form.price" placeholder="اینجا تایپ کنید" type="text"
                                            class="form-control" />
                                    </div>
                                <div class="mt-4">
                                    <label class="form-label">توضیحات تغیرات بروز رسانی محصول <span
                                            class="text-danger">*</span></label>
                                    <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" />
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
