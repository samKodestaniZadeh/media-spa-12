<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import TicketReply from '@/Pages/Users/Seller/Support/support-reply.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({auth:Object,users:Object,tickets:Object,replies:Object,notification:Object
    ,companies:Object});

const form = useForm({
    parent_id:props.tickets.id,
    menu : props.tickets.menu,
    recepiant:props.tickets.recepiant,
    subject:props.tickets.subject,
    file:null,
    text:null,
    user:null,
    destination:null,
});

const submit = (id) => {
    form.destination = id
    if(form.text){
        form.post(route('supportSeller.store'))
    }
    else
    {
        errors.value.errors = 'لطفا پس از برسی موارد الزامی مجدد فرم را ارسال نمایید'
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
                <form >
                <div class="content-header">
                    <h2 class="content-title">جزییات پیام</h2>
                </div>
                <div class="card mb-4">
                        <div class="card-body">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4>تاریخچه مکالمات</h4>
                                </div>
                                <div class="col-sm-12 mt-3">
                                        <div class="d-flex me-2" >
                                            <img v-if="props.tickets.userimage[0] && props.tickets.userimage[0].status == 4" style="height:40px" class="img-xs rounded-circle" :src="'/../storage/'+props.tickets.userimage[0].url" alt="User" />
                                            <img v-else style="height:40px" class="img-xs rounded-circle" src="/../storage/files/default-user.png" alt="User" />
                                            <span class="font-xs text-muted me-1">{{props.tickets.user.user_name}}|
                                                {{ moment(props.tickets.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                            </span>
                                        </div>
                                        <div class="mb-4 me-5">
                                            <p>{{props.tickets.text}} <a v-if="props.tickets.user.id !== props.users.id" :href="'#CollapseExample'+props.tickets.id" class="reply" data-bs-toggle="collapse" aria-expanded="false" :aria-controls="'CollapseExample'+props.tickets.id">پاسخ</a></p>
                                        </div>
                                        <div class="submenu text-a-r collapse " :id="'CollapseExample'+props.tickets.id">
                                            <div class="card-body">
                                                <div class="mb-4">
                                                    <label class="form-label">پاسخ:</label>
                                                    <textarea v-model="form.text" name="text" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <div class="card-body" >
                                                <label class="form-label"> آپلودفایل<span class="text-danger me-1"></span></label>
                                                <div class="input-upload">
                                                    <input name="file" class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file" />
                                                    <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                    {{ form.progress.percentage }}%
                                                    </progress>
                                                </div>
                                            </div>
                                            <button  @click.prevent="submit(props.tickets.user.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary me-4">ثبت</button>
                                        </div>
                                        <div class="mb-4 me-5" v-if="props.tickets.file">
                                         <p> جهت مشاهده فایل های ضمیه را دانلود نمایید.</p>
                                            <a :href="$page.props.ziggy.url+'/storage/'+props.tickets.file.url" class="btn btn-sm btn-primary" >دانلود</a>
                                        </div>
                                     <hr>
                                    </div>
                                    <TicketReply  :tickets="props.tickets" :replies="props.replies" :users="props.users" :auth="props.auth" />
                                </div>
                        </div>
                </div>
            </form>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
