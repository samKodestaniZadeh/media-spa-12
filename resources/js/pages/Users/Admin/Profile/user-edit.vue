<script setup>

import { computed, ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';

const errors = computed(() => usePage().props.errors);

const hasErrors = computed(() => Object.keys(errors.value).length > 0);

const props = defineProps({orders:Object,users:Object,cartPrice:Object,cartCount:Object,
cartDiscount:Object,cartCoupon:Object,cartTotal:Object,user:Object,roles:Object,flash:String,
notifications:Object,companies:Object,descriptions:Object});
const roles = ref(props.user.roles);
const rol = ref([]);
roles.value.forEach(element => {
    rol.value = element
});

const form =  useForm({user:props.user.id,status:props.user.status,role:rol.value.id});
const form2 =  useForm({user:props.user.id,role_id:null});

const submit = () =>{

    form.put(route('userModir.update',form.user))
};


const submitDel = (id) => {
    form2.role_id = id,
    form2.put(route('userModir.update',form2.user))
//form.delete(route('role_user.destroy',id))

};
</script>
<template>
<Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
<main class="main-wrap rtl">
    <section class="content-main ">
        <div class="content-header">
                <div>
                    <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                    <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                </div>
            </div>

        <div class="card mx-auto card-login">
            <div class="card-body">
                <h4 class="card-title mb-4">ویرایش اطلاعات کاربری</h4>
                <p>نام کاربری:{{props.user.user_name}}</p>
                <form >
                    <template v-for="(role,index) in props.users.roles" :key="index">
                            <div v-if="role.id == 4" class="col-lg-6 mb-3 ms-1">
                                <label class="form-label">وضعیت حساب <span class="text-danger">*</span></label>
                                <select v-model.lazy="form.status" class="form-select">
                                    <option value="0">ثبت</option>
                                    <option value="1">اخیرا</option>
                                    <option value="2">مسدود</option>
                                    <option value="3">غیرفعال</option>
                                    <option value="4">آنلاین</option>
                                </select>
                            </div>
                    </template>
                    <div class="col-lg-6 mb-3 ms-1">
                        <label class="form-label">نوع نقش<span class="text-danger">*</span></label>
                        <select v-model.lazy="form.role" class="form-select">
                            <option v-for="(role,index) in props.roles" :key="index" :value="role.id">{{role.name}}</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3 ms-1">
                    <div class="card-body" v-if="props.user.roles">
                            <table class="table table-responsive">
                                <thead>
                                    <tr class="col">
                                        <th scope="col">شناسه</th>
                                        <th scope="col">نام</th>
                                        <th scope="col">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="(role,index) in props.user.roles" :key="index">
                                        <td >{{(role.id).toLocaleString("fa-IR")}}</td>
                                        <td >{{role.name}}</td>
                                        <button @click.prevent="submitDel(role.id)" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-sm btn-primary mt-1 mb-1">حذف</button>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
                <div class="col-lg-3 mb-4">
                    <button @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="btn btn-primary w-100">ثبت</button>
                </div>
            </div>
        </div>
    </section>
    <Footer :companies="props.companies" />
</main>
</template>
