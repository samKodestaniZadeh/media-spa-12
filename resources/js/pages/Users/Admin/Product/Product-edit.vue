<script setup>

import { computed,ref,watch} from 'vue';
import { Head, Link, useForm,usePage } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue'
import { Inertia } from '@inertiajs/inertia';
import VueMultiselect from 'vue-multiselect';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({
    product:Object,users:Object,children:Object,wallet:Number,alert: Object,companies:Object,menus: Object,
    descriptions:Object,path:String,cart:Object
});

const form =  useForm({id:props.product.id,name:props.product.name,name_en:props.product.name_en,
    group:null,type:null,category:null,text:props.product.text,status:props.product.status,
    sings:props.product.sings,design_type:props.product.design_type,prerequisites:[],
    prerequisite_version:[],additional_facilities:[],user_id:props.product.user_id,
    browser_compatibility:[],demo_link:props.product.demo_link,price:props.product.price,
    version:props.product.version,file:props.product.file.url,image:props.product.image.url,
    test:[] , sub_test:[],
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
    Inertia.visit(route('productAdmin.edit',form.id),{ only: [errors.value,hasErrors.value,props.alert] })
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
        form.post(route('productAdmin.store')
            // ,{
            //     onFinish:() => submitTime()
            // }
        )
    }
};
const ApiKey = ref('cfw3yx4hh06riwl1qwbq3fwcmjr80c5v0z2ki1fid7agx2ow');
const menus = ref([]);


if (props.menus && props.menus.length > 0) {

    props.menus.forEach(element => {
        if (element.sections.length > 0 && element.routes.length > 0) {
            element.routes.forEach(ruote => {
                if(ruote.name == props.path)
                {
                    element.sections.forEach(section => {

                        if(section.name == 'products' && props.product.group.id == element.id )
                        {
                            menus.value.push(element),
                            form.group = element

                        }
                    });
                }
            });
        }
    });
}


const menu = ref([]);
// menu.value.push(props.product.type)

if (menus.value.length > 0) {

    menus.value.forEach(element => {
        if (form.group == element  ) {

            element.children.forEach(child => {
                if(child.routes.length > 0){
                    child.routes.forEach(route => {
                        if (route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section =>
                                {
                                    if(section.name == 'products' && props.product.type.id == child.id  )
                                    {

                                        menu.value.push(child)
                                        form.type = child
                                    }
                                    else{
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
}

const sections = ref([]);
// menu.value.push(props.product.category)

if (menu.value.length > 0)
{
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
                                        if(section.name == 'products' && props.product.category.id == child.id )
                                        {

                                            sections.value.push(child)
                                            form.category = child
                                        }
                                        else{
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
}
const group = () => {
    if (menu.value.length > 0) {
        menu.value.splice(0)
    }
    // form.type = null,
    menus.value.forEach(element => {
        if (form.group == element  ) {

            element.children.forEach(child => {
                if(child.routes.length > 0){
                    child.routes.forEach(route => {
                        if (route.name == props.path)
                        {
                            if (child.sections.length > 0)
                            {
                                child.sections.forEach(section =>
                                {
                                    if(section.name == 'products' && menu.value[0].id !== child.id)
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

             step.value = 'step2'

    }
    else if (step.value == 'step2') {

            step.value = 'step3'

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
// console.log(prerequisites.value,form.prerequisites);
if (props.product.menus) {
    props.product.menus.forEach(element => {

        if(element.routes.length > 0)
        {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    if (element.sections.length > 0)
                    {
                        element.sections.forEach(section => {
                            if (section.name == 'prerequisites' && element.parent_id == null) {
                                form.prerequisites.push(element)
                            }

                        });
                    }
                }
            });
        }
    });
}

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
                            if (section.name == 'prerequisites' && element.parent_id == null) {
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
// console.log(prerequisite_version.value , form.prerequisite_version);
if (props.product.menus) {
    props.product.menus.forEach(element => {

        if(element.routes.length > 0)
        {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    if (element.sections.length > 0)
                    {
                        element.sections.forEach(section => {
                            if (section.name == 'prerequisites' && element.parent_id) {
                                form.prerequisite_version.push(element)
                                // console.log(element);
                            }

                        });
                    }
                }
            });
        }
    });
}

if (props.menus) {
    props.menus.forEach(element => {
        form.prerequisites.forEach(child1 => {

            if(element.id == child1.id && element.routes.length > 0)
            {
                element.routes.forEach(route => {
                    if(route.name == props.path)
                    {
                        if (element.sections.length > 0)
                        {
                            element.sections.forEach(section => {

                                if (section.name == 'prerequisites' ) {

                                    element.children.forEach(child => {
                                        prerequisite_version.value.push(child)
                                        // form.prerequisite_version.push(child)
                                    });
                                }
                            });
                        }
                    }
                });
            }
        })
    });
}

const sub_prerequisites = ()=>{

    if (prerequisite_version.value.length > 0) {
        prerequisite_version.value.splice(0)
    }


    props.menus.forEach(element => {

        form.prerequisites.forEach(child1 => {

            if (element == child1) {

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

                                            if(child.parent_id == element.id)
                                            {
                                                prerequisite_version.value.push(child)
                                            }

                                        }
                                    });

                                }
                            }
                        })
                    }
                });
            }
        })
    });
}

const additional_facilities = ref([]);

if (props.product && props.product.menus) {

    props.product.menus.forEach(element => {

        if(element.routes.length > 0 )
        {
            element.routes.forEach(route => {
                if (route.name == props.path) {
                    if(element.sections.length > 0)
                    {
                        element.sections.forEach(section => {
                            if (section.name == 'additional_facilities') {
                                form.additional_facilities.push(element)

                            }
                        })
                    }
                }
            })
        }
    });
}

if (props.menus) {

    props.menus.forEach(element => {
        if(element.routes.length > 0 )
        {
            element.routes.forEach(route => {
                if (route.name == props.path) {
                    if(element.sections.length > 0)
                    {
                        element.sections.forEach(section => {

                            if (section.name == 'additional_facilities' && form.additional_facilities  && form.additional_facilities.id == element.id) {
                                additional_facilities.value.push(element)
                                form.additional_facilities = element
                            }
                            else if(section.name == 'additional_facilities')
                            {
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

if (props.product.menus) {

    props.product.menus.forEach(element => {
        if (element.routes.length > 0) {
            element.routes.forEach(route => {
                if (route.name == props.path) {
                    element.sections.forEach(section =>  {
                        if (section.name == 'browsers') {
                            form.browser_compatibility.push(element)
                        }
                    })
                }
            });
        }
    });
}

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
// console.log( tests.value,form.test);
if (props.product.menus) {

    props.product.menus.forEach(element => {
        if(element.routes.length > 0)
        {
            element.routes.forEach(route => {
                if(route.name == props.path)
                {
                    element.sections.forEach(section =>  {
                        if (section.name == 'tests' && element.parent_id == null) {
                            form.test.push(element)

                        }
                    })

                }
            });
        }
    });
}

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
                            if (section.name == 'tests') {

                                tests.value.push(element)

                            }

                        });
                    }
                }
            });
        }
    });
}

const test_version = ref([]);
// console.log( test_version.value,form.sub_test);
if (props.product.menus) {

    props.product.menus.forEach(element => {
        // console.log(element);
        if (element) {

                if(element.routes.length > 0)
                {
                    element.routes.forEach(route => {
                        if(route.name == props.path)
                        {
                            if (element.sections.length > 0)
                            {
                                element.sections.forEach(section => {
                                    if (section.name == 'tests' && element.parent_id) {
                                        form.sub_test.push(element)
                                        // console.log(element);
                                    }
                                });
                            }
                        }
                    })
                }

        }
    });
}

if (props.menus) {

    props.menus.forEach(element => {
        if (element.children.length > 0) {
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
                                        // form.sub_test.push(child)
                                        // console.log(child);
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

const sub_test = ()=>{

    if (test_version.value.length > 0) {
        test_version.value.splice(0)
    }

    props.menus.forEach(element => {

        form.test.forEach(child1 => {

            if (element == child1) {

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

                                                if(child.parent_id == element.id)
                                                {
                                                    test_version.value.push(child)
                                                }

                                            }
                                        });

                                    }
                                }
                            })
                        }
                    });

            }
        })

    });
}

</script>
<template>
<Header :cart="props.cart"  :wallet="props.wallet" :alert="props.alert" :users="props.users"
        :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />

<main class="main-wrap rtl">
    <section class="content-main">
        <div class="row content-header">
            <div class="d-flex col-sm-12">
                <div class="content-title card-title" v-if="props.descriptions" v-html="props.descriptions.subject"></div>
                <table>
                    <thead>
                        <tr  class="d-flex me-auto">
                            <button v-if="step !== 'step'"  @click.prevent="back" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing" class="btn btn-md rounded font-sm hover-up ms-1">قبلی</button>
                            <select v-model.lazy="form.status" class="form-select" v-if="step == 'step3'">
                                <option value="0">ثبت</option>
                                <option value="1">انتظار</option>
                                <option value="2">مسدود</option>
                                <option value="3">منقضی</option>
                                <option value="4">منتشر</option>
                                <option value="5">متوقف</option>
                            </select>
                            <button v-if="step == 'step3'" @click.prevent="submit" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing" class="btn btn-md rounded font-sm hover-up me-auto">
                                <span v-if="form.processing">پردازش...</span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="form.processing"></span>
                                <span v-else >ویرایش</span>
                            </button>

                            <button v-if="step !== 'step3'"  @click.prevent="next" :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing" class="btn btn-md rounded font-sm hover-up me-auto">بعدی</button>
                        </tr>
                    </thead>
                </table>
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
                                    <Editor :api-key="ApiKey" :init="{menubar: false }" v-model="form.text" />
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
                                            <option v-if="menus.length > 0" v-for="(menu, index) in menus" :key="index" :value="menu" >{{ menu.name }}</option>
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
                                            <div class="mt-2">
                                                <a :href="$page.props.ziggy.url+'/storage/'+form.file">دانلود</a>
                                            </div>

                                            <input class="form-control mt-5" type="file" @input="form.file = $event.target.files[0]"
                                                id="file" accept="zip/rar/*" />
                                            <progress v-if="form.progress" :value="form.progress.percentage" max="5">
                                                {{ form.progress.percentage }}%
                                            </progress>
                                        </div>

                                    </div>
                                    <div class="card-body col-sm-6">
                                        <label class="form-label">تصویر کاور <span class="text-danger">*</span></label>
                                        <div class="input-upload">
                                            <img :src="$page.props.ziggy.url+'/storage/'+form.image" :alt="form.name" />

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
                                            <VueMultiselect
                                                v-model="form.prerequisites"
                                                 :multiple="true"
                                                 :options="prerequisites"
                                                 label="name"
                                                :close-on-select="true"
                                                track-by="name"
                                                placeholder="انتخاب نمایید"
                                                @Select="sub_prerequisites" >

                                            </VueMultiselect>
                                        </div>
                                        <div class="col-lg-6" v-if="form.prerequisites" v-for="prerequisite,index in form.prerequisites" :key="index" >
                                            <label class="form-label">نسخه پیش نیازها محصول ({{prerequisite.name}}-{{ index }})</label>
                                            <select  v-model.lazy="form.prerequisite_version" class="form-select">
                                                <template  v-for="child,index in prerequisite_version" :key="index">
                                                    <option v-if="prerequisite.id == child.parent_id"  :value="child" >{{ child.name }}-{{ index }}</option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row gx-2">
                                        <div class="col-lg-6">
                                            <label class="form-label">امکانات اضافی</label>
                                            <VueMultiselect
                                                v-model="form.additional_facilities"
                                                :options="additional_facilities" :multiple="true"
                                                :close-on-select="true"
                                                label="name"
                                                placeholder="انتخاب نمایید"
                                                track-by="name"
                                                >
                                            </VueMultiselect>
                                        </div>
                                        <div class="col-lg-6" >
                                            <label class="form-label">سازگار با مرورگر </label>
                                            <div class="row gx-2">
                                                <VueMultiselect
                                                    v-model="form.browser_compatibility"
                                                    :multiple="true"
                                                    :options="browsers"
                                                    label="name"
                                                    :close-on-select="true"
                                                    track-by="name"
                                                    placeholder="انتخاب نمایید"
                                                    >

                                                </VueMultiselect>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-2">
                                        <div class="col-lg-6">
                                            <label class="form-label">تست</label>
                                            <VueMultiselect
                                                v-model="form.test"
                                                 :multiple="true"
                                                 :options="tests"
                                                 label="name"
                                                :close-on-select="true"
                                                track-by="name"
                                                placeholder="انتخاب نمایید"
                                                @Select="sub_test" >

                                            </VueMultiselect>

                                        </div>
                                        <div class="col-lg-6" v-if="form.test" v-for="tes,index in form.test" :key="index" >
                                            <label class="form-label">نسخه پیش نیازها محصول ({{tes.name}})</label>
                                            <select v-model.lazy="form.sub_test[tes.id]" class="form-select">
                                                <template  v-for="child,index in test_version" :key="index">
                                                    <option v-if="tes.id == child.parent_id" :value="child" >{{ child.name }}</option>
                                                </template>
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
@import url(vue-multiselect/dist/vue-multiselect.css);
</style>
