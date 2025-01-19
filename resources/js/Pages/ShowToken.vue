<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div>
            <h1>Your new personal access token</h1>
            <p>
                Below is your token (Bearer Token). Copy it now, you won't see
                it again!
            </p>
            <pre>{{ token }}</pre>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    props: {
        token: String,
    },
};
</script>
