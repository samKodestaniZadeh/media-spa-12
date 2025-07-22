<script setup>

import Header from './Header2.vue';
import Footer from './Footer2.vue';
import { computed, ref,onMounted,watch } from 'vue';
import { Link, useForm ,usePage} from '@inertiajs/vue3';
import swal from 'sweetalert2';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

import 'vue3-carousel/dist/carousel.css'

import {Countdown} from 'vue3-flip-countdown';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth: Object, menus: Object, alert: Object, flash: String, results:Object, cartTarahis: Object,
    cartCount: Number, cartPrice: Number, cartDiscount: Number, cartCoupon: Number, cartTotal: Number,
    companies: Object,users:Object,path:String,orders:Object,querystring:String,usersRating:Object,
    webDesigns:Object,menu: Object,time:String
});

const form = useForm({ id:null,model:null,
    name: null, lasst_name: null, email: null, tel: null, price: null, text: null,
    group: null, type: null, title: null, file: null, q:null,category:null,entekhab:null
});

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
const ApiKey = ref('cfw3yx4hh06riwl1qwbq3fwcmjr80c5v0z2ki1fid7agx2ow');

watch(() => props.alert, (val) => {
  if (val) {
    if (val.title) {
      swal.fire(val.title, val.text, val.icon);
    } else {
      swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', swal.stopTimer);
          toast.addEventListener('mouseleave', swal.resumeTimer);
        }
      }).fire({
        title: val.text,
        icon: val.icon,
      });
    }
  }
});

watch(errors, (newErrors) => {
  const errorMessages = Object.values(newErrors)
    .flat()
    .map(msg => `${msg}<br>`)
    .join('')

  if (errorMessages) {
    swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
      }
    }).fire({
      title: errorMessages,
      icon: 'error',
    })
  }
}, { immediate: true })


const submit = () => {
    if (form.name && form.lasst_name && form.email && form.tel  && form.group && form.type && form.text && form.title && form.entekhab) {

        if (props.companies && props.companies.name == form.entekhab) {
                form.post(route('tarahi.store'))
            }
            else
            {
                form.post(route('website_design.store'))
            }
    }
    else
    {
        let text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
}

const submitLogin = () => {

    if (form.group && form.type && form.text && form.title && form.entekhab) {
        if(form.title.indexOf('پروژه') == -1)
        {
            if (props.companies && props.companies.id == form.entekhab) {
                form.post(route('tarahi.store'))
            }
            else
            {
                form.post(route('tarahi.store'))
            }
        }
        else
        {
            let text = 'لطفا از کلمه "پروژه" در عنوان استفاده ننمایید.'
            validate(text)
        }
    }
    else
    {
        let text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }

}

const clear = () =>{
    form.group = null,
    form.type = null,
    form.category = null
}
const menus = ref([]);

if (props.menus && props.menus.length > 0) {

    props.menus.forEach(element => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section => {
                        if(section.name == 'tarahis')
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
const sections = ref([]);
const categores = ref([]);

const group = () => {
    if (menu.value.length > 0) {
        menu.value.splice(0)
    }
    // form.type = null,
    menus.value.forEach(element => {
        if (form.group == element && element.children.length > 0 ) {

            element.children.forEach(child => {
                if(child.routes.length > 0){
                    child.routes.forEach(route => {
                        if (route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section =>
                                {
                                    if(section.name == 'tarahis')
                                    {
                                        menu.value.push(child)
                                    }
                                });
                            }
                        }
                    });
                }
            });
        }
    });
};

const type = () => {
    if (sections.value.length > 0) {
        sections.value.splice(0)
    }
    // form.category = null,
        menu.value.forEach(element => {
            if (form.type ==  element && element.children.length > 0) {
                element.children.forEach(child => {
                    if(child.routes.length > 0)
                    {
                        child.routes.forEach( route => {
                            if (route.name == props.path)
                            {
                                if (child.sections.length > 0)
                                {
                                    child.sections.forEach(section => {
                                        if(section.name == 'tarahis')
                                        {
                                            sections.value.push(child)
                                        }

                                    });
                                }
                            }
                        });
                    }
                });

            }

        });

};

const category = () => {
    sections.value.forEach(element => {
        if (form.category == element && element.children !== null) {
            categores.value = element.children
        }
        else
        {
            categores.value = null
        }
    });

};

const submitFilter = (data) => {

    form.q = data
    form.get(route('website_design.index'))

};

const settings={
      itemsToShow: 1,
      snapAlign: 'center',
};

const breakpoints={
      // 700px and up
      700: {
        itemsToShow: 3,
        snapAlign: 'center',
      },
      // 1024 and up
      1024: {
        itemsToShow: 3,
        snapAlign: 'start',
      },
}
const Rate = ref([]);
const role = ref();
if (props.users) {
    props.users.roles.forEach(element => {

    if (element.id == 3) {
        role.value = element
    }
    });
}

const submitCart = (id) => {
    form.id = id,
    form.model = 'App\\Models\\WebDesign',
    form.post(route('cart.store') )
}

const discounts = ref([null]);

// فقط در محیط مرورگر
const getPageUrl = (baseUrl, page) => {
    if (typeof window !== 'undefined') {
        let queryString = window.location.search;

        // حذف پارامتر page از URL قبلی
        queryString = queryString.replace(/(\?|&)page=\d+/, '');

        // ساخت URL جدید با پارامتر page جدید
        let newUrl = `${baseUrl}?page=${page}${queryString ? '&' + queryString.substring(1) : ''}#result`;
        return newUrl;
    }
    return `${baseUrl}?page=${page}#result`; // در محیط سرور فقط این URL بدون query string
};

const showMore = ref(false)

onMounted(() => {
  const url = new URL(window.location.href)
  // اگه category توی کوئری باشه (مثلاً ?category=5) منو باز بشه
  showMore.value = url.searchParams.has('category')
})

function toggleShowMore(event) {
  event.preventDefault() // اینجا جلوی رفرش صفحه رو می‌گیریم

  showMore.value = !showMore.value

  const url = new URL(window.location.href)
  if (showMore.value) {
    url.searchParams.set('category', 'open') // مقدار فقط واسه باز شدن دستی
  } else {
    url.searchParams.delete('category')
  }
  window.history.replaceState({}, '', url)
}
</script>
<template>
    <Header :cart="props.cart"  :alert="props.alert" :users="props.users" :companies="props.companies"  @event-submit-tarahi-filter="submitFilter"
     :menus="props.menus" :menu="props.menu"/>
    <main class="main">

        <div class="mb-30 container">
            <div class="row flex-row-reverse">
                <div class="col-lg-4-5">
                    <section class="home-slider position-relative mb-30">
                        <div class="home-slide-cover mt-30">
                            <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">
                                <div class="single-hero-slider single-animation-wrap" style="background-image: url(assets/imgs/slider/slider-3.png)">
                                    <div class="slider-content">
                                        <h1 class="display-2 mb-40">
                                             تخفیفات شگفت انگیز
                                            <br />
                                              را از دست ندهید

                                        </h1>
                                        <!-- <p class="mb-65">برای خبرنامه روزانه ثبت نام کنید</p>
                                        <form class="form-subcriber d-flex">
                                            <input type="email" placeholder="آدرس ایمیل شما" />
                                            <button class="btn" type="submit">اشتراک</button>
                                        </form> -->
                                    </div>
                                </div>
                                <div class="single-hero-slider single-animation-wrap" style="background-image: url(assets/imgs/slider/slider-4.png)">
                                    <div class="slider-content">
                                        <h1 class="display-2 mb-40">
                                             از بن های تخفیف
                                            <br />
                                         روزانه استفاده نمایید
                                        </h1>
                                        <!-- <p class="mb-65">برای خبرنامه روزانه ثبت نام کنید</p>
                                        <form class="form-subcriber d-flex">
                                            <input type="email" placeholder="آدرس ایمیل شما" />
                                            <button class="btn" type="submit">اشتراک</button>
                                        </form> -->
                                    </div>
                                </div>
                            </div>
                            <div class="slider-arrow hero-slider-1-arrow"></div>
                        </div>
                    </section>
                    <!--End hero-->
                    <!--Products Tabs-->

                        <!--End Deals-->
                    <section class="product-tabs section-padding position-relative" v-if="props.results && props.results.total > 0">
                        <div class="section-title style-2">
                            <h3>پروژها</h3>
                            <ul class="nav nav-tabs links" id="myTab" role="tablist">
                                <li class="nav-item" >
                                    <Link
                                        class="nav-link " :class="props.querystring == 0 ? 'active' : ' '"
                                        :href="route('website_design.index','q')+'all'+'#result'"
                                    >
                                       کل
                                    </Link>
                                </li>
                                <li class="nav-item" >
                                    <Link
                                        class="nav-link" :class="props.querystring == 'sort=DESC'? 'active' : ' '"

                                        :href="route('website_design.index','sort')+'DESC'+'#result'"
                                    >
                                       جدیدترین
                                    </Link>
                                </li>
                                <li class="nav-item" >
                                    <Link
                                        class="nav-link" :class="props.querystring == 'sort=Bestselling'? 'active' : ' '"
                                        :href="route('website_design.index','sort')+'Bestselling'+'#result'"
                                    >
                                     بیشترین پیشنهاد
                                    </Link>
                                </li>
                                <li class="nav-item" >
                                    <Link
                                        class="nav-link" :class="props.querystring == 'sort=open'? 'active' : ' '"
                                        :href="route('website_design.index','sort')+'open'+'#result'"

                                    >
                                       پروژه باز
                                    </Link>
                                </li>
                                <li class="nav-item" >
                                    <Link
                                        class="nav-link" :class="props.querystring == 'sort=expensive'? 'active' : ' '"
                                        :href="route('website_design.index','sort')+'expensive'+'#result'"
                                    >
                                     بالا ترین مبلغ
                                    </Link>
                                </li>
                                <li class="nav-item" >
                                    <Link
                                        class="nav-link" :class="props.querystring == 'sort=cheapest'? 'active' : ' '"
                                        :href="route('website_design.index','sort')+'cheapest'+'#result'"
                                    >
                                        پایین ترین مبلغ
                                    </Link>
                                </li>
                                <li class="nav-item" >
                                    <Link
                                        class="nav-link" :class="props.querystring == 'updated=updateDate'? 'active' : ' '"
                                        :href="route('website_design.index','updated')+'updateDate'+'#result'"
                                    >
                                        بروزترین
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <!--End nav-tabs-->

                        <div class="tab-content" id="myTabContent" >
                            <div class="tab-pane fade show active" >
                                <div class="row">
                                <div class="col-xl-3 col-lg-4 col-md-6" v-for="(result ,index) in props.results.data" :key="index">
                                    <div class="product-cart-wrap style-2" >
                                        <div class="product-img-action-wrap">
                                            <div class="product-img">
                                                <Link v-if="props.companies.image && props.companies.image.status == 4 || props.companies.image &&  props.companies.image.status == 5" :href="route('website_design.show',[result.slug])" >
                                                    <img class="h-300" :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" alt=""  />
                                                </Link>
                                            </div>
                                        </div>
                                        <div class="product-content-wrap">
                                            <div class="deals-countdown-wrap">
                                                <div class="deals-countdown"  :data-countdown="result.expired_at"></div>

                                            </div>
                                            <div class="deals-content">
                                                <div class="product-category" v-if="result.menus">
                                                    <template v-for="(menu, index) in result.menus" :key="index">
                                                        <template v-for="(section, index) in menu.sections" :key="section.id">
                                                            <a href="" v-if="section.name == 'tarahis'">{{ menu.name + ' ' }}</a>
                                                        </template>
                                                    </template>
                                                </div>
                                                <h2><Link :href="route('website_design.show',[result.slug])">{{result.title}}</Link></h2>
                                                <div class="product-rate-cover">
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" v-if="result.ratings_avg_rating" :style="'width:' + result.ratings_avg_rating*20 + '%' "></div>
                                                    </div>
                                                    <span class="font-small ml-5 text-muted" v-if="result.ratings_avg_rating" > ({{ result.ratings_avg_rating }})</span>
                                                    <span class="font-small ml-5 text-muted" v-else > (0.000)</span>
                                                </div>
                                                <div>
                                                    <span class="font-small text-muted" v-if="result.user">کارفرما <Link href="">({{ result.user.name_show }})</Link></span>
                                                </div>
                                                <div class="product-card-bottom">
                                                    <div class="product-price"  v-if="result.discount">
                                                        <span>{{(result.price-(result.price*result.discount.percent/100)).toLocaleString("fa-IR")}}</span>
                                                        <span class="old-price">{{(result.price).toLocaleString("fa-IR")}}</span>
                                                    </div>
                                                    <div class="product-price" v-else>
                                                        <span>{{(result.price).toLocaleString("fa-IR")}}</span>
                                                        <!-- <span class="old-price">$32.8</span> -->
                                                    </div>
                                                    <div class="add-cart">
                                                        <Link class="add" :href="route('website_design.show', [result.slug])" ><i class="mr-5"></i>جزییات </Link>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            </div>
                            <!--En tab one-->
                        </div>
                        <!--End tab-content-->
                        <div class="pagination-area mb-20 mt-20" v-if="props.results && props.results.total > 9">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-start">
                                    <!-- فلش قبلی -->
                                    <li
                                        class="page-item"
                                        :class="{ disabled: !props.results.prev_page_url || props.results.current_page === 1 }"
                                    >
                                        <Link
                                            class="page-link"
                                            :href="
                                                props.results.prev_page_url && props.results.current_page > 1
                                                    ? props.results.prev_page_url
                                                    : '#'
                                            "
                                            preserve-scroll
                                            preserve-state
                                            aria-disabled="props.results.current_page === 1"
                                        >
                                            <i class="fi-rs-arrow-small-right"></i>
                                        </Link>
                                    </li>

                                    <!-- صفحه اول -->
                                    <li class="page-item" :class="{ active: props.results.current_page === 1 }">
                                        <Link
                                            class="page-link"
                                            :href="getPageUrl(props.results.first_page_url, 1)"
                                            preserve-scroll
                                            preserve-state
                                            >1</Link
                                        >
                                    </li>

                                    <!-- ... قبل از currentPage -2 -->
                                    <li class="page-item" v-if="props.results.current_page > 4">
                                        <span class="page-link dot">...</span>
                                    </li>

                                    <!-- صفحات وسط (currentPage -2 تا currentPage +2) -->
                                    <template v-for="i in 5" :key="i">
                                        <li
                                            class="page-item"
                                            v-if="
                                                props.results.current_page - 3 + i > 1 &&
                                                props.results.current_page - 3 + i < props.results.last_page
                                            "
                                            :class="{ active: props.results.current_page === props.results.current_page - 3 + i }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="getPageUrl(props.results.path, props.results.current_page - 3 + i)"
                                                preserve-scroll
                                                preserve-state
                                            >
                                                {{ props.results.current_page - 3 + i }}
                                            </Link>
                                        </li>
                                    </template>

                                    <!-- ... بعد از currentPage +2 -->
                                    <li class="page-item" v-if="props.results.current_page < props.results.last_page - 3">
                                        <span class="page-link dot">...</span>
                                    </li>

                                    <!-- صفحه آخر -->
                                    <li
                                        class="page-item"
                                        v-if="props.results.last_page !== 1"
                                        :class="{ active: props.results.current_page === props.results.last_page }"
                                    >
                                        <Link
                                            class="page-link"
                                            :href="getPageUrl(props.results.path, props.results.last_page)"
                                            preserve-scroll
                                            preserve-state
                                        >
                                            {{ props.results.last_page }}
                                        </Link>
                                    </li>

                                    <!-- فلش بعدی -->
                                    <li
                                        class="page-item"
                                        :class="{
                                            disabled:
                                                !props.results.next_page_url || props.results.current_page === props.results.last_page,
                                        }"
                                    >
                                        <Link
                                            class="page-link"
                                            :href="
                                                props.results.next_page_url && props.results.current_page < props.results.last_page
                                                    ? props.results.next_page_url
                                                    : '#'
                                            "
                                            preserve-scroll
                                            preserve-state
                                            aria-disabled="props.results.current_page === props.results.last_page"
                                        >
                                            <i class="fi-rs-arrow-small-left"></i>
                                        </Link>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <!--end product card-->

                    </section>
                    <!--Products Tabs-->
                    <section class="product-tabs section-padding position-relative">
                        <section id="contact" class="contact-us ptb-100">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12 pb-3 message-box d-none">
                                            <div class="alert alert-danger"></div>
                                        </div>

                                        <div class="col-md-7" id="web">
                                            <form>
                                                <h5>ثبت سفارش طراحی وبسایت</h5>
                                                <div class="row" v-if="form.entekhab == 0 || props.companies && form.entekhab == props.companies.id">
                                                    <div class="col-sm-6 col-12" v-if="$page.props.auth.user == null">
                                                        <div class="form-group">
                                                            <input v-model.lazy="form.name" type="text" class="form-control" placeholder="نام"
                                                                required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12" v-if="$page.props.auth.user == null">
                                                        <div class="form-group">
                                                            <input v-model.lazy="form.lasst_name" type="text" class="form-control"
                                                                placeholder="نام و نام خانوادگی" required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12" v-if="$page.props.auth.user == null">
                                                        <div class="form-group">
                                                            <input v-model.lazy="form.email" type="email" class="form-control" placeholder="ایمیل"
                                                                required="required">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-12" v-if="$page.props.auth.user == null">
                                                        <div class="form-group">
                                                            <input v-model.lazy="form.tel" type="text" name="phone" class="form-control"
                                                                placeholder="تلفن">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 col-6">
                                                        <div class="form-group">
                                                            <label for="file">انتخاب مجری پروژه</label>
                                                            <select v-model="form.entekhab" @change="clear" class="form-control ltr">
                                                                <!-- <option value="0">فریلنسرها</option> -->
                                                                <option v-if="props.companies" :value="props.companies.id">{{ props.companies.name_show }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-6" v-if="form.entekhab == 0 || props.companies && form.entekhab == props.companies.id">
                                                        <div class="form-group">
                                                            <label for="file"></label>
                                                            <select v-model.lazy="form.group" @change="group" class="form-control ltr">
                                                                <option v-if="menus.length > 0" :value="menu" v-for="(menu, index) in menus" :key="index">{{ menu.name }}</option>
                                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="form.entekhab == 0 || props.companies && form.entekhab == props.companies.id">
                                                    <div class="col-sm-6 col-6">
                                                        <div class="form-group">
                                                            <select v-model.lazy="form.type" @change="type" class="form-control ltr">
                                                                <option v-if="menu.length > 0 && form.group" v-for="(type, index) in menu" :key="index" :value="type">{{ type.name }}</option>
                                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-6">
                                                        <div class="form-group">
                                                            <select v-model.lazy="form.category" @change="category" class="form-control ltr">
                                                                <option v-if="sections.length > 0 && form.type" v-for="(category, index) in sections" :key="index" :value="category">{{ category.name }}</option>
                                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="form.entekhab == 0">
                                                    <div class="col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <input v-model.lazy="form.title" type="text" class="form-control"
                                                                placeholder=" یک عنوان در ارتباط با پروژه بنویسید.عنوان پروژه برای جذب طراحان مهم است."
                                                                required="required">
                                                        </div>

                                                        <div class="form-group">
                                                            <!-- <date-picker v-model.lazy="form.date" format="YYYY-MM-DD HH:mm:ss" display-format="dddd jDD jMMMM jYYYY" color="#1ABC9C" :min="now" type="date" placeholder=" حداكثر زمان براي طراحي و راه اندازي پروژه چقدر است؟"></date-picker> -->
                                                            <input v-model.lazy="form.price" type="text" class="form-control"
                                                                placeholder=" مبلغ(ریال) بودجه شما چقدر است؟" required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="props.companies && form.entekhab == props.companies.id">
                                                    <div class="col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <input v-model.lazy="form.title" type="text" class="form-control"
                                                                placeholder="یک عنوان در ارتباط با پروژه بنویسید."
                                                                required="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="form.entekhab == 0 || props.companies && form.entekhab == props.companies.id">
                                                    <div class="col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label for="file">در صورت نیاز میتوانید فایل با پسوند zip و rar بارگزاری نمایید.</label>
                                                            <input class="form-control" type="file" @input="form.file = $event.target.files[0]"
                                                                id="file" accept="zip/rar/*" />
                                                            <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                                {{ form.progress.percentage }}%
                                                            </progress>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="form.entekhab == 0 || props.companies && form.entekhab == props.companies.id">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text"
                                                                placeholder="توضیحات راجع به سایت دلخواه و همچنین اگر نمونه سایت مشابهی در رسته کاری خود می شناسید ،در اینجا معرفی نمایید." />
                                                                <!-- <textarea v-model.lazy="form.text" name="text" id="text" class="form-control" rows="7" cols="25"
                                                                placeholder="توضیحات راجع به سایت دلخواه و همچنین اگر نمونه سایت مشابهی در رسته کاری خود می شناسید ،در اینجا معرفی نمایید."></textarea> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" v-if="form.entekhab == 0 || props.companies && form.entekhab == props.companies.id">
                                                <div class="col-12 mt-3">
                                                    <button v-if="$page.props.auth.user == null" @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary" id="btnContactUs">
                                                        <span v-if="form.processing">پردازش...</span>
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                        <span v-else>ثبت</span>
                                                    </button>
                                                    <button v-else @click.prevent="submitLogin" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary" id="btnContactUs">
                                                        <span v-if="form.processing">پردازش...</span>
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                                        <span v-else>ثبت</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            </section>
                    </section>
                    <!--End Deals-->

                </div>
                <div class="col-lg-1-5 primary-sidebar sticky-sidebar pt-30">
                    <div class="sidebar-widget widget-category-2 mb-30">
                        <h5 class="section-title style-1 mb-30">دسته بندی پروژها</h5>
                        <ul v-for="(menu, index) in props.menus" :key="index">
                            <template v-for="(men, index) in menu.children" :key="index">
                                <li v-if="men.tarahis_count > 0">
                                    <Link :href="route('website_design.index', 'type') + men.id + '#result'">
                                        <!-- <img src="assets/imgs/theme/icons/category-1.svg" alt="" /> -->
                                        {{ menu.name + ' ' + men.name }}
                                    </Link>
                                    <span class="count">{{ men.tarahis_count }}</span>
                                </li>
                            </template>

                                <a v-if="showMore"
                                    href="javascript:void(0);"
                                    class="text-primary text-decoration-none d-inline-flex align-items-center mb-2"
                                    @click="toggleShowMore($event)"
                                >
                                    <i :class="showMore ? 'fas fa-times me-1' : 'fas fa-folder-open me-1'"></i>
                                    <span v-if="!showMore">بیشتر...</span>
                                    <span v-else>کمتر</span>
                                </a>
                                <template v-for="(men, index) in menu.children" :key="index">
                                <div v-show="showMore" class="list-unstyled">
                                    <template v-for="(me, index) in men.children" :key="index">
                                    <li v-if="me.tarahis_count > 0">
                                        <Link :href="route('website_templates.index', 'category') + me.id + '#result'">
                                        {{ men.name + ' ' +me.name }}
                                        </Link>
                                        <span class="count">{{ me.tarahis_count }}</span>
                                    </li>
                                    </template>
                                </div>
                            </template>
                        </ul>
                    </div>
                    <!-- Product sidebar Widget -->
                </div>
            </div>
        </div>

        <!--End category slider-->
        <section class="section-padding mb-30">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0" v-if="props.orders.length > 0">
                        <h4 class="section-title style-1 mb-30 animated animated">پرفروش ترین</h4>
                        <div class="product-list-small animated animated">
                            <article class="row align-items-center hover-up" v-for="(result,index) in props.orders " :key="index">
                                <figure class="col-md-4 mb-0">
                                    <Link :href="route('website_templates.show', [result.product.slug])"
                                    v-if="(result.image && result.image.status == 4) || 5" >
                                        <img :src="$page.props.ziggy.url + '/storage/' + result.product.image.url" alt="" />
                                    </Link>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h6>
                                        <Link :href="route('website_templates.show', [result.product.slug])" >{{ result.product.name }}</Link>
                                    </h6>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" :style="'width:' + result.product.ratings_avg_rating * 20 + '%'"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted" v-if=" result.product.ratings_avg_rating">({{ result.product.ratings_avg_rating }})</span>
                                        <span class="font-small ml-5 text-muted" v-else>(0.0000)</span>
                                    </div>
                                    <div class="product-price" v-if="result.product.discount">
                                        <span class="d-flex flex-column">
                                            <span>{{(result.product.price -(result.product.price * result.product.discount.percent) / 100).toLocaleString('fa-IR')}}</span>
                                            <span class="old-price">{{result.product.price.toLocaleString('fa-IR')}}</span>
                                        </span>
                                    </div>
                                    <div class="product-price" v-else >
                                        <span>{{result.product.price.toLocaleString('fa-IR')}}</span>
                                        <span class="old-price"></span>
                                    </div>
                                </div>
                            </article>

                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-md-0" v-if="props.favorites && props.favorites.length > 0">
                        <h4 class="section-title style-1 mb-30 animated animated">پرطرفدارترین</h4>
                        <div class="product-list-small animated animated">
                            <article class="row align-items-center hover-up" v-for="result,index in props.favorites" :key="index">
                                <figure class="col-md-4 mb-0 ">
                                    <Link :href="route('website_templates.show', [result.favoritable.slug])"><img :src="$page.props.ziggy.url + '/storage/' + result.favoritable.image.url" alt="" /></Link>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h6>
                                        <Link :href="route('website_templates.show', [result.favoritable.slug])">{{ result.favoritable.name }}</Link>
                                    </h6>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" :style="'width:' + result.favoritable.ratings_avg_rating * 20 + '%'"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted" v-if="result.favoritable.ratings_avg_rating"> ({{ result.favoritable.ratings_avg_rating }})</span>
                                        <span class="font-small ml-5 text-muted" v-else > (0.0000)</span>
                                    </div>
                                    <div class="product-price" v-if="result.favoritable.discount">
                                        <span class="d-flex flex-column">
                                            <span>{{(result.favoritable.price -(result.favoritable.price * result.favoritable.discount.percent) / 100).toLocaleString('fa-IR')}}</span>
                                            <span class="old-price">{{result.favoritable.price.toLocaleString('fa-IR')}}</span>
                                        </span>
                                    </div>
                                    <div class="product-price" v-else >
                                        <span>{{result.favoritable.price.toLocaleString('fa-IR')}}</span>
                                        <span class="old-price"></span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-lg-block" v-if="props.resultsNew && props.resultsNew.length">
                        <h4 class="section-title style-1 mb-30 animated animated">جدیدترین</h4>
                        <div class="product-list-small animated animated">
                            <article class="row align-items-center hover-up"  v-for="(result, index) in props.resultsNew" :key="index">
                                <figure class="col-md-4 mb-0">
                                    <Link :href="route('website_templates.show', [result.slug])">
                                        <img v-if="result.image" :src="$page.props.ziggy.url + '/storage/' + result.image.url" alt="" />
                                    </Link>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h6>
                                        <Link :href="route('website_templates.show', [result.slug])">{{ result.name }}</Link>
                                    </h6>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" :style="'width:' + result.ratings_avg_rating * 20 + '%'"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted" v-if="result.ratings_avg_rating"> ({{ result.ratings_avg_rating }})</span>
                                        <span class="font-small ml-5 text-muted" v-else> (0.0000)</span>
                                    </div>
                                    <div class="product-price" v-if="result.discount">
                                        <span class="d-flex flex-column">
                                            <span>{{(result.price -(result.price * result.discount.percent) / 100).toLocaleString('fa-IR')}}</span>
                                            <span class="old-price">{{result.price.toLocaleString('fa-IR')}}</span>
                                        </span>
                                    </div>
                                    <div class="product-price" v-else >
                                        <span>{{result.price.toLocaleString('fa-IR')}}</span>
                                        <span class="old-price"></span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-sm-5 mb-md-0 d-none d-xl-block" v-if="props.topRated && props.topRated.length > 0">
                        <h4 class="section-title style-1 mb-30 animated animated">رتبه برتر</h4>
                        <div class="product-list-small animated animated">
                            <article class="row align-items-center hover-up" v-for="result,index in props.topRated" :key="index">
                                <figure class="col-md-4 mb-0">
                                    <Link :href="route('website_templates.show', [result.slug])">
                                        <img  v-if="result.image" :src="$page.props.ziggy.url + '/storage/' + result.image.url" alt="" />
                                    </Link>
                                </figure>
                                <div class="col-md-8 mb-0">
                                    <h6>
                                        <Link :href="route('website_templates.show', [result.slug])">{{ result.name }}</Link>
                                    </h6>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" :style="'width:' + result.avg_rating * 20 + '%'"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted" v-if="result.avg_rating"> ({{ result.avg_rating }})</span>
                                        <span class="font-small ml-5 text-muted" v-else> (0.0000)</span>
                                    </div>
                                    <div class="product-price" v-if="result.discount">
                                        <span class="d-flex flex-column">
                                            <span>{{(result.price -(result.price * result.discount.percent) / 100).toLocaleString('fa-IR')}}</span>
                                            <span class="old-price">{{result.price.toLocaleString('fa-IR')}}</span>
                                        </span>
                                    </div>
                                    <div class="product-price" v-else >
                                        <span>{{result.price.toLocaleString('fa-IR')}}</span>
                                        <span class="old-price"></span>
                                    </div>
                                </div>
                            </article>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End 4 columns-->
    </main>
    <Footer :companies="props.companies" />
</template>
<style>
.carousel__item {
  min-height: 200px;
  width: 100%;

  font-size: 20px;
  border-radius: 8px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.carousel__slide {
  padding: 10px;
}

.carousel__prev,
.carousel__next {
  box-sizing: content-box;
  border: 5px solid white;
}
.no-animation__card{
    font-size:1rem !important
}
</style>
