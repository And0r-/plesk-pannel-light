<template>
    <div>
        <h1 class="text-3xl font-bold mb-6">Manage Domains</h1>
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Create Domain and User</h2>
            <DomainForm @domainCreated="fetchDomains" />
        </div>
        <div>
            <h2 class="text-xl font-bold mb-4">Existing Domains</h2>
            <DomainList :domains="domains" :loading="loading" />
        </div>
    </div>
</template>

<script>
import DomainForm from './DomainForm.vue';
import DomainList from './DomainList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

export default {
    components: { DomainForm, DomainList },
    layout: AuthenticatedLayout,
    data() {
        return {
            domains: [],
            loading: false,
        };
    },
    methods: {
        async fetchDomains() {
            this.loading = true;
            try {
                const response = await axios.get('/api/v1/domains');
                this.domains = response.data.data;
            } catch (error) {
                console.error('Failed to fetch domains:', error);
            } finally {
                this.loading = false;
            }
        },
    },
    mounted() {
        this.fetchDomains();
    },
};
</script>
