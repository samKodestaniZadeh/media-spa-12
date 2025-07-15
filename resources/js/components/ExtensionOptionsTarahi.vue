<script setup>

import { computed,ref } from 'vue';
import { useForm,usePage } from '@inertiajs/vue3';
import swal from 'sweetalert2';
import {Countdown} from 'vue3-flip-countdown';
import StarRating from 'vue-star-rating';

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({tarahi:Object,auth:Object,cart:Object,time:String,count:Number||String,menus:Object,
    alert:Object,flash:String,tarahi_count:Number||String,tarahi_order:Number||String,companies:Object,users:Object,
    tarahi_averageRating:String,tarahi_usersRated:Number,tarahi_averageRating : Number,
    tarahi_timesRated : Number,companies2: Object
});

const form = useForm({
    id:null,
    name:null,
    expired: null,
    price:null,
    tarahi_id:null,
});

const emit = defineEmits(['EventSubmitFavorite','EventSubmit']);


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

const submitFavorite = (id) => {
    if (props.auth.user)
    {
        emit('EventSubmitFavorite',id)
    }
    else
    {
        let text
        text = 'لطفا وارد حساب کاربری خود شوید.'
        validate(text)
    }

};

const favorite = ref([]);
if (props.users) {
    props.users.favorites.forEach(element => {
        if (element.favoritable_id == props.tarahi.id && element.favoritable_type == 'App\\Models\\Tarahi') {
            favorite.value = element
        }
    });
}

const submit = () => {

    if (props.auth.user)
    {
        emit('EventSubmit',form.expired,form.price)
    }
    else
    {
        let text
        text = 'لطفا وارد حساب کاربری خود شوید.'
        validate(text)
    }
};


</script>
<template>
    <div class="d-flex  mt-5">
        <div class="bd-highlight">
            <h5 class="card-title mt-3">فرصت ارسال پیشنهاد</h5>
        </div>
        <div class="ml-auto bd-highlight pl-2">
            <p class="card-title" >
                <Countdown class="ltr" v-if="props.tarahi.status == 4 " :deadline="props.tarahi.expired_at" :flipAnimation="false" :labels="{days: 'روز',hours: 'ساعت',minutes: 'دقیقه',seconds: 'ثانیه'}" />
                <Countdown class="ltr" v-else deadline="2023-07-14 18:38:38.000000" :flipAnimation="false" :labels="{days: 'روز',hours: 'ساعت',minutes: 'دقیقه',seconds: 'ثانیه'}" />
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">اطلاعات پروژه:</div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">عنوان</h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <p class="card-title" >{{props.tarahi.title}}</p>
                    </div>
                </div>
                <!-- <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">بازدید</h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <p class="card-title" >{{(props.tarahi_count).toLocaleString("fa-IR")}}</p>
                    </div>
                </div> -->
                <div class="d-flex bd-highligh mt-3" v-if="props.tarahi_order > 0 ">
                    <div class="bd-highlight">
                        <h5 class="card-title ">پیشنهاد</h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <p class="card-title" >{{(props.tarahi_order).toLocaleString("fa-IR")}}</p>
                    </div>
                </div>
                <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">مبلغ</h5>
                    </div>
                    <!-- <div class="ml-auto bd-highlight">

                        <div  class="ml-auto bd-highlight pl-2">
                            <p class="card-title">{{(props.tarahi.price).toLocaleString("fa-IR")}} ریال</p>
                        </div>
                    </div> -->
                    <div class="ml-auto bd-highlight pl-2">
                        <div v-if="props.tarahi.discount">
                            <p class="text-danger ">
                                <s v-if="props.tarahi.price">{{ (props.tarahi.price).toLocaleString("fa-IR") }}</s>
                                <s v-else-if="props.tarahi.price_block">{{ (props.tarahi.price_block).toLocaleString("fa-IR") }}</s>
                                <span class="badge text-success ml-2">{{ props.tarahi.discount.percent }}%</span>
                            </p>
                            <p class="card-title ml-5" v-if="props.tarahi.price">{{ (
                                props.tarahi.price - (props.tarahi.price * props.tarahi.discount.percent /
                                    100)).toLocaleString("fa-IR") }}ریال
                            </p>
                            <p class="card-title ml-5" v-else-if="props.tarahi.price_block">{{ (
                                props.tarahi.price_block - (props.tarahi.price_block * props.tarahi.discount.percent /
                                    100)).toLocaleString("fa-IR") }}ریال
                            </p>
                        </div>
                        <div v-else class="ml-auto bd-highlight pl-2">
                            <p class="card-title" v-if="props.tarahi.price !== null">{{ (props.tarahi.price).toLocaleString("fa-IR") }} ریال</p>
                            <p class="card-title" v-else-if="props.tarahi.price_block !== null">{{ (props.tarahi.price_block).toLocaleString("fa-IR") }} ریال</p>
                            <p class="card-title" v-else>تعیین نشده</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">ضمانت</h5>
                    </div>
                    <div class="ml-auto bd-highlight">

                        <div  class="ml-auto bd-highlight">
                            <p class="card-title" v-if="props.companies && props.tarahi && props.tarahi.price*props.companies.design_damage > 0">{{(props.tarahi.price*props.companies.design_damage).toLocaleString("fa-IR")}} ریال</p>
                            <p class="card-title" v-else-if="props.companies && props.tarahi && props.tarahi.price_block*props.companies.design_damage > 0">{{(props.tarahi.price_block*props.companies.design_damage).toLocaleString("fa-IR")}} ریال</p>
                            <p class="card-title" v-else>تعیین نشده</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex bd-highligh mt-3">
                    <div class="m-auto bd-highlight" v-if="favorite && favorite.favoritable_id == props.tarahi.id" >
                        <a @click.prevent="submitFavorite(props.tarahi.id)" href="#" class="text-danger" title="حذف از علاقه مندی">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                            </svg>
                        </a>
                    </div>
                    <div class="m-auto bd-highlight" v-else>
                        <a @click.prevent="submitFavorite(props.tarahi.id)" href="#" class="color-succss"  title="افزودن به علاقه مندی">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                            </svg>
                        </a>
                    </div>
                    <div class="m-auto bd-highlight">
                        <a v-if="props.auth.user" href="#" type="button" class="color-succss" data-toggle="modal" data-target="#exampleModal" title="ارسال پیشنهاد به این پروژه">
                            <img style="margin-top: 13px;" src="https://img.icons8.com/material-outlined/24/3ccadc/shopping-basket-add.png" data-bs-toggle="modal" data-bs-target="#staticBackdrop1" />
                        </a>

                        <a v-else  @click.prevent="submit" href="#" class="color-succss"   data-bs-toggle="tooltip" data-bs-placement="bottom" title="ارسال پیشنهاد به این پروژه">
                            <img style="margin-top: 13px;" src="https://img.icons8.com/material-outlined/24/3ccadc/shopping-basket-add.png"/>
                        </a>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="list-inline team-social social-icon my-4">
                        <div class="fa-12px d-flex">
                                <span  v-if="props.tarahi_timesRated > 0 " >{{ props.tarahi_timesRated}} رای</span>
                                <span  v-else >بدون رای</span>
                                <star-rating class="ml-auto" v-if="props.tarahi_averageRating > 0"   v-bind:star-size="10" v-bind:max-rating="7" v-bind:read-only="true" v-model:rating="props.tarahi_averageRating" ></star-rating>
                                <star-rating class="ml-auto" v-else  v-bind:star-size="10"  v-bind:max-rating="7" v-bind:read-only="true" v-model:rating="props.tarahi_averageRating" ></star-rating>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <span style=" transform: rotate(40deg); margin-top: 15px; margin-right: -5px;" v-if="tarahi.status == 4" class="category position-absolute badge badge-pill badge-primary">پروژه باز</span>
                <span style=" transform: rotate(40deg); margin-top: 25px; margin-right: -10px;" v-if="tarahi.status == 3" class="category position-absolute badge badge-pill badge-danger"> پروژه منقضی شد</span>
                <span style=" transform: rotate(40deg); margin-top: 25px; margin-right: -10px;" v-if="tarahi.status == 6" class="category position-absolute badge badge-pill badge-success">پروژه انجام شد</span>
                <img v-if="props.tarahi.image && props.tarahi.image.status == 4" :src="'/./storage/'+props.tarahi.image.url" class="card-img" :alt="props.tarahi.name">
                <img v-else-if="props.companies && props.companies.image" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="card-img" :alt="props.companies.name">
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ارسال پیشنهاد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex">
                    <p>آیا میخواهید به پروژه
                    <strong style="font-size: 18px;" v-if="props.tarahi">{{props.tarahi.title}}</strong>
                     پیشنهاد ارسال نمایید؟
                     توجه داشته باشید در صورت پذیرش پیشنهاد شما از طرف کارفرما می بایست مبلغ
                     <strong style="font-size: 18px;" v-if="props.companies2 && props.tarahi && props.tarahi.price ">
                        {{ (props.tarahi.price*props.companies2.design_damage).toLocaleString("fa-IR") }}
                    </strong>
                    <strong style="font-size: 18px;" v-else>
                        {{ (form.price*props.companies2.design_damage).toLocaleString("fa-IR") }}
                    </strong>
                       ریال بابت ضمانت اجرای پروژه واریز نمایید؛
                        در صورت موفقیت اتمام پروژه مبلغ ضمانت پروژه به شما عودت داده خواهد شد.
                        ضمنا میتوانید مبلغ پیشنهادی خود را به کارفرما اعلام نمایید،
                        درصورت پذیرش کارفرما مبلغ ضمانت پروژه بسته به مبلغ جدید تغییر خواهد کرد.
                    </p>
                </div>
                <div class="col-lg-12">
                    <div class="bg-white">
                        <div class="card-body">
                            <div class="row gx-2">
                                <div class="col-lg-12">
                                    <form @submit.prevent="" >
                                        <label class="form-label">تحویل پروژه<span class="text-danger">*</span></label>
                                        <input type="text" v-model="form.expired" class="form-control mt-2" placeholder="تعداد روز را وارد نمایید مثال : 10">
                                    </form>
                                </div>
                                <div class="col-lg-12">
                                    <form >
                                        <label class="form-label">مبلغ پیشنهادی</label>
                                        <input type="text" v-model="form.price" class="form-control mt-2" placeholder="مبلغ پیشنهادی را وارد نمایید مثال : 100000ریال">
                                    </form>
                                    <label class="form-label mt-2" v-if="form.price > 0">ضمانت اجرایی مبلغ پیشنهادی</label>
                                    <p v-if="props.companies && form.price > 0" class="mt-2">
                                        {{ (form.price*props.companies2.design_damage).toLocaleString("fa-IR") }}ریال
                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" href="#" class="btn btn-primary" data-dismiss="modal" @click.prevent="submit"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing" >
                    <span v-if="form.processing">پردازش...</span>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                    <span v-else >ثبت</span>
                </button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">انصراف</button>

            </div>
            </div>
        </div>
    </div>
</template>

