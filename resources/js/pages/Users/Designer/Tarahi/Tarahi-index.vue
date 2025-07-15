<script setup>

import { computed,ref} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import StarRating from 'vue-star-rating';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    tarahis:Object,users:Object,cart:Object,wallet:Number,notifications:Object,names:Object,
    ids:Object,statuses:Object,notifications: Object,companies:Object,descriptions:Object,
    menus:Object,orders:Object,time:String,token:String,alert:Object,
});

const form = useForm({
    name: null,
    status:null,
    id:null,
    dargah:null,
    menu : null,
    recepiant:null,
    subject:null,
    text:null,
    product:null,
    designer:null,
    file: null,
    price:null,
    rate:null,
    discount:null,
    model:null,
});
const submitTimer = ()=>{
    Inertia.visit(route('tarahiDesigner.index'),{ only: [props.alert,errors.value,hasErrors.value] })
}

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
            title: props.alert.text,
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
        title: errors.value.name || errors.value.id || errors.value.text || errors.value.price || errors.value.rate,
        icon:'error',
    })

}
const tarahi = ref();
const submitTarahi = (id) => {
    tarahi.value = id
}
const  reqDesigner = ref();
const submitFinalTarahi = (id) => {
    reqDesigner.value = id.tarahi_register
    tarahi.value = id
}
const submitCart =(id) => {

    // form.id = tarahi.value.id,
    form.id = id,
    form.name = 'designer',
    form.model = 'App\\Models\\ReqDesigner',
    form.post(route('cart.store'),{
        onFinish:()=> submitTimer()
    })

}

const submitDestroy = () => {
    form.id = tarahi.value
     if( form.id)
     {

        //  form.post(route('support.store'))
         form.delete(route('tarahiDesigner.destroy',form.id),{
                onFinish:()=> submitTimer()
            });
    }
    else
    {
        let text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }

};
const storeFinal = (id) => {
    form.id = id
    form.name = 'designer'
    form.designer = id.tarahi_register
};
const submitFinal = () => {

    if(form.id && form.price && form.rate && form.text)
    {
        form.post(route('tarahiDesigner.store'),{
            onFinish:()=> submitTimer()
        })
    }
    else
    {
        let text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
};

const submitReport = (designer) => {
    form.designer = designer
    if( form.recepiant && form.subject && form.text && form.designer)
     {
         form.post(route('tarahiDesigner.store'),{
            onFinish:()=> submitTimer()
         });
    }
    else
    {
        let text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
};

const subTarahi = ref();

props.tarahis.data.forEach(element => {
    if(element.tarahi_register && element.tarahi_register.reqdesigner_id == element.id)
    {
        subTarahi.value = element.tarahi_register
    }

});

const menus =[];

if (props.menus) {

    props.menus.forEach(element => {
        if (element.section == 'tickets') {
            menus.push(element)
        }
    });
}


const menu = ref([]);
const sections = ref([]);
const projects = ref(props.tarahis.data);
const group = () => {
    form.subject = null,
    menus.forEach(element => {
        if(form.recepiant == element && element.children !== null) {
            element.children.forEach( childelement =>{

                if(childelement.routes.length > 0)
                {
                    menu.value.push(childelement)
                }
            })

        }
    });
};

const category = (reqDesigner) => {
    projects.value.forEach(element => {

        if (reqDesigner == element && sections.value.length == 0) {

            sections.value.push(element.tarahi_register)

        }

    });

};

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
                <div class="bg-white mb-4"  v-if="props.tarahis.total > 0 && subTarahi">
                    <div class="card-body">
                        <div class="table-responsive">
                            <article class="itemlist">
                                <table v-if="props.tarahis.total > 0 " class="table table-hover">
                                    <thead>
                                        <tr class="col">
                                            <th scope="col">شناسه</th>
                                            <th scope="col">پروژه</th>
                                            <th scope="col">مبلغ</th>
                                            <th scope="col">تاریخ</th>
                                            <th scope="col">انقضا</th>
                                            <th scope="col">بازدید</th>
                                            <th scope="col">وضعیت</th>
                                            <th scope="col">عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(reqDesigner,index) in props.tarahis.data" :key="index">
                                            <tr v-if="reqDesigner.tarahi_register" >
                                                <td >{{(reqDesigner.tarahi_register.id).toLocaleString("fa-IR")}}</td>
                                                <td v-if="reqDesigner.tarahi_register.status == 4 || reqDesigner.tarahi_register.status == 6">
                                                    <Link  :href="route('website_design.show',[reqDesigner.tarahi_register.slug])">
                                                        <div class="left">
                                                            <img v-if="reqDesigner.tarahi_register.image" :src="$page.props.ziggy.url +'/storage/' +reqDesigner.tarahi_register.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.tarahi_register.title">
                                                            <img v-else-if="props.companies" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.tarahi_register.title">
                                                        </div>
                                                        <div class="info">
                                                            <h6 class="mb-0 text-nofull"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{reqDesigner.tarahi_register.title}}</span></span></h6>
                                                        </div>
                                                    </Link>
                                                </td>
                                                <td v-else>
                                                    <Link  href="#">
                                                        <div class="left">
                                                            <img v-if="reqDesigner.tarahi_register.image" :src="$page.props.ziggy.url +'/storage/' +reqDesigner.tarahi_register.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.tarahi_register.title">
                                                            <img v-else-if="props.companies" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="img-sm img-thumbnail" :alt="reqDesigner.tarahi_register.title">
                                                        </div>
                                                        <div class="info">
                                                            <h6 class="mb-0 text-nofull"><span style="vertical-align: inherit;"><span style="vertical-align: inherit;">{{reqDesigner.tarahi_register.title}}</span></span></h6>
                                                        </div>
                                                    </Link>
                                                </td>
                                                <td>{{(reqDesigner.tarahi_register.price_block).toLocaleString("fa-IR")}}</td>
                                                <td>
                                                    {{ moment(reqDesigner.tarahi_register.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                </td>
                                                <td v-if="reqDesigner.tarahi_register.status > 4">
                                                    {{ moment(reqDesigner.tarahi_register.date).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                </td>
                                                <td v-else>نامشخص</td>
                                                <td>{{ reqDesigner.tarahi_register.views_count }}</td>
                                                <td>
                                                    <span v-if="reqDesigner.tarahi_register.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 2"  class="badge badge-pill badge-soft-secondary">در حال واگذاری</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 3" class="badge badge-pill badge-soft-danger"> منقضی</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 4" class="badge badge-pill badge-soft-success">منتشر</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 5" class="badge badge-pill badge-soft-pink">واگذار شده</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 6" class="badge badge-pill badge-soft-dark">انجام شده</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 7" class="badge badge-pill badge-soft-info">ثبت نظر</span>
                                                    <span v-if="reqDesigner.tarahi_register.status == 8" class="badge badge-pill badge-soft-warning">بارگذاری فایل</span>
                                                </td>

                                                <td class="text-end" v-if="reqDesigner.tarahi_register.status == 2  && reqDesigner.status !== 3 || reqDesigner.tarahi_register.status == 5 && reqDesigner.status !== 3  || reqDesigner.tarahi_register.status == 7 && reqDesigner.status !== 3 || reqDesigner.tarahi_register.status == 8 && reqDesigner.status !== 3
                                                    || reqDesigner.tarahi_register.status == 6 && reqDesigner.status !== 3">
                                                    <div class="dropdown">
                                                        <a href="" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                        <div class="dropdown-menu">


                                                            <Link href="" v-if="reqDesigner.tarahi_register.status == 4 || reqDesigner.tarahi_register.status == 6" class="dropdown-item" :href="route('website_design.show',[reqDesigner.tarahi_register.slug])">نمایش جزئیات</Link>
                                                            <Link  href="" v-if="reqDesigner.tarahi_register.status == 5 && moment(reqDesigner.tarahi_register.date).locale('fa', fa).format('jYYYY/jM/jD HH:mm') > moment(props.time).locale('fa', fa).format('jYYYY/jM/jD HH:mm') ||
                                                                    reqDesigner.tarahi_register.status == 8 &&  moment(reqDesigner.tarahi_register.date).locale('fa', fa).format('jYYYY/jM/jD HH:mm') > moment(props.time).locale('fa', fa).format('jYYYY/jM/jD HH:mm') "
                                                                    class="dropdown-item" :href="route('tarahiDesigner.show',[reqDesigner.tarahi_register.slug])" >بارگذاری فایل</Link>
                                                            <button v-if="reqDesigner.tarahi_register.status == 2" type="button" class="dropdown-item"   @click="submitCart(reqDesigner.id)">پرداخت</button>
                                                            <button v-if="reqDesigner.tarahi_register.status == 7" type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" @click="storeFinal(reqDesigner)" >اتمام پروژه</button>
                                                            <button v-if="reqDesigner.tarahi_register.status == 5 || reqDesigner.tarahi_register.status == 8" type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop2" @click="submitTarahi(reqDesigner.tarahi_register)">لغو</button>
                                                            <Link href="" v-if="reqDesigner.tarahi_register.status == 6" class="dropdown-item" :href="route('tarahiDesigner.edit',[reqDesigner.tarahi_register.id])">فروش</Link>
                                                        </div>
                                                    </div>
                                                </td>
                                                <div class="modal fade " id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">پذیرش پروژه</h5>
                                                            <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                                آیا میخواهید پروژه <strong style="font-size: large;" v-if="tarahi">{{ tarahi.title }}</strong>  را با مبلغ <strong style="font-size: large;" v-if="tarahi">{{ (tarahi.total).toLocaleString("fa-ir") }}</strong> ریال بپذیرید؟
                                                            </p>
                                                            <h5>توجه <span class="text-danger">*</span></h5>
                                                            <p>قبل از پذیرش پروژه فوق می بایست مبلغ <strong style="font-size: large;" v-if="tarahi">{{ (tarahi.total * props.companies.design_damage).toLocaleString("fa-ir") }}</strong> ریال بابت ضمانت اجرای پروژه واریز نمایید،
                                                                درنظر داشته باشید چنانچه پروژه فوق را با موفقیت به پایان برسانید.مبلغ ضمانت به حساب
                                                                کیف پول شما باز گردانده خواهد شد و در صورت لغو پروژه قبل از اتمام زمان آن، از جانب شما، مبلغ ضمانت به عنوان خسارت
                                                                به کارفرما پرداخت می شود که به هیچ عنوان قابل بازگشت نخواهد بود.این <Link :href="route('terms-conditions.index')"  >قوانین</Link> در
                                                                مورد طرف مقابل(کارفرما) نیز صادق می باشد.
                                                            </p>
                                                            <p>لطفا مبلغ از طریق روشهای موجود واریز نمایید.</p>
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
                                                            <button class="btn btn-sm btn-primary" type="button" v-if="form.dargah == 'wallet' || form.dargah == null"
                                                            @click.prevent="submitCart" data-bs-dismiss="modal"
                                                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                                تایید
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                            </button>
                                                            <form v-else-if="form.dargah == 'behpardakht'" :action="route('cart.store')" method="POST" >
                                                                <input type="hidden" name="_token" :value="token" />
                                                                <input type="hidden" name="dargah" value="behpardakht" />
                                                                <input type="hidden" name="id" :value="reqDesigner.tarahi_register.id" />
                                                                <input type="hidden" name="name" :value="'designer'" />
                                                                <input type="hidden" name="reqDesigner_id" :value="reqDesigner.id" />
                                                                <button type="submit" class="btn btn-sm btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید</button>
                                                            </form>
                                                            <form v-else-if="form.dargah == 'sepehr'" :action="route('cart.store')" method="POST" >
                                                                <input type="hidden" name="_token" :value="token" />
                                                                <input type="hidden" name="dargah" value="sepehr" />
                                                                <input type="hidden" name="id" :value="reqDesigner.tarahi_register.id" />
                                                                <input type="hidden" name="name" :value="'designer'" />
                                                                <input type="hidden" name="reqDesigner_id" :value="reqDesigner.id" />
                                                                <button type="submit" class="btn btn-sm btn-primary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید</button>
                                                            </form>
                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">انصراف</button>

                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade " id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel1">انصراف پروژه</h5>
                                                            <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                                آیا میخواهید از انجام پروژه <strong style="font-size: large;" v-if="tarahi" >{{tarahi.title}}</strong> انصراف دهید؟
                                                            </p>
                                                            <p>
                                                                <p>
                                                                    در صورت تایید، مبلغ <strong style="font-size: large;" v-if="tarahi" >{{ (tarahi.total*companies.design_damage).toLocaleString("fa-ir") }}</strong> ریال
                                                                    ضمانت اجرای پروژه شما بابت خسارت به حساب کارفرمای پروژه پرداخت خواهد شد که این مبلغ تحت هیچ عنوان قابل بازگشت نخواهد بود.
                                                                </p>
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <Link class="btn btn-sm btn-primary" data-bs-dismiss="modal" href=""
                                                            @click.pervent="submitDestroy" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                            </Link>
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">انصراف</button>

                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade " id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel2" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel2">اتمام پروژه</h5>
                                                            <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                                کارفرمای پروژه <strong style="font-size: large;" v-if="reqDesigner">{{reqDesigner.tarahi_register.title}}</strong> ،اتمام پروژه را اعلام نموده است.
                                                            </p>
                                                            <p>
                                                            لازم به ذکراست پس از ثبت اتمام پروژه تحت هیچ عنوان قابل بازگشت نبوده و جای هیچ گونه اعتراضی نسبت عملکرد کارفرما پروژه نمی باشد.
                                                            درصورت داشتن اعتراض، فرم گزارش را ارسال نمایید و از اعلام اتمام پروژه خود داری نمایید.

                                                            </p>
                                                            <p>
                                                                لطفا میزان رضایت و نظر خود را از کارفرما پروژه اعلام نمایید.
                                                            </p>
                                                            <div class="cart-bod">
                                                                <div class="mt-4">
                                                                    <label class="form-label">مبلغ پروژه <span class="text-danger">*</span></label>
                                                                    <div class="row gx-2">
                                                                        <input v-model.lazy="form.price" placeholder="مبلغ پروژه یا مبلغ توافق با کارفرما را وارد نمایید:مثال 100000 ریال" type="text" class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="mt-4">
                                                                    <label class="form-label">درصد تخفیف </label>
                                                                    <div class="row gx-2">
                                                                        <input v-model.lazy="form.discount" placeholder="در صورت نیاز میتوانید به کارفرما تخفیف دهید:مثال 10" type="text" class="form-control" />
                                                                    </div>
                                                                </div>
                                                                <div class="mt-4">
                                                                    <label class="form-label">میزان رای <span class="text-danger">*</span></label>
                                                                    <div class="row gx-2">
                                                                        <star-rating class="ml-auto" v-bind:star-size="30"  v-bind:max-rating="7"  v-model:rating="form.rate" ></star-rating>
                                                                        <!-- <input v-model.lazy="form.rate" placeholder="میزان رای خود را از 1 تا 7 وارد نمایید.مثال:4" type="text" class="form-control" /> -->
                                                                    </div>
                                                                </div>
                                                                <div class="mt-4">
                                                                    <label class="form-label">نظر <span class="text-danger">*</span></label>
                                                                    <textarea  v-model.lazy="form.text" placeholder="لطفا نظر خود را راجع به کارفرمای پروژه بصورت یک جمله کوتاه بیان کنید." class="form-control" rows="4"></textarea>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <Link class="btn btn-sm btn-primary" data-bs-dismiss="modal" href=""
                                                            @click.pervent="submitFinal" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >تایید
                                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                            </Link>
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">انصراف</button>

                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <div class="mt-5" v-if="props.tarahis.total > 9">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-start">
                                            <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                            v-for="link in props.tarahis.links" :key="link.id" >
                                            <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                            v-html="link.label" ></Link>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <p v-else>گزینه ای یافت نشد.</p>
            </section >
        <Footer :companies="props.companies" />
    </main>
</template>
