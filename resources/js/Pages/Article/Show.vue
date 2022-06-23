<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { defineProps, reactive, toRefs } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import Markdown from "vue3-markdown-it";

const article = usePage().props.value.article;
</script>

<template>
    <AppLayout :title="article.title">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-white text-3xl">{{ article.title }}</h1>
                <p class="text-gray-300">{{ article.beautiful_date }} by {{ article.redactor.name }}</p>
                <div>
                    <img v-if="article.article_url" class=" w-full overflow-hidden sm:rounded-lg my-5"
                        :src="article.article_url" :alt="article.title">
                    <Markdown class="mt-5 text-white" :source="article.content" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
