<template>
    <div>
      <!-- Erfolgsmeldung -->
      <div v-if="globalSuccessMessage" class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ globalSuccessMessage }}
      </div>
  
      <!-- Fehlermeldung -->
      <div v-if="globalErrorMessage" class="bg-red-100 text-red-800 p-3 rounded mb-4">
        <p><strong>Error:</strong> {{ globalErrorMessage.error }}</p>
        <p v-if="globalErrorMessage.plesk_error_id">
          <strong>Plesk Error ID:</strong> {{ globalErrorMessage.plesk_error_id }}
        </p>
        <p v-if="globalErrorMessage.plesk_error_message">
          <strong>Plesk Error Message:</strong> {{ globalErrorMessage.plesk_error_message }}
        </p>
      </div>
  
      <!-- Formular -->
      <Spinner :show="isLoading" />
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div class="flex flex-wrap gap-4">
          <!-- Domain -->
          <div class="flex-1">
            <label for="domain" class="block text-sm font-medium text-gray-700">Domain</label>
            <input
              type="text"
              id="domain"
              v-model="form.domain"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              @input="updateFtpUser"
              autocomplete="new-password"
              autofocus
            />
            <p v-if="errors.domain" class="text-red-500 text-sm mt-1">{{ errors.domain }}</p>
          </div>
  
          <!-- FTP User -->
          <div class="flex-1">
            <label for="ftp_user" class="block text-sm font-medium text-gray-700">FTP User</label>
            <input
              type="text"
              id="ftp_user"
              v-model="form.ftp_user"
              class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              @input="ftpUserModified = true"
              autocomplete="new-password"
            />
            <p v-if="errors.ftp_user" class="text-red-500 text-sm mt-1">{{ errors.ftp_user }}</p>
          </div>
  
          <!-- Password -->
          <div class="flex-1 relative">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative flex items-center">
              <input
                :type="showPassword ? 'text' : 'password'"
                id="password"
                v-model="form.password"
                class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10"
                autocomplete="new-password"
              />
              <!-- Passwort anzeigen/ausblenden -->
              <button
                type="button"
                @click="togglePasswordView"
                class="absolute inset-y-0 right-8 flex items-center px-2 text-gray-500"
              >
                <span v-if="showPassword">👁️</span>
                <span v-else>👁️‍🗨️</span>
              </button>
              <!-- Passwort generieren -->
              <button
                type="button"
                @click="generatePassword"
                class="absolute inset-y-0 right-0 flex items-center px-2 text-blue-600 hover:text-blue-800"
              >
                🔄
              </button>
            </div>
            <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
          </div>
        </div>
  
        <!-- Submit Button -->
        <button
          type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-4"
          :disabled="isLoading"
        >
          Create Domain
        </button>
      </form>
    </div>
  </template>
  
  
  <script>
  import axios from 'axios';
  import { randomPassword, lower, upper, digits } from 'secure-random-password';
  import Spinner from '@/Components/Spinner.vue';
  
  export default {
    components: { Spinner },
    data() {
      return {
        form: {
          domain: '',
          ftp_user: '',
          password: '',
        },
        ftpSuffix: '',
        ftpUserModified: false,
        errors: {},
        showPassword: false,
        isLoading: false, // Spinner-Zustand
        globalErrorMessage: null,
        globalSuccessMessage: null,
      };
    },
    created() {
        this.resetForm();
    },
    methods: {
    resetForm() {
      this.form = {
        domain: '',
        ftp_user: '',
        password: this.generatePassword(),
      };
      this.ftpSuffix = randomPassword({ length: 10, characters: [lower, digits] });
      this.ftpUserModified = false;
    },
      togglePasswordView() {
        this.showPassword = !this.showPassword;
      },
      generatePassword() {
        const securePassword = randomPassword({
          length: 16,
          characters: [lower, upper, digits],
        });
        this.form.password = securePassword;
        return securePassword;
      },
      updateFtpUser() {
        if (!this.ftpUserModified) {
            this.form.ftp_user = `${this.form.domain.toLowerCase()}_${this.ftpSuffix}`;
        }
      },
      async handleSubmit() {
        this.errors = {};
        this.globalErrorMessage = null
        this.globalSuccessMessage = null
        this.isLoading = true; // Spinner starten
        try {
          const response = await axios.post('/api/v1/domains', this.form);
          this.globalSuccessMessage = response.data.message;
          this.$emit('domainCreated');

          this.resetForm();
        } catch (error) {
            if (error.response?.status === 422 && error.response.data?.errors) {
                this.errors = error.response.data.errors;
            } else {
                this.globalErrorMessage = error.response?.data;
            }
        } finally {
          this.isLoading = false; // Spinner stoppen
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
  .btn:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
  }
  .btn:hover:not(:disabled) {
    background-color: #0056b3;
  }
  </style>
  