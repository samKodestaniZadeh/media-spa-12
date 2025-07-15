<script setup>
import { useHead } from '@vueuse/head'

const props = defineProps({
  title: String,
  description: String,
  keywords: String,
  image: String,
  url: String,
  noIndex: {
    type: Boolean,
    default: false,
  }
})

useHead({
  title: props.title ?? 'عنوان پیش‌فرض',
  meta: [
    // SEO Basics
    { name: 'description', content: props.description ?? 'توضیحات پیش‌فرض صفحه' },
    { name: 'keywords', content: props.keywords ?? 'سایت,فروشگاه,مدیا' },
    { name: 'robots', content: props.noIndex ? 'noindex, nofollow' : 'index, follow' },
  ]
})
</script>
<template>

</template>
