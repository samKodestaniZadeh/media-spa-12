<script setup>

import { computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({product:Object,users:Object,cartPrice:Object,cartCount:Object,
cartDiscount:Object,cartCoupon:Object,cartTotal:Object,flash:String});

const form =  useForm({id:props.product.id,file:props.product.file,version:null});

const submit = () =>{

    if( form.file == null && form.version == null)
    {
        errors.value.errors = 'لطفا مورد الزامی فرم را پر نمایید.'
    }
    else
    {
        form.post(route('product.update'))
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
                        <div>
                            <h2 class="content-title">بروزرسانی محصول</h2>
                            <p>اطلاعات کامل درباره کسب و کار شما در اینجا</p>
                        </div>
                        <div>
                            <!-- <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">ذخیره در پیش نویس</button> -->
                            <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">ذخیره</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>نسخه محصول <span class="text-danger">*</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="input-upload">

                                <input v-model.lazy="form.version" placeholder="اینجا تایپ کنید" type="text" class="form-control" />

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>فایل <span class="text-danger">*</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="input-upload">
                                <!-- <img src="assets/imgs/theme/upload.svg" alt="" /> -->
                                <!-- <input  class="form-control" type="file" /> -->
                                <input  class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file"  accept="zip/*"/>
                                <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                    {{ form.progress.percentage }}%
                                </progress>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <Footer/>
</main>
</template>
