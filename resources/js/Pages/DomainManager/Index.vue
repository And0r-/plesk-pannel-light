<template>
    <div>
        <!-- Haupttitel -->
        <h1 class="text-3xl font-bold mb-6">Manage Domains</h1>

        <!-- Formularbereich -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Create Domain and User</h2>
            <DomainForm @domainCreated="fetchDomains" />
        </div>

        <!-- Bereich für bestehende Domains -->
        <div>
            <h2 class="text-xl font-bold mb-4">Existing Domains</h2>
            <DomainList :domains="domains" />
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
      };
    },
    methods: {
      async fetchDomains() {
        try {
          const response = await axios.get('/api/v1/domains');
          this.domains = response.data.data;
        } catch (error) {
            console.error('Failed to fetch domains:', error);
        }
      },
    },
    mounted() {
      this.fetchDomains();
    },
  };
  </script>
  