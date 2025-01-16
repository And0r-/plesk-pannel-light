<script setup>
const props = defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <a
        :href="href"
        target="_blank"
        rel="noopener noreferrer"
        class="block w-full flex items-center p-4 rounded-lg text-gray-300 hover:bg-blue-600 hover:text-white transition duration-150 ease-in-out"
    >
        <slot />
    </a>
</template>
