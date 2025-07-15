<script setup>

import { computed ,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const props = defineProps({
    companies:Object,socials:Object,time: String,menus:Object,path:String,
});

const asset = '/img/footer-bg.png';

const form =  useForm({email:null});

const submitEmail = () =>{
    form.post(route('store'))
};
var now = new Date();
var full_year = now.getFullYear();

const menus = ref([]);

if (props.menus && props.menus.length > 0) {

props.menus.forEach(element => {
    console.log(element);
    if (element.sections.length > 0 && element.routes.length > 0) {
        element.routes.forEach(route => {
            if(route.name == props.path)
            {
                element.sections.forEach(section => {
                    if(section.name == 'namads')
                    {
                        menus.value.push(element)
                    }
                });
            }
        });
    }
});
}

const menu = ref([]);


if (menus.value) {


    menus.value.forEach(element => {

            element.children.forEach(child => {
                if(child.routes.length > 0){
                    child.routes.forEach(route => {
                        if (route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section =>
                                {
                                    if(section.name == 'namads')
                                    {
                                        menu.value.push(child)
                                    }
                                });
                            }
                        }
                    });
                }
            });

    });
}

</script>
<template>

    <div class="shape-img subscribe-wrap">
            <img :src="$page.props.ziggy.url+'/img/footer-top-shape.png'" alt="footer shape" class="img-fluid">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form @submit.prevent="submitEmail" class="subscribe-form subscribe-form-footer d-none d-md-block d-lg-block">
                            <div class="d-flex align-items-center">
                                <!-- <input v-model.lazy.trim="form.email" type="text" class="form-control input" id="email-footer" name="email"
                                    placeholder="ایمیل خود را وارد نمایید">
                                <input type="submit" class="button btn btn-primary" id="submit-footer" value="اشتراک"> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <footer class="footer-section">
        <div class="footer-top pt-150 pb-5 background-img-2" :style="'background:url('+$page.props.ziggy.url+asset+') no-repeat center top / cover'">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-3 mb-3 mb-lg-0">
                        <div class="footer-nav-wrap text-white">
                            <img v-if="props.companies && props.companies.image && props.companies.image == 4" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" :alt="props.companies.name" width="70" height="40" class="">
                            <p v-if="props.socials && props.socials.length > 0">ما را در شبکه های اجتماعی دنبال نمایید</p>

                            <div class="social-list-wrap">
                                <ul class="social-list list-inline list-unstyled">
                                    <li class="list-inline-item" v-for="social in props.socials" :key="social.id" >
                                        <a v-if="social.link" :href="social.link" target="_blank" :title="social.name" v-html="social.tag">
                                        </a>
                                    </li>
                                    <!-- <li class="list-inline-item">
                                        <a href="https://instagram.com" target="_blank" title="Instagram">
                                            <span class="ti-instagram"></span>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a target="_blank" title="Telegram">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                                            </svg>
                                        </a>
                                    </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 me-auto mb-4 mb-lg-0">
                        <div class="footer-nav-wrap text-white">
                            <h5 class="mb-3 text-white">دسترسی سریع</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="#about">درباره ما</a></li>
                                <li class="mb-2"><a href="#contact">تماس با ما</a></li>
                                <li class="mb-2"><Link :href="route('faq.index')">سوالات متداول</Link></li>
                                <li class="mb-2"><Link :href="route('terms-conditions.index')">قوانین و مقررات</Link></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 me-auto mb-4 mb-lg-0">
                        <div class="footer-nav-wrap text-white">
                            <h5 class="mb-3 text-white">پشتیبانی</h5>
                            <ul class="list-unstyled support-list">
                                <li v-if="props.companies && props.companies.profile && props.companies.profile.status == 4" class="mb-2 d-flex align-items-center"><span class="ti-location-pin mr-2"></span>{{ props.companies.ostan }} </li>
                                <li v-if="props.companies && props.companies.tel" class="mb-2 d-flex align-items-center"><span class="ti-mobile mr-2"></span> <a
                                        :href="'tel:'+props.companies.tel">{{ props.companies.tel }}</a></li>
                                <li v-if="props.companies" class="mb-2 d-flex align-items-center"><span class="ti-email mr-2"></span><a
                                    :href="'mailto:'+ props.companies.email">{{ props.companies.email }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="footer-nav-wrap text-white" >
                            <h5 class="mb-3 text-white">نمادها</h5>
                            <template v-for="men,index in menu" :key="index">
                                <!-- <h6 v-if="men.name && men.namad && men.namad.status == 4" class="mb-3 text-white">{{ men.name }}</h6> -->
                                <div v-if="men.namad && men.namad.status == 4" v-html="men.namad.tag"></div>
                            </template>
                            <!--<h5 class="mb-3 text-white">موقعیت</h5>
                            <img src="img/map.png" alt="location map" class="img-fluid"> -->
                            <!-- <a referrerpolicy="origin" target="_blank" href="https://trustseal.enamad.ir/?id=217775&amp;Code=JvEGszjmVjhfztIZYQKr">
                            <img referrerpolicy="origin" src="https://Trustseal.eNamad.ir/logo.aspx?id=217775&amp;Code=JvEGszjmVjhfztIZYQKr" alt="" style="cursor:pointer" id="JvEGszjmVjhfztIZYQKr">
                            </a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom gray-light-bg pt-4 pb-4">
            <div class="container">
                <div class="row text-center justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <p class="copyright-text pb-0 mb-0"> 1400-{{ moment(time).locale("fa", fa).format('jYYYY') }} طراحی توسط {{ props.companies.name + props.companies.lasst_name }} <a v-if="props.companies" :href="route('website_design.index', 'q')+'all'">{{ props.companies.name_show }}</a> © کلیه حقوق مادی و معنوی
                         محفوظ است.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</template>


