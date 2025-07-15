<script setup>

import { computed, ref } from 'vue';

import { Head, Link, useForm, usePage} from '@inertiajs/vue3';
import swal from 'sweetalert2';
import {Countdown} from 'vue3-flip-countdown';

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    product: Object, auth: Object, cart: Object, time: String, count: Number,
    alert: Object, flash: String, product_count: Number, product_order: Number,
    companies: Object,product_averageRating:String,product_usersRated:Number,
    users:Object,product_timesRated : Number
});
const emit = defineEmits(['EventSubmitFavorite','EventSubmitCart']);

const submitFavorite = (id) => {
    if(props.auth.user)
    {
        emit('EventSubmitFavorite',id)
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
            title:'لطفا وارد حساب کاربری خود شوید.',
            icon:'error'
        })
    }

};

const submitCart = (id) => {
   emit('EventSubmitCart',id)
}



const favorite = ref([]);
if (props.users) {
    props.users.favorites.forEach(element => {
        if (element.favoritable_id == props.product.id && element.favoritable_type == 'App\\Models\\Product') {
            favorite.value = element
        }
    });
}

</script>
<template>
    <div class="d-flex mt-5" v-if="props.product.discount">
            <div class="bd-highlight">
                <h5 class="card-title mt-3">پیشنهاد شگفت‌انگیز</h5>
            </div>
            <div class="ml-auto bd-highlight pl-2">
                <p class="card-title" >
                    <Countdown class="ltr" v-if="props.product.discount " :deadline="props.product.discount.expired" :flipAnimation="false" :labels="{days: 'روز',hours: 'ساعت',minutes: 'دقیقه',seconds: 'ثانیه'}" />
                    <Countdown class="ltr" v-else deadline="2023-07-14 18:38:38.000000" :flipAnimation="false" :labels="{days: 'روز',hours: 'ساعت',minutes: 'دقیقه',seconds: 'ثانیه'}" />
                </p>
            </div>
        </div>
    <div class="row" :class="props.product.discount ? '' :'mt-5'">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">اطلاعات محصول:</div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">نام </h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <p class="card-title">{{ props.product.name }}</p>
                    </div>
                </div>
                <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">نام انگلیسی</h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <p class="card-title">{{ props.product.name_en }}</p>
                    </div>
                </div>
                <!-- <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">بازدید</h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <p class="card-title">{{ (props.product_count).toLocaleString("fa-IR") }}</p>
                    </div>
                </div> -->
                <div class="d-flex bd-highligh mt-3" v-if="props.product_order > 0">
                    <div class="bd-highlight">
                        <h5 class="card-title ">فروش</h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <p class="card-title">{{ (props.product_order).toLocaleString("fa-IR") }}</p>
                    </div>
                </div>
                <div class="d-flex bd-highligh mt-3">
                    <div class="bd-highlight">
                        <h5 class="card-title ">مبلغ</h5>
                    </div>
                    <div class="ml-auto bd-highlight pl-2">
                        <div v-if="props.product.discount">
                            <p class="text-danger ">
                                <s>{{ (props.product.price).toLocaleString("fa-IR") }}</s>
                                <span class="badge text-success ml-2">{{ props.product.discount.percent }}%</span>
                            </p>
                            <p class="card-title ml-5">{{ (
                                props.product.price - (props.product.price * product.discount.percent /
                                    100)).toLocaleString("fa-IR") }}ریال
                            </p>
                        </div>
                        <div v-else class="ml-auto bd-highlight pl-2">
                            <p class="card-title">{{ (props.product.price).toLocaleString("fa-IR") }} ریال</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex bd-highligh mt-3">
                    <div class=" m-auto bd-highlight">
                        <!-- <iframe :src="props.product.demo_link">پیش نمایش</iframe> -->
                        <Link class="color-succss"
                            :href="route('website_templates.create', ['product']) + props.product.slug">
                        پیش نمایش</Link>
                    </div>
                    <div class="m-auto bd-highlight" v-if="favorite && favorite.favoritable_id == props.product.id">
                        <a href="#" @click.prevent="submitFavorite(props.product.id)" class="text-danger"
                            title="حذف از علاقه مندی">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                        </svg>
                        </a>
                    </div>
                    <div class="m-auto bd-highlight" v-else>
                        <a href="#" @click.prevent="submitFavorite(props.product.id)" class="color-succss"
                            title="افزودن به علاقه مندی">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z" />
                        </svg>
                        </a>
                    </div>

                    <div class="m-auto bd-highlight">
                        <Link  href="#" @click.prevent="submitCart(props.product.id)" class="color-succss" data-bs-toggle="tooltip"
                            data-bs-placement="bottom" title="افزودن">
                        <img style="margin-top: 13px;"
                            src="https://img.icons8.com/material-outlined/24/3ccadc/shopping-basket-add.png" />
                        </Link>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="list-inline team-social social-icon my-4">
                        <div class="fa-12px d-flex">
                                <span  v-if="props.product_timesRated > 0 " >{{ props.product_timesRated}} رای</span>
                                <span  v-else >بدون رای</span>
                                <!-- <star-rating class="ml-auto" v-if="props.product_averageRating > 0"   v-bind:star-size="10" v-bind:max-rating="7" v-bind:read-only="true" v-model:rating="props.product_averageRating" ></star-rating>
                                <star-rating class="ml-auto" v-else  v-bind:star-size="10"  v-bind:max-rating="7" v-bind:read-only="true" v-model:rating="props.product_averageRating" ></star-rating> -->
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <span v-if="props.product.status == 5" style=" transform: rotate(40deg); margin-top: 22px; margin-right: -6px;"  class="category position-absolute badge badge-pill badge-warning">توقف فروش</span>
            <img v-if="props.product.image && props.product.image.status == 4 || props.product.image.status == 5" :src="'/./storage/' + props.product.image.url"
                class="card-img" :alt="props.product.name">
            <img v-else :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" class="card-img"
                :alt="props.companies.name">
        </div>
    </div>

</template>

