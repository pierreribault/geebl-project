<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { defineProps, reactive, toRefs } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import Markdown from "vue3-markdown-it";
console.log(usePage().props.value.artist)

</script>

<template>
    <AppLayout :title="$page.props.artist.name">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-white text-3xl">{{ $page.props.artist.name }}</h1>
                <div>
                    <img v-if="$page.props.artist.cover_url" class=" w-full overflow-hidden sm:rounded-lg my-5"
                        :src="$page.props.artist.cover_url" :alt="$page.props.artist.name" >
                        <div v-if=" $page.props.artist.bio" class="text-sm text-white">{{
                    $page.props.artist.bio }}
                </div>
            </div>
        </div>
        </div>
    </AppLayout>
</template>
