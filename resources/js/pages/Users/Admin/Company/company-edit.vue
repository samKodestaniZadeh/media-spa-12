<script setup>
import {
    computed,
    ref
} from 'vue';
import {
    usePage
} from '@inertiajs/vue3';
import {
    Head,
    Link,
    useForm
} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    users: Object,
    cartPrice: Number,
    cartCount: Number,
    ostans: Object,
    shahrs: Object,
    cartDiscount: Number,
    cartCoupon: Number,
    cartTotal: Number,
    notifications: Object,
    companies: Object,
    descriptions:Object,
    wallet:Number
});

const form = useForm({
    id: props.companies.id ? props.companies.id : null,
    name: props.companies.name ? props.companies.name : null,
    economical_number: props.companies.economical_number ? props.companies.economical_number : null,
    national_number: props.companies.national_number ? props.companies.national_number : null,
    postal_code: props.companies.postal_code ? props.companies.postal_code : null,
    phone: props.companies.phone ? props.companies.phone : null,
    mobile: props.companies.mobile ? props.companies.mobile : null,
    ostan: props.companies.ostan ? props.companies.ostan : null,
    shahr: props.companies.shahr ? props.companies.shahr : null,
    address: props.companies.address ? props.companies.address : null,
    image: props.companies.image ? props.companies.image.url : null,
    tax: props.companies.tax ? props.companies.tax : null,
    complications: props.companies.complications ? props.companies.complications : null,
    comison: props.companies.comison ? props.companies.comison : null,
    email: props.companies.email ? props.companies.email : null,
    telegram: props.companies.telegram ? props.companies.telegram : null,
    instagram: props.companies.instagram ? props.companies.instagram : null,
    link: props.companies.link ? props.companies.link : null,
    comison_designer: props.companies.comison_designer ? props.companies.comison_designer : null,
    design_damage:props.companies.design_damage ? props.companies.design_damage : null,
});

const submit = () => {

    if (form.name == null && form.economical_number == null && form.national_number == null && form.postal_code == null &&
        form.phone == null && form.mobile == null && form.ostan == null && form.shahr == null && form.address == null &&
        form.image == null && form.tax == null && form.complications && form.comison && form.comison_designer && form.design_damage) {
        errors.value.errors = 'لطفا پس از برسی موارد الزامی مجدد فرم را ارسال نمایید'
    } else {
        form.post(route('company.store'))
    }
};

const ostans = ref(props.ostans);
const shahrs = ref();

const change = () => {

    if (props.shahrs == form.ostan) {
        shahrs.value = props.shahrs
    }

};
</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
        <form>
            <div class="row">
                <div class="col-12">
                    <div class="content-header">
                        <div>
                            <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                            <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                        </div>
                        <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">ثبت</button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">نام<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy.trim="form.name" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">شماره اقتصادی<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.economical_number" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">شماره ثبت / شماره ملی:<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.national_number" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کد پستی:<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.postal_code" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">تلفن:<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.phone" class="form-control" type="phone" placeholder="09123456789" name="tel" autocomplete="tel" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">همراه:<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.mobile" class="form-control" type="tel" placeholder="09123456789" name="tel" autocomplete="tel" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label for="" class="form-label">استان<span class="text-danger">*</span></label>
                                            <select v-model.lazy="form.ostan" @change="change" class="form-select" name="" id="">
                                                <option v-for="(ostan,index ) in ostans" :key="index" v-if="ostans.length > 0">{{ostan}}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" v-if="form.ostan">
                                        <div class="mt-4">
                                            <label for="" class="form-label">شهر<span class="text-danger">*</span></label>
                                            <select v-model.lazy="form.shahr" class="form-select" name="" id="">
                                                <template v-for="(shahr,index1 ) in props.shahrs" :key="index1">
                                                    <option v-for="(shahrs,index ) in shahr" :key="index" v-if="index1 == form.ostan">{{shahrs}}</option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="form-label">آدرس<span class="text-danger">*</span></label>
                                        <textarea v-model.lazy.trim="form.address" placeholder="اینجا تایپ کنید" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>اطلاعات</h4>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">مالیات<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy.trim="form.tax" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">عوارض<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.complications" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کمیسیون<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy.trim="form.comison" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">تلگرام<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.telegram" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">اینستاگرام<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy.trim="form.instagram" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">ایمیل<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.email" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">آدرس دامنه<span class="text-danger">*</span></label>
                                            <div class="row gx-2">
                                                <input v-model.lazy.trim="form.link" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">کمیسیون طراح<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.comison_designer" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row  gx-2">
                                    <div class="col-lg-6">
                                        <div class="mt-4">
                                            <label class="form-label">ضمانت پروژه<span class="text-danger">*</span></label>
                                            <input v-model.lazy.trim="form.design_damage" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>تصویر<span class="text-danger">*</span></h4>
                        </div>
                        <div class="card-body">
                            <div class="input-upload">
                                <img :src="$page.props.ziggy.url+'/storage/'+form.image" alt="" />
                                <input class="form-control" type="file" @input="form.image = $event.target.files[0]" id="image" accept="image/*" />
                                <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                    {{ form.progress.percentage }}%
                                </progress>
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
