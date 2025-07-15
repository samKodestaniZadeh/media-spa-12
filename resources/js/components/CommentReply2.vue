<script setup>

import { computed,ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import CommentReply from '@/Components/CommentReply2.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Editor from '@tinymce/tinymce-vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({comments:Object,comment_id:Object,files:Object,tarahi:Object});

const form = useForm({
    parent_id:null,
    text : null,
    user_id:null,
    tarahi_id:null,
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
    Inertia.visit(route('guest_comment.show',props.tarahi.slug),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submitReply = (id) => {

    form.parent_id = id
    form.user_id=props.tarahi.user.id
    form.tarahi_id=props.tarahi.id

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
</script>
<template >

<div v-for="(comment,index) in props.comments" :key="index">
<div class="children"  v-if="props.comment_id == comment.parent_id">
        <div class="comment" :id="comment.id">
                <div class="comment-author">
                    <img v-if="comment.user.image && comment.user.image.url && comment.user.image.status == 4"   class="avatar img-fluid rounded-circle" :src="$page.props.ziggy.url+'/storage/'+comment.user.image.url" :alt="comment.user.name_show" />
                    <img  v-else class="avatar img-fluid rounded-circle" :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="comment.user.name_show"/>
                </div>
            <div class="comment-body">
                <div class="comment-meta">

                    <div class="comment-meta-author"><Link class="color-succss" :href="route('profile.show',[comment.user.user_name])">{{comment.user.name_show}}</Link></div>

                    <div class="comment-meta-date"><Link :href="route('profile.show',[comment.user.user_name])">
                        {{ moment(comment.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                    </Link></div>
                </div>
                <div class="comment-content">
                    <div v-html="comment.text"></div>
                </div>
                <div class="comment-reply" v-if="$page.props.auth.user">
                    <a  data-bs-toggle="collapse" :href="'#multiCollapseExample'+comment.id"
                    aria-expanded="false" :aria-controls="'multiCollapseExample'+comment.id">
                        پاسخ
                    </a>
                </div>
                <div class="submenu text-a-l collapse " :id="'multiCollapseExample'+comment.id">
                        <form class="comment-form row " >
                            <div class="form-group col-md-12" >
                                <!-- <textarea v-model.lazy="form.text" class="form-control" rows="8" placeholder="اینجا تایپ کنید"  autofocus></textarea> -->
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
        </div>
        <CommentReply :comments="props.comments" :comment_id ="comment.id" :files="props.files" />
    </div>
    </div>
</template>
