<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Editor from '@tinymce/tinymce-vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({auth:Object,users:Object,tickets:Object,replies:Object,alert: Object});

const form = useForm({
    parent_id:props.tickets.id,
    menu : props.tickets.menu,
    recepiant:props.tickets.recepiant,
    subject:props.tickets.subject,
    text:null,
    destination:null,
    file: null
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
    Inertia.visit(route('support.show',props.tickets.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = (id) => {
    form.destination = id
    if(form.text){
        form.post(route('support.store'),{
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
    <div class="col-sm-12 mt-3" v-for="(ticket,index) in props.replies" :key="index" >
        <form>
        <div class="d-flex me-2" >

                <img v-if="ticket.user && ticket.user.image && ticket.user.image.status == 4" class="img-sm img-thumbnail" :src="$page.props.ziggy.url +'/storage/' +ticket.user.image.url" :alt="ticket.user.name_show" />
                <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" class="img-sm img-thumbnail" :alt="ticket.user.name_show">
                <Link :href="route('profile.show', [ticket.user.user_name])">
                    <span class="font-xs text-muted me-1">{{ticket.user.name_show}}|
                        {{ moment(ticket.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                    </span>
                </Link>
        </div>
        <div class="mb-4 me-5">
            <div class="d-flex">
                <div v-html="ticket.text"></div>
                <a  v-if="ticket.user.id !== props.auth.user.id && props.tickets.status !== 3" :href="'#CollapseExample'+ticket.id" class="reply me-1" data-bs-toggle="collapse" aria-expanded="false" :aria-controls="'CollapseExample'+ticket.id">پاسخ</a>
            </div>
        </div>
        <div class="submenu text-a-r collapse " :id="'CollapseExample'+ticket.id">
            <div class="card-body">
                <div class="mb-4">
                    <label  class="form-label">شرح<span class="text-danger me-1">*</span></label>
                    <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" placeholder="اینجا تایپ کنید" />
                </div>
            </div>
            <div class="card-body" >
                <label class="form-label"> آپلودفایل<span class="text-danger me-1"></span></label>
                <div class="input-upload">
                    <input name="file" class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file" />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                    {{ form.progress.percentage }}%
                    </progress>
                </div>
            </div>
            <button  @click.prevent="submit(ticket.user.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary me-4">
                <span v-if="form.processing">پردازش...</span>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                <span v-else >ارسال</span>
            </button>
        </div>
        <div class="mb-4 me-5" v-if="ticket.file">
            <p> جهت مشاهده فایل های ضمیه را دانلود نمایید.</p>
            <a :href="$page.props.ziggy.url+'/storage/'+ticket.file.url" class="btn btn-sm btn-primary" >دانلود</a>
        </div>
    </form>
    <hr>
    </div>
</template>
