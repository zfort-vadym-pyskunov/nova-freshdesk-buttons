<template>
    <div :class="textAlign">
        <span v-if="loading"><loader class="text-60" /></span>
        <template v-else>
            <a :disabled="newUrl === ''"
               :class="field.freshdeskStyle"
               :href="newUrl"
               target="_blank">{{ field.freshdeskNewLabel }}</a>
            <a :disabled="viewUrl === ''"
               :class="field.freshdeskStyle"
               :href="viewUrl"
               target="_blank">{{ field.freshdeskViewLabel }}</a>
        </template>
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
        data: () => ({
            loading: true,
            newUrl: '',
            viewUrl: '',
            textAlign: 'text-center',
        }),
        async created() {
            await this.getButtons();
        },
        methods: {
            getButtons() {
                const root = '/kuznetsov-zfort/nova-freshdesk-buttons';
                const resourceId = this.$parent.resource['id'].value;
                Nova.request().get(`${root}/${this.resourceName}/${resourceId}`).then(response => {
                    this.newUrl = response.data.newUrl;
                    this.viewUrl = response.data.viewUrl;
                    this.loading = false;
                    this.textAlign = 'text-right';
                });
            },
        },
    }
</script>
