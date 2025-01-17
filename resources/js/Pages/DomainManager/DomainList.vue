<template>
    <div>
        <AlertMessages
            :successMessage="successMessage"
            :errorMessage="errorMessage"
        />

        <div v-if="loading" class="flex justify-center items-center h-32">
            <Spinner :show="loading" :inline="true" />
        </div>

        <div v-else class="overflow-x-auto">
            <table
                class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md"
            >
                <thead>
                    <tr class="bg-gray-200 text-left text-gray-700">
                        <th class="border px-4 py-2">Domain Name</th>
                        <th class="border px-4 py-2">Creation Date</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="domain in domains"
                        :key="domain.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="border px-4 py-2">{{ domain.name }}</td>
                        <td class="border px-4 py-2">
                            {{ domain.creationDate }}
                        </td>
                        <td class="border px-4 py-2">
                            <span
                                :class="{
                                    'text-green-500':
                                        domain.enabled && !domain.loading,
                                    'text-red-500':
                                        !domain.enabled && !domain.loading,
                                }"
                                class="cursor-pointer underline flex items-center space-x-2"
                                @click="toggleStatus(domain)"
                            >
                                <span v-if="!domain.loading">
                                    {{ domain.enabled ? 'active' : 'disabled' }}
                                </span>
                                <Spinner
                                    v-else
                                    :show="domain.loading"
                                    :inline="true"
                                />
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import AlertMessages from '@/Components/AlertMessages.vue';
import Spinner from '@/Components/Spinner.vue';
import axios from 'axios';

export default {
    components: { AlertMessages, Spinner },
    data() {
        return {
            domains: [],
            loading: false,
            successMessage: null,
            errorMessage: null,
        };
    },
    methods: {
        async fetchDomains() {
            this.loading = true;
            try {
                await this.updateDomains();
            } finally {
                this.loading = false;
            }
        },
        async updateDomains() {
            try {
                const response = await axios.get('/api/v1/domains');
                this.domains = response.data.data;
            } catch (error) {
                console.error('Failed to update domains:', error);
                this.errorMessage = 'Failed to load domains.';
            }
        },
        async toggleStatus(domain) {
            domain.loading = true;
            const newStatus = domain.enabled ? 'disabled' : 'active';
            try {
                const response = await axios.post(
                    `/api/v1/domains/${domain.id}/status`,
                    { status: newStatus }
                );

                if (response.status === 200) {
                    domain.enabled = !domain.enabled;
                    this.successMessage = response.data.message;
                    this.errorMessage = null;
                }
            } catch (error) {
                this.errorMessage =
                    error.response?.data?.error ||
                    'An unexpected error occurred.';
                this.successMessage = null;
            } finally {
                domain.loading = false;
            }
        },
    },
    mounted() {
        this.fetchDomains();
    },
};
</script>

<style scoped>
.table-auto {
    border-spacing: 0;
}

.table-auto th,
.table-auto td {
    text-align: left;
    vertical-align: middle;
}

.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

.text-green-500 {
    color: #28a745;
}

.text-red-500 {
    color: #dc3545;
}
</style>
