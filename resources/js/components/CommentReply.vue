<script setup>

import { computed,ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import CommentReply from '@/Components/CommentReply.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Editor from '@tinymce/tinymce-vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({comments:Object,comment_id:Number,files:Object,product:Object});

const form = useForm({
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
        form.post(route('comment.store'),
            // {
            //     onFinish:() => submitTime()
            // }
        );
    }
}
</script>
<template >
    <template v-for="(comment,index) in props.comments" :key="index">
        <div class=" single-comment justify-content-between d-flex mb-30 ml-30" >
            <div class="user justify-content-between d-flex">
                <div class="thumb text-center">
                    <img  v-if="comment.user.image && comment.user.image.url && comment.user.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+comment.user.image.url" :alt="comment.user.name_show" >
                    <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="comment.user.name_show" >
                    <Link :href="route('profile.show',[comment.user.user_name])" class="font-heading text-brand">{{ comment.user.name_show }}</Link>
                </div>
                <div class="desc">
                    <div class="d-flex justify-content-between mb-10">
                        <div class="d-flex align-items-center">
                            <span class="font-xs text-muted">{{ moment(comment.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }} </span>
                        </div>
                        <!-- <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width:80%">
                            </div>
                        </div> -->
                    </div>
                    <p class="mb-10">
                        <span v-html="comment.text"></span>
                        <a class="reply" data-bs-toggle="collapse" :href="'#multiCollapseExample'+comment.id" aria-expanded="false" :aria-controls="'multiCollapseExample'+comment.id">پاسخ</a>
                    </p>

                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12  m-auto collapse" :id="'multiCollapseExample'+comment.id">
            <form class="form-contact comment_form mb-50 " action="#"  >
                <div class="row ">
                    <div class="col-12">
                        <div class="form-group">
                            <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text" placeholder="نظر خود را اینجا تایپ کنید." />
                        </div>
                    </div>
                </div>
                <div class="form-group">

                    <button @click.prevent="submitReply(comment.id)" class="button button-contactForm" type="submit">
                        <span v-if="form.processing">پردازش...</span>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                        <span v-else > ارسال پاسخ</span>
                    </button>
                </div>
            </form>
        </div>
        <CommentReply :comments="comment.replies" :comment_id ="comment.id" :product="props.product" />
    </template>
</template>
