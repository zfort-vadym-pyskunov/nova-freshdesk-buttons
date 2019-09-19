<template>
    <div class="text-right">
        <a :disabled="field.freshdeskNewUrl === ''"
           :class="field.freshdeskStyle"
           :href="field.freshdeskNewUrl"
           target="_blank">{{ field.freshdeskNewLabel }}</a>
        <a :disabled="field.freshdeskViewUrl === ''"
           :class="field.freshdeskStyle"
           :href="field.freshdeskViewUrl"
           target="_blank">{{ field.freshdeskViewLabel }}</a>
    </div>
</template>

<style>
    .nova-freshdesk {
        white-space: nowrap;
    }
</style>

<script>
    export default {
        props: ['resourceName', 'field'],
    }
</script>
