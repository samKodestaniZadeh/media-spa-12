<script setup>

<<<<<<< HEAD
import {computed,ref} from 'vue';
=======
import {computed,watch} from 'vue';
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import {Link,useForm,usePage} from '@inertiajs/vue3';
import Aside from '@/Components/AsideAdmin.vue';
import swal from 'sweetalert2';
<<<<<<< HEAD
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
=======


const errors = computed(() => usePage().props.errors);

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
const props = defineProps({
    users: Object,ids: Object,statuses: Object,subjects: Object,cartNumber: Number,shahrs: Object,wallet:Number,
    ostans: Object,alert:Object,notifications: Object,companies:Object,descriptions:Object,cart:Object
});

const form = useForm({
<<<<<<< HEAD
    subject: null,
    status: null,
    id: props.companies ? props.companies.id : null,
    name: props.companies ? props.companies.user.name_show : null,
    economical_number: props.companies ? props.companies.economical_number : null,
    national_number: props.companies ? props.companies.national_number : null,
    postal_code: props.companies ? props.companies.postal_code : null,
    phone: props.companies ? props.companies.phone : null,
    mobile: props.companies ? props.companies.mobile : null,
    ostan: props.companies ? props.companies.ostan : null,
    shahr: props.companies ? props.companies.shahr : null,
    address: props.companies ? props.companies.address : null,
    image: props.companies && props.companies.image? props.companies.image.url : null,
    tax: props.companies ? props.companies.tax : null,
    complications: props.companies ? props.companies.complications : null,
    comison: props.companies ? props.companies.comison : null,
    email: props.companies ? props.companies.email : null,
    telegram: props.companies ? props.companies.telegram : null,
    instagram: props.companies ? props.companies.instagram : null,
    link: props.companies ? props.companies.link : null,
    comison_designer: props.companies ? props.companies.comison_designer : null,
    design_damage:props.companies ? props.companies.design_damage : null,
});
console.log(props.companies)
=======

    id: props.companies ? props.companies.id : null,
    user_id: props.companies ? props.companies.user_id : null,
    tax: props.companies ? props.companies.tax : null,
    complications: props.companies ? props.companies.complications : null,
    comison: props.companies ? props.companies.comison : null,
    comison_designer: props.companies ? props.companies.comison_designer : null,
    design_damage:props.companies ? props.companies.design_damage : null,
    job: props.companies ? props.companies.job : null,
    status: props.companies ? props.companies.status : null,

});

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
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

<<<<<<< HEAD
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
        title: [errors.value.name ? errors.value.name +'<br>' :'' ],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('company.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}


const ostans = ref(props.ostans);
const shahrs = ref();

const change = () => {

    if (props.shahrs == form.ostan) {
        shahrs.value = props.shahrs
    }

};

const submitCompany = () => {
    if (form.name == null || form.economical_number == null || form.national_number == null || form.postal_code == null ||
        form.phone == null || form.mobile == null || form.ostan == null || form.shahr == null || form.address == null ||
        form.image == null || form.tax == null || form.complications == null|| form.comison == null|| form.comison_designer == null||
        form.design_damage == null)
=======
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


const submitCompany = () => {
    if (form.tax == null || form.complications == null || form.comison == null || form.comison_designer == null ||
        form.design_damage == null || form.job == null
        )
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    } else
    {
<<<<<<< HEAD
        form.post(route('company.store'),{
            onFinish:() => submitTime()
        })
=======
        form.post(route('company.store'),
        // {
        //     onFinish:() => submitTime()
        // }
        )
>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
    }
};

</script>
<template>
    <body >
    <div class="screen-overlay"></div>
<Header :cart="props.cart"  :roles="props.roles" :alert="props.alert" :users="props.users" :wallet="props.wallet"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

<main class="main-wrap rtl">
    <section class="content-main">
        <div class="row content-header">
            <div class="d-flex col-sm-12">
                <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                <td class="me-auto">
                    <Link v-if="props.companies == null" :href="route('company.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link>
                </td>
            </div>
            <div class="col-sm-12">
                <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row gx-5">
                    <Aside class="col-lg-3 border-end" />
                    <div class="col-lg-9">
<<<<<<< HEAD
                        <section class="content-body p-xl-4" v-if="form.name">
                            <form>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row gx-3">
                                            <div class="col-6 mb-3">
                                                <label class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">نام فروشگاه</font></font></label>
                                                <input v-model="form.name" class="form-control" type="text" placeholder="اینجا تایپ کنید">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-6 mb-3">
                                                <label class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">شماره اقتصادی</font></font></label>
                                                <input v-model.lazy.trim="form.economical_number" class="form-control" type="text" placeholder="اینجا تایپ کنید">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-6 mb-3">
                                                <label class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">شماره ثبت / شماره ملی</font></font></label>
                                                <input v-model.lazy.trim="form.national_number" class="form-control" type="text" placeholder="اینجا تایپ کنید">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">کد پستی</font></font></label>
                                                <input v-model.lazy.trim="form.postal_code" class="form-control" type="text" placeholder="اینجا تایپ کنید">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">تلفن</font></font></label>
                                                <input v-model.lazy.trim="form.phone" class="form-control" type="tel" placeholder="+1234567890">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">همراه</font></font></label>
                                                <input v-model.lazy.trim="form.mobile" class="form-control" type="tel" placeholder="09123456789" name="tel" autocomplete="tel" />
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-lg-12">
                                                <div class="row  gx-2">
                                                    <div class="col-lg-6">
                                                        <div class="mt-4">
                                                            <label for="" class="form-label">استان<span class="text-danger">*</span></label>
                                                            <select v-model.lazy="form.ostan" @change="change" class="form-select" name="" id="">
                                                                <option v-for="(ostan,index ) in ostans" :key="index" v-if="ostans.length > 0">{{ostan}}</option>
                                                                <option v-else disabled>گزینه ای یافت نشد.</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6" v-if="form.ostan">
                                                        <div class="mt-4">
                                                            <label for="" class="form-label">شهر<span class="text-danger">*</span></label>
                                                            <select v-model.lazy="form.shahr" class="form-select" name="" id="">
                                                                <template v-for="(shahr,index1 ) in props.shahrs" :key="index1">
                                                                    <option v-for="(shahrs,index ) in shahr" :key="index" v-if="index1 == form.ostan">{{shahrs}}</option>
                                                                </template>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-lg-12 mb-3">
                                                <label class="form-label">نشانی</label>
                                                <!-- <input v-model.lazy.trim="form.address" class="form-control" type="text" placeholder="اینجا تایپ کنید"> -->
                                                <textarea v-model.lazy.trim="form.address" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea>
                                            </div>
                                            <!-- col .// -->
                                        </div>
                                        <!-- row.// -->
                                    </div>
                                    <!-- col.// -->
                                    <aside class="col-lg-4">
                                        <figure class="text-lg-center">
                                            <img v-if="props.companies && props.companies.image" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="img-lg mb-3 img-avatar" :alt="props.companies.name">
                                            <img v-else src="/storage/images/logo.jpg" class="img-lg mb-3 img-avatar" alt="">
                                            <figcaption>
                                                <input class="form-control" type="file" @input="form.image = $event.target.files[0]" id="image" accept="image/*" />
                                                <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                    {{ form.progress.percentage }}%
                                                </progress>
                                                <!-- <a class="btn btn-light rounded font-md" @click.prevent="submitImage"> <i class="icons material-icons md-backup font-md"></i><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">بارگذاری</font></font></a> -->
                                            </figcaption>
                                        </figure>
                                    </aside>
                                    <!-- col.// -->
=======
                        <section class="content-body p-xl-4" v-if="props.companies">
                            <form>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="row gx-3">
                                            <div class="col-6 mb-3">
                                                <label class="form-label"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">مالیات</span></span></label>
                                                <input v-model="form.tax" class="form-control" type="text" placeholder="مالیات به عدد وارد نمایید.مثال:0.1 معادل 10 درصد">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-6 mb-3">
                                                <label class="form-label"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">عوارض</span></span></label>
                                                <input v-model.lazy.trim="form.complications" class="form-control" type="text" placeholder="عوارض به عدد وارد نمایید.مثال:0.1 معادل 10 درصد">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-6 mb-3">
                                                <label class="form-label"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">کمیسیون</span></span></label>
                                                <input v-model.lazy.trim="form.comison" class="form-control" type="text" placeholder="کمیسیون به عدد وارد نمایید.مثال:0.1 معادل 10 درصد">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">کمیسیون طراح</span></span></label>
                                                <input v-model.lazy.trim="form.comison_designer" class="form-control" type="text" placeholder="کمیسیون به عدد وارد نمایید.مثال:0.1 معادل 10 درصد">
                                            </div>
                                            <!-- col .// -->
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">زمان جاب</span></span></label>
                                                <input v-model.lazy.trim="form.job" class="form-control" type="tel" placeholder="زمان جاب به دقیقه وارد نمایید. مثال:1">
                                            </div>

                                        </div>
                                        <!-- row.// -->
                                    </div>

>>>>>>> b254bd31864daeeaa805e9f88aa61a499df7051b
                                </div>
                                <!-- row.// -->
                                <br>
                                <button class="btn btn-primary" @click.prevent="submitCompany" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    <span v-if="form.processing">پردازش...</span>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                    <span v-else >ویرایش</span>
                                </button>
                            </form>
                            <hr class="my-5">
                        </section>
                        <p v-else> گزینه ای یافت نشد.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <Footer :companies="props.companies" />
</main>
</body>
</template>
