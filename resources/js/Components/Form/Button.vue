<template>
    <button
        :type="type"
        :class="`px-4 py-2 ${
            isDisabled ? 'bg-gray-400' : 'bg-blue-600'
        } text-white rounded-md hover:bg-blue-700 focus:outline-none`"
        :disabled="isDisabled"
    >
        <slot />
    </button>
</template>

<script>
/**
 * A reusable button component with dynamic styling for disabled and hover states.
 *
 * Usage:
 * <Button :type="'submit'" :isDisabled="isSubmitting">
 *     Submit
 * </Button>
 *
 * Props:
 * - type (String): The type of the button (e.g., "button", "submit", "reset"). Default: "button".
 * - isDisabled (Boolean): Whether the button is disabled. Default: false.
 *
 * Slots:
 * - default: The content or label to be displayed inside the button (e.g., "Submit", "Cancel").
 *
 * Features:
 * - Dynamically updates styling based on `isDisabled`.
 * - Supports hover and focus styles for better user experience.
 * - Customizable via the `type` prop for different button behaviors.
 */

export default {
    props: {
        type: { type: String, default: 'button' },
        isDisabled: { type: Boolean, default: false },
    },
};
</script>
