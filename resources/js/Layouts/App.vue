<template>
  <div id="app" class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-4 shadow-md">
      <div class="container mx-auto flex items-center justify-between">
        <div class="text-2xl font-bold">
          <a href="/">Plesk Panel Light</a>
        </div>
        <!-- Mobile Menu Toggle -->
        <button @click="toggleMobileNav" class="md:hidden">
          ☰
        </button>
      </div>
    </header>

    <!-- Mobile Navigation -->
    <nav
      v-if="mobileNavVisible"
      class="bg-white shadow-md p-4 md:hidden"
    >
      <ul>
        <li class="mb-2">
          <a href="/domains" class="block p-2 rounded hover:bg-gray-100">
            Domains
          </a>
        </li>
        <li>
          <a href="/settings" class="block p-2 rounded hover:bg-gray-100">
            Settings
          </a>
        </li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow container mx-auto flex mt-6 flex-col md:flex-row">
      <!-- Navigation -->
      <aside class="w-full md:w-1/4 bg-white shadow-md rounded-lg p-4 mb-4 md:mb-0">
        <nav class="hidden md:block">
          <ul>
            <li class="mb-2">
              <a href="/domains" class="block p-2 rounded hover:bg-gray-100">
                Domains
              </a>
            </li>
            <li>
              <a href="/settings" class="block p-2 rounded hover:bg-gray-100">
                Settings
              </a>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Page Content -->
      <main class="w-full md:w-3/4 bg-white shadow-md rounded-lg p-6">
        <!-- Global Messages -->
        <div v-if="$page.props.successMessage" class="bg-green-100 text-green-800 p-3 rounded mb-4">
          {{ $page.props.successMessage }}
        </div>
        <div v-if="$page.props.errorMessage" class="bg-red-100 text-red-800 p-3 rounded mb-4">
          <p><strong>Error:</strong> {{ $page.props.errorMessage.error }}</p>
          <p v-if="$page.props.errorMessage.plesk_error_id">
            <strong>Plesk Error ID:</strong> {{ $page.props.errorMessage.plesk_error_id }}
          </p>
          <p v-if="$page.props.errorMessage.plesk_error_message">
            <strong>Plesk Error Message:</strong> {{ $page.props.errorMessage.plesk_error_message }}
          </p>
        </div>

        <!-- Dynamic Slot Content -->
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppLayout',
  data() {
    return {
      mobileNavVisible: false,
    };
  },
  methods: {
    toggleMobileNav() {
      this.mobileNavVisible = !this.mobileNavVisible;
    },
  },
  props: {
    successMessage: {
      type: String,
      default: null,
    },
    errorMessage: {
      type: Object,
      default: null,
    },
  },
};
</script>

<style>
.container {
  max-width: 1200px;
}
</style>
