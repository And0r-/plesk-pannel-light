<template>
    <div>
        <div class="overflow-x-auto">
            <table
                class="table-auto w-full border-collapse border border-gray-300"
            >
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">
                            Domain Name
                        </th>
                        <th class="border border-gray-300 px-4 py-2">
                            Creation Date
                        </th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="domain in domains" :key="domain.id">
                        <td class="border border-gray-300 px-4 py-2">
                            {{ domain.name }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ domain.creationDate }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            <span
                                :class="{
                                    'text-green-500': domain.enabled,
                                    'text-red-500': !domain.enabled,
                                }"
                                class="cursor-pointer underline"
                                @click="toggleStatus(domain)"
                            >
                                {{ domain.enabled ? 'active' : 'disabled' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        domains: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            successMessage: '',
            errorMessage: '',
        };
    },
    methods: {
        async toggleStatus(domain) {
            this.successMessage = '';
            this.errorMessage = '';

            const newStatus = domain.enabled ? 'disabled' : 'active';
            try {
                const response = await axios.post(
                    `/api/v1/domains/${domain.id}/status`,
                    {
                        status: newStatus,
                    }
                );

                if (response.status === 200) {
                    // Aktualisiere den Status lokal
                    domain.enabled = !domain.enabled;
                    this.successMessage = response.data.message;
                }
            } catch (error) {
                if (error.response && error.response.data?.error) {
                    this.errorMessage = error.response.data.error;
                } else {
                    this.errorMessage =
                        'An unexpected error occurred. Please try again.';
                }
            }
        },
    },
};
</script>

<style scoped>
.text-green-500 {
    color: #28a745;
}

.text-red-500 {
    color: #dc3545;
}

.cursor-pointer {
    cursor: pointer;
}

.underline {
    text-decoration: underline;
}
</style>
