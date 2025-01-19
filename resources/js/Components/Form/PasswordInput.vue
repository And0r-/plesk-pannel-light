<template>
    <InputField
        :id="id"
        :label="label"
        v-model="password"
        :type="showPassword ? 'text' : 'password'"
        :placeholder="placeholder"
        :error="error"
    >
        <template #actionIcons>
            <button
                type="button"
                @click="togglePasswordView"
                class="text-gray-500"
            >
                <span v-if="showPassword">👁️</span>
                <span v-else>👁️‍🗨️</span>
            </button>
            <button
                type="button"
                @click="generatePassword"
                class="ml-2 text-blue-600 hover:text-blue-800"
            >
                🔄
            </button>
        </template>
    </InputField>
</template>

<script>
/**
 * PasswordInput
 *
 * A specialized input field component for password management with features like:
 * - Toggle visibility (show/hide password)
 * - Generate a random secure password
 *
 * Usage:
 * <PasswordInput
 *     id="password"
 *     label="Password"
 *     v-model="form.password"
 *     :error="errors.password"
 * />
 *
 * Props:
 * - id (String): The unique ID for the input field.
 * - label (String): The label displayed above the input field.
 * - placeholder (String): Placeholder text for the input.
 * - error (String): Error message displayed below the input.
 *
 * Slots:
 * - actionIcons: Contains icons/buttons for toggling password visibility and generating a new password.
 *
 * Emits:
 * - update:modelValue: Emits the updated password value whenever it changes.
 *
 * Methods:
 * - togglePasswordView(): Toggles between visible and hidden password input types.
 * - generatePassword(): Generates a new random password and updates the model value.
 * - reset(): Resets the password field by generating a new password.
 *
 * Lifecycle:
 * - Generates an initial password when the component is mounted.
 */

import InputField from './InputField.vue';
import { randomPassword, lower, upper, digits } from 'secure-random-password';

export default {
    components: { InputField },
    props: {
        id: String,
        label: String,
        placeholder: String,
        error: String,
    },
    data() {
        return {
            password: '',
            showPassword: false,
        };
    },
    emits: ['update:modelValue'],
    methods: {
        togglePasswordView() {
            this.showPassword = !this.showPassword;
        },
        generatePassword() {
            const newPassword = randomPassword({
                length: 16,
                characters: [lower, upper, digits],
            });
            this.password = newPassword;
            this.$emit('update:modelValue', newPassword);
        },
        reset() {
            this.generatePassword();
        },
    },
    mounted() {
        this.generatePassword();
    },
};
</script>
