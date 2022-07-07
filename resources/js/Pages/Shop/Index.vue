<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { defineProps, reactive, toRefs } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import Markdown from "vue3-markdown-it";
import JetButton from '@/Jetstream/Button.vue';

const products = usePage().props.value.products;
</script>

<template>
    <AppLayout title="Articles">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Nos produits
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col">
            <Link v-for="product in products" :key="product.id" :href="`/shop/${product.slug}`"
                class="flex flex-col md:flex-row overflow-hidden my-6 text-white hover:text-gray-300">
            <div class="h-64 w-auto md:w-1/2">
                <img class="inset-0 h-full w-full object-cover object-center rounded-xl" :src="product.product_url" />
            </div>
            <div class="w-full py-4 px-6 flex flex-col justify-between">
                <h3 class="font-semibold text-lg leading-tight">{{ product.name }}</h3>
                <Markdown class="mt-2" :source="product.short_content" />
                <div class="flex justify-between">
                    <p class=" text-sm text-gray-400 uppercase tracking-wide font-semibold mt-2">
                        {{ product.price }} €
                    </p>
                    <Link :href="`/shop/${product.slug}`">
                        <JetButton>View more</JetButton>
                    </Link>
                </div>
            </div>
            </Link>
        </div>
    </AppLayout>
</template>
