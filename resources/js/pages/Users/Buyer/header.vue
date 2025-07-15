<script setup>
// import BreezeGuestLayout from '@/Layouts/Guest.vue';
import {computed,ref,onMounted, onBeforeUnmount,watch} from 'vue';
import {Head,Link,useForm,usePage} from '@inertiajs/vue3';
import swal from 'sweetalert2';

const errors = computed(() => usePage().props.errors);
const props = defineProps({
    products: Object, cart:Object, codes: Object, alert: Object, users: Object, orders: Object, notifications: Object,
    dark: String,companies:Object,asidemini:String,path:String,descriptions: Object,roles: Object,wallet:Number
});

const asidemini = ref([props.asidemini]);
const dark = ref([props.dark]);
const offcanvas = ref([]);
const show = ref([]);
const notif = ref([]);

watch(() => props.notifications, (val) => {
  notif.value = val;

}, { immediate: true });

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

const emit = defineEmits(['eventDarkmode', 'eventMinimize', 'eventOffcanvas_aside']);

const darkmode = () => {
    if (dark.value == '') {
        dark.value = 'dark'
        emit('eventDarkmode', dark.value)
    } else {
        dark.value = ''
        emit('eventDarkmode', dark.value)
    }
}

const minimize = () => {
    if (asidemini.value == '' && offcanvas.value == 'offcanvas-active') {

        offcanvas.value = ''
        show.value = ''
        emit('eventOffcanvas_aside', offcanvas.value, show.value)

    } else if (asidemini.value == '') {
        asidemini.value = 'aside-mini'
        emit('eventMinimize', asidemini.value)
    } else {
        asidemini.value = ''
        emit('eventMinimize', asidemini.value)

    }

}

const offcanvas_aside = () => {

    if (offcanvas.value == '') {
        offcanvas.value = 'offcanvas-active'
        show.value = 'show'
        emit('eventOffcanvas_aside', offcanvas.value, show.value)

    } else {
        offcanvas.value = ''
        show.value = ''
        emit('eventOffcanvas_aside', offcanvas.value, show.value)
    }

}


onMounted(() => {
    const scriptClass = 'dynamic-script';

    function addJs(address) {
        // حذف اگر همین فایل از قبل وجود داشته باشه (پیشگیری تکراری)
        const exists = document.querySelector(`script[src="${address}"]`);
        if (exists) return;

        const script = document.createElement('script');
        script.src = address;
        script.async = false;
        script.defer = true;
        script.classList.add(scriptClass);
        document.body.appendChild(script);
    }

    const items = [
        "/assets/backend/assets/js/vendors/jquery-3.6.0.min.js",
        "/assets/backend/assets/js/vendors/bootstrap.bundle.min.js",
        "/assets/backend/assets/js/vendors/select2.min.js",
        "/assets/backend/assets/js/vendors/perfect-scrollbar.js",
        "/assets/backend/assets/js/vendors/jquery.fullscreen.min.js",
        "/assets/backend/assets/js/vendors/chart.js",
        "/assets/backend/assets/js/main.js",
        "/assets/backend/assets/js/custom-chart.js",
    ];

    const uniqueItems = [...new Set(items)];
    uniqueItems.forEach(addJs);
});

// موقع ترک این کامپوننت، همه فایل‌های اضافه‌شده رو حذف کن
onBeforeUnmount(() => {
    document.querySelectorAll('script.dynamic-script').forEach(script => {
        script.remove();
    });
});

</script>
<template>
    <head>
        <link :href="$page.props.ziggy.url+'/assets/backend/assets/css/main.css'" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" :href="$page.props.ziggy.url+'/assets/css/mohi.css'">
    </head>

<body>
    <div class="screen-overlay"></div>
    <aside class="navbar-aside rtl" id="offcanvas_aside">
        <div class="aside-top">
            <Link :href="route('index')" class="brand-wrap" v-if="props.companies && props.companies.image && props.companies.image.status == 4">
            <img :src="$page.props.ziggy.url + '/storage/' + props.companies.image.url" class="" height="40" width="60" :alt="props.companies.name_show" />
            </Link>

        </div>
        <nav>
            <ul class="menu-aside">
                <li class="menu-item" :class="[$page.url == '/users/dashboard' ? 'active' : '']">
                    <Link class="menu-link" :href="route('dashboard.index')">
                    <i class="icon material-icons md-home"></i>
                    <span class="text">پیشخوان</span>
                    </Link>
                </li>
                <li class="menu-item has-submenu" :class="[$page.url == '/users/order' ? 'active' : '',
                        $page.url == '/users/favorite' ? 'active' : '', $page.url == '/users/tarahi' ? 'active' : '']">
                    <a class="menu-link">
                        <i class="icon material-icons md-shopping_cart"></i>
                        <span class="text">خرید</span>
                    </a>
                    <div class="submenu text-a-l collapse " id="multiCollapseExample1">
                        <Link :href="route('website_templates.index', 'q') + 'all'">محصولات</Link>
                        <Link :class="[$page.url == '/users/order' ? 'active' : '']" :href="route('order.index')">محصولات
                        خریداری شده
                        </Link>
                        <Link :class="[$page.url == '/users/favorite' ? 'active' : '']" :href="route('favorite.index')">
                        علاقه مندی ها
                        </Link>
                        <Link :href="route('website_design.index', 'q') + 'all'">طراحی وبسایت</Link>
                        <Link :class="[$page.url == '/users/tarahi' ? 'active' : '']" :href="route('tarahi.index')">
                        سفارشات وبسایت من
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" :class="[$page.url == '/users/support' ? 'active' : '',
                        $page.url == '/users/support/create' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons ">
                            <img src="https://img.icons8.com/ios-filled/20/95A5A6/headset.png" />
                        </i>
                        <span class="text">پشتیبانی</span>
                    </a>
                    <div class="submenu text-a-l collapse multi-collapse" id="multiCollapseExample4">
                        <Link :class="[$page.url == '/users/support' ? 'active' : '']" :href="route('support.index',)">تیکت
                        من
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" :class="[$page.url == '/users/bank' ? 'active' : '',
                     $page.url == '/users/payment' ? 'active' : '', $page.url == '/users/payment/create' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons md-monetization_on"></i>
                        <span class="text">مالی</span>
                    </a>
                    <div class="submenu text-a-l collapse multi-collapse" id="multiCollapseExample5">
                        <Link :class="[$page.url == '/users/bank' ? 'active' : '']" :href="route('bank.index')">
                        اطلاعات
                        حساب
                        </Link>
                        <Link :class="[$page.url == '/users/payment/create' ? 'active' : '']" :href="route('payment.create')">
                        برداشت حساب
                        </Link>
                        <Link :class="[$page.url == '/users/payment' ? 'active' : '']" :href="route('payment.index')">گردش
                        حساب
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" :class="[$page.url == '/users/profile' ? 'active' : '',
                        $page.url == '/users/identity' ? 'active' : '', $page.url == '/users/sikll' ? 'active' : '',
                        $page.url == '/users/network' ? 'active' : '', $page.url == '/users/social' ? 'active' : '',]">
                    <a class="menu-link" >
                        <i class="icon material-icons md-person"></i>
                        <span class="text">حساب کاربری</span>
                    </a>
                    <div class="submenu text-a-l collapse multi-collapse" id="multiCollapseExample6">
                        <Link :class="[$page.url == '/users/profile' ? 'active' : '',
                            $page.url == '/users/identity' ? 'active' : '', $page.url == '/users/sikll' ? 'active' : '',
                            $page.url == '/users/network' ? 'active' : '', $page.url == '/users/social' ? 'active' : '']" :href="route('profile.index')">
                        اطلاعات کاربری
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" :class="[$page.url == '/users/comment' ? 'active' : '',]">
                    <a class="menu-link" >
                        <i class="icon material-icons md-comment"></i>
                        <span class="text">نظرات</span>
                    </a>
                    <div class="submenu text-a-l collapse multi-collapse" id="multiCollapseExample19">
                        <Link :class="[$page.url == '/users/comment' ? 'active' : '']" :href="route('comment.index')">
                            نظرات من
                        </Link>
                    </div>
                </li>
                <!-- <li class="menu-item" :class="[$page.url == '/users/comment' ? 'active' : '']">
                    <Link :class="[$page.url == '/users/comment' ? 'active' : '']" class="menu-link" :href="route('comment.index')">
                    <i class="icon material-icons md-comment"></i>
                    <span class="text">نظرات من</span>
                    </Link>
                </li> -->

            </ul>
            <hr />

            <ul class="menu-aside" v-for="role,index in props.users.roles" :key="index">

                <li class="menu-item has-submenu" v-if="role.id == 1" :class="[$page.url == '/users/product' ? 'active' : '',
                     $page.url == '/users/product/create' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/material-rounded/24/95A5A6/add-shopping-cart.png" />
                        </i>
                        <span class="text">فروش</span>
                    </a>
                    <div class="submenu text-a-l collapse " id="multiCollapseExample2">
                        <Link :class="[$page.url == '/users/product' ? 'active' : '']" :href="route('product.index')">
                        محصولات من
                        </Link>
                    </div>
                </li>

            </ul>
            <hr />
            <ul class="menu-aside" v-for="role,index in props.users.roles" :key="index">

                <li class="menu-item has-submenu" v-if="role.id == 2" :class="[$page.url == '/users/tarahiDesigner' ? 'active' : '']">
                    <a class="menu-link">
                        <i class="icon material-icons ">
                            <img src="https://img.icons8.com/material/24/95A5A6/laptop-settings.png" />
                        </i>
                        <span class="text">طراحی وبسایت</span>
                    </a>
                    <div class="submenu text-a-l collapse " id="multiCollapseExample3">
                        <Link :class="[$page.url == '/users/tarahiDesigner' ? 'active' : '']" :href="route('tarahiDesigner.index')">لیست سفارشات
                        </Link>
                    </div>
                </li>

            </ul>
            <hr />

            <ul class="menu-aside" v-for="role,index in props.users.roles" :key="index">
                <li class="menu-item has-submenu" v-if="role.id == 3" :class="[$page.url == '/users/company' ? 'active' : '',
                    $page.url == '/users/session' ? 'active' : '', $page.url == '/users/menu' ? 'active' : '',
                    $page.url == '/users/page' ? 'active' : '', $page.url == '/users/description' ? 'active' : '',
                    $page.url == '/users/newsletterAdmin' ? 'active' : '', $page.url == '/users/socialAdmin' ? 'active' : '',
                    $page.url == '/users/route' ? 'active' : '', $page.url == '/users/section' ? 'active' : '',
                    $page.url == '/users/section' ? 'active' : '', $page.url == '/users/section' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons md-settings"></i>
                        <span class="text">عمومی</span>
                    </a>
                    <div class="submenu text-a-l collapse multi-collapse" id="multiCollapseExample7">
                        <Link :class="[$page.url == '/users/company' ? 'active' : '',
                        $page.url == '/users/menu' ? 'active' : '', $page.url == '/users/description' ? 'active' : '',
                        $page.url == '/users/page' ? 'active' : '', $page.url == '/users/newsletterAdmin' ? 'active' : '',
                        $page.url == '/users/socialAdmin' ? 'active' : '', $page.url == '/users/route' ? 'active' : '',
                        $page.url == '/users/section' ? 'active' : '',]" :href="route('company.index')">تعاریف
                        پایه
                        </Link>
                        <Link :class="[$page.url == '/users/session' ? 'active' : '']" :href="route('session.index')">آماربازدید
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" v-if="role.id == 3"  :class="[$page.url == '/users/supportAdmin' ? 'active' : '',
                        $page.url == '/users/supportAdmin/create' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons ">
                            <img src="https://img.icons8.com/ios-filled/20/95A5A6/headset.png" />
                        </i>
                        <span class="text">پشتیبانی</span>
                    </a>
                    <div class="submenu text-a-l collapse multi-collapse" id="multiCollapseExample17">

                        <Link :class="[$page.url == '/users/supportAdmin' ? 'active' : '']" :href="route('supportAdmin.index')">
                        تیکت ها
                        </Link>

                    </div>
                </li>
                <li class="menu-item has-submenu" v-if="role.id == 3"  :class="[$page.url == '/users/paymentAdmin' ? 'active' : '',
                    $page.url == '/users/bankAdmin' ? 'active' : '',$page.url == '/users/depositAdmin' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/external-phatplus-lineal-phatplus/24/95A5A6/external-financial-global-crisis-phatplus-lineal-phatplus.png" />
                        </i>
                        <span class="text">مدیریت مالی</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample8">
                        <Link :class="[$page.url == '/users/bankAdmin' ? 'active' : '']" :href="route('bankAdmin.index')">
                        بانک ها
                        </Link>
                    </div>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample8">
                        <Link :class="[$page.url == '/users/paymentAdmin' ? 'active' : '']" :href="route('paymentAdmin.index')">
                          برداشت
                        </Link>
                    </div>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample8">
                        <Link :class="[$page.url == '/users/depositAdmin' ? 'active' : '']" :href="route('depositAdmin.index')">
                          واریز
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" v-if="role.id == 3"  :class="[$page.url == '/users/commentAdmin' ? 'active' : '',
                    $page.url == '/users/productAdmin' ? 'active' : '', $page.url == '/users/coupon' ? 'active' : '',
                    $page.url == '/users/discountAdmin' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/windows/24/95A5A6/delivery-settings.png" />
                        </i>
                        <span class="text">مدیریت محصولات</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample9">
                        <Link :class="[$page.url == '/users/productAdmin' ? 'active' : '']" :href="route('productAdmin.index')">لیست محصولات
                        </Link>
                        <Link :class="[$page.url == '/users/commentAdmin' ? 'active' : '']" :href="route('commentAdmin.index')">
                        لیست نظرات
                        </Link>
                        <Link :class="[$page.url == '/users/coupon' ? 'active' : '']" :href="route('coupon.index')">
                        بن تخفیف
                        </Link>
                        <Link :class="[$page.url == '/users/discountAdmin' ? 'active' : '']" :href="route('discountAdmin.index')">
                        تخفیفات
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" v-if="role.id == 3"  :class="[$page.url == '/users/webdesign' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/material/24/95A5A6/laptop-settings.png" />
                        </i>
                        <span class="text">مدیریت وبسایت</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample18">
                        <Link :class="[$page.url == '/users/webdesign' ? 'active' : '']" :href="route('webdesign.index')">لیست طراحي وبسایت</Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" v-if="role.id == 3"  :class="[$page.url == '/users/tarahiAdmin' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/ios-filled/20/95A5A6/product.png" />
                        </i>
                        <span class="text">مدیریت سفارشات</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample13">
                        <Link :class="[$page.url == '/users/tarahiAdmin' ? 'active' : '']" :href="route('tarahiAdmin.index')">لیست
                        سفارشات</Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" v-if="role.id == 3"  :class="[$page.url == '/users/blogAdmin' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/material/24/95A5A6/test-passed--v1.png" />
                        </i>
                        <span class="text">مدیریت وبلاگ</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample16">
                        <Link :class="[$page.url == '/users/blogAdmin' ? 'active' : '']" :href="route('blogAdmin.index')">وبلاگ
                        </Link>
                    </div>
                </li>
                <li class="menu-item has-submenu" v-if="role.id == 3"  :class="[$page.url == '/users/profileAdmin' ? 'active' : '',
                    $page.url == '/users/profileAdmin/' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/material/24/95A5A6/conference-background-selected.png" />
                        </i>
                        <span class="text">مدیریت کاربران</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample15">
                        <Link :class="[$page.url == '/users/profileAdmin' ? 'active' : '']" :href="route('profileAdmin.index')">
                        اطلاعات کاربران
                        </Link>
                    </div>
                </li>

            </ul>
            <hr />
            <ul class="menu-aside" v-for="role,index in props.users.roles" :key="index">

                <li class="menu-item has-submenu" v-if="role.id == 4" :class="[$page.url == '/users/userModir' ? 'active' : '']">

                    <a class="menu-link">
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/material/24/95A5A6/conference-background-selected.png" />
                        </i>
                        <span class="text">مدیریت کاربران</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample10">
                        <Link :class="[$page.url == '/users/userModir' ? 'active' : '']" :href="route('userModir.index')">لیست
                        کاربران
                        </Link>
                    </div>

                </li>
                <li class="menu-item has-submenu" v-if="role.id == 4" :class="[$page.url == '/users/orderModir' ? 'active' : '']">
                    <a class="menu-link" >
                        <i class="icon material-icons">
                            <img src="https://img.icons8.com/material/24/95A5A6/area-chart--v1.png" />
                        </i>
                        <span class="text">مدیریت در آمد</span>
                    </a>
                    <div class="submenu text-a-l  collapse multi-collapse" id="multiCollapseExample14">
                        <Link :class="[$page.url == '/users/orderModir' ? 'active' : '']" :href="route('orderModir.index')">در آمد
                        </Link>
                    </div>
                </li>

            </ul>

        </nav>
    </aside>
    <main class="main-wrap ">
        <header class="main-header navbar rtl">
            <div class="col-search">
                <a href="javascript:history.back()"><i class="material-icons md-arrow_forward"></i> برگشت </a>
            </div>
            <div class="col-nav">
                <button @click="offcanvas_aside" class="btn btn-icon btn-mobile ms-auto" data-trigger="#offcanvas_aside"><i class="material-icons md-apps"></i></button>
                <ul class="nav">
                    <li class="nav-item">
                        <Link class="nav-link btn-icon" href="#">
                        <i class="material-icons md-notifications animation-shake"></i>
                        <template v-for="(notification, index) in notif" :key="index">
                            <span v-if="notification.read_at == null" class="badge rounded-pill bg-danger">{{
                            notif.length }}</span>
                        </template>

                        </Link>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="#" @click="darkmode" class="nav-link btn-icon darkmode"> <i
                            class="material-icons md-nights_stay"></i> </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="requestfullscreen nav-link btn-icon"><i
                            class="material-icons md-cast"></i></a>
                    </li> -->
                    <li class="nav-item" v-if="'cart' !== props.path">
                        <Link v-if="props.cart && props.cart.total > 0" class="nav-link btn-icon" :href="route('cart.index') ">
                        <i class="fa-solid fa-basket-shopping-simple"></i>
                        <i class="icon material-icons md-shopping_bag"></i>
                        <span class="badge rounded-pill bg-danger">{{ props.cart.count }}<span class="visually-hidden"></span></span>
                        </Link>
                        <Link v-else class="nav-link btn-icon" :href="route('cart.index')">
                        <i class="fa-solid fa-basket-shopping-simple"></i>
                        <i class="icon material-icons md-shopping_bag"></i>
                        </Link>
                    </li>
                    <li class="dropdown nav-item">
                        <a class="dropdown-toggle nav-link btn-icon" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false">
                            <i class="material-icons md-account_balance_wallet"></i>
                        </a>
                        <div style="left:0px;text-align:right;" class="dropdown-menu dropdown-menu-start" aria-labelledby="dropdownAccount">
                            <a v-if="props.users.profile && props.wallet" class="dropdown-item" href="#">
                                <i class="material-icons md-account_balance_wallet"></i>
                                موجودی: {{ (props.wallet).toLocaleString("fa-IR") }} ریال
                            </a>
                            <a v-else class="dropdown-item" href="#"><i class="material-icons md-account_balance_wallet"></i>موجودی: 0
                                ریال</a>
                        </div>
                    </li>
                    <li class="dropdown nav-item">
                        <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" id="dropdownAccount" aria-expanded="false">
                            <img v-if="props.users.profile && props.users.profile.image && props.users.profile.image.status == 4" style="height:40px" class="img-xs rounded-circle" :src="$page.props.ziggy.url+'/storage/'+ props.users.profile.image.url" :alt="props.users.show_name" />
                            <img v-else style="height:40px" class="img-xs rounded-circle" :src="$page.props.ziggy.url+'/storage/images/default-user.png'" :alt="props.users.show_name" />
                        </a>
                        <div style="left:0px;text-align:right;" class="dropdown-menu dropdown-menu-start" aria-labelledby="dropdownAccount">
                            <Link class="dropdown-item" :href="route('profile.index')"><i class="material-icons md-perm_identity"></i>ویرایش پروفایل
                            </Link>
                            <!-- <a class="dropdown-item" href="#"><i class="material-icons md-settings"></i>تنظیمات کاربری</a>
                            <a class="dropdown-item" href="#"><i class="material-icons md-receipt"></i>صورتحساب</a>
                            <a class="dropdown-item" href="#"><i class="material-icons md-help_outline"></i>مرکز کمک</a> -->
                            <div class="dropdown-divider"></div>

                            <div class="mt-4 flex items-center justify-between">
                                <Link :href="route('logout')" method="post" as="button" class="dropdown-item text-danger"><i class="material-icons md-exit_to_app"></i>خروج
                                </Link>
                            </div>

                        </div>
                    </li>
                </ul>
            </div>
        </header>
    </main>
</body>
</template>
