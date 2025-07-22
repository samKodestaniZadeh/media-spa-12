<script setup>

import { computed, ref} from 'vue';
import { useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';
import { Inertia } from '@inertiajs/inertia';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    orders: Object, users: Object, cartPrice: Object, cartCount: Object, menus: Object,
    cartDiscount: Object, cartCoupon: Object, cartTotal: Object, notifications: Object, companies: Object,
    descriptions: Object, children: Object,path:String,alert: Object,cart:Object
});

const form = useForm({
    name: null, name_en: null, group: null, type: null, category: null, text: null,
    browser_compatibility: [], demo_link: null, price: null, version: null, file: null,
    image: null,prerequisites:null ,prerequisite_version:null ,additional_facilities:null,
    test:null , sub_test:null,
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
        title: [errors.value.name ? errors.value.name +'<br>' :'' ,
                errors.value.name_en ? errors.value.name_en +'<br>' :'' ,
                errors.value.group ? errors.value.group +'<br>' :'' ,
                errors.value.type ? errors.value.type +'<br>' :'' ,
                errors.value.category ? errors.value.category +'<br>':'',
                errors.value.text ? errors.value.text +'<br>' :'' ,
                errors.value.demo_link ? errors.value.demo_link +'<br>' :'' ,
                errors.value.version ? errors.value.version +'<br>' :'' ,
                errors.value.file ? errors.value.file +'<br>' :'' ,
                errors.value.image ? errors.value.image +'<br>' :'' ,],
        icon:'error',
    })

}

const submitTime = ()=>{
    Inertia.visit(route('product.create'),{ only: [errors.value,hasErrors.value,props.alert] })
}
const submit = () => {

    if (form.name == null || form.name_en == null || form.group == null || form.type == null ||
        form.category == null || form.text == null || form.demo_link == null || form.version == null ||
        form.file == null || form.image == null)
    {
            let text
            text = 'موارد ستاره دار الزامی است.'
           validate(text)
    }
    else
    {
        form.post(route('product.store'),{
            onFinish:() => submitTime()
        })
    }
};



const menus = ref([]);

if (props.menus && props.menus.length > 0) {

    props.menus.forEach(element => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section => {
                        if(section.name == 'products')
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
                                    if(section.name == 'products')
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
                                        if(section.name == 'products')
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

const step = ref('step');
const next = () => {
    if (step.value == 'step') {
        if (form.name !== null && form.name_en !== null && form.text !== null) {

            step.value = 'step2'
        }
        else
        {
            let text
            text = 'موارد ستاره دار الزامی است.'
           validate(text)
        }

    }
    else if (step.value == 'step2') {
        if (form.price !== null && form.group !== null && form.type !== null && form.category !== null && form.demo_link !== null && form.version !== null
            && form.file !== null && form.image !== null) {
            step.value = 'step3'
        }
        else
        {

            let text
            text = 'موارد ستاره دار الزامی است.'
            validate(text)
        }
    }

};
const back = () => {
    if (step.value == 'step3') {
        step.value = 'step2'
    }
    else if (step.value == 'step2') {
        step.value = 'step'
    }

};

const prerequisites = ref([]);

if (props.menus) {
    props.menus.forEach(element => {
        if(element.routes.length > 0)
        {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    if (element.sections.length > 0)
                    {
                        element.sections.forEach(section => {
                            if (section.name == 'prerequisites') {
                                prerequisites.value.push(element)
                            }

                        });
                    }
                }
            });
        }
    });
}

const prerequisite_version = ref([]);

const sub_prerequisites = ()=>{

    if (prerequisite_version.value.length > 0) {
        prerequisite_version.value.splice(0)
    }

    props.menus.forEach(element => {
        if (element == form.prerequisites && element.children.length > 0) {
            element.children.forEach(child => {
                if(child.routes.length > 0)
                {
                    child.routes.forEach(route => {
                        if(route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section => {
                                    if (section.name == 'prerequisites') {
                                        prerequisite_version.value.push(child)
                                    }
                                });

                            }
                        }
                    })
                }
            });
        }
    });
}

const additional_facilities = ref([]);

if (props.menus) {

    props.menus.forEach(element => {
        if(element.routes.length > 0 )
        {
            element.routes.forEach(route => {
                if (route.name == props.path) {
                    if(element.sections.length > 0)
                    {
                        element.sections.forEach(section => {
                            if (section.name == 'additional_facilities') {
                                additional_facilities.value.push(element)
                            }
                        })
                    }
                }
            })
        }
    });
}

const browsers = ref([]);

if (props.menus) {

    props.menus.forEach(element => {
        if (element.routes.length > 0) {
            element.routes.forEach(route => {
                if (route.name == props.path) {
                    element.sections.forEach(section =>  {
                        if (section.name == 'browsers') {
                            browsers.value.push(element)
                        }
                    })
                }
            });
        }
    });
}

const tests = ref([]);

if (props.menus) {

    props.menus.forEach(element => {
        if(element.routes.length > 0)
        {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section =>  {
                        if (section.name == 'tests') {
                            tests.value.push(element)
                        }
                    })

                }
            });
        }
    });
}

const test_version = ref([]);

const sub_test = ()=>{

    if (test_version.value.length > 0) {
        test_version.value.splice(0)
    }

    props.menus.forEach(element => {
        if (element == form.test && element.children.length > 0) {
            element.children.forEach(child => {
                if(child.routes.length > 0)
                {
                    child.routes.forEach(route => {
                        if(route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section => {
                                    if (section.name == 'tests') {
                                        test_version.value.push(child)
                                    }
                                });
                            }
                        }
                    })
                }
            });
        }
    });

}
</script>
<template>
    <Header :cart="props.cart" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
        :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

    <main class="main-wrap rtl">
        <section class="content-main">
            <div class="row content-header">
                    <div class="d-flex col-sm-12">
                        <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                        <td class="me-auto">
                            <button v-if="step !== 'step'"  @click.prevent="back" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing" class="btn btn-md rounded font-sm hover-up ms-1">قبلی</button>

                            <button v-if="step == 'step3'" @click.prevent="submit" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing" class="btn btn-md rounded font-sm hover-up me-auto">
                                <span v-if="form.processing">پردازش...</span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                <span v-else >ایجاد</span>
                            </button>

                            <button v-if="step !== 'step3'"  @click.prevent="next" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing" class="btn btn-md rounded font-sm hover-up me-auto">بعدی</button>
                        </td>
                    </div>
                    <div class="col-sm-12">
                        <div v-if="props.descriptions" v-html="props.descriptions.text"></div>
                    </div>
                </div>
            <div class="row">
                <form class="row">
                    <div class="col-lg-12">
                        <div class="card" v-if="step == 'step'">
                            <div class="card-header">
                                <h4>اطلاعات پایه</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="mt-1 col-sm-6">
                                        <label for="product_name" class="form-label">نام محصول <span
                                                class="text-danger">*</span></label>
                                        <input v-model.lazy="form.name" type="text" placeholder="اینجا تایپ کنید"
                                            class="form-control" id="product_name" />
                                    </div>
                                    <div class="mt-1 col-sm-6">
                                        <label for="product_name" class="form-label">نام انگلیسی محصول <span
                                                class="text-danger">*</span></label>
                                        <input v-model.lazy="form.name_en" type="text" placeholder="اینجا تایپ کنید"
                                            class="form-control" id="product_name" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label">توضیحات کامل درباره محصول <span
                                            class="text-danger">*</span></label>
                                    <Editor api-key="0jyg8kag8oi7nb67i513jba26ynnauhhfpwlqckgygf32ly5" :init="{menubar: false }" v-model="form.text" />
                                </div>
                            </div>
                        </div>
                        <div class="card mt-4" v-if="step == 'step2'">
                            <div class="card-header">
                                <h4>اطلاعات تکمیلی</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label">قیمت پیشنهادی محصول <span class="text-danger">*</span></label>
                                        <input v-model.lazy="form.price" placeholder="اینجا تایپ کنید" type="text"
                                            class="form-control" />
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label"> گروه محصول <span class="text-danger">*</span></label>
                                        <select v-model.lazy="form.group" class="form-select" @change="group">
                                            <option v-if="menus.length > 0" :value="menu" v-for="(menu, index) in menus" :key="index">{{ menu.name }}</option>
                                            <option v-else disabled>گزینه ای یافت نشد.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mt-4">
                                        <label class="form-label">نوع محصول <span class="text-danger">*</span></label>
                                        <select v-model.lazy="form.type" @change="type" class="form-select">
                                                <option v-if="menu.length > 0 && form.group" v-for="(type, index) in menu" :key="index" :value="type">{{ type.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mt-4">
                                        <label class="form-label"> دسته بندی محصول <span
                                                class="text-danger">*</span></label>
                                        <select v-model.lazy="form.category" @change="category" class="form-select">
                                            <option v-if="sections.length > 0 && form.type" v-for="(category, index) in sections" :key="index" :value="category">{{ category.name }}</option>
                                            <option v-else disabled>گزینه ای یافت نشد.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-4 col-sm-6">
                                        <label class="form-label">لینک دمو <span class="text-danger">*</span></label>

                                            <input v-model.lazy="form.demo_link" placeholder="اینجا تایپ کنید" type="text"
                                                class="form-control" />

                                    </div>
                                    <div class="mt-4 col-sm-6">
                                        <label class="form-label">نسخه محصول <span class="text-danger">*</span></label>
                                        <div class="row gx-2">
                                            <input v-model.lazy="form.version" placeholder="اینجا تایپ کنید" type="text"
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-body col-sm-6">
                                        <label class="form-label">فایل <span class="text-danger">*</span></label>
                                        <div class="input-upload">

                                            <input class="form-control" type="file" @input="form.file = $event.target.files[0]"
                                                id="file" accept="zip/rar/*" />
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                {{ form.progress.percentage }}%
                                            </progress>
                                        </div>
                                    </div>
                                    <div class="card-body col-sm-6">
                                        <label class="form-label">تصویر کاور <span class="text-danger">*</span></label>
                                        <div class="input-upload">

                                            <input class="form-control" type="file" @input="form.image = $event.target.files[0]"
                                                id="image" accept="image/*" />
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                {{ form.progress.percentage }}%
                                            </progress>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" v-if="step == 'step3'">
                            <div class="row gx-2">
                                <div class="card-header">
                                    <h4>اطلاعات اضافی</h4>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="card-body">
                                    <div class="row gx-2">
                                        <div class="col-lg-6">
                                            <label class="form-label">پیش نیازها</label>
                                            <select v-model.lazy="form.prerequisites"  class="form-select" @change="sub_prerequisites">
                                                <option v-if="prerequisites.length > 0" v-for="prerequisite,index in prerequisites" :key="index" :value="prerequisite">{{ prerequisite.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label"> نسخه پیش نیاز</label>
                                            <select v-model.lazy="form.prerequisite_version" class="form-select">
                                                <option v-if="prerequisite_version.length > 0" :value="child" v-for="child,index in prerequisite_version" :key="index">{{ child.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row gx-2">
                                        <div class="col-lg-6">
                                            <label class="form-label">امکانات اضافی</label>
                                            <select v-model.lazy="form.additional_facilities"  class="form-select">
                                                <option v-if="additional_facilities.length > 0" v-for="additional_facilitie,index in additional_facilities" :key="index" :value="additional_facilitie">{{ additional_facilitie.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6" >
                                            <label class="form-label">سازگار با مرورگر </label>
                                            <div class="row gx-2">
                                                <div class="d-flex">
                                                    <div class="form-check" v-if="browsers.length > 0" v-for="browser,index in browsers" :key="index">
                                                        {{ browser.name }}
                                                        <input class="form-check-input" type="checkbox" :value="browser" v-model="form.browser_compatibility">
                                                    </div>
                                                    <option v-else >گزینه ای یافت نشد.</option>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-2">
                                        <div class="col-lg-6">
                                            <label class="form-label">تست</label>
                                            <select v-model.lazy="form.test" @change="sub_test" class="form-select">
                                                <option v-if="tests.length > 0" v-for="test,index in tests" :key="index" :value="test">{{ test.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="form-label"> نسخه تست</label>
                                            <select v-model.lazy="form.sub_test" class="form-select">
                                                <option v-if="form.test" v-for="version,index in test_version" :key="index" :value="version">{{ version.name }}</option>
                                                <option v-else disabled>گزینه ای یافت نشد.</option>
                                            </select>
                                        </div>
                                    </div>
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
<style scoped>
.logo {
  display: block;
  margin: 0 auto 2rem;
}
.mce-content-body {
 display: none;
}

@media (min-width: 1024px) {
  #sample {
    display: flex;
    flex-direction: column;
    place-items: center;
    width: 1000px;
  }
}
</style>
