<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import ExternalLink from '@/Components/ExternalLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-blue-600 text-white">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div
                                class="flex shrink-0 items-center text-2xl font-bold"
                            >
                                <Link :href="route('dashboard')">
                                    <a href="/">Plesk Panel Light</a>
                                </Link>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md bg-blue-700 px-3 py-2 text-sm font-medium leading-4 text-white hover:bg-blue-800 focus:outline-none"
                                            >
                                                <div
                                                    v-if="
                                                        $page.props.auth &&
                                                        $page.props.auth.user
                                                    "
                                                >
                                                    {{
                                                        $page.props.auth.user
                                                            .name
                                                    }}
                                                </div>

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Home
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('domains')"
                            :active="route().current('domains')"
                        >
                            Domains
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div v-if="$page.props.auth && $page.props.auth.user">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-blue-600 shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Main Content -->
            <div
                class="flex-grow container mx-auto flex mt-6 flex-col md:flex-row"
            >
                <!-- Navigation -->
                <aside
                    class="w-full md:w-1/4 bg-gray-800 text-white shadow-md rounded-lg p-4"
                >
                    <nav>
                        <!-- Titel der Navigation -->
                        <div>
                            <span
                                class="text-gray-400 uppercase text-sm font-bold tracking-wide"
                            >
                                Administrativ
                            </span>
                        </div>

                        <!-- Navigationseinträge -->
                        <ul class="mt-4 space-y-2">
                            <li>
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    <span class="text-lg font-medium"
                                        >Home</span
                                    >
                                </NavLink>
                            </li>

                            <!-- Domains -->
                            <li>
                                <NavLink
                                    :href="route('domains')"
                                    :active="route().current('domains')"
                                >
                                    <span class="text-lg font-medium"
                                        >Domains</span
                                    >
                                </NavLink>
                            </li>
                        </ul>

                        <div class="mt-12">
                            <span
                                class="text-gray-400 uppercase text-sm font-bold tracking-wide"
                            >
                                Developer Tools
                            </span>
                        </div>

                        <ul class="mt-4 space-y-2">
                            <!-- Swagger -->
                            <li>
                                <NavLink href="/api/documentation">
                                    <span class="text-lg font-medium"
                                        >Swagger</span
                                    >
                                </NavLink>
                            </li>

                            <!-- Externe Links -->
                            <li>
                                <ExternalLink
                                    href="https://s2404.rootserver.io:8443"
                                >
                                    <span class="text-lg font-medium"
                                        >Plesk</span
                                    >
                                </ExternalLink>
                            </li>
                        </ul>
                    </nav>
                </aside>
                <!-- Page Content -->
                <main class="w-full md:w-3/4 bg-white shadow-md rounded-lg p-6">
                    <slot></slot>
                </main>
            </div>
        </div>
    </div>
</template>
