<script setup>
import { computed,ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
</script>
<template>
    <aside class="col-lg-3 border-end">
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/profile' ? 'active':'']" aria-current="page" :href="route('profile.index')">اطلاعات کاربری</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/identity' ? 'active':'']" aria-current="page" :href="route('identity.index')">اطلاعات هویتی</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/sikll' ? 'active':'']" aria-current="page" :href="route('sikll.index')">مهارت ها</Link>
        </nav>
        <!-- <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/connection' ? 'active':'']" aria-current="page" :href="route('connection.index')">راه ارتباطی</Link>
        </nav> -->
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link " :class="[$page.url == '/users/network' ? 'active':'']" aria-current="page" :href="route('network.index')">اعلان ها</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link " :class="[$page.url == '/users/social' ? 'active':'']" aria-current="page" :href="route('social.index')">شبکه های اجتماعی</Link>
        </nav>
    </aside>
</template>
