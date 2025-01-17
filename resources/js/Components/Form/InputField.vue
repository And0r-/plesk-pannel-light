<template>
    <div>
        <label :for="id" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <div class="relative mt-1">
            <input
                :id="id"
                v-model="localValue"
                :type="type"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :placeholder="placeholder"
                @input="markAsManuallyModified"
            />
            <!-- Action Icons (e.g., show password, regenerate password) -->
            <span
                v-if="$slots.actionIcons"
                class="absolute inset-y-0 right-0 flex items-center pr-3"
            >
                <slot name="actionIcons" />
            </span>
        </div>
        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>

<script>
/**
 * A reusable input field component with support for dynamic prefixes, suffixes,
 * error messages, and action icons (e.g., show password, regenerate password).
 *
 * Usage:
 * <InputField
 *     id="example"
 *     label="Example Label"
 *     v-model="exampleValue"
 *     placeholder="Enter something"
 *     :error="exampleError"
 *     :prefixRef="referenceValue"
 *     :randomSuffix="8"
 * >
 *     <template #actionIcons>
 *         <button @click="doSomething">🔄</button>
 *     </template>
 * </InputField>
 *
 * Props:
 * - id (String): The unique ID for the input field.
 * - label (String): The label displayed above the input field.
 * - modelValue (String|Number): The v-model binding for the input's value.
 * - type (String): The type of the input (e.g., "text", "password"). Default: "text".
 * - placeholder (String): Placeholder text for the input.
 * - prefixRef (String): A dynamic prefix for the input value.
 * - randomSuffix (Number): Length of a randomly generated suffix appended to the prefix.
 * - error (String): Error message displayed below the input.
 *
 * Slots:
 * - actionIcons: Optional slot for custom action icons (e.g., show password, regenerate password).
 *
 * Methods:
 * - reset(): Resets the input to its default value (based on prefixRef and randomSuffix).
 */
export default {
    props: {
        id: String,
        label: String,
        modelValue: [String, Number],
        type: { type: String, default: 'text' },
        placeholder: String,
        prefixRef: String,
        randomSuffix: Number,
        error: String,
    },
    data() {
        return {
            localValue: this.modelValue || '',
            manuallyModified: false,
        };
    },
    watch: {
        modelValue(newValue) {
            this.localValue = newValue;
        },
        localValue(newValue) {
            this.$emit('update:modelValue', newValue);
        },
        prefixRef() {
            if (!this.manuallyModified) {
                this.setDefaultValue();
            }
        },
    },
    methods: {
        setDefaultValue() {
            const prefix = this.prefixRef ? this.prefixRef.toLowerCase() : '';
            const suffix = this.randomSuffix ? this.generateRandomSuffix() : '';
            this.localValue = prefix ? `${prefix}_${suffix}` : '';
        },
        generateRandomSuffix() {
            if (this.randomSuffix) {
                return Math.random()
                    .toString(36)
                    .substring(2, 2 + this.randomSuffix);
            }
            return '';
        },
        markAsManuallyModified() {
            this.manuallyModified = true;
        },
        reset() {
            this.manuallyModified = false;
            this.setDefaultValue();
        },
    },
    mounted() {
        this.setDefaultValue();
    },
};
</script>
