<script setup>

import { computed,ref} from 'vue';
import swal from 'sweetalert2';
import { useForm ,usePage} from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import Editor from '@tinymce/tinymce-vue'
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({
    auth:Object,users:Object,tarahis:Object,notifications:Object,menus:Object,wallet:Number,
    companies:Object,descriptions:Object,reqDesigner:Object,alert:Object,path:String,cart:Object,
});

const form = useForm({
    text: props.tarahis.text,price:props.tarahis.price,id:props.tarahis.id,
    group: null,type: null,category: null,title:props.tarahis.title,
    file:props.tarahis.file?props.tarahis.file.url: null,
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
const submitTime = ()=>{
    Inertia.visit(route('tarahi.show',form.id),{ only: [errors.value,hasErrors.value,props.alert] })
}

const submit = () => {

     if (form.text == null || form.price == null || form.id == null || form.group == null || form.type == null || form.category == null
        || form.title == null)
     {
        let text
        text = 'موارد ستاره دار الزامی است.'
           validate(text)
    }
    else if(form.title.indexOf('پروژه') == -1)
    {

        form.post(route('tarahi.store'),{
            onFinish:() => submitTime()
        });
    }
    else
    {
        let text = 'لطفا از کلمه "پروژه" در عنوان استفاده ننمایید.'
        validate(text)
    }
};


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
        title: [errors.value.price ? errors.value.price +'<br>' :'' ,
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.group ? errors.value.group +'<br>' :'' ,
                errors.value.type ? errors.value.type +'<br>' :'' ,
                errors.value.category ? errors.value.category +'<br>' :'' ,
                errors.value.title ? errors.value.title +'<br>':''],
        icon:'error',
    })

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


</script>
<template>
<Header :cart="props.cart" :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount" :wallet="props.wallet"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main">
            <div class="row">
                <div class="col-12">
                    <div class="content-header">
                        <div>
                            <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                            <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                        </div>
                        <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-md rounded font-sm hover-up">
                            <span v-if="form.processing">پردازش...</span>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                            <span v-else>ویرایش</span>
                        </button>
                    </div>
                </div>
                <form>
                    <div class="col-lg-12">
                        <div class="bg-white">
                            <div class="card-header">
                                <h4>اطلاعات</h4>
                            </div>
                            <div class="card-body">
                                <div class="mt-4">
                                    <!-- <label class="form-label">زمان پروژه <span class="text-danger">*</span></label> -->
                                    <div class="row gx-2">
                                        <div class="col-sm-6 col-6">
                                            <div class="form-group">
                                                <label class="form-label"> گروه خدمات<span class="text-danger">*</span></label>
                                                <select v-model.lazy="form.group" @change="group" class="form-select ltr" >
                                                    <option v-if="menus.length > 0" :value="menu" v-for="(menu, index) in menus" :key="index">{{ menu.name }}</option>
                                                    <option v-else disabled>گزینه ای یافت نشد.</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-6">
                                            <div class="form-group">
                                                <label class="form-label"> نوع خدمات <span class="text-danger">*</span></label>
                                                <select v-model.lazy="form.type" @change="type" class="form-select ltr">
                                                    <option v-if="menu.length > 0 && form.group" v-for="(type, index) in menu" :key="index" :value="type">{{ type.name }}</option>
                                                    <option v-else disabled>گزینه ای یافت نشد.</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-2">
                                    <div class="col-sm-6 mt-4">
                                            <label class="form-label"> دسته بندی محصول <span
                                                    class="text-danger">*</span></label>
                                            <select v-model.lazy="form.category" @change="category" class="form-select">
                                                <option v-if="sections.length > 0 && form.type" v-for="(category, index) in sections" :key="index" :value="category">{{ category.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                    <div class="col-sm-6 col-6">
                                        <div class="mt-4">
                                            <label class="form-label">عنوان<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.title" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-2">
                                    <div class="col-sm-6 col-6">
                                        <div class="mt-4">
                                            <label class="form-label">مبلغ<span class="text-danger">*</span></label>
                                            <input v-model.lazy="form.price" placeholder="اینجا تایپ کنید" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-6">
                                        <div class="mt-4">
                                            <label class="form-label">فایل</label>
                                            <input  class="form-control" type="file" @input="form.file = $event.target.files[0]"  id="file"  accept="zip/rar/*"/>
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="5">{{ form.progress.percentage }}%</progress>
                                            <a v-if="props.tarahis.file" :href="route('download.edit',props.tarahis.file.id)" method="put">نمایش فایل پیوست</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="form-label">توضیحات کامل درباره محصول <span class="text-danger">*</span></label>
                                    <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    </section>
    <Footer :companies="props.companies" />
    </main>
</template>
