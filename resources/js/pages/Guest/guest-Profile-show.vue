<script setup>
import Header from './Header.vue';
import Footer from './Footer.vue';
import { computed,ref} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';


const props = defineProps({
    user:Object,products:Object,tarahis:Object,orders_count:Number,companies:Object,role:Object});

const form = useForm({
    q: null,
});
const submit = ()=>{

    form.get(route('guest-product.index'));
};


const siklls = ref([]);
if (props.user && props.user.siklls)
{
    props.user.siklls.forEach(element => {
        if (element.status == 4)
        {
            siklls.value.push(element)
        }
    });
}
</script>
<template>

<Header :companies="props.companies" />

<div class="main">
    <section class="hero-section pt-100 "
                    style="background: url('../img/hero-bg-shape-2.png')no-repeat center center / cover;">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-9 col-lg-7">
                    <div class="page-header-content text-white text-center pt-sm-5 pt-md-5 pt-lg-0">
                        <h1 class="text-white mb-0">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">پروفایل کاربر</font>
                            </font>
                        </h1>
                        <div class="custom-breadcrumb">
                            <ol class="breadcrumb d-inline-block bg-transparent list-inline py-0">
                                <li class="list-inline-item breadcrumb-item"><a href="#">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">صفحه اصلی</font>
                                        </font>
                                    </a></li>
                                <li class="list-inline-item breadcrumb-item"><a href="#">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">صفحات</font>
                                        </font>
                                    </a></li>
                                <li class="list-inline-item breadcrumb-item active">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">پروفایل کاربر</font>
                                    </font>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="background-header" style="height:5rem"></div>
    </section>
    <section class="promo-section ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 col-sm-12 col-lg-5">
                    <div class="team-single-img">
                        <img v-if="props.user.image && props.user.image.status == 4 " :src="$page.props.ziggy.url+'/storage/'+props.user.image.url" :alt="props.user.user_name" class="img-fluid rounded shadow-sm">
                        <img v-else :src="$page.props.ziggy.url+'/storage/images/default-user.png'" class="img-fluid rounded shadow-sm" :alt="props.user.user_name" />
                        </div>
                </div>
                <div class="col-md-12 col-sm-12 col-lg-6">
                    <div class="team-single-text">
                        <div class="team-name mb-4">
                            <h4 class="mb-1">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">{{ props.user.name_show }}</font>
                                </font>
                            </h4>
                            <span>
                                <font style="vertical-align: inherit;" >
                                    <!-- <font style="vertical-align: inherit;" v-if="props.role" >{{ props.role.name }}</font>
                                    <font style="vertical-align: inherit;" v-else >خریدار</font> -->
                                </font>
                            </span>
                        </div>
                        <ul class="team-single-info" v-if="props.role.id > 2">
                            <li v-if="props.user && props.user.tel">
                                <strong>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">تلفن: </font>
                                    </font>
                                </strong><span>
                                    <a :href="'tel:'+props.user.tel">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;" v-if="props.user && props.user.tel > 0">{{ props.user.tel }}</font>
                                            <font style="vertical-align: inherit;" v-else>-</font>
                                        </font>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <strong>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">ایمیل: </font>
                                    </font>
                                </strong><span>
                                    <a :href="'mailto:'+props.user.email">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;" v-if="props.user && props.user.email" >{{ props.user.email }}</font>
                                        <font style="vertical-align: inherit;" v-else>-</font>
                                    </font>
                                    </a>
                                </span>
                            </li>
                        </ul>
                        <div class="text-content mt-20">
                            <p v-if="props.user && props.user.profile">
                                <font style="vertical-align: inherit;">
                                    {{ props.user.profile.biography }}
                                </font>
                            </p>

                        </div>
                        <ul class="team-social-list list-inline mt-4" v-if="props.role.id > 2">
                            <!-- <li v-for="(links,index) in props.user.socials" :key="index" class="list-inline-item" v-html="links.tag"></li> -->
                                <li class="list-inline-item" v-for="(social,index) in props.user.socials" :key="index" >
                                    <a v-if="social.link" :href="social.link.link" v-html="social.tag" class="color-primary"></a>
                                </li>

                            <!-- <li class="list-inline-item">
                                <a href="#" class="color-primary"><span
                                        class="ti-facebook"></span></a>
                                </li>
                            <li class="list-inline-item"><a href="#" class="color-primary"><span
                                        class="ti-instagram"></span></a></li>
                            <li class="list-inline-item"><a href="#" class="color-primary"><span
                                        class="ti-dribbble"></span></a></li>
                            <li class="list-inline-item"><a href="#" class="color-primary"><span
                                        class="ti-linkedin"></span></a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="section-heading">
                        <h5>
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">عناوین و سمت</font>
                            </font>
                        </h5>
                        <div class="section-heading-line-left"></div>
                    </div>
                    <ul class="list-unstyled">
                        <li class="py-2">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="badge badge-primary mr-3"><span class="ti-check"></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0">
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;" v-if="props.role.id == 1" >{{ props.role.name }}</font>
                                            <font style="vertical-align: inherit;" v-else-if="props.role.id == 2" >{{ props.role.name }}</font>
                                            <font style="vertical-align: inherit;" v-else-if="props.role.id == 3" >تیم مدیریت {{ props.companies.name }}</font>
                                            <font style="vertical-align: inherit;" v-else-if="props.role.id == 4" >مدیریت {{ props.companies.name }}</font>
                                            <font style="vertical-align: inherit;" v-else >خریدار/کارفرما</font>
                                        </font>
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="section-heading mt-40">
                        <h5>
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">مهارتهای حرفه ای</font>
                            </font>
                        </h5>
                        <div class="section-heading-line-left"></div>
                    </div>
                    <div class="mt-3" v-if="props.user && props.user.siklls.length > 0 ">
                        <div class="progress-item" v-for="sikll,index in siklls" :key="index">
                            <div v-if="sikll.status == 4">
                                <div class="progress-title">
                                    <h6>
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">{{ sikll.subject }}</font>
                                        </font>
                                        <span class="float-right" ><span class="progress-number">
                                                <font style="vertical-align: inherit;">
                                                    <font style="vertical-align: inherit;">{{ sikll.number }}</font>
                                                </font>
                                            </span>
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;"> درصد</font>
                                            </font>
                                        </span>
                                    </h6>
                                </div>
                                <div class="progress p-1">
                                    <span :style="'width: '+ sikll.number+'%'"><span
                                            class="progress-line"></span>
                                    </span>
                                </div>
                            </div>
                            <div v-else>
                                <h6>
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">ثبت نشده {{ index }}</font>
                                    </font>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <h6 v-else>
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">ثبت نشده</font>
                        </font>
                    </h6>
                </div>
            </div>
        </div>
    </section>

</div>


<Footer :companies="props.companies" />
</template>

