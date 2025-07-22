<script setup>

import { computed,ref} from 'vue';

import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import Aside from '@/Components/Aside.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import AsideAdmin2 from '@/Components/AsideAdmin2.vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    auth:Object,canResetPassword: Boolean,status: String,users:Object,ostans:Object,wallet:Number,
    shahrs:Object,notifications:Object,companies:Object,descriptions:Object,socials:Object,menus:Object,
    path:String,alert:Object,cart:Object
});

var now =  new Date();

const form =  useForm({
    id:props.users.id,
    user_name:props.users.user_name,
    name:props.users.name,
    lasst_name:props.users.lasst_name,
    name_show:props.users.name_show,
    tel:props.users.tel,
    // shahr:props.users.profile?props.users.profile.shahr:null,
    // address:props.users.profile?props.users.profile.address:null,
    birth:props.users.profile?props.users.profile.birth:null,
    gender:props.users.profile?props.users.profile.gender:null,
    email: props.users.email,
    image:props.users.image? props.users.image.url:null,
    biography:props.users.profile?props.users.profile.biography:null,
    password: null,
    password_confirmation: null,
    socials:[],

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
    Inertia.visit(route('social.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submitSocial = () =>{

    form.socials.forEach(element => {
        // console.log(element.link);
        if(element.link == null)
        {
            let text
            text = 'موارد ستاره دار الزامی است.'
            validate(text)
        }
        else
        {
            form.post(route('social.store'),{
                onFinish:() => submitTime()
            })
        }
    });


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

                form.socials.push({link : element.link.user_id == props.users.id ? element.link.link : null ,
                    id: element.link.linkable_id ? element.link.id :null,
                social_id:element.link.linkable_id ,name:social.name,status:element.link.status})
            }
            else
            {
                form.socials.push({link : null ,id:null,social_id:element.id ,name:social.name})
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
                        <td class="me-auto" v-if="form.socials.length > 0">

                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body bg-white">
                        <div class="row gx-5">
                            <Aside />
                            <div class="col-lg-9">
                                <section class="content-body p-xl-4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row gx-3" >
                                                    <template v-for="socia,index in form.socials" :key="index">

                                                        <div class="class row">
                                                            <div class="class col mb-3" v-if="socia.id" >
                                                                <label class="form-label" >{{socia.name}}</label>
                                                                <input  v-model="socia.link" class="form-control"  type="text" placeholder="لینک آدرس را وارد نمایید" />
                                                            </div>
                                                            <div class="class col-4 mb-3" v-else >
                                                                <label class="form-label" >{{socia.name}}</label>
                                                                <input  v-model="socia.link" class="form-control"  type="text" placeholder="لینک آدرس را وارد نمایید" />
                                                            </div>
                                                            <div class="class col mb-3" v-if="socia.id && socia.link">
                                                                <label class="form-label">عملیات <span class="text-danger">*</span></label>
                                                                <p>
                                                                    <Link class="btn btn-md rounded font-sm hover-up" :href="route('link.destroy',[socia.id])" method="delete"
                                                                    as="button" @finish="submitTime()" >حذف</Link>
                                                                </p>
                                                            </div>
                                                            <div class="class col mb-3" v-else>
                                                                <label class="form-label">عملیات <span class="text-danger">*</span></label>
                                                                <p>
                                                                    <button @click.prevent="submitSocial" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                                                        <span v-if="form.processing">پردازش...</span>
                                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                                        <span v-else >ارسال</span>
                                                                    </button>
                                                                </p>
                                                            </div>
                                                            <div class="class col mb-3" v-if="socia.id && socia.link">
                                                                <h6 class="text-danger">وضعیت</h6>
                                                                <p v-if="socia.status == 0">ثبت شده</p>
                                                                <p v-else-if="socia.status == 1"> درانتظار</p>
                                                                <p v-else-if="socia.status == 2">مسدود شده</p>
                                                                <p v-else-if="socia.status == 3">رد شده</p>
                                                                <p v-else-if="socia.status == 4">تایید شده</p>
                                                                <p v-else >نامشخص</p>
                                                            </div>
                                                            <div class="class col mb-3" v-else>

                                                            </div>
                                                        </div>
                                                    </template>
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
