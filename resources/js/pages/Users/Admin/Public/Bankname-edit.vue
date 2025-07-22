<script setup>

import { computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({users:Object,cartPrice:Number,cartCount:Number,cartDiscount:Number,
    cartCoupon:Number,cartTotal:Number,notifications:Object,banknames:Object,companies:Object,descriptions:Object});

const form =  useForm({id:props.banknames.id,name:props.banknames.name,status:props.banknames.status});

const submit = () =>{

    if(form.name == null,form.status == null)
    {
        errors.value.errors = 'لطفا پس از برسی موارد الزامی مجدد فرم را ارسال نمایید'
    }
    else
    {
        form.post(route('bankname.store'))
    }
};
</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
        <form >
            <div class="row">
                <div class="col-12">
                    <div class="content-header">
                        <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                        <div class="d-flex me-auto">
                            <select v-model.lazy="form.status" class="form-select">
                                    <option value="0">ثبت</option>
                                    <option value="1">انتظار</option>
                                    <option value="2">بررسی</option>
                                    <option value="3"> منقضی</option>
                                    <option value="4">منتشر</option>
                            </select>
                            <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">ویرایش</button>
                        </div>
                    </div>
                    <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                </div>
                <div class="col-lg-6">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.name" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <Footer :companies="props.companies" />
</main>
</template>
