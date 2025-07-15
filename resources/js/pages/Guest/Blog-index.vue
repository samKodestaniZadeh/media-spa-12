<script setup>

import Header from './Header2.vue';
import Footer from './Footer2.vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed,ref} from 'vue';
import swal from 'sweetalert2';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,blogs:Object,alert:Object,flash:String,tarahis:Object,cartTarahis:Object,
    cartCount:Number,cartPrice:Number,cartDiscount:Number,cartCoupon:Number,cartTotal:Number,
    companies:Object,results:Object,q:String,path:String,menu: Object,menus: Object,
});

const form = useForm({
    q: props.q,
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

const alert = ref(props.alert);

if (alert.value) {

swal.fire(
    alert.value.title,
    alert.value.text,
    alert.value.icon,
)

alert.value = null
};

if (hasErrors.value == true) {

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
    title: [errors.value.q ? errors.value.q +'<br>' :'' ,
            ],
    icon:'error',
})

}

const submitTime = ()=>{
Inertia.visit(route('blog.index'),{ only: [errors.value,hasErrors.value,props.alert] })
}
const submit = (data)=>{
        console.log(form.q);
        form.q = data
    if (form.q) {
        form.get(route('blog.index'))
    }
    else
    {
        let text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }

};
const submitDel = ()=>{

if (form.q !== null) {
    form.q = null
    form.get(route('blog.index'))
}
else
{
    let text = 'موارد ستاره دار الزامی است.'
    validate(text)
}

};

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


</script>
<template >
    <Header :companies="props.companies" :results="props.results"  :menus="props.menus" :cart="props.cart" :menu="props.menu" @event-submit-blog-filter="submit" />
    <main class="main">
            <div class="page-header mt-30 mb-75">
                <div class="container">
                    <div class="archive-header">
                        <div class="row align-items-center">
                            <div class="col-xl-3">
                                <h1 class="mb-15">بلاگ </h1>
                                <div class="breadcrumb">
                                    <!-- <a href="index.html" rel="nofollow">صفحه اصلی<i class="fi-rs-home mr-5"></i></a>
                                    <span></span> بلاگ -->
                                </div>
                            </div>
                            <!-- <div class="col-xl-9 text-end d-none d-xl-block">
                                <ul class="tags-list">
                                    <li class="hover-up">
                                        <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Shopping</a>
                                    </li>
                                    <li class="hover-up active">
                                        <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Recips</a>
                                    </li>
                                    <li class="hover-up">
                                        <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Kitchen</a>
                                    </li>
                                    <li class="hover-up">
                                        <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>News</a>
                                    </li>
                                    <li class="hover-up mr-0">
                                        <a href="blog-category-grid.html"><i class="fi-rs-cross mr-10"></i>Food</a>
                                    </li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-content mb-50">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="shop-product-fillter mb-50">
                                <div class="totall-product">
                                    <h2>
                                        <!-- <img class="w-36px mr-10" src="assets/imgs/theme/icons/category-1.svg" alt="" /> -->
                                         بلاگ {{ props.companies.name_show }}
                                    </h2>
                                </div>

                            </div>
                            <div class="loop-grid">
                                <div class="row" v-if="props.blogs.total > 0">
                                    <article class="col-xl-3 col-lg-4 col-md-6 text-center hover-up mb-30 animated" v-for="blog,index in props.blogs.data" :key="index" >
                                        <div class="post-thumb" >
                                            <Link :href="route('blog.show',[blog.slug])" v-if="blog.type">

                                                <img v-if="blog.image && blog.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+blog.image.url" class="border-radius-15" :alt="blog.name">
                                                <img v-else-if="props.companies" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="border-radius-15":alt="props.companies.name">
                                            </Link>
                                            <div class="entry-meta">
                                                <!-- <a class="entry-meta meta-2" href="blog-category-grid.html"><i class="fi-rs-heart"></i></a> -->
                                            </div>
                                        </div>
                                        <div class="entry-content-2">
                                            <h6 class="mb-10 font-sm"><Link class="entry-meta text-muted" :href="route('blog.index','type')+blog.group.name+'#'">{{ blog.group.name }}</Link></h6>
                                            <h4 class="post-title mb-15">
                                                <Link :href="route('blog.show',[blog.slug])">{{ blog.title }}</Link>
                                            </h4>
                                            <div class="entry-meta font-xs color-grey mt-10 pb-10">
                                                <div>
                                                    <span class="post-on mr-10">{{ moment(blog.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}</span>
                                                    <span class="hit-count has-dot mr-10">{{ blog.views_count }} بازدید</span>
                                                    <span class="hit-count has-dot">{{ blog.comments_count }}نظرات</span>
                                                </div>
                                            </div>
                                        </div>
                                    </article>

                                </div>
                                <div class="row" v-if="props.results.total > 0">
                                    <article class="col-xl-3 col-lg-4 col-md-6 text-center hover-up mb-30 animated" v-for="blog,index in props.results.data" :key="index" >
                                        <div class="post-thumb" >
                                            <Link :href="route('blog.show',[blog.slug])" v-if="blog.type">

                                                <img v-if="blog.image && blog.image.status == 4" :src="$page.props.ziggy.url+'/storage/'+blog.image.url" class="border-radius-15" :alt="blog.name">
                                                <img v-else-if="props.companies" :src="$page.props.ziggy.url+'/storage/'+props.companies.image.url" class="border-radius-15":alt="props.companies.name">
                                            </Link>
                                            <div class="entry-meta">
                                                <!-- <a class="entry-meta meta-2" href="blog-category-grid.html"><i class="fi-rs-heart"></i></a> -->
                                            </div>
                                        </div>
                                        <div class="entry-content-2">
                                            <h6 class="mb-10 font-sm"><Link class="entry-meta text-muted" :href="route('blog.index','type')+blog.group.name+'#'">{{ blog.group.name }}</Link></h6>
                                            <h4 class="post-title mb-15">
                                                <Link :href="route('blog.show',[blog.slug])">{{ blog.title }}</Link>
                                            </h4>
                                            <div class="entry-meta font-xs color-grey mt-10 pb-10">
                                                <div>
                                                    <span class="post-on mr-10">{{ moment(blog.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}</span>
                                                    <span class="hit-count has-dot mr-10">{{ blog.views_count }} بازدید</span>
                                                    <span class="hit-count has-dot">{{ blog.comments_count }}نظرات</span>
                                                </div>
                                            </div>
                                        </div>
                                    </article>

                                </div>
                            </div>
                            <div class="pagination-area mb-20 mt-20" v-if="props.blogs && props.blogs.total > 9  ">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-start">
                                        <!-- فلش قبلی -->
                                        <li
                                            class="page-item"
                                            :class="{ disabled: !props.blogs.prev_page_url || props.blogs.current_page === 1 }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="
                                                    props.blogs.prev_page_url && props.blogs.current_page > 1
                                                        ? props.blogs.prev_page_url
                                                        : '#'
                                                "
                                                preserve-scroll
                                                preserve-state
                                                aria-disabled="props.blogs.current_page === 1"
                                            >
                                                <i class="fi-rs-arrow-small-right"></i>
                                            </Link>
                                        </li>

                                        <!-- صفحه اول -->
                                        <li class="page-item" :class="{ active: props.blogs.current_page === 1 }">
                                            <Link
                                                class="page-link"
                                                :href="getPageUrl(props.blogs.first_page_url, 1)"
                                                preserve-scroll
                                                preserve-state
                                                >1</Link
                                            >
                                        </li>

                                        <!-- ... قبل از currentPage -2 -->
                                        <li class="page-item" v-if="props.blogs.current_page > 4">
                                            <span class="page-link dot">...</span>
                                        </li>

                                        <!-- صفحات وسط (currentPage -2 تا currentPage +2) -->
                                        <template v-for="i in 5" :key="i">
                                            <li
                                                class="page-item"
                                                v-if="
                                                    props.blogs.current_page - 3 + i > 1 &&
                                                    props.blogs.current_page - 3 + i < props.blogs.last_page
                                                "
                                                :class="{ active: props.blogs.current_page === props.blogs.current_page - 3 + i }"
                                            >
                                                <Link
                                                    class="page-link"
                                                    :href="getPageUrl(props.blogs.path, props.blogs.current_page - 3 + i)"
                                                    preserve-scroll
                                                    preserve-state
                                                >
                                                    {{ props.blogs.current_page - 3 + i }}
                                                </Link>
                                            </li>
                                        </template>

                                        <!-- ... بعد از currentPage +2 -->
                                        <li class="page-item" v-if="props.blogs.current_page < props.blogs.last_page - 3">
                                            <span class="page-link dot">...</span>
                                        </li>

                                        <!-- صفحه آخر -->
                                        <li
                                            class="page-item"
                                            v-if="props.blogs.last_page !== 1"
                                            :class="{ active: props.blogs.current_page === props.blogs.last_page }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="getPageUrl(props.blogs.path, props.blogs.last_page)"
                                                preserve-scroll
                                                preserve-state
                                            >
                                                {{ props.blogs.last_page }}
                                            </Link>
                                        </li>

                                        <!-- فلش بعدی -->
                                        <li
                                            class="page-item"
                                            :class="{
                                                disabled:
                                                    !props.blogs.next_page_url || props.blogs.current_page === props.blogs.last_page,
                                            }"
                                        >
                                            <Link
                                                class="page-link"
                                                :href="
                                                    props.blogs.next_page_url && props.blogs.current_page < props.blogs.last_page
                                                        ? props.blogs.next_page_url
                                                        : '#'
                                                "
                                                preserve-scroll
                                                preserve-state
                                                aria-disabled="props.blogs.current_page === props.blogs.last_page"
                                            >
                                                <i class="fi-rs-arrow-small-left"></i>
                                            </Link>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="pagination-area mb-20 mt-20" v-if="props.results && props.results.total > 9  ">
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

                        </div>
                    </div>
                </div>
            </div>
        </main>
     <Footer :companies="props.companies" :socials="props.socials" :time="props.time" :menus="props.menus" :path="props.path" />
</template>

