<script setup>

import { computed,ref} from 'vue';

import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import AsideAdmin2 from '@/Components/AsideAdmin2.vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,canResetPassword: Boolean,status: String,users:Object,ostans:Object,wallet:Number,
    shahrs:Object,notifications:Object,companies:Object,descriptions:Object,socials:Object,menus:Object,
    path:String,alert:Object,user:Object,cart:Object
});

var now =  new Date();

const form =  useForm({
    id:props.user.id,
    user_name:props.users.user_name,
    name:props.users.name,
    lasst_name:props.users.lasst_name,
    name_show:props.users.name_show,
    tel:props.users.tel,
    birth:props.users.profile?props.users.profile.birth:null,
    gender:props.users.profile?props.users.profile.gender:null,
    email: props.users.email,
    image:props.users.image? props.users.image.url:null,
    biography:props.users.profile?props.users.profile.biography:null,
    password: null,
    password_confirmation: null,
    social:[],

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
        title: [errors.value.link ? errors.value.link +'<br>' :'' ,],

        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('socialAdmin.show',form.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submitSocial = () =>{
    form.post(route('linkAdmin.store'),{
        onFinish:() => submitTime()
    })
};

const menus = ref([]);

if (props.menus && props.menus.length > 0) {

    props.menus.forEach(element => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section => {
                        if(section.name == 'socials')
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

    menus.value.forEach(element => {

        if(element.children.length > 0) {
            element.children.forEach(child => {
                if(child.routes.length > 0){
                    child.routes.forEach(route => {
                        if (route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section =>
                                {
                                    if(section.name == 'socials')
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


menu.value.forEach(social => {

    props.socials.data.forEach(element => {

        if(social.id == element.title )
        {

            if (element.link) {

                form.social.push({link : element.link.user_id == props.users.id ? element.link.link : null ,
                    id: element.link.linkable_id ? element.link.id :null,
                social_id:element.link.linkable_id ,name:social.name,status:element.link.status})
            }
            else
            {
                form.social.push({link : null ,id:null,social_id:element.id ,name:social.name})
            }

        }
    });

});
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

                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body bg-white">
                        <div class="row gx-5">
                            <AsideAdmin2 :id="form.id" />
                            <div class="col-lg-9">
                                <section class="content-body p-xl-4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row gx-3" >
                                                    <div class="col-12">
                                                        <template v-for="forms in form.social" :key="forms.id" >
                                                            <div class="row">
                                                                <div class="col mb-3" >
                                                                    <label class="form-label">{{forms.name}}<span class="text-danger">*</span></label>
                                                                    <input  v-model.lazy="forms.link" class="form-control" type="text" placeholder="اینجا تایپ کنید" />
                                                                </div>
                                                                <div class="class col mb-3">
                                                                    <label v-if="forms.link" class="form-label">عملیات <span class="text-danger">*</span></label>
                                                                    <p class="d-flex" v-if="forms.link">
                                                                        <select v-model.lazy="forms.status" class="form-select">
                                                                                <option value="0">ثبت</option>
                                                                                <option value="1">انتظار</option>
                                                                                <option value="2">بررسی</option>
                                                                                <option value="3">منقضی</option>
                                                                                <option value="4">منتشر</option>
                                                                        </select>
                                                                        <button @click.prevent="submitSocial" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                                                            <span v-if="form.processing">پردازش...</span>
                                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                                            <span v-else >ارسال</span>
                                                                        </button>
                                                                    </p>
                                                                </div>

                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <br />
                                    </form>
                                    <hr/>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
