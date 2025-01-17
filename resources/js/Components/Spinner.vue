<template>
    <div :class="spinnerClass" v-if="show">
        <div class="spinner-circle"></div>
    </div>
</template>

<script>
/**
 * A reusable loading spinner component with support for both global and inline modes.
 *
 * Usage:
 * <Spinner :show="isLoading" />
 * <Spinner :show="isLoading" :inline="true" />
 *
 * Props:
 * - show (Boolean): Controls the visibility of the spinner. Default: false.
 * - inline (Boolean): Determines whether the spinner is displayed inline (relative to its container)
 *   or as a global overlay. Default: false.
 *
 * Features:
 * - **Global Mode**: Displays a fullscreen overlay with a semi-transparent background and a centered spinner.
 * - **Inline Mode**: Displays the spinner within its parent container without overlaying the entire screen.
 * - Animated: Includes a smooth rotation animation for the spinner circle.
 *
 * Styling:
 * - Global spinner has a dark semi-transparent background.
 * - Inline spinner takes up the full width and height of its container.
 *
 * Accessibility:
 * - The `show` prop allows for conditional rendering to improve performance.
 */

export default {
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        inline: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        spinnerClass() {
            return this.inline
                ? 'inline-spinner flex justify-center items-center'
                : 'global-spinner flex justify-center items-center';
        },
    },
};
</script>

<style scoped>
.global-spinner {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.inline-spinner {
    position: relative;
    width: 100%;
    height: 100%;
}

.spinner-circle {
    border: 5px solid #ccc;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
