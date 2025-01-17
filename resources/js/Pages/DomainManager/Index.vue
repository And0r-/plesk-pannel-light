<template>
    <div>
        <h1 class="text-3xl font-bold mb-6">Manage Domains</h1>
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Create Domain and User</h2>
            <DomainForm @domainCreated="updateDomains" />
        </div>
        <div>
            <h2 class="text-xl font-bold mb-4">Existing Domains</h2>
            <DomainList />
        </div>
    </div>
</template>

<script>
import DomainForm from './DomainForm.vue';
import DomainList from './DomainList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: { DomainForm, DomainList },
    layout: AuthenticatedLayout,
    methods: {
        updateDomains() {
            this.$refs.domainList.updateDomains();
        },
    },
};
</script>
