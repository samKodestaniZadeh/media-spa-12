<script setup>

import { computed, ref} from 'vue';
import { Head, Link, useForm ,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    orders:Object,users:Object,cartPrice:Number||String,cartCount:Number||String,cartDiscount:Number||String,cartCoupon:Number||String,
    cartTotal:Number||String,user:Object,roles:Object,alert:String,notifications:Object,companies:Object,descriptions:Object,wallet:Number,
    cart:Object
});
const roles = ref(props.user.roles);
const rol = ref([]);
roles.value.forEach(element => {
    rol.value = element
});

const form =  useForm({user:props.user.id,status:props.user.status,role:rol.value.id});
const form2 =  useForm({user:props.user.id,role_id:null});

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
    Inertia.visit(route('userModir.show',props.user.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{
    form.post(route('userModir.store'),{
            onFinish:() => submitTime()
        })
};


const submitDel = (id) => {
    form2.role_id = id,
    form2.post(route('userModir.store'),{
            onFinish:() => submitTime()
        })
};

</script>
<template>
<Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main ">
            <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <!-- <a href="#" class="btn btn-primary"><i class="material-icons md-plus"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ایجاد جدید</font></font></a> -->
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
        <div class="card mx-auto card-login">
            <div class="card-body">
                <form >
                <h4 class="card-title mb-4">ویرایش اطلاعات کاربری</h4>
                <p>نام کاربر:{{props.user.user_name}}</p>
                    <div class="row mb-3">
                        <template v-for="(role,index) in props.users.roles" :key="index">
                                <div v-if="role.id == 4" class="col-lg-6 mb-3">
                                    <label class="form-label">وضعیت حساب <span class="text-danger">*</span></label>
                                    <select v-model.lazy="form.status" class="form-select">
                                        <option value="0">ثبت</option>
                                        <option value="1">مسدود</option>
                                        <option value="2">اخیرا</option>
                                        <option value="3">غیرفعال</option>
                                        <option value="4">آنلاین</option>
                                    </select>
                                </div>
                        </template>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">نوع نقش<span class="text-danger">*</span></label>
                            <select v-model.lazy="form.role" class="form-select">
                                <option v-for="(role,index) in props.roles" :key="index" :value="role.id">{{role.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3 ms-1">
                    <div class="card-body" v-if="props.user.roles">
                            <table class="table table-responsive">
                                <thead>
                                    <tr class="col">
                                        <th scope="col">شناسه</th>
                                        <th scope="col">نام</th>
                                        <th scope="col">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(role,index) in props.user.roles" :key="index">
                                        <td >{{(role.id).toLocaleString("fa-IR")}}</td>
                                        <td >{{role.name}}</td>
                                        <button @click.prevent="submitDel(role.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-sm btn-primary mt-1 mb-1">
                                            <span v-if="form.processing">پردازش...</span>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                            <span v-else >حذف</span>
                                        </button>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
                <div class="col-lg-3 mb-4">
                    <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary w-100">
                        <span v-if="form.processing">پردازش...</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                        <span v-else >ثبت</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <Footer :companies="props.companies" />
</main>
</template>
