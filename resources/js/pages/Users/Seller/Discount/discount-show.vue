<script setup>
import { computed } from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { usePage } from '@inertiajs/vue3';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DatePicker from 'vue3-persian-datetime-picker';
import BreezeButton from "@/Components/Button.vue";
import BreezeGuestLayout from "@/Layouts/Guest.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({users:Object,discounts:Object,ids:Object,statuses:Object,prices:Object,notifications:Object,
time:String,companies:Object,descriptions:Object});

const form = useForm({
    expired: props.discounts.expired,
    percent:props.discounts.percent,
    id:props.discounts.id,
});

const submit = () => {
     if(form.expired == null && form.percent == null && form.id == null){
         errors.value.errors = 'لطفا یکی از موارد را انتخاب نمایید.'
    }else{
        form.put(route('discountVisitor.update',form.id));
    }
};
</script>
<template>
    <Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <div class="screen-overlay"></div>
        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="content-header">
                    <div>
                        <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                        <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                    </div>
                    <BreezeButton @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">ثبت</BreezeButton>
                </div>
                <div class="mb-4" v-if="props.discounts">
                     <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead >
                                    <tr>

                                        <th scope="col">شناسه</th>
                                        <th scope="col">منقضی</th>
                                        <th scope="col">درصد</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>

                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>{{(props.discounts.id).toLocaleString("fa-IR")}}</td>
                                        <td>
                                            <date-picker v-model="form.expired"  format="YYYY-MM-DD HH:mm:ss" display-format="dddd jDD jMMMM jYYYY mm:ss"  color="#1ABC9C"  type="datetime" ></date-picker>
                                        </td>
                                        <td>
                                            <BreezeInput type="text" class="form-control" v-model="form.percent"/>
                                        </td>
                                        <td>
                                            <!-- <date-picker v-model="props.discounts.created_at" :disabled="true"  format="YYYY-MM-DD HH:mm:ss" display-format="dddd jDD jMMMM jYYYY"  color="#1ABC9C"  type="datetime" ></date-picker> -->
                                            {{ moment(props.discounts.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td>
                                            <span v-if="props.discounts.expired > props.time" class="badge badge-pill badge-soft-success"> فعال</span>
                                            <span v-else class="badge badge-pill badge-soft-danger" >منقضی</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>گزینه ای یافت نشد.</p>
                </div>
            </section>
        <Footer :companies="props.companies" />
    </main>
</template>

