<script setup>

import { computed,ref} from 'vue';

import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import Aside from '@/Components/AsideAdmin.vue';

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
    form.post(route('social.store'),{
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
<Header :cart="props.cart"  :roles="props.roles" :alert="props.alert" :users="props.users" :wallet="props.wallet"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <Link :href="route('socialAdmin.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="">
                    <div class="card-body bg-white">
                        <div class="row gx-5">
                            <Aside class="col-lg-3 border-end" />
                            <div class="col-lg-9">
                                    <section class="content-body p-xl-4">
                                            <div class="table-responsive" v-if="props.socials.total > 0">
                                                <table v-if="props.socials.total > 0" class="table table-hover">
                                                    <thead>
                                                        <tr class="col">
                                                            <th scope="col"> شناسه</th>
                                                            <th scope="col">نام</th>
                                                            <th scope="col">تاریخ</th>
                                                            <th scope="col">بروزرسانی</th>
                                                            <th scope="col">وضعیت</th>
                                                            <th scope="col">عملیات</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        <tr v-for="(social,index) in props.socials.data" :key="index">
                                                            <td >{{(social.id).toLocaleString("fa-IR")}}</td>
                                                            <td >{{social.menu.name}}</td>
                                                            <td>
                                                                {{ moment(social.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                            </td>
                                                            <td>
                                                                {{ moment(social.updated_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                            </td>
                                                            <td>
                                                                <span v-if="social.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                                                <span v-if="social.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                                                <span v-if="social.status == 2"  class="badge badge-pill badge-soft-secondary"> بررسی</span>
                                                                <span v-if="social.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                                                <span v-if="social.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                            </td>
                                                            <td class="text-end">
                                                                <div class="dropdown">
                                                                    <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                                    <div class="dropdown-menu">
                                                                        <Link class="dropdown-item" :href="route('socialAdmin.edit',[social.id])">ویرایش اطلاعات</Link>
                                                                        <Link class="dropdown-item text-danger" :href="route('socialAdmin.destroy',[social.id])" method="delete" as="button" :on-finish="submitTime">حذف</Link>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="mt-5" v-if="props.socials.total > 9">
                                                    <nav aria-label="Page navigation example">
                                                        <ul class="pagination justify-content-start">
                                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                            v-for="link in props.socials.links" :key="link.id" >
                                                            <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                            v-html="link.label" ></Link>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                            <p v-else>گزینه ای یافت نشد.</p>
                                    </section>
                                </div>
                        </div>
                    </div>
                </div>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
