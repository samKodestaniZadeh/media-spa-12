<script setup>

import { computed,ref,watch } from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';
const ApiKey = ref('cfw3yx4hh06riwl1qwbq3fwcmjr80c5v0z2ki1fid7agx2ow');


const page = usePage()
const errors = computed(() => page.props?.errors || {})

const props = defineProps({
    users:Object,comments:Object,notifications:Object,companies:Object,descriptions:Object,alert:Object,wallet:Number,
    cart:Object
});

const form = useForm({
    id:null,
    parent_id:null,
    text : null,
    user_id:null,
    status:props.comments.status,
    product_id:props.comments.commentable_id
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

watch(() => props.alert, (val) => {
  if (val) {
    if (val.title) {
      swal.fire(val.title, val.text, val.icon);
    } else {
      swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', swal.stopTimer);
          toast.addEventListener('mouseleave', swal.resumeTimer);
        }
      }).fire({
        title: val.text,
        icon: val.icon,
      });
    }
  }
});

watch(errors, (newErrors) => {
  const errorMessages = Object.values(newErrors)
    .flat()
    .map(msg => `${msg}<br>`)
    .join('')

  if (errorMessages) {
    swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
      }
    }).fire({
      title: errorMessages,
      icon: 'error',
    })
  }
}, { immediate: true })



const submitReply = (id) => {
    form.parent_id = id,
    form.user_id=props.comments.user_id,
    form.product_id=props.comments.commentable_id,
    form.status=null
    if(form.text){
        form.post(route('commentAdmin.store'))
    }
    else
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }

}

const submitStatus = (id) => {
    form.id = id,
    form.user_id=props.comments.user_id,
    form.product_id=props.comments.commentable_id

    form.put(route('commentAdmin.update',form.id))
}

</script>
<template>
<Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

        <div class="screen-overlay"></div>
        <main class="main-wrap rtl">
        <section class="content-main">
            <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <div class="d-flex me-auto">
                            <select v-model.lazy="form.status" class="form-select">
                                <option value="0">ثبت</option>
                                <option value="1">انتظار</option>
                                <option value="2">مسدود</option>
                                <option value="3">منقضی</option>
                                <option value="4">منتشر</option>
                            </select>
                            <button @click.prevent="submitStatus(props.comments.id)" class="btn btn-primary me-auto ms-1">
                                <span v-if="form.processing">پردازش...</span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                <span v-else >ویرایش</span>
                            </button>
                        </div>
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
                                            <div class="col-sm-10 mt-3">
                                                <img v-if="props.comments.user.image && props.comments.user.image.status == 4"  class="img-sm img-thumbnail" :src="$page.props.ziggy.url +'/storage/'+props.comments.user.image.url" :alt="props.comments.user.name_show" />
                                                <img v-else class="img-sm img-thumbnail" :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="props.comments.user.name_show" />

                                                <span class="font-xs text-muted me-1">
                                                    <Link  :href="route('profile.show',[props.comments.user.user_name])"> {{props.comments.user.name_show}}
                                                        |
                                                        {{ moment(props.comments.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                    </Link>
                                                </span>
                                                <div class="mb-4 me-5">
                                                    <div class="d-flex">
                                                        <div v-html="props.comments.text"></div>
                                                        <a :href="'#CollapseExample'+props.comments.id" class="reply" data-bs-toggle="collapse" aria-expanded="false" :aria-controls="'CollapseExample'+props.comments.id">پاسخ</a>
                                                    </div>
                                                </div>
                                                <div class="submenu text-a-r collapse " :id="'CollapseExample'+props.comments.id">
                                                    <div class="card-body">
                                                        <div class="mb-4">
                                                            <label class="form-label">پاسخ:<span class="text-danger me-1">*</span></label>
                                                            <!-- <textarea v-model="form.text" name="text" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea> -->
                                                            <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text" placeholder="اینجا تایپ کنید" />
                                                        </div>
                                                    </div>
                                                    <button  @click.prevent="submitReply(props.comments.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary me-4">
                                                        <span v-if="form.processing">پردازش...</span>
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                        <span v-else >ارسال</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                     <hr>
                                    </div>
                            </div>
                        </div>
                </div>
            </form>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
