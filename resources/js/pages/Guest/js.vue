<script setup>

</script>
<template>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/jquery-3.5.0.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/popper.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/bootstrap.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/jquery.magnific-popup.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/jquery.easing.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/jquery.mb.YTPlayer.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/wow.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/owl.carousel.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/jquery.countdown.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/validator.min.js'"></component>
    <component :is="'script'" :src="$page.props.ziggy.url+'/js/scripts.js'"></component>
</template>
