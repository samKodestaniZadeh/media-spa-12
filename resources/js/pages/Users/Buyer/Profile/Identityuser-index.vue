<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm , usePage} from '@inertiajs/vue3';
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

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,canResetPassword: Boolean,status: String,users:Object,ostans:Object,shahrs:Object,
    notifications:Object,companies:Object,descriptions:Object,alert:Object,now:String,wallet:Number,
    cart:Object
});

var now =  props.now;

const form =  useForm({
    id:props.users?props.users.id:null,
    national_code:props.users.identity?props.users.identity.national_code:null,
    name:props.users?props.users.name:null,
    lasst_name:props.users?props.users.lasst_name:null,
    birth:props.users.profile?props.users.profile.birth:null,
    gender:props.users.profile?props.users.profile.gender:null,
    file:props.users.identity && props.users.identity.file? props.users.identity.file.url:null,
    identity:props.users.identity?props.users.identity.id:null,
    national_id:props.users.identity?props.users.identity.national_id:null,
    economical_number:props.users.identity?props.users.identity.economical_number:null,

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
        title: [errors.value.national_code ? errors.value.national_code +'<br>' :'' ,
                errors.value.name ? errors.value.name +'<br>' :'' ,
                errors.value.lasst_name ? errors.value.lasst_name +'<br>' :'' ,
                errors.value.name_show ? errors.value.name_show +'<br>' :'' ,
                errors.value.file ? errors.value.file +'<br>':'',
                errors.value.birth ? errors.value.birth +'<br>' :'',
                errors.value.gender ? errors.value.gender +'<br>' :'',
                errors.value.national_id ? errors.value.national_id +'<br>' :'',
                errors.value.economical_number ? errors.value.economical_number +'<br>' :'',],

        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('identity.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{

    if (

            form.birth && form.file  && form.lasst_name  && form.name && form.economical_number && form.national_id
            || form.gender && form.national_code && form.birth && form.file  && form.lasst_name  && form.name
        )
        {

            form.post(route('identity.store'),{
                    onFinish:() => submitTime()
                });
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
                        <td class="me-auto"  v-if="props.users.identity && props.users.identity.status !== 4">
                            <!-- <button v-if="form.transaction == 'برداشت' " class="btn btn-primary" @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">ثبت</button> -->
                            <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                <span v-if="form.processing">پردازش...</span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                <span v-else >ارسال</span>
                            </button>
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
                            <div class="col-lg-9" >
                                <div class="col-lg-2 me-auto">
                                    <h6 class="text-danger">وضعیت هویتی</h6>
                                    <p v-if="props.users.identity && props.users.identity.status == 0">ثبت شده</p>
                                    <p v-else-if="props.users.identity &&  props.users.identity.status == 1"> درانتظار</p>
                                    <p v-else-if="props.users.identity && props.users.identity.status == 2">مسدود شده</p>
                                    <p v-else-if="props.users.identity && props.users.identity.status == 3">رد شده</p>
                                    <p v-else-if="props.users.identity && props.users.identity.status == 4">تایید شده</p>
                                    <p v-else >نامشخص</p>
                                </div>
                                <section class="content-body p-xl-4" v-if="props.users.identity && props.users.identity.status !== 4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row gx-3" v-if="props.users.person == 0">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">کد ملی</label>
                                                        <input  v-model.number.lazy="form.national_code" class="form-control" type="text" placeholder="اینجا تایپ کنید" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">نام</label>
                                                        <input  v-model.lazy="form.name" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name" autocomplete="name" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">نام خانوادگی</label>
                                                        <input  v-model.lazy="form.lasst_name" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name" autocomplete="name" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">تولد</label>
                                                        <date-picker v-model.lazy="form.birth" color="#1ABC9C" :max="now" type="date" ></date-picker>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label for="gender" class="form-label">جنسیت</label>
                                                        <select  v-model.lazy="form.gender" class="form-select" name="gender" id="gender">
                                                            <option >خانم</option>
                                                            <option >آقا</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row gx-3" v-else-if="props.users.person == 1">
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label">شناسه ملی</label>
                                                        <input  v-model.number.lazy="form.national_id" class="form-control" type="text" placeholder="اینجا تایپ کنید" />
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label">کد اقتصادی</label>
                                                        <input  v-model.number.lazy="form.economical_number" class="form-control" type="text" placeholder="اینجا تایپ کنید" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">نام مدیرعامل</label>
                                                        <input  v-model.lazy="form.name" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name" autocomplete="name" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">نام خانوادگی مدیرعامل</label>
                                                        <input  v-model.lazy="form.lasst_name" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name" autocomplete="name" />
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">تاریخ تاسیس</label>
                                                        <date-picker v-model.lazy="form.birth" color="#1ABC9C" :max="now" type="date" ></date-picker>
                                                    </div>
                                                    <div class="col-lg-6 mb-3" v-if="props.users.person == 0">
                                                        <label for="gender" class="form-label">جنسیت</label>
                                                        <select  v-model.lazy="form.gender" class="form-select" name="gender" id="gender">
                                                            <option >خانم</option>
                                                            <option >آقا</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <aside class="col-lg-4">
                                                <figure class="text-lg-center">
                                                   <img :src="$page.props.ziggy.url+'/assets/imgs/theme/upload.svg'" alt="national code Photo" />
                                                    <input  class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file"  accept="zip/rar/*"/>
                                                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                        {{ form.progress.percentage }}%
                                                    </progress>

                                                </figure>
                                            </aside>
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
