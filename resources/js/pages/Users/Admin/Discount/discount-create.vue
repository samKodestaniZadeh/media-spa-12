<script setup>
import Footer from '@/Pages/Users/Buyer/footer.vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm, usePage } from '@inertiajs/vue3';
import swal from 'sweetalert2';
import { computed, ref } from 'vue';
import DatePicker from 'vue3-persian-datetime-picker';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    users: Object,
    cartPrice: Number,
    cartCount: Number,
    cartDiscount: Number,
    cartCoupon: Number,
    cartTotal: Number,
    wallet: Number,
    notifications: Object,
    results: Object,
    companies: Object,
    descriptions: Object,
    menus: Object,
    subjects: Object | String,
    path: String,
    alert: Object,
    cart: Object,
});

const form = useForm({
    code: null,
    percent: null,
    number: null,
    expired: null,
    percent_min: null,
    percent_max: null,
    results: [],
    status: null,
    subject: props.subjects ? props.subjects : null,
});

const validate = (text) => {
    swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', swal.stopTimer);
            toast.addEventListener('mouseleave', swal.resumeTimer);
        },
    }).fire({
        title: text,
        icon: 'error',
    });
};

const alert = ref(props.alert);

if (alert.value) {
    if (alert.value.title) {
        swal.fire(props.alert.title, props.alert.text, props.alert.icon);
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
            },
        }).fire({
            title: props.alert.text,
            icon: props.alert.icon,
        });
    }

    alert.value = null;
}

if (hasErrors.value == true) {
    swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', swal.stopTimer);
            toast.addEventListener('mouseleave', swal.resumeTimer);
        },
    }).fire({
        title: [
            errors.value.number ? errors.value.number + '<br>' : '',
            errors.value.expired ? errors.value.expired + '<br>' : '',
            errors.value.percent_min ? errors.value.percent_min + '<br>' : '',
            errors.value.percent_max ? errors.value.percent_max + '<br>' : '',
            errors.value.results ? errors.value.results + '<br>' : '',
        ],
        icon: 'error',
    });
}

const submitTime = () => {
    Inertia.visit(route('discountAdmin.create'), { only: [errors.value, hasErrors.value, props.alert] });
};

const submit = () => {
    form.post(route('discountAdmin.store')
        // , {
        //     onFinish: () => submitTime(),
        // }
    );
};

const submitFilter = () => {
    form.get(route('discountAdmin.create'), {
        onFinish: () => submitTime(),
    });
};

const menus = ref([]);
if (props.menus) {
    props.menus.forEach((menu) => {
        menu.children.forEach((element) => {
            menus.value.push(element);
        });
    });
}
</script>
<template>
    <Header
        :cart="props.cart"
        :alert="props.alert"
        :users="props.users"
        :orders="props.orders"
        :notifications="props.notifications"
        :dark="props.dark"
        :companies="props.companies"
    />
    <main class="main-wrap rtl">
        <section class="content-main">
            <div class="row content-header">
                <div class="d-flex col-sm-12">
                    <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                    <td class="me-auto">
                        <button
                            @click="submit"
                            class="btn btn-primary btn-sm font-sm rounded"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">پردازش...</span>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                            <span v-else>ایجاد</span>
                        </button>
                    </td>
                </div>
                <div class="col-sm-12">
                    <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                </div>
            </div>
            <form>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mt-4 bg-white">
                            <div class="card-header d-flex">
                                <h4>تخفیف</h4>
                                <div class="me-auto">
                                    <select v-model.lazy="form.subject" @change="submitFilter" class="form-select">
                                        <option value="All">همه محصولات</option>
                                        <option v-for="(menu, index) in menus" :key="index" :value="menu.id">{{ menu.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" v-if="props.results.total > 0">
                                    <article class="itemlist">
                                        <table class="table-hover table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">انتخاب</th>
                                                    <th>شناسه</th>
                                                    <th>نام</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(result) in props.results.data" :key="result.id">
                                                    <td class="text-center">
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                v-model="form.results[result.id]"
                                                                :value="result.id"
                                                            />
                                                        </div>
                                                    </td>
                                                    <td>{{ result.id }}</td>
                                                    <td>
                                                        <b>{{ result.name }}</b>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mt-4 bg-white">
                            <div class="card-header">
                                <h4>تخفیف رندومی</h4>
                            </div>
                            <div class="card-body">
                                <div class="col-lg-12">
                                    <div class="row gx-2">
                                        <div class="col-lg-6">
                                            <div class="mt-4">
                                                <label class="form-label">کمترین درصد<span class="text-danger">*</span></label>
                                                <input
                                                    v-model.lazy="form.percent_min"
                                                    placeholder="اینجا تایپ کنید"
                                                    type="text"
                                                    class="form-control"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mt-4">
                                                <label class="form-label">بیشترین درصد<span class="text-danger">*</span></label>
                                                <input
                                                    v-model.lazy="form.percent_max"
                                                    placeholder="اینجا تایپ کنید"
                                                    type="text"
                                                    class="form-control"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mt-4">
                                                <label class="form-label">تاریخ انقضا<span class="text-danger">*</span></label>
                                                <date-picker
                                                    v-model="form.expired"
                                                    format="YYYY-MM-DD HH:mm:ss"
                                                    display-format="dddd jDD jMMMM jYYYY"
                                                    color="#1ABC9C"
                                                    type="datetime"
                                                ></date-picker>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <Footer :companies="props.companies" />
    </main>
</template>
