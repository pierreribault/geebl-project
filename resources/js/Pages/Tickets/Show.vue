<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
import { usePage } from '@inertiajs/inertia-vue3';

const user = usePage().props.value.user;
const transactions = usePage().props.value.transactions;

console.log(transactions);

</script>

<template>
    <AppLayout title="Tickets">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                My Tickets
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="(transaction, key) in $page.props.transactions" :key="key" class="w-full my-5">
                    <div class="flex">
                        <div class="h-32 w-auto md:w-1/4">
                            <img :src="transaction.event.cover_url"
                                class="inset-0 h-full w-full object-cover object-center" />
                        </div>
                        <div class="ml-5 flex flex-col justify-between w-full">
                            <div class="flex">
                                <h2 class="text-white font-bold text-lg">{{ transaction.event.name }} - <span
                                        class="text-gray-500">#{{ transaction.transaction.toUpperCase() }}</span> /
                                </h2>
                                <div class="ml-2">
                                    <span v-if="transaction.status === 'pending'"
                                        class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-green-300 text-green-700">
                                        Payment Completed
                                    </span>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <div>
                                    <p class="text-gray-300">Date: {{ transaction.event.beautiful_date }}</p>
                                    <p class="text-gray-300">Total price: {{ transaction.total }}€</p>
                                    <p class="text-gray-300">Quantity: {{ transaction.count }}</p>
                                </div>
                                <div>
                                    <p class="text-white font-bold mb-2">Actions</p>
                                    <button type="button"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition">
                                        Cancel order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-white text-xl text-bold mb-2">Tickets of event</h3>
                        <div class="flex">
                            <div v-for="ticket in transaction.tickets" :key="ticket.id"
                                class="bg-back p-4 mr-4 rounded text-center">
                                <div v-html="ticket.qrcode"></div>
                                <p class="text-gray-400 my-4">Placement:
                                    <span class="text-white capitalize">{{ticket.category.name }}</span>
                                </p>
                                <div>
                                    <span
                                        class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-green-300 text-green-700">
                                        Non-Used
                                    </span>
                                </div>
                                <button type="button"
                                    class="mt-5 inline-flex items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                    Resell
                                </button>
                            </div>
                        </div>
                    </div>
                    <JetSectionBorder />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
