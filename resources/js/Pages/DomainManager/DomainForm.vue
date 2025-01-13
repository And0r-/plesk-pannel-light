<template>
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label for="domain" class="block text-sm font-medium">Domain</label>
        <input
          type="text"
          id="domain"
          v-model="form.domain"
          class="input-field"
        />
        <p v-if="errors.domain" class="text-red-500 text-sm">{{ errors.domain }}</p>
      </div>
  
      <div>
        <label for="ftp_user" class="block text-sm font-medium">FTP User</label>
        <input
          type="text"
          id="ftp_user"
          v-model="form.ftp_user"
          class="input-field"
        />
        <p v-if="errors.ftp_user" class="text-red-500 text-sm">{{ errors.ftp_user }}</p>
      </div>
  
      <div>
        <label for="password" class="block text-sm font-medium">Password</label>
        <input
          type="password"
          id="password"
          v-model="form.password"
          class="input-field"
        />
        <p v-if="errors.password" class="text-red-500 text-sm">{{ errors.password }}</p>
      </div>
  
      <button type="submit" class="btn btn-primary mt-4">Create Domain</button>
    </form>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          domain: '',
          ftp_user: '',
          password: '',
        },
        errors: {},
      };
    },
    methods: {
      async handleSubmit() {
        this.errors = {};
        try {
          const response = await axios.post('/api/v1/domains', this.form);
          this.$inertia.replace({ props: { successMessage: response.data.message } });
          this.$emit('domainCreated');
        } catch (error) {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            this.$inertia.replace({ props: { errorMessage: error.response.data } });
          }
        }
      },
    },
  };
  </script>
  
  
  <style scoped>
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
  