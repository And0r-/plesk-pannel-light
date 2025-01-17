<template>
    <div>
        <Spinner :show="isLoading" />

        <AlertMessages
            :successMessage="successMessage"
            :errorMessage="errorMessage"
        />

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1">
                    <InputField
                        id="domain"
                        label="Domain"
                        v-model="form.domain"
                        :error="errors.domain"
                        ref="domainField"
                    />
                </div>
                <div class="flex-1">
                    <InputField
                        id="ftp_user"
                        label="FTP User"
                        v-model="form.ftp_user"
                        :prefixRef="form.domain"
                        :randomSuffix="10"
                        :error="errors.ftp_user"
                        ref="ftpUserField"
                    />
                </div>
                <div class="flex-1">
                    <PasswordInput
                        id="password"
                        label="Password"
                        v-model="form.password"
                        :error="errors.password"
                        ref="passwordField"
                    />
                </div>
            </div>
            <Button :type="'submit'" :isDisabled="isLoading" class="mt-4"
                >Create Domain</Button
            >
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import InputField from '@/Components/Form/InputField.vue';
import PasswordInput from '@/Components/Form/PasswordInput.vue';
import Button from '@/Components/Form/Button.vue';
import AlertMessages from '@/Components/AlertMessages.vue';
import Spinner from '@/Components/Spinner.vue';

export default {
    components: {
        InputField,
        PasswordInput,
        Button,
        AlertMessages,
        Spinner,
    },
    data() {
        return {
            form: {
                domain: '',
                ftp_user: '',
                password: '',
            },
            errors: {},
            isLoading: false,
            errorMessage: null,
            successMessage: null,
        };
    },
    methods: {
        resetForm() {
            this.$refs.domainField.reset();
            this.$refs.ftpUserField.reset();
            this.$refs.passwordField.reset();
        },
        async handleSubmit() {
            this.errors = {};
            this.errorMessage = null;
            this.successMessage = null;
            this.isLoading = true;

            try {
                const response = await axios.post('/api/v1/domains', this.form);
                this.successMessage = response.data.message;
                this.$emit('domainCreated');
                this.resetForm();
            } catch (error) {
                if (
                    error.response?.status === 422 &&
                    error.response.data?.errors
                ) {
                    this.errors = error.response.data.errors;
                } else {
                    this.errorMessage = error.response?.data;
                }
            } finally {
                this.isLoading = false;
            }
        },
    },
};
</script>
