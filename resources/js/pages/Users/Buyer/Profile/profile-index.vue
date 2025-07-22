<script setup>

import { computed,ref,watch} from 'vue';
import { Head, Link, useForm , usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import Aside from '@/Components/Aside.vue';
import swal from 'sweetalert2';


const errors = computed(() => usePage().props.errors);

const props = defineProps({
    auth:Object,canResetPassword: Boolean,status: String,users:Object,ostans:Object,shahrs:Object,
    notifications:Object,companies:Object,descriptions:Object,alert:Object,token:String,wallet:Number,
    cart:Object
});


const form =  useForm({
    // national_code:props.users.national_code,
    user_name:props.users.user_name,
    // name:props.users.name,
    // lasst_name:props.users.lasst_name,
    name_show:props.users.name_show,
    tel:props.users.tel,
    phone:props.users.phone,
    // shahr:props.users.profile?props.users.profile.shahr:null,
    // address:props.users.profile?props.users.profile.address:null,
    // birth:props.users.profile?props.users.profile.birth:null,
    // gender:props.users.profile?props.users.profile.gender:null,
    // email: props.users.email,
    image:props.users.profile && props.users.profile.image? props.users.profile.image.url:null,
    biography:props.users.profile?props.users.profile.biography:null,
    password: null,
    password_confirmation: null,
    token:props.token?props.token:null,
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

watch(() => errors.value, (val) => {
  if (val && Object.keys(val).length > 0) {
    Object.values(val).forEach((errMsg) => {
      swal
        .mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", swal.stopTimer);
            toast.addEventListener("mouseleave", swal.resumeTimer);
          },
        })
        .fire({
          title: errMsg,
          icon: "error",
        });
    });
  }
});





const submit = () =>{

    if(form.tel == props.users.tel)
    {
        form.tel = null;
        if(form.name_show == null )
        {
            let text
            text = 'موارد ستاره دار الزامی است.'
            validate(text)
        }
        else
        {
            form.post(route('profile.store'));
        }
    }
    else
    {
        if(form.tel)
        {
            let text
            text = 'موارد ستاره دار الزامی است.'
            validate(text)
        }
        else
        {
            form.post(route('profile.store'),
            // {
            //     onFinish:() => submitTime()
            // }
            );
        }
    }


};
const submitImage = () =>{

    form.post(route('profile.imagestore'),
    // {
    //     onFinish:() => submitTime()
    // }
    );

};

const submitPass = () => {

    if(form.password == null && form.password_confirmation == null )
    {
        let text
        text = 'لطفا پس از بررسی موارد الزامی مجدد فرم را ارسال نمایید.'
        validate(text)
    }
    else
    {
        if(form.password == form.password_confirmation)
        {
            form.post(route('password.change'),
            // {
            //     onFinish:() => submitTime()
            // }
            )
        }
        else
        {
            let text
            text = 'رمز عبور و تکرار رمز عبور باید باهم برابر باشد.'
            validate(text)
        }
    }

};

const submitTelChange = () => {
    if(form.tel !== null)
    {
        form.post(route('profile.store'),
        // {
        //     onFinish:() => submitTime()
        // }
        );
    }
    else
    {

        let text
        text = 'لطفا پس از بررسی موارد الزامی مجدد فرم را ارسال نمایید.'
        validate(text)
    }
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
<Header :cart="props.cart" :wallet="props.wallet" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">

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
                            <div class="col-lg-9">
                                <div class="col-lg-2 me-auto">
                                    <h6 class="text-danger">وضعیت پروفایل</h6>
                                    <p v-if="props.users.profile && props.users.profile.status == 0">ثبت شده</p>
                                    <p v-else-if="props.users.profile && props.users.profile.status == 1"> درانتظار</p>
                                    <p v-else-if="props.users.profile && props.users.profile.status == 2">مسدود شده</p>
                                    <p v-else-if="props.users.profile && props.users.profile.status == 3">رد شده</p>
                                    <p v-else-if="props.users.profile && props.users.profile.status == 4">تایید شده</p>
                                    <p v-else >نامشخص</p>
                                </div>
                                <section class="content-body p-xl-4">
                                    <form>
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="row gx-3">

                                                    <div class="col-6 mb-3">
                                                        <label class="form-label">نام کاربری<span class="text-danger">*</span></label>
                                                        <input  v-model.lazy="form.user_name" readonly="readonly" class="form-control" type="text" placeholder="اینجا تایپ کنید" />
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label class="form-label">نام نمایشی <span class="text-danger">*</span></label>
                                                        <input  v-model.lazy="form.name_show" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name_show" autocomplete="name_show" />
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">تلفن </label>
                                                        <input  v-model.lazy="form.phone" class="form-control" type="text" placeholder="اینجا تایپ کنید" name="name_show" autocomplete="name_show" />
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="biography" class="form-label">بیوگرافی</label>
                                                        <textarea v-model.lazy="form.biography" class="bg-light" name="" id="" cols="55" rows="7"
                                                        placeholder="اینجا تایپ کنید" ></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <aside class="col-lg-4">
                                                <figure class="text-lg-center">

                                                    <img  v-if="props.users.image && props.users.image.url" class="img-lg mb-3 img-avatar" :src="$page.props.ziggy.url+'/storage/'+props.users.image.url" :alt="props.users.name_show" />
                                                    <img  v-else class="img-lg mb-3 img-avatar" :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="props.companies ? props.companies.name:null" />
                                                    <input  class="form-control" type="file" @input="form.image = $event.target.files[0]"  id="image"  accept="image/*"/>
                                                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                        {{ form.progress.percentage }}%
                                                    </progress>

                                                </figure>
                                            </aside>

                                        </div>

                                        <br />

                                        <!-- <button @click.prevent="submit" class="btn btn-primary" type="submit">ذخیره</button> -->
                                    </form>
                                    <hr/>
                                    <div class="row" style="max-width: 920px">
                                        <div class="col-md">
                                            <article class="box mb-3 bg-light">
                                                <h6>تغییر رمز عبور</h6>
                                                <button type="button" class="btn btn-light btn-sm rounded font-md" data-bs-toggle="modal" data-bs-target="#staticBackdrop1">تغییر</button>
                                            </article>
                                        </div>

                                        <div class="col-md">
                                            <article class="box mb-3 bg-light">
                                                <h6>تغییر شماره تلفن همراه</h6>
                                                <button type="button" class="btn btn-light btn-sm rounded font-md" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">تغییر</button>
                                            </article>
                                        </div>

                                    </div>

                                </section>
                                <div class="modal fade " id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">تغییر رمز عبور</h5>
                                            <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <BreezeLabel for="password" value="رمز عبور" class="pb-1"/>
                                                <BreezeInput id="password" type="password" class="form-control " v-model="form.password" required autocomplete="new-password" />
                                            </div>
                                            <div  class="form-group">
                                                <BreezeLabel for="password_confirmation" value="تکرار رمز عبور" class="pb-1"/>
                                                <BreezeInput id="password_confirmation" type="password" class="form-control " v-model="form.password_confirmation" required autocomplete="new-password" />
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-sm btn-primary" data-bs-dismiss="modal"
                                            @click.pervent="submitPass" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">انصراف</button>

                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade " id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">تغییر شماره تلفن همراه</h5>
                                            <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <BreezeLabel class="pb-1" for="tel" value="تلفن همراه"/>
                                                <BreezeInput id="tel" type="tel" class="form-control" v-model="form.tel" autocomplete="tel" placeholder="مثال 09120123456"/>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-sm btn-primary" data-bs-dismiss="modal"
                                            @click.pervent="submitTelChange" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">انصراف</button>

                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
