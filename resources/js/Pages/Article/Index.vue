<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { defineProps, reactive, toRefs } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import Markdown from "vue3-markdown-it";

const articles = usePage().props.value.articles;
</script>

<template>
    <AppLayout title="Articles">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Nos derniers articles
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col">
            <Link v-for="article in articles" :key="article.id" :href="`/articles/${article.slug}`"
                class="flex flex-col md:flex-row overflow-hidden my-6 text-white hover:text-gray-300">
                <div class="h-64 w-auto md:w-1/2">
                    <img class="inset-0 h-full w-full object-cover object-center" :src="article.article_url" />
                </div>
                <div class="w-full py-4 px-6 flex flex-col justify-between">
                    <h3 class="font-semibold text-lg leading-tight">{{ article.title }}</h3>
                    <Markdown class="mt-2" :source="article.short_content" />
                    <p class=" text-sm text-gray-400 uppercase tracking-wide font-semibold mt-2">
                        {{ article.redactor.name }} &bull; {{ article.date }}
                    </p>
                </div>
            </Link>
        </div>
    </AppLayout>
</template>
