<script setup>

import { computed,ref,onMounted } from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import TicketReply from '@/Pages/Users/Admin/Support/support-reply.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,users:Object,tickets:Object,replies:Object,notification:Object
    ,companies:Object,descriptions:Object,alert:Object,wallet:Number,cart:Object
});

const form = useForm({
    parent_id:props.tickets.id,
    menu : props.tickets.menu,
    recepiant:props.tickets.recepiant,
    subject:props.tickets.subject,
    file:null,
    text:null,
    user:null,
    destination:null,
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
        title: [errors.value.destination ? errors.value.destination +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,
                ],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('supportAdmin.show',[props.tickets.id]),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = (id) => {
    form.destination = id
    if(form.text){
        form.post(route('supportAdmin.store'),{
            onFinish:() => submitTime()
        })
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
        <div class="screen-overlay"></div>
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
                <form >
                <div class="card mb-4">
                        <div class="card-body">
                            <div class="card mb-4">
                                <div class="col-sm-12 mt-3">
                                        <div class="d-flex me-2" >
                                            <img v-if="props.tickets.user && props.tickets.user.image && props.tickets.user.image.status == 4" class="img-sm img-thumbnail" :src="$page.props.ziggy.url +'/storage/' +props.tickets.user.image.url" :alt="props.tickets.user.name_show" />
                                            <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" class="img-sm img-thumbnail" :alt="props.tickets.user.name_show">
                                            <Link :href="route('profile.show', [props.tickets.user.user_name])">
                                                <span class="font-xs text-muted me-1">{{props.tickets.user.name_show}}|
                                                    {{ moment(props.tickets.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                </span>
                                            </Link>
                                        </div>
                                        <div class="mb-4 me-5">
                                            <div class="d-flex">
                                                <div v-html="props.tickets.text"></div>
                                                <a v-if="props.tickets.user.id !== props.users.id && props.tickets.status !== 3" :href="'#CollapseExample'+props.tickets.id" class="reply me-1" data-bs-toggle="collapse" aria-expanded="false" :aria-controls="'CollapseExample'+props.tickets.id">پاسخ</a>
                                            </div>
                                        </div>
                                        <div class="submenu text-a-r collapse " :id="'CollapseExample'+props.tickets.id">
                                            <div class="card-body">
                                                <div class="mb-4">
                                                    <label class="form-label">پاسخ:<span class="text-danger me-1">*</span></label>
                                                    <!-- <textarea v-model="form.text" name="text" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea> -->
                                                    <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" placeholder="اینجا تایپ کنید" />
                                                </div>
                                            </div>
                                            <div class="card-body" >
                                                <label class="form-label"> آپلودفایل<span class="text-danger me-1"></span></label>
                                                <div class="input-upload">
                                                    <!-- <img src="assets/imgs/theme/upload.svg" alt="" /> -->
                                                    <input name="file" class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file" />
                                                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                    {{ form.progress.percentage }}%
                                                    </progress>
                                                </div>
                                            </div>
                                            <button  @click.prevent="submit(props.tickets.user.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary me-4">
                                                <span v-if="form.processing">پردازش...</span>
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                <span v-else >ارسال</span>
                                            </button>
                                        </div>
                                        <div class="mb-4 me-5" v-if="props.tickets.file">
                                         <p> جهت مشاهده فایل های ضمیه را دانلود نمایید.</p>
                                            <!-- <button @click.prevent="submitfile(props.tickets.file)" class="btn btn-sm btn-primary" >دانلود</button> -->
                                            <a :href="$page.props.ziggy.url+'/storage/'+props.tickets.file.url" class="btn btn-sm btn-primary" >دانلود</a>
                                        </div>
                                     <hr>
                                    </div>
                                    <TicketReply  :tickets="props.tickets" :replies="props.replies" :users="props.users" :auth="props.auth" />
                                </div>
                        </div>
                </div>
            </form>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
