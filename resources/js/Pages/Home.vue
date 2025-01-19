<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-4">
                        <h2
                            class="text-xl font-semibold leading-tight text-gray-800"
                        >
                            Welcome to your Plesk Panel Light
                        </h2>
                        <p>
                            This project was developed as a solution for a job
                            application task. It provides the foundation for a
                            lightweight management tool that works with domains
                            via the Plesk API.
                        </p>

                        <h3 class="text-lg font-medium">Task</h3>
                        <p>The task was to:</p>
                        <ul class="list-disc list-inside text-gray-700">
                            <li>Create new domains using the Plesk API.</li>
                            <li>
                                Display a list of existing domains with domain
                                name, creation date, and status.
                            </li>
                        </ul>

                        <h3 class="text-lg font-medium">Project Overview</h3>
                        <ul class="list-disc list-inside text-gray-700">
                            <li>
                                <strong>Technologies:</strong> Laravel (with
                                Breeze and Sanctum), Vue, Inertia.js, Swagger,
                                Tailwind CSS.
                            </li>
                            <li>
                                <strong>Authentication:</strong> Laravel Breeze
                                was used to set up user authentication,
                                including login, password reset, email
                                verification, and profile management.
                            </li>
                            <li>
                                <strong>Modularity:</strong> The project is
                                designed to be flexible and extendable to
                                integrate additional features.
                            </li>
                            <li>
                                <strong>RESTful API:</strong> All
                                functionalities are also available through a
                                RESTful API, documented via Swagger.
                            </li>
                        </ul>

                        <h3 class="text-lg font-medium">
                            Challenges and Solutions
                        </h3>
                        <ul class="list-disc list-inside text-gray-700">
                            <li>
                                <strong
                                    >Transaction-Safe Domain Creation:</strong
                                >
                                The Plesk API processes domain creation and user
                                creation in separate steps. If user data is
                                invalid, the domain remains, leading to
                                inconsistencies.
                                <br />
                                <em>Solution:</em> The `webspace->create` method
                                was adapted to optionally work in a
                                transaction-safe mode. If user creation fails,
                                the domain is automatically deleted.
                            </li>
                            <li>
                                <strong>Domain Status Changes:</strong>
                                Plesk handles status changes differently
                                depending on whether they are made via the API
                                or the GUI.
                                <br />
                                <em>Solution:</em> A status switch was
                                implemented to ensure changes are correctly
                                handled through the API. Currently, it is
                                intended that the status is managed exclusively
                                via the Plesk Panel Light.
                            </li>
                        </ul>

                        <h3 class="text-lg font-medium">
                            Vision: Extendability Through Plugins
                        </h3>
                        <p>
                            A long-term vision for Plesk Panel Light is to
                            convert modules, tests, and frontend components into
                            standalone plugins. These could be installed,
                            activated, or updated via an integrated store,
                            allowing the system to be flexibly tailored to
                            specific needs.
                        </p>
                        <p>
                            This would make the panel not only more modular but
                            also easier to maintain and extend without impacting
                            the core structure. Customers could individually add
                            features, increasing usability and the value of the
                            project.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
