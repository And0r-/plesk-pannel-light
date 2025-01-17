<template>
    <div>
        <div
            v-if="successMessage"
            class="bg-green-100 text-green-800 p-3 rounded mb-4"
        >
            {{ successMessage }}
        </div>

        <div
            v-if="errorMessage"
            class="bg-red-100 text-red-800 p-3 rounded mb-4"
        >
            <p><strong>Error:</strong> {{ errorMessage.error }}</p>
            <p v-if="errorMessage.plesk_error_id">
                <strong>Plesk Error ID:</strong>
                {{ errorMessage.plesk_error_id }}
            </p>
            <p v-if="errorMessage.plesk_error_message">
                <strong>Plesk Error Message:</strong>
                {{ errorMessage.plesk_error_message }}
            </p>
        </div>
    </div>
</template>

<script>
/**
 * A reusable component for displaying success and error messages with dynamic content.
 *
 * Usage:
 * <AlertMessages
 *     :successMessage="'Operation successful!'"
 *     :errorMessage="{
 *         error: 'Operation failed.',
 *         plesk_error_id: 1234,
 *         plesk_error_message: 'Invalid domain name.'
 *     }"
 * />
 *
 * Props:
 * - successMessage (String): The success message to display. Default: null.
 * - errorMessage (Object): The error message object to display, containing:
 *     - error (String): A general error description.
 *     - plesk_error_id (Number): An optional error ID (e.g., from the backend).
 *     - plesk_error_message (String): A detailed error message.
 *
 * Features:
 * - Automatically displays styled success or error messages when their respective props are set.
 * - Supports additional metadata for error messages (e.g., error IDs or detailed backend messages).
 * - Simple and reusable for various forms and actions.
 */

export default {
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
