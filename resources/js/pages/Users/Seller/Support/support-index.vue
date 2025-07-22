<script setup>
import { computed} from 'vue';
import Header from '@/Pages/Users/Buyer/header.vue';
import Footer from '@/Pages/Users/Buyer/footer.vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({users:Object,tickets:Object,ids:Object,statuses:Object,subjects:Object,
cartNumber:Number,cartPrice:Number,cartCount:Number,cartDiscount:Number,cartCoupon:Number,
cartTotal:Number,notifications:Object,companies:Object,descriptions:Object});

const form = useForm({
    subject: null,
    status:null,
    id:null,
});
const submit = () => {
    form.get(route('support.search'));

};
</script>
<template>
    <Header :cartPrice="props.cartPrice" :cartCount="props.cartCount" :cartDiscount="props.cartDiscount"
            :cartCoupon="props.cartCoupon" :cartTotal="props.cartTotal" :alert="props.alert" :users="props.users"
            :orders="props.orders" :notifications="props.notifications" :dark="props.dark" :companies="props.companies" />
        <div class="screen-overlay"></div>
        <main class="main-wrap rtl" >
            <section class="content-main">
                <div class="content-header">
                    <div>
                        <h2 class="content-title card-title" v-if="props.descriptions">{{ props.descriptions.subject }}</h2>
                        <p v-if="props.descriptions" >{{ props.descriptions.text }}</p>
                    </div>
                </div>
                <div class="card mb-4" v-if="props.tickets.total > 0">
                    <header class="card-header">
                        <form>
                        <div class="row gx-3">
                            <div class="col-lg-2 col-md-6 ">
                                <input v-model="form.id" name="id" id="id" type="text" placeholder="شناسه تیکت" class="form-control" />
                            </div>
                            <div class="col-lg-2 col-md-6 ">
                                <input v-model="form.subject" name="subject" id="subject" type="text" placeholder="عنوان" class="form-control" />
                            </div>
                            <div class="col-lg-2 col-6 col-md-3 ms-auto">
                                <select v-model="form.status" name="status" id="status" class="form-select">
                                    <option value="0">ثبت شده</option>
                                    <option value="1"> در انتظار پاسخ</option>
                                    <option value="2">در حال بررسی</option>
                                    <option value="3" >بسته شده</option>
                                    <option value="0">پاسخ داده شده</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 ">
                                <Link @click.prevent="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                 class="btn btn-primary btn-sm rounded font-sm">فیلتر</Link>
                            </div>
                        </div>
                        </form>
                    </header>
                    <div class="card-body" v-if="props.ids">
                        <div class="table-responsive">
                            <table v-if="props.id.total > 0 " class="table table-hover">
                                <thead >
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">جزییات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="ticket in props.ids.data" :key="ticket.id">
                                        <td>{{ticket.id}}</td>
                                        <td>{{ticket.subject}}</td>
                                        <td>{{ticket.created_at}}</td>
                                        <td>
                                            <span v-if="ticket.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                            <span v-if="ticket.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                            <span v-if="ticket.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                            <span v-if="ticket.status == 3" class="badge badge-pill badge-soft-danger">منقضی</span>
                                            <span v-if="ticket.status == 4" class="badge badge-pill badge-soft-success">پاسخ</span>
                                        </td>
                                        <td class="text-end">
                                            <Link :href="route('support.show',[ticket.id,props.auth.user.role])" class="btn btn-primary btn-sm rounded font-sm">جزییات</Link>
                                        </td>
                                    </tr>
                                        <div class="mt-5" v-if="props.ids.total > 9 ">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">
                                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                v-for="link in props.ids.links" :key="link.id" >
                                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                v-html="link.label" ></Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </tbody>
                            </table>
                             <table v-else class="table table-hover">
                                <tbody >
                                    <tr>
                                        <td>گزینه ای یافت نشد</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body" v-else-if="props.statuses">
                        <div class="table-responsive">
                            <table v-if="props.statuses.total > 0" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">جزییات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="ticket in props.statuses.data" :key="ticket.id">
                                        <td>{{ticket.id}}</td>
                                        <td>{{ticket.subject}}</td>
                                        <td>{{ticket.created_at}}</td>
                                        <td>
                                            <span v-if="ticket.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                            <span v-if="ticket.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                            <span v-if="ticket.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                            <span v-if="ticket.status == 3" class="badge badge-pill badge-soft-danger">منقضی</span>
                                            <span v-if="ticket.status == 4" class="badge badge-pill badge-soft-success">پاسخ</span>
                                        </td>
                                        <td class="text-end">
                                            <Link :href="route('support.show',[ticket.id])" class="btn btn-primary btn-sm rounded font-sm">جزییات</Link>
                                        </td>
                                    </tr>
                                        <div class="mt-5" v-if="props.statuses.total > 9 ">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">
                                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                v-for="link in props.statuses.links" :key="link.id" >
                                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                v-html="link.label" ></Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </tbody>
                            </table>
                            <table v-else class="table table-hover">
                                <tbody >
                                    <tr >
                                        <td>گزینه ای  یافت نشد</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body"  v-else-if="props.subjects">
                        <div class="table-responsive">
                            <table v-if="props.subjects.total > 0" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">جزییات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ticket in props.subjects.data" :key="ticket.id">
                                        <td>{{ticket.id}}</td>
                                        <td>{{ticket.subject}}</td>
                                        <td>{{ticket.created_at}}</td>
                                        <td>
                                            <span v-if="ticket.status == 0" class="badge badge-pill badge-soft-info">ثبت</span>
                                            <span v-if="ticket.status == 1" class="badge badge-pill badge-soft-warning">انتظار</span>
                                            <span v-if="ticket.status == 2"  class="badge badge-pill badge-soft-secondary">بررسی</span>
                                            <span v-if="ticket.status == 3" class="badge badge-pill badge-soft-danger">منقضی</span>
                                            <span v-if="ticket.status == 4" class="badge badge-pill badge-soft-success">پاسخ</span>
                                        </td>
                                        <td class="text-end">
                                            <Link :href="route('support.show',[ticket.id,props.auth.user.role])" class="btn btn-primary btn-sm rounded font-sm">جزییات</Link>
                                        </td>
                                    </tr>
                                        <div class="mt-5"  v-if="props.subjects.total > 9 ">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">
                                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                v-for="link in props.subject.links" :key="link.id" >
                                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                v-html="link.label" ></Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </tbody>
                            </table>
                            <table v-else class="table table-hover">
                                <tbody>
                                    <tr >
                                        <td>گزینه ای یافت نشد</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body"  v-else-if="props.tickets">
                        <div class="table-responsive">
                            <table v-if="props.tickets.total > 0" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">شناسه</th>
                                        <th scope="col">عنوان</th>
                                        <th scope="col">تاریخ</th>
                                        <th scope="col">وضعیت</th>
                                        <th scope="col">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr v-for="ticket in props.tickets.data" :key="ticket.id">
                                        <td>{{(ticket.id).toLocaleString("fa-IR")}}</td>
                                        <td>{{ticket.subject}}</td>
                                        <td>
                                            {{ moment(ticket.created_at).locale("fa", fa).format('jYYYY/jM/jD HH:mm') }}
                                        </td>
                                        <td>
                                            <span v-if="ticket.status == 0" class="badge badge-pill badge-soft-info">ثبت شده</span>
                                            <span v-if="ticket.status == 1" class="badge badge-pill badge-soft-warning"> در انتظار پاسخ</span>
                                            <span v-if="ticket.status == 2"  class="badge badge-pill badge-soft-secondary">در حال بررسی</span>
                                            <span v-if="ticket.status == 3" class="badge badge-pill badge-soft-danger">بسته شده</span>
                                            <span v-if="ticket.status == 4" class="badge badge-pill badge-soft-success">پاسخ داده شده</span>
                                        </td>
                                        <td class="text-end">
                                            <Link :href="route('supportSeller.show',[ticket.id])" class="btn btn-primary btn-sm rounded font-sm">نمایش</Link>
                                        </td>
                                    </tr>
                                        <div class="mt-5" v-if="props.tickets.total > 9 ">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">
                                                <li :class="['page-item',link.url == null ? 'disable' :'',link.active ? 'active' : '']"
                                                v-for="link in props.tickets.links" :key="link.id" >
                                                <Link class="page-link" :href="link.url == null ? '#' : link.url"
                                                v-html="link.label" ></Link>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>گزینه ای یافت نشد.</p>
                </div>
            </section>
            <Footer :companies="props.companies" />
</main>
</template>

