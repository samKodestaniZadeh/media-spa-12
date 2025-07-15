<script setup>

import { computed,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import DatePicker from 'vue3-persian-datetime-picker';
import AsideAdmin2 from '@/Components/AsideAdmin2.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,canResetPassword: Boolean,status: String,users:Object,ostans:Object,shahrs:Object,
    notifications:Object,companies:Object,descriptions:Object,user:Object,alert:Object,wallet:Number
    ,cart:Object
});

var now =  new Date();

const form =  useForm({
    id:props.user.id,
    user_name:props.user.user_name,
    name:props.user.name,
    lasst_name:props.user.lasst_name,
    name_show:props.user.name_show,
    tel:props.user.tel,
    birth:props.user.profile?props.user.profile.birth:null,
    gender:props.user.profile?props.user.profile.gender:null,
    email: props.user.email,
    image:props.user.image? props.user.image.url:null,
    biography:props.user.profile?props.user.profile.biography:null,
    password: null,
    password_confirmation: null,
    status:props.user.profile && props.user.profile.status
});

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
    Inertia.visit(route('profileAdmin.show',form.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () =>{
    form.post(route('profileAdmin.store'),{
        onFinish:() => submitTime()
    })
};

const ostans = ref(props.ostans);
const shahrs = ref();

const change = () =>{

    if(props.shahrs == form.ostan)
    {
        shahrs.value = props.shahrs
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
                        <td class="d-flex me-auto">
                            <select v-model.lazy="form.status" class="form-select">
                                <option value="0">ثبت شده</option>
                                <option value="1">در انتظار</option>
                                <option value="2">مسدود</option>
                                <option value="3">رد</option>
                                <option value="4">تایید</option>
                            </select>
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
                <div class="">
                    <div class="card-body bg-white">
                        <div class="row gx-5">
                            <AsideAdmin2 :id="form.id" />
                            <div class="col-lg-9">
                                <div class="col-lg-2 me-auto">
                                    <h6 class="text-danger">وضعیت پروفایل</h6>
                                    <p v-if="props.user.profile && props.user.profile.status == 0">ثبت شده</p>
                                    <p v-else-if="props.user.profile && props.user.profile.status == 1"> درانتظار</p>
                                    <p v-else-if="props.user.profile && props.user.profile.status == 2">مسدود شده</p>
                                    <p v-else-if="props.user.profile && props.user.profile.status == 3">رد شده</p>
                                    <p v-else-if="props.user.profile && props.user.profile.status == 4">تایید شده</p>
                                    <p v-else >نامشخص</p>
                                </div>
                                <section class="content-body p-xl-4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row gx-3">
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label">نام کاربری</label>
                                                        <input  v-model.lazy="form.user_name" readonly="readonly" class="form-control" type="text" placeholder="اینجا تایپ کنید" />
                                                    </div>

                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">نام نمایشی</label>
                                                        <input  v-model.lazy="form.name_show" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name_show" autocomplete="name_show" />
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="biography" class="form-label">بیوگرافی</label>
                                                        <textarea v-model.lazy="form.biography" class="bg-light" name="" id="" cols="55" rows="7"
                                                        placeholder="اینجا تایپ کنید" ></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <aside class="col-lg-4">
                                                <figure class="text-lg-center">
                                                    <!-- <input v-model.lazy="form.image" class="form-control" type="hidden" /> -->
                                                    <img  v-if="props.user.image && props.user.image.url" class="img-lg mb-3 img-avatar" :src="$page.props.ziggy.url+'/storage/'+props.user.image.url" :alt="props.user.show_name" />
                                                    <img  v-else class="img-lg mb-3 img-avatar" :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="props.user.show_name" />
                                                    <input  class="form-control" type="file" @input="form.image = $event.target.files[0]"  id="image"  accept="image/*"/>
                                                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                        {{ form.progress.percentage }}%
                                                    </progress>
                                                    <figcaption class="mt-4">
                                                        <button @click.prevent="submitImage" class="btn btn-light rounded font-md" href="#"> <i class="icons material-icons md-backup font-md"></i> بارگذاری </button>
                                                    </figcaption>
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
