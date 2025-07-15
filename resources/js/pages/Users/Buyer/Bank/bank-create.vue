<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    menus:Object,users:Object,companies:Object,descriptions:Object,path:String,alert:Object,
    wallet:Number,cart:Object
});


const form = useForm({
    bankname:null,
    accountname:null,
    accountnumber:null,
    cartnumber:null,
    shabanumber:null,
    image:null,
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
        title: [errors.value.bankname ? errors.value.bankname +'<br>' :'' ,
                errors.value.accountname ? errors.value.accountname +'<br>' :'' ,
                errors.value.accountnumber ? errors.value.accountnumber +'<br>' :'' ,
                errors.value.cartnumber ? errors.value.cartnumber +'<br>' :'' ,
                errors.value.shabanumber ? errors.value.shabanumber +'<br>':'',
                errors.value.image ? errors.value.image +'<br>' :'',],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('bank.create'),{ only: [errors.value,hasErrors.value,props.alert] })
}
const submit = () => {
    if (form.bankname == null || form.accountname == null || form.accountnumber == null || form.cartnumber == null ||
        form.shabanumber == null || form.image == null)
    {
            let text
            text = 'موارد ستاره دار الزامی است.'
           validate(text)
    }
    else
    {
        form.post(route('bank.store'),{
            onFinish:() => submitTime()
        })
    }
};

const menus = ref([]);

if (props.menus && props.menus.length > 0) {

    props.menus.forEach(element => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section => {
                        if(section.name == 'banks')
                        {
                            element.children.forEach(child => {
                                menus.value.push(child)
                            });
                        }
                    });
                }
            });
        }
    });
}

</script>
<template>

<Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <main class="main-wrap rtl" >
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary">
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
                <div class="card mb-4">
                    <div class="row"  id="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>اطلاعات حساب</h4>
                                </div>
                                <div class="card-body">

                                        <div class=" mb-4">
                                            <form >
                                            <div class="d-flex">
                                                <div class="col-sm-4" >
                                                    <label class="form-label">انتخاب بانک<span class="text-danger">*</span></label>
                                                    <select v-model.lazy.trim="form.bankname"  class="form-select">
                                                        <option v-if="menus.length > 0" :value="menu" v-for="(menu, index) in menus" :key="index">{{ menu.name }}</option>
                                                        <option v-else disabled>گزینه ای یافت نشد.</option>
                                                    </select>

                                                </div>
                                                <div class="col-sm-4 me-1"  >
                                                    <label  class="form-label">نام و نام خانوادگی صاحب حساب <span class="text-danger">*</span></label>
                                                    <input v-model.lazy.trim="form.accountname" type="text" class="form-control" placeholder="اینجا تایپ کنید.">
                                                </div>

                                                <div class="col-sm-4 me-1">
                                                    <label  class="form-label">شماره حساب <span class="text-danger">*</span></label>
                                                    <input v-model.lazy.trim="form.accountnumber" type="text" class="form-control" placeholder="اینجا تایپ کنید.">
                                                </div>

                                            </div>
                                            <div class="d-flex">
                                                <div class="col-sm-4 me-1">
                                                    <label  class="form-label">شماره کارت <span class="text-danger">*</span></label>
                                                    <input v-model.lazy.trim="form.cartnumber" type="text" class="form-control" placeholder="اینجا تایپ کنید.">
                                                </div>

                                                <div class="col-sm-4 me-1">
                                                    <label  class="form-label">شماره شبا (بدون IR ) <span class="text-danger">*</span></label>
                                                    <input v-model.lazy.trim="form.shabanumber" type="text" class="form-control" placeholder="اینجا تایپ کنید.">
                                                </div>
                                                <div class="col-sm-4 me-1">
                                                    <label class="form-label"> آپلود عکس<span class="text-danger">*</span></label>
                                                    <input  class="form-control" type="file" @input="form.image = $event.target.files[0]"  id="image"  accept="image/*"/>
                                                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                        {{ form.progress.percentage }}%
                                                    </progress>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </section>
            <Footer :companies="props.companies"/>
        </main>
</template>
