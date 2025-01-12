<template>
    <div class="container mx-auto">
      <h1 class="text-2xl font-bold mb-4">Manage Domains</h1>
  
      <!-- Success Message -->
      <div v-if="successMessage" class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ successMessage }}
      </div>
  
      <!-- Form -->
      <form @submit.prevent="createDomain" class="mb-8">
        <div>
          <label for="domain" class="block text-sm font-medium">Domain</label>
          <input
            type="text"
            id="domain"
            v-model="form.domain"
            class="input-field"
          />
          <p v-if="errors.domain" class="text-red-500 text-sm">{{ errors.domain[0] }}</p>
        </div>
  
        <div>
          <label for="ftp_user" class="block text-sm font-medium">FTP User</label>
          <input
            type="text"
            id="ftp_user"
            v-model="form.ftp_user"
            class="input-field"
          />
          <p v-if="errors.ftp_user" class="text-red-500 text-sm">{{ errors.ftp_user[0] }}</p>
        </div>
  
        <div>
          <label for="password" class="block text-sm font-medium">Password</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="input-field"
          />
          <p v-if="errors.password" class="text-red-500 text-sm">{{ errors.password[0] }}</p>
        </div>
  
        <button type="submit" class="btn btn-primary mt-4">Create Domain</button>
      </form>
  
      <!-- Domains List -->
      <h2 class="text-xl font-bold mb-4">Existing Domains</h2>
      <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-100">
            <th class="border border-gray-300 px-4 py-2">Domain Name</th>
            <th class="border border-gray-300 px-4 py-2">Creation Date</th>
            <th class="border border-gray-300 px-4 py-2">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="domain in domains" :key="domain.id">
            <td class="border border-gray-300 px-4 py-2">{{ domain.name }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ domain.creationDate }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ domain.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        form: {
          domain: '',
          ftp_user: '',
          password: '',
        },
        errors: {},
        successMessage: '',
        domains: [],
      };
    },
    methods: {
      async createDomain() {
        this.errors = {};
        try {
          const response = await axios.post('/api/v1/domains', this.form);
          this.successMessage = response.data.message;
          this.fetchDomains(); // Update domain list
        } catch (error) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors; // Validation errors
          } else {
            this.successMessage = '';
            alert(error.response.data.error || 'An unexpected error occurred.');
          }
        }
      },
      async fetchDomains() {
        try {
          const response = await axios.get('/api/v1/domains');
          this.domains = response.data.data;
        } catch (error) {
          console.error('Error fetching domains:', error);
        }
      },
    },
    mounted() {
      this.fetchDomains(); // Load domains on component initialization
    },
  };
  </script>
  
  <style>
    .input-field {
      width: 100%;
      padding: 0.5rem;
      margin-top: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .btn {
      padding: 0.5rem 1rem;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #0056b3;
    }
  </style>
  