<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    users:Object,cartPrice:Object,cartCount:Object,cartDiscount:Object,cartCoupon:Object,cartTotal:Object,
    notifications:Object,menus:Object,companies:Object,descriptions:Object,alert:Object,subs:Object,
    wallet:Number,cart:Object
});

const form =  useForm({
    id:props.menus.id,
    parent_id:props.menus.parent_id,
    name:props.menus.name,
    status:props.menus.status,
    subs:[],
    sectionable_type:null,
    sectionable_id:null,
    type:null,
    del:null,
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
        title: [errors.value.name ? errors.value.name +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('section.show',props.menus.id),{ only: [errors.value,hasErrors.value,props.alert] })
}
const submit = () =>{
    form.subs = null
    if( form.name == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('section.store'),{
            onFinish:() => submitTime()
        })
    }
};

if (props.subs) {
    props.subs.data.forEach(element => {
        form.subs.push(element)
    });
}

const submitSub = () =>{
    if( form.name == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.put(route('section.update',form.id),{
            onFinish:() => submitTime()
        })
    }
};
const submitDel = (sectionable_type,sectionable_id) =>{
        form.type = sectionable_type,
        form.del = sectionable_id

    if( form.name == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.put(route('section.update',form.id),{
            onFinish:() => submitTime()
        })
    }
};

</script>
<template>
<Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
        <div class="row content-header">
            <div class="d-flex col-sm-12">
                <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                <td class="d-flex me-auto">
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
        <form >
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام</label>
                                            <div class="row gx-2">
                                                <input v-model.lazy="form.name" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام مدل<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.sectionable_type" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4 ">
                                            <label class="form-label">آیدی<span class="text-danger">*</span></label>
                                            <div class="d-flex">
                                                <input v-model.lazy="form.sectionable_id" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                                <button @click.prevent="submitSub" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                                                    <span v-if="form.processing">پردازش...</span>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                    <span v-else >ایجاد</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" v-if="props.subs.total > 0">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>زیر مجموع</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2" v-for="menu, index in props.subs.data" :key="index">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام مدل</label>
                                            <input v-model.lazy="menu.sectionable_type" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">آیدی</label>
                                            <div class="d-flex">
                                                <input v-model.lazy="menu.sectionable_id" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                                <button @click.prevent="submitDel(menu.sectionable_type,menu.sectionable_id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                                 class="btn btn-md rounded font-sm hover-up">
                                                 <span v-if="form.processing">پردازش...</span>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                    <span v-else >حذف</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5" v-if="props.subs.total > 9">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-start">
                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                            v-for="link in props.subs.links" :key="link.id" >
                                            <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                            v-html="link.label" ></Link>
                                            </li>
                                        </ul>
                                    </nav>
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
