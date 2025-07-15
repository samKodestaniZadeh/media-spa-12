<script setup>


import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import {  computed,ref,watch,onMounted  } from 'vue';
import {Link, useForm,usePage,router} from '@inertiajs/vue3';
import swal from 'sweetalert2';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const props = defineProps({
    cart:Object,wallet:Number,alert: Object, users: Object, orders: Object, notifications: Object,
    dark: String,companies:Object,descriptions:Object,asidemini:String,path:String,roles:Object,

});

const form = useForm({
    id: null,
});


const asidemini = ref([props.asidemini]);
const dark = ref([props.dark]);
const offcanvas = ref([]);
const show = ref([]);

const darkmode = () => {
    if( dark.value == '')
    {
        dark.value = 'dark'
    }
    else
    {
        dark.value = ''
    }
}

const minimize = () => {
    if(  asidemini.value == '' && offcanvas.value == 'offcanvas-active')
    {
        offcanvas.value = ''
        show.value = ''
    }
    else if(asidemini.value == '')
    {
        asidemini.value = 'aside-mini'
    }
    else
    {
        asidemini.value = ''

    }

}

const offcanvas_aside = () => {

    if( offcanvas.value == '')
    {
        offcanvas.value = 'offcanvas-active'
        show.value = 'show'
    }
    else
    {
        offcanvas.value = ''
        show.value = ''
    }


}
onMounted(() => {
  setInterval(() => {
    router.reload({
      only: ['notifications']
    });
  }, 50000);
});
</script>
<template>

    <body >
        <div class="screen-overlay" ></div>
    <Header :cart="props.cart"  :roles="props.roles" :alert="props.alert" :users="props.users" :wallet="props.wallet"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

        <main class="main-wrap rtl">
            <section class="content-main ">
                <div class="content-header">
                    <div>
                        <h2 class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></h2>
                        <p v-if="props.descriptions" v-html="props.descriptions.text"></p>
                    </div>
                </div>
                <div class="col-lg-12" v-if=" props.notifications[0]">
                    <div class="card mb-4">
                        <article class="card-body">
                            <h5 class="card-title">فعالیت های اخیر</h5>
                            <ul class="verti-timeline list-unstyled font-sm"
                                v-for="(notification,index) in props.notifications" :key="index">
                                <li class="event-list"  :class="[index == '0'? 'active':'']" >
                                    <div class="event-timeline-dot">
                                        <i class="material-icons md-play_circle_outline font-xxl " :class="[index == '0'? 'animation-fade-right':'']"></i>
                                    </div>
                                    <div class="media">
                                        <div class="me-3">
                                            <h6>
                                                <span>
                                                    {{ moment(notification.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                                </span>
                                                <i class="material-icons md-trending_flat text-brand ml-15 d-inline-block"></i>
                                            </h6>
                                        </div>
                                        <div class="media-body" v-if="notification.data.id">
                                            <Link
                                                :href="route(notification.data.route,[notification.data.id])+'?'+'id'+'='+notification.id">
                                                {{ notification.data.massage }}
                                            </Link>
                                            <Link
                                                :href="route('dashboard.create')+'?'+'id'+'='+notification.id" class="me-2">
                                                حذف
                                            </Link>
                                        </div>
                                        <div class="media-body" v-else>
                                            <Link :href="route(notification.data.route)+'?'+'id'+'='+ notification.id">
                                                {{ notification.data.massage }}
                                            </Link>
                                            <Link
                                                :href="route('dashboard.create')+'?'+'id'+'='+notification.id" class="me-2">
                                                حذف
                                            </Link>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </article>
                    </div>
                </div>
            </section>
            <Footer :companies="props.companies" />
        </main>
    </body>
</template>
