<script setup>

import { Link} from '@inertiajs/vue3';
</script>
<template>
    <aside class="col-lg-3 border-end">
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/company' ? 'active':'']" aria-current="page" :href="route('company.index')">مشخصات</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/menu' ? 'active':'']" aria-current="page" :href="route('menu.index')">منو</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/section' ? 'active':'']" aria-current="page" :href="route('section.index')">قسمت</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/route' ? 'active':'']" aria-current="page" :href="route('route.index')">روت</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/installment' ? 'active':'']" aria-current="page" :href="route('installment.index')">قسط</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/description' ? 'active':'']" aria-current="page" :href="route('description.index')">توضیحات</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/page' ? 'active':'']" aria-current="page" :href="route('page.index')">صفحات</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link" :class="[$page.url == '/users/newsletterAdmin' ? 'active':'']" aria-current="page" :href="route('newsletterAdmin.index')">خبر نامه</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link " :class="[$page.url == '/users/socialAdmin' ? 'active':'']" aria-current="page" :href="route('socialAdmin.index')">شبکه های اجتماعی</Link>
        </nav>
        <nav class="nav nav-pills flex-lg-column mb-4">
            <Link class="nav-link " :class="[$page.url == '/users/namadAdmin' ? 'active':'']" aria-current="page" :href="route('namadAdmin.index')">نماد ها</Link>
        </nav>
    </aside>
</template>
