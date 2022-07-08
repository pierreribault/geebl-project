<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import Header from '../../Components/Header.vue';
</script>

<template>

    <Head :title="$page.props.artist.name" />

    <Header />

    <div class="mt-20">
        <div class="container mx-auto max-w-6xl text-white">
            <div
                class="rounded-xl bg-gradient-to-r bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-2 sm:p-6 dark:bg-gray-800">
                <div class="flex items-center space-x-4">
                    <img class="w-10 h-10 rounded-full" :src="$page.props.artist.cover_url" alt="">
                    <div class="space-y-1 font-medium dark:text-white">
                        <div class="text-xl">{{ $page.props.artist.name }}</div>
                        <div v-if="$page.props.artist.bio" class="text-sm text-gray-500 dark:text-gray-400">{{ $page.props.artist.bio }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>