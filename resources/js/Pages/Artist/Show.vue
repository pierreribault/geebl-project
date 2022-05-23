<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import Header from '../../Components/Header.vue';
</script>

<template>

    <Head :title="$page.props.artist.name" />

    <Header />

    <div class="mt-20">
        <div class="container mx-auto max-w-6xl">
            <h1>{{ $page.props.artist.name }}</h1>
        </div>
    </div>
</template>