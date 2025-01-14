<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  href: {
    type: String,
    required: true,
  },
  active: {
    type: Boolean,
  },
});

const classes = computed(() =>
  props.active
    ? 'block w-full flex items-center p-4 rounded-lg bg-blue-700 text-white hover:bg-blue-800 transition duration-150 ease-in-out'
    : 'block w-full flex items-center p-4 rounded-lg text-gray-300 hover:bg-blue-600 hover:text-white transition duration-150 ease-in-out',
);
</script>

<template>
  <Link :href="href" :class="classes">
    <slot />
  </Link>
</template>
