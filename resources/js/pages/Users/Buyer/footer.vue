<script setup>
import moment from "moment-jalaali";
import fa from "moment/src/locale/fa";
const props = defineProps({
    companies:Object
});
//document.write(new Date().getFullYear());
var now = new Date();
var full_year = now.getFullYear();
// const props = defineProps({auth:Object,cart:Object});

</script>
<template>
    <footer class="main-footer font-xs">
        <div class="row pb-30 pt-15 rtl">
            <div class="col-sm-6" v-if="props.companies">

                {{ moment(now).locale("fa", fa).format('jYYYY') }}-{{(1400).toLocaleString("fa-IR")}} © طراحی توسط  <a v-if="props.companies" :href="route('website_design.index', 'q')+'all'">{{ props.companies.name + ' '+ props.companies.lasst_name }}</a>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-start" v-if="props.companies" >کلیه حقوق مادی و معنوی {{ props.companies.name_show }} محفوظ است.</div>
            </div>
        </div>
    </footer>

</template>


