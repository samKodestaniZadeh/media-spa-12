<script setup>

import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import SubMenu from '@/Components/SubMenu.vue';
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";

const errors = computed(() => usePage().props.value.errors);
const hasErrors = computed(() => Object.keys(errors.value).length > 0);
const props = defineProps({menus:Object});

const form = useForm({
    parent_id:null,
    text : null,
    user_id:null,
    product_id:null,
});

</script>
<template >
    <div v-for="(sub,index) in props.menus" :key="index" >
        <select v-model.lazy="form.type" class="form-select" >
        <option v-if="props.menus && props.menus.length > 0"> {{sub.name}} </option>
        <option v-else disabled>گزینه ای یافت نشد.</option>
    </select>
    <SubMenu  :menus="sub.menuchildren" class="mt-4" />
    </div>
</template>
