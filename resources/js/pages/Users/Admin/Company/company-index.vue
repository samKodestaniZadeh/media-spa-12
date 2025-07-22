<script setup>

import {computed,watch} from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import {Link,useForm,usePage} from '@inertiajs/vue3';
import Aside from '@/Components/AsideAdmin.vue';
import swal from 'sweetalert2';


const errors = computed(() => usePage().props.errors);

const props = defineProps({
    users: Object,ids: Object,statuses: Object,subjects: Object,cartNumber: Number,shahrs: Object,wallet:Number,
    ostans: Object,alert:Object,notifications: Object,companies:Object,descriptions:Object,cart:Object
});

const form = useForm({

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


const submitCompany = () => {
    if (form.tax == null || form.complications == null || form.comison == null || form.comison_designer == null ||
        form.design_damage == null || form.job == null
        )
    {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    } else
    {
        form.post(route('company.store'),
        // {
        //     onFinish:() => submitTime()
        // }
        )
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
