<script setup>
import { computed,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
const props = defineProps({id:Number
});
const form =  useForm({
    id:props.id,
});

</script>
<template>
    <aside class="col-lg-3 border-end">
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/profileAdmin/'+form.id ? 'active':'']" aria-current="page" :href="route('profileAdmin.show',[form.id])">اطلاعات کاربران</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/identityAdmin/'+form.id ? 'active':'']" aria-current="page" :href="route('identityAdmin.show',[form.id])">اطلاعات هویتی کاربران</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/sikllAdmin/' + form.id ? 'active':'']" aria-current="page" :href="route('sikllAdmin.show',[form.id])">مهارت ها</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link " :class="[$page.url == '/users/networkAdmin/'+form.id ? 'active':'']" aria-current="page" :href="route('networkAdmin.show',[form.id])">اعلان ها</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link " :class="[$page.url == '/users/socialAdmin/'+form.id ? 'active':'']" aria-current="page" :href="route('socialAdmin.show',[form.id])">شبکه های اجتماعی</Link>
        </nav>
    </aside>
</template>
