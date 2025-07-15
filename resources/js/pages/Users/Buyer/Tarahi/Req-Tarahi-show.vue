<script setup>

import { computed, ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    tarahi:Object,users:Object,cart:Object,notifications:Object,names:Object,ids:Object,statuses:Object,companies:Object,descriptions:Object,reqDesigners:Object,
    token:String,alert:Object,wallet:Number,
});

const form = useForm({
    name: null,
    status:null,
    id:null,
    reqDesigner_id:null,
    dargah:null,
    model:null,
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
        if (alert.value.title)
    {

        swal.fire(
        props.alert.title,
        props.alert.text,
        props.alert.icon,
        )

    }
    else
    {

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
            title:props.alert.text,
            icon:props.alert.icon,
        })
    }
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
    Inertia.visit(route('reqTarahi.show',props.tarahi.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submitCart = (id,reqDesigner) => {
    form.id = id,
    form.name = 'karfarma',
    form.model = 'App\\Models\\Tarahi',
    form.reqDesigner_id = reqDesigner,
    form.post(route('cart.store'),{
        onFinish: () => submitTime()
    })
}

// const submitCart =(id,reqDesigner) => {


//     if( form.dargah == null){
//         let text = 'لطفا یکی از موارد را جهت پرداخت انتخاب نمایید.'
//         validate(text)
//     }
//     else
//     {
//         form.id = id
//         form.name = 'karfarma'
//         form.reqDesigner_id = reqDesigner
//         form.post(route('cart.store'),{
//             onFinish:() => submitTimer()
//         })

//     }

// }
</script>
<template>
<Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
        <section class="content-main">
            <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <!-- <Link :href="route('product.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link> -->
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                   <div class="bg-white mb-4"  v-if="props.reqDesigners.total > 0">

                <div class="card-body" v-if="props.reqDesigners">
                    <div class="table-responsive">
                        <article class="itemlist">
                            <table v-if="props.reqDesigners.total > 0 " class="table table-hover">
                                <thead>
                                    <tr class="col">
                                        <th scope="col">شناسه</th>
                                        <th scope="col">کاربر</th>
                                        <th scope="col">مدت زمان</th>
                                        <th scope="col">مبلغ پیشنهادی</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(reqDesigner,index) in props.reqDesigners.data" :key="index">
                                        <td >{{(reqDesigner.id).toLocaleString("fa-IR")}}</td>

                                        <td >
                                            <Link class="img-wrap" :href="route('profile.show',[reqDesigner.user.user_name])">
                                                <img v-if="reqDesigner.user.image" :src="$page.props.ziggy.url +'/storage/' +reqDesigner.user.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.user.name_show">
                                                <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" class="img-sm img-thumbnail" :alt="reqDesigner.user.name_show">
                                            </Link>
                                            <div class="info">
                                                <h6 class="mb-0 text-nofull"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{reqDesigner.user.name_show}}</span></span></h6>
                                            </div>
                                        </td>
                                        <td >
                                            در {{ reqDesigner.expired }} روز
                                        </td>
                                        <td >{{(reqDesigner.price).toLocaleString("fa-IR")}}</td>
                                        <td >
                                            {{ moment(reqDesigner.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td>
                                            <span v-if="reqDesigner.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                            <span v-if="reqDesigner.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                            <span v-if="reqDesigner.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                            <span v-if="reqDesigner.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                            <span v-if="reqDesigner.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                            <span v-if="reqDesigner.status == 5" class="badge badge-pill badge-soft-pink">انتخاب شده</span>
                                        </td>
                                        <td class="text-end" v-if="reqDesigner.file || props.tarahi.reqdesigner_id == null && reqDesigner.status !== 3
                                            || props.tarahi.reqdesigner_id == reqDesigner.id && reqDesigner.file == null" >
                                            <div class="dropdown">
                                                <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                <div class="dropdown-menu">
                                                    <a v-if="reqDesigner.file" class="dropdown-item" :href="route('download.edit',reqDesigner.file.id)" method="put">دانلود فایل</a>
                                                    <!-- <button v-if="props.tarahi.reqdesigner_id == null && reqDesigner.status !== 3" type="button" class="dropdown-item" data-bs-toggle="modal" :data-bs-target="'#staticBackdrop'+reqDesigner.id">واگذاری پروژه</button> -->
                                                    <button v-if="props.tarahi.reqdesigner_id == null && reqDesigner.status !== 3" class="dropdown-item" @click.prevent="submitCart(props.tarahi.id,reqDesigner.id)" >واگذاری پروژه</button>
                                                    <Link v-if="props.tarahi.reqdesigner_id == reqDesigner.id && reqDesigner.file == null" class="dropdown-item text-danger"  :href="route('reqTarahi.destroy',[props.tarahi.id])" @finish="submitTimer"   method="delete" >لغو واگذاری</Link>
                                                </div>
                                            </div>
                                        </td>
                                        <div class="modal fade " :id="'staticBackdrop'+reqDesigner.id" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">انتخاب طراح پروژه</h5>
                                                    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        آیا میخواهید پروژه خود را به مبلغ پیشنهادی {{ (reqDesigner.price).toLocaleString("fa-ir") }} ریال به طراح {{ reqDesigner.user.name_show }} بسپارید؟
                                                    </p>
                                                    <p v-if="props.tarahi.price < reqDesigner.price">
                                                        ضمنا مبلغ پیشنهادی طراح بیشتر از مبلغ پروژه شما می باشد.
                                                    </p>
                                                    <h5>توجه <span class="text-danger">*</span></h5>
                                                    <p>در نظر داشته باشید که پس از واریز مبلغ پروژه، درصورت لغو پروژه بعد از پذیرش پروژه توسط طراح،از جانب شما مبلغ <strong style="font-size: large;" v-if="props.companies">{{ (reqDesigner.price * props.companies.design_damage).toLocaleString("fa-ir") }}</strong> ریال بابت ضمانت حسن انجام کار از مبلغ بودجه پروژه کسر شده که به عنوان خسارت به طراح داده خواهد شد  و مابقی مبلغ آن به کیف پولتان واریز میگردد.این <Link :href="route('terms-conditions.index')"  >قوانین</Link> در مورد طرف مقابل(طراح)نیز صدق می کند.</p>
                                                    <p>لطفا مبلغ پروژه را از یکی طریق روشهای موجود واریز نمایید.</p>
                                                    <div class="m-2 col-6">
                                                        <div class="d-flex">
                                                            <label class="form-check-label" for="flexRadioDefault1"> کیف پول</label>
                                                            <input class="form-check-input me-auto" v-model="form.dargah" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="wallet"></div>
                                                        <div class="d-flex">
                                                            <label class="form-check-label" for="flexRadioDefault1">درگاه ملت</label>
                                                            <input class="form-check-input me-auto" v-model="form.dargah" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="behpardakht"></div>
                                                        <div class="d-flex">
                                                            <label class="form-check-label" for="flexRadioDefault1">درگاه صادرات</label>
                                                            <input class="form-check-input me-auto" v-model="form.dargah" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="sepehr">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button  class="btn btn-sm btn-primary" type="button" v-if="form.dargah == 'wallet' || form.dargah == null"
                                                        @click.prevent="submitCart(props.tarahi.id,reqDesigner.id)" data-bs-dismiss="modal"
                                                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                        تایید
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                    </button>
                                                    <form v-else-if="form.dargah == 'behpardakht'" :action="route('cart.store')" method="POST" >
                                                        <input type="hidden" name="_token" :value="token" />
                                                        <input type="hidden" name="dargah" value="behpardakht" />
                                                        <input type="hidden" name="id" :value="props.tarahi.id" />
                                                        <input type="hidden" name="name" :value="'karfarma'" />
                                                        <input type="hidden" name="reqDesigner_id" :value="reqDesigner.id" />
                                                        <button type="submit" class="btn btn-sm btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید</button>
                                                    </form>
                                                    <form v-else-if="form.dargah == 'sepehr'" :action="route('cart.store')" method="POST" >
                                                        <input type="hidden" name="_token" :value="token" />
                                                        <input type="hidden" name="dargah" value="sepehr" />
                                                        <input type="hidden" name="id" :value="props.tarahi.id" />
                                                        <input type="hidden" name="name" :value="'karfarma'" />
                                                        <input type="hidden" name="reqDesigner_id" :value="reqDesigner.id" />
                                                        <button type="submit" class="btn btn-sm btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید</button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">انصراف</button>

                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    <div class="mt-5" v-if="props.reqDesigners.total > 9">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">
                                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                v-for="link in props.reqDesigners.links" :key="link.id" >
                                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                v-html="link.label" ></Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </tbody>
                            </table>
                        </article>
                    </div>
                    </div>
                </div>
                   <p v-else>گزینه ای یافت نشد.</p>
            </section >
        <Footer :companies="props.companies" />
    </main>
</template>
