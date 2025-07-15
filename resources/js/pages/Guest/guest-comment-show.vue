<script setup>
import Header from './Header.vue';
import Footer from './Footer.vue';
import { computed,ref} from 'vue';
import { Head, Link, useForm ,usePage} from '@inertiajs/vue3';
import CommentReply from '@/Components/CommentReply.vue';
import swal from 'sweetalert2';
import ExtensionOption from '@/Components/ExtensionOptions.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({comments:Object,replies:Object,product:Object,count:Number,auth:Object,cartCount:Number,time:String,
    alert:Object,flash:Object,product_count:Number,product_order:Number,companies:Object
});

const form = useForm({
    id:null,
    parent_id:null,
    text : null,
    user_id:null,
    product_id:null,
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
        title: [errors.value.text ? errors.value.text +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('guest_comment.show',props.product.slug),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () => {

    form.user_id=props.product.user.id
    form.product_id=props.product.id
    if(form.text == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('comment.store'),{
            onFinish:() => submitTime()
        })
    }

}
const submitReply = (id) => {
    form.parent_id = id
    form.user_id=props.product.user.id
    form.product_id=props.product.id

    if(form.text == null)
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {
        form.post(route('comment.store'),{
            onFinish:() => submitTime()
        });
    }
}

const submitFavorite = (id) => {
    form.id = id
    form.post(route('favorite.store'),{
            onFinish:() => submitTime()
        })
};
const submitCart =(id) => {
    form.id = id
    form.post(route('cart.store'),{
            onFinish:() => submitTime()
        })
}

const favorite = ref();
props.product.favorite.forEach(element => {
    if(props.auth.user && element.user_id == props.auth.user.id)
    {
        favorite.value = element
    }
});
</script>
<template>
<Header :companies="props.companies"/>
<!--hero section end-->
<section class="hero-section pt-100 background">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-9 col-lg-7">
                <div class="page-header-content text-white text-center pt-sm-5 pt-md-5 pt-lg-0">
                    <h1 class="text-white mb-0">قالب ها</h1>
                    <div class="custom-breadcrumb">
                        <ol class="breadcrumb d-inline-block bg-transparent list-inline py-0">
                            <li class="list-inline-item breadcrumb-item"><a href="#">خانه</a></li>
                            <li class="list-inline-item breadcrumb-item"><a href="#">صفحات</a></li>
                            <li class="list-inline-item breadcrumb-item active">دانلود و خرید</li>
                            <li class="list-inline-item breadcrumb-item active">قالب ها</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="background-header" style="height:5rem"></div>
</section>
<!--hero section end-->

<div class="container-fluid mb-4">
    <ExtensionOption :product="props.product" :product_count="props.product_count" :product_order="props.product_order"
                     :cart="props.cart" :time="props.time" :count="props.count" :alert="props.alert" :flash="props.flash" :auth="props.auth"
                     :companies="props.companies" />
<div class="row mt-5">
    <div class="col-sm-8">
        <div class="card text-right">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <Link class="nav-link color-succss" :href="route('website_templates.show',props.product.slug)">توضیحات</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link active" href="#">پرسش و پاسخ</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link color-succss" :href="route('guest_support.show',props.product.slug)">پشتیبانی</Link>
                    </li>
                </ul>
            </div>
            <div class="card-body text-left">
                 <div class="comments-area mb-5">
                    <h5 class="comments-title"> نظرات</h5>
                    <div class="comment-list">
                        <div class="comment" v-for="(comment,index) in props.comments" :key="index">
                                <div class="comment-author" :id="comment.id">
                                     <img  v-if="comment.user.image && comment.user.image.url && comment.user.image.status == 4"  class="avatar img-fluid rounded-circle" :src="$page.props.ziggy.url+'/storage/'+comment.user.image.url" :alt="comment.user.name_show" />
                                     <img  v-else class="avatar img-fluid rounded-circle" :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="comment.user.name_show"/>
                                </div>
                                <div class="comment-body border-top">
                                <div class="comment-meta">

                                    <div class="comment-meta-author"><Link class="color-succss" :href="route('profile.show',[comment.user.user_name])" >{{comment.user.name_show}}</Link></div>

                                    <div class="comment-meta-date"><Link :href="route('profile.show',[comment.user.user_name])">
                                    {{ moment(comment.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                    </Link></div>
                                </div>
                                <div class="comment-content">
                                    <div v-html="comment.text"></div>
                                </div>
                                <div class="comment-reply" v-if="props.auth.user">
                                    <a  data-bs-toggle="collapse" :href="'#multiCollapseExample'+comment.id"
                                    aria-expanded="false" :aria-controls="'multiCollapseExample'+comment.id">
                                        پاسخ
                                    </a>
                                </div>
                                <div class="submenu text-a-l collapse " :id="'multiCollapseExample'+comment.id">
                                        <form class="comment-form row " >
                                            <div class="form-group col-md-12" >
                                                <!-- <textarea v-model.lazy="form.text" class="form-control" rows="8" placeholder="اینجا تایپ کنید" ></textarea> -->
                                                <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" />
                                            </div>
                                            <div class="form-submit col-md-12">
                                                <button @click.prevent="submitReply(comment.id)" class="btn btn-sm btn-primary mt-4" type="submit">
                                                    <span v-if="form.processing">پردازش...</span>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                    <span v-else >ارسال</span>
                                                </button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                            <CommentReply :comments="props.replies" :comment_id ="comment.id" :files="props.files" :product="props.product" />
                        </div>
                    </div>
                    <div class="comment-respond" v-if="props.auth.user">
                        <h5 class="comment-reply-title">ارسال نظر</h5>
                        <p class="comment-notes">آدرس ایمیل شما منتشر نخواهد شد. قسمتهای مورد نیاز علامت گذاری شده اند.</p>
                        <form class="comment-form row" >
                                <div class="form-group col-md-12" >
                                    <!-- <textarea v-model.lazy="form.text" class="form-control" rows="8" placeholder="اینجا تایپ کنید" ></textarea> -->
                                    <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" />
                                </div>
                                <div class="form-submit col-md-12">
                                    <button @click.prevent="submit" class="btn btn-sm btn-primary mt-4" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        <span v-if="form.processing">پردازش...</span>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                        <span v-else >ارسال</span>
                                    </button>
                                </div>
                        </form>
                    </div>

                    <div class="comment-respond mt-5" v-else>
                        <h5 class="comment-reply-title">ارسال نظر</h5>
                        <p class="comment-notes">برای ارسال نظر وارد حساب کاربری خود شوید.</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-footer">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header">
                مشخصات فروشنده
            </div>
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">نام کاربر</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <Link class="color-succss" :href="route('profile.show',[props.product.user.user_name])">{{props.product.user.name_show}}</Link>
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">تعداد محصولات</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">{{(props.count).toLocaleString("fa-IR")}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3 mb-3">
            <div class="card-header">
                مشخصات محصول:
            </div>
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">ورژن</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">{{(props.product.version).toLocaleString("fa-IR")}}</p>
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">انتشار</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">
                            {{ moment(props.product.created_at).locale("fa", fa).format('jYYYY/jM/jD') }}
                        </p>
                    </div>
                </div>
                <div class="d-flex bd-highlight">
                    <div class="bd-highlight">
                        <h5 class="card-title">بروز رسانی</h5>
                    </div>
                    <div class="ml-auto bd-highlight">
                        <p class="card-text">
                            {{ moment(props.product.updated_at).locale("fa", fa).format('jYYYY/jM/jD') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<Footer   :companies="props.companies" />
</template>


