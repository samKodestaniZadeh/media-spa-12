<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import Editor from '@tinymce/tinymce-vue';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    users:Object,cartPrice:Number,cartCount:Number,cartDiscount:Number,cartCoupon:Number,cartTotal:Number,
    notifications:Object,companies:Object,descriptions:Object,path:String,alert:Object, menus: Object,
    wallet:Number,cart:Object
});

const form =  useForm({name:null,name_en:null,group:null,type:null,category:null,
    price:null,text:null,image:null,
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
        title: [errors.value.name ? errors.value.name +'<br>' :'' ,
        errors.value.name_en ? errors.value.name_en +'<br>' :'' ,
        errors.value.group ? errors.value.group +'<br>' :'' ,
        errors.value.type ? errors.value.type +'<br>' :'' ,
        errors.value.category ? errors.value.category +'<br>' :'' ,
        errors.value.price ? errors.value.price +'<br>' :'' ,
        errors.value.text ? errors.value.text +'<br>' :'' ,
        errors.value.image ? errors.value.image +'<br>' :'' ,],
        icon:'error',
    })
}

const submitTime = ()=>{
    Inertia.visit(route('webdesign.create'),{ only: [errors.value,hasErrors.value,props.alert] })
}
const submit = () =>{

    if(form.name == null && form.name_en == null && form.group == null && form.type == null && form.category == null && form.price == null &&
    form.text == null && form.image == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('webdesign.store'),{
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
                        if(section.name == 'web_designs')
                        {
                            menus.value.push(element)
                        }
                    });
                }
            });
        }
    });
}

const menu = ref([]);
const sections = ref([]);
const categores = ref([]);

const group = () => {
    if (menu.value.length > 0) {
        menu.value.splice(0)
    }
    // form.type = null,
    menus.value.forEach(element => {
        if (form.group == element && element.children.length > 0 ) {

            element.children.forEach(child => {
                if(child.routes.length > 0){
                    child.routes.forEach(route => {
                        if (route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section =>
                                {
                                    if(section.name == 'web_designs')
                                    {
                                        menu.value.push(child)
                                    }
                                });
                            }
                        }
                    });
                }
            });
        }
    });
};

const type = () => {
    if (sections.value.length > 0) {
        sections.value.splice(0)
    }
    // form.category = null,
        menu.value.forEach(element => {
            if (form.type ==  element && element.children.length > 0) {
                element.children.forEach(child => {
                    if(child.routes.length > 0)
                    {
                        child.routes.forEach( route => {
                            if (route.name == props.path)
                            {
                                if (child.sections.length > 0)
                                {
                                    child.sections.forEach(section => {
                                        if(section.name == 'web_designs')
                                        {
                                            sections.value.push(child)
                                        }

                                    });
                                }
                            }
                        });
                    }
                });

            }

        });

};

const category = () => {
    sections.value.forEach(element => {
        if (form.category == element && element.children !== null) {
            categores.value = element.children
        }
        else
        {
            categores.value = null
        }
    });

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
                        <table>
                            <thead>
                            <td class="me-auto">
                                <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                    <span v-if="form.processing">پردازش...</span>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                    <span v-else >ارسال</span>
                                </button>
                            </td>
                            </thead>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
        <form >
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-3">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy.trim="form.name" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام انگلیسی<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy.trim="form.name_en" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label"> گروه <span class="text-danger">*</span></label>
                                            <select v-model.lazy="form.group" class="form-select" @change="group">
                                                <option v-if="menus.length > 0" :value="menu" v-for="(menu, index) in menus" :key="index">{{ menu.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نوع  <span class="text-danger">*</span></label>
                                            <select v-model.lazy="form.type" @change="type" class="form-select">
                                                    <option v-if="menu.length > 0 && form.group" v-for="(type, index) in menu" :key="index" :value="type">{{ type.name }}</option>
                                                    <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label"> دسته بندی  <span
                                                class="text-danger">*</span></label>
                                            <select v-model.lazy="form.category" @change="category" class="form-select">
                                                <option v-if="sections.length > 0 && form.type" v-for="(category, index) in sections" :key="index" :value="category">{{ category.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">قیمت <span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.price" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">تصویر کاور <span class="text-danger">*</span></label>
                                            <div class="input-upload">

                                                <input class="form-control" type="file" @input="form.image = $event.target.files[0]"
                                                    id="image" accept="image/*" />
                                                <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                    {{ form.progress.percentage }}%
                                                </progress>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mt-4">
                                            <label class="form-label">توضیحات <span class="text-danger">*</span></label>
                                        <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" />
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
