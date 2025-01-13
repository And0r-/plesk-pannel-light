<template>
    <div>
      <!-- Haupttitel -->
      <h1 class="text-3xl font-bold mb-6">Manage Domains</h1>
  
      <div class="flex flex-col lg:flex-row lg:gap-8">
        <!-- Formularbereich -->
        <div class="lg:w-4/12 mb-8 lg:mb-0">
          <h2 class="text-xl font-bold mb-4">Create Domain and User</h2>
          <DomainForm @domainCreated="fetchDomains" />
        </div>
  
        <!-- Bereich für bestehende Domains -->
        <div class="lg:w-8/12">
          <h2 class="text-xl font-bold mb-4">Existing Domains</h2>
          <DomainList :domains="domains" />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import DomainForm from './DomainForm.vue';
  import DomainList from './DomainList.vue';
  import AppLayout from '@/Layouts/App.vue';
  import axios from 'axios';
  
  export default {
    components: { DomainForm, DomainList },
    layout: AppLayout,
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
          this.$inertia.replace({ props: { errorMessage: error.response.data } });
        }
      },
    },
    mounted() {
      this.fetchDomains();
    },
  };
  </script>
  