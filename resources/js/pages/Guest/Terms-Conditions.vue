<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import Header from './Header2.vue';
import Footer from './Footer2.vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed,ref} from 'vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import swal from 'sweetalert2';
import DatePicker from 'vue3-persian-datetime-picker';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({auth:Object,pages:Object,alert:Object,flash:String,tarahis:Object,cartTarahis:Object,
cartCount:Number,cartPrice:Number,cartDiscount:Number,cartCoupon:Number,cartTotal:Number,companies:Object,menus: Object,menu: Object});

const form =  useForm({name:null,lasst_name:null,email:null,tel:null,date:null,text:null});

</script>
<template >
    <Header :companies="props.companies" :menus="props.menus" :cart="props.cart" :menu="props.menu" />
    <main class="main pages" style="transform: none;">

            <div class="page-content pt-50" style="transform: none;">
                <div class="container" style="transform: none;">
                    <div class="row" style="transform: none;">
                        <div class="col-xl-10 col-lg-12 m-auto" style="transform: none;">
                            <div class="row" style="transform: none;">
                                <div class="col-lg-9">
                                    <div class="single-page pr-30 mb-lg-0 mb-sm-5">
                                        <div class="single-header style-2">
                                            <h2>{{ props.pages.title }}</h2>
                                            <div class="entry-meta meta-1 meta-3 font-xs mt-15 mb-15">
                                                <span class="post-by">توسط <Link :href="route('profile.show',[props.pages.user.user_name])">{{ props.pages.user.name_show }}</Link></span>
                                                <span class="post-on has-dot">تاریخ {{ moment(props.pages.created_at).locale("fa", fa).format('jYYYY/jM/jD') }}</span>
                                                <span class="post-on has-dot">بروزرسانی {{ moment(props.pages.updated_at).locale("fa", fa).format('jYYYY/jM/jD') }}</span>
                                                <!-- <span class="time-reading has-dot">زمان مطالعه {{ (10).toLocaleString("fa-IR") }} دقیقه</span> -->
                                                <!-- <span class="hit-count has-dot">29k Views</span> -->
                                            </div>
                                        </div>
                                        <div class="single-content mb-50">
                                            <h4>خوش آمدید به {{ props.pages.title }} <span> {{ props.companies.name_show }} </span> </h4>
                                            <ol start="1">
                                                <span v-html="props.pages.data"></span>
                                            </ol>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- <div v-if="props.pages" v-html="props.pages.data"> </div> -->
    <Footer :companies="props.companies" :socials="props.socials" :time="props.time" :menus="props.menus" :path="props.path" />
</template>
