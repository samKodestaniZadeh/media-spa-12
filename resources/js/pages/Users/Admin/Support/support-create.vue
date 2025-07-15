<script setup>

import { computed,ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import Editor from '@tinymce/tinymce-vue';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    menus:Object,orders:Object,users:Object,notifications:Object,companies:Object,descriptions:Object,
    alert:Object,path:String,user:Object,wallet:Number,cart:Object
});

const form = useForm({
    menu : null,
    recepiant:null,
    subject:null,
    text:null,
    destination:null,
    file: null,
    product:null
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
        title: [errors.value.menu ? errors.value.menu +'<br>' :'' ,
                errors.value.recepiant ? errors.value.recepiant +'<br>' :'' ,
                errors.value.subject ? errors.value.subject +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.destination ? errors.value.destination +'<br>':'',
                errors.value.file ? errors.value.file +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('supportAdmin.create'),{ only: [errors.value,hasErrors.value,props.alert] })
}

const menus = ref([]);

if (props.menus && props.menus.length > 0) {

    props.menus.forEach(element => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section => {
                        if(section.name == 'supports')
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
const descriptions = ref([]);
const sections = ref([]);
const subs = ref();
const step = ref('step');

const recepiant = () => {

    if (menu.value.length > 0) {
        menu.value.splice(0)
    }
    if (descriptions.value.length > 0) {

        descriptions.value.splice(0)
    }
    form.subject = null
    subs.value = null
    menus.value.forEach(element => {

        if (form.recepiant == element && element.children.length > 0 )
        {

            element.children.forEach(child =>
            {
                if(child.routes.length > 0){
                    child.routes.forEach(route => {
                        if (route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section =>
                                {
                                    if(section.name == 'supports')
                                    {
                                        menu.value.push(child)
                                    }
                                });
                            }
                        }
                    });
                }
            });

            if (element.descriptions.length > 0)
            {
                element.descriptions.forEach(description => {
                    if (description) {
                        descriptions.value.push(description);
                    }
                })
            }
        }
    });
};

const subject = () => {
    if (sections.value.length > 0) {
        sections.value.splice(0)
    }
    if (descriptions.value.length > 0) {

        descriptions.value.splice(0)
    }

    menu.value.forEach(element => {
         if(form.subject ==  element && element.routes.length > 0)
        {
            element.routes.forEach( route => {
                if (route.name == props.path)
                {
                    if (element.sections.length > 0)
                    {
                        element.sections.forEach(section => {

                            if(section.name == 'subs')
                            {
                               subs.value = true
                            }
                            else if(section.name == 'supports')
                            {
                                subs.value = false
                            }
                            else if(section.name == 'destination')
                            {
                                form.destination = true
                            }
                        });
                    }
                }
            })

            if (element.descriptions.length > 0)
            {
                element.descriptions.forEach(description => {
                    if (description) {
                        descriptions.value.push(description);
                    }
                })
            }
        }

    });
};


const next = () => {

    if (step.value == 'step')
    {
        step.value = 'step2'
    }
    else if (step.value == 'step2')
    {
        if (subs.value == true && form.subject)
        {
            if (form.recepiant && form.subject && form.destination) {
                step.value = 'step3'
                subs.value = null
            }
            else
            {
                let text
                text = 'موارد ستاره دار الزامی است.'
                validate(text)
            }
        }
        else if(form.recepiant && form.subject)
        {
            step.value = 'step3'
            subs.value = null
        }
        else
        {
            let text
            text = 'موارد ستاره دار الزامی است.'
            validate(text)
        }
    }

};
const submit = () => {
    if (form.recepiant == null||form.subject == null||form.destination == null||form.text == null) {
        let text
        text = 'موارد ستاره دار الزامی است.'
        validate(text)
    }
    else
    {

        form.post(route('supportAdmin.store'),{
            onFinish:() => submitTime()
        })
    }
};

const orders = ref(props.user);
if (props.tarahis) {
    props.tarahis.forEach(tarahi => {
        orders.value.push(tarahi)
    });
}
if (props.orders) {
    props.orders.forEach(order => {
        order.products.forEach(product => {
            orders.value.push(product)
        });

    });
}

</script>
<template>
<Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

        <main class="main-wrap rtl">
            <section class="content-main">
                <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <!-- <Link :href="route('support.create')" class="btn btn-primary btn-sm rounded font-sm">ایجاد</Link> -->
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
                <div class="card mb-4">
                    <header class="card-header">
                        <h4>اطلاعات تیکت</h4>
                    </header>
                    <div class="card-body" v-if="step == 'step2'">
                        <form >
                            <div class=" mb-4">
                                <div class="d-flex" >
                                    <div class="col-sm-4"  id="recepiant">
                                        <label class="form-label">انتخاب بخش<span class="text-danger me-1">*</span></label>
                                        <select v-model.lazy="form.recepiant" @change="recepiant" name="recepiant" class="form-select" required aria-label="select example">
                                                <option  v-for="(menu,index) in menus" :key="index" v-if="menus.length > 0" :value="menu" >{{menu.name}}</option>
                                                <option v-else disabled >گزینه ای یافت نشد.</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 me-1"  id="subject" v-if="menu.length > 0">
                                        <label class="form-label">موضوع<span class="text-danger me-1">*</span></label>
                                        <select  v-model.lazy="form.subject" @change="subject"  name="subject" class="form-select" required aria-label="select example">
                                            <option v-for="(section,index) in menu" :key="index" v-if="menu.length > 0" :value="section" >{{section.name}}</option>
                                            <option v-else disabled >گزینه ای یافت نشد.</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 me-1"  id="product" v-if="subs == true">
                                        <label  class="form-label">کاربر<span class="text-danger me-1">*</span></label>
                                        <select v-model.lazy="form.destination" name="product" class="form-select"  aria-label="select example">
                                                <option v-for="(order,index) in orders" :key="index" :value="order">
                                                    <template v-if="order.name">{{ order.name_show }}</template>
                                                </option>
                                                <option v-if="orders == ''" disabled>گزینه ای یافت نشد.</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer" v-if="step == 'step2'">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item" v-for="description,index in descriptions" :key="index">
                                <h2 class="accordion-header" :id="'heading'+index">
                                <button class="accordion-button" :class="index > 0 ? 'collapsed' : ''" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+index" :aria-expanded="index > 0 ? false : true" :aria-controls="'collapse'+index" v-html="description.subject">
                                </button>
                                </h2>
                                <div :id="'collapse'+index" class="accordion-collapse " :class="index > 0 ? 'collapse' : 'collapse show'" :aria-labelledby="'heading'+index" data-bs-parent="#accordionExample">
                                <div class="accordion-body" v-html="description.text">
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" v-if="step == 'step' || subs == true && form.destination || subs == false">
                        <div class="text-center">
                            <strong>آیا مایل به ارسال تیکت هستید؟</strong>
                            <div class="col">
                                <button  @click.prevent="next" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary">بله</button>
                                <Link :href="route('dashboard.index')" class="btn btn-secondary btn-sm me-2">انصراف</Link>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-4"  id="text" v-if="step == 'step3'">
                        <label  class="form-label">شرح<span class="text-danger me-1">*</span></label>
                        <!-- <textarea  v-model.lazy="form.text" name="text" placeholder="اینجا تایپ کنید" class="form-control" rows="4" required autofocus autocomplete="text"></textarea> -->
                        <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" placeholder="اینجا تایپ کنید" />
                    </div>
                    <div class="card-body" v-if="step == 'step3'">
                        <label class="form-label"> آپلودفایل<span class="text-danger me-1"></span></label>
                        <div class="input-upload">
                            <!-- <img src="assets/imgs/theme/upload.svg" alt="" /> -->
                            <input name="file" class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file" />
                            <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                            {{ form.progress.percentage }}%
                            </progress>
                        </div>
                    </div>
                    <div class="card-body" v-if="step == 'step3'">
                        <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">پردازش...</span>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                            <span v-else >ارسال</span>
                        </button>
                    </div>
                </div>
            </section>
            <Footer :companies="props.companies" />
        </main>
</template>
