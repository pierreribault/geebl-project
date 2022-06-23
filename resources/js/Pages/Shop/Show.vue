<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { defineProps, reactive, toRefs } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import Markdown from "vue3-markdown-it";

const product = usePage().props.value.product;

console.log(product);
</script>

<template>
    <AppLayout :title="product.name">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-white text-3xl">{{ product.name }}</h1>
                <div>
                    <div class="float">
                        <img v-if="product.product_url" class=" w-full overflow-hidden sm:rounded-lg my-5"
                            :src="product.product_url" :alt="product.name">
                    </div>
                    <Markdown class="mt-5 text-white" :source="product.description" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
