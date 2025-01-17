<script setup>
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    tooltip: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="relative group">
        <a
            :href="href"
            target="_blank"
            rel="noopener noreferrer"
            class="block w-full flex items-center p-4 rounded-lg text-gray-300 hover:bg-blue-600 hover:text-white transition duration-150 ease-in-out"
        >
            <slot />
        </a>
        <!-- Tooltip -->
        <div
            v-if="tooltip"
            class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-100 text-gray-800 text-sm rounded-md p-2 shadow-lg opacity-0 group-hover:opacity-100 transition duration-150 ease-in-out pointer-events-none"
        >
            {{ tooltip }}
        </div>
    </div>
</template>
