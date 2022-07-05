<script setup>
import axios from "axios";
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
import VueCountdown from '@chenfengyuan/vue-countdown';
import JetDialogModal from '@/Jetstream/DialogModal.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetDangerButton from '@/Jetstream/DangerButton.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import { usePage } from '@inertiajs/inertia-vue3';

const user = usePage().props.value.user;
const transactions = usePage().props.value.transactions;


const cancelOrder = ref(false);
const transactionToCancel = ref(null);

const confirmCancelOrder = (transaction) => {
    cancelOrder.value = true;
    transactionToCancel.value = transaction;
};

const deleteOrder = async () => {
    const { data } = await axios.post(route("tickets.refund"), {
        transaction: transactionToCancel.value
    });

    location.reload();

    cancelOrder.value = false;
    transactionToCancel.value = null
}

const closeModal = () => {
    cancelOrder.value = false;
    transactionToCancel.value = null
};

console.log(transactions);

</script>

<template>
    <AppLayout title="Tickets">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                My Tickets
            </h2>
        </template>

        <JetDialogModal :show="cancelOrder" @close="closeModal">
            <template #title>
                Cancel your order
            </template>

            <template #content>
                Are you sure you want to cancel your order? Once your order is deleted, all of its tickets will be
                permanently deleted. This action is irreversible.
            </template>

            <template #footer>
                <JetSecondaryButton @click="closeModal">
                    Abort
                </JetSecondaryButton>

                <JetDangerButton class="ml-3" @click="deleteOrder">
                    Cancel Order
                </JetDangerButton>
            </template>
        </JetDialogModal>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-for="(transaction, key) in $page.props.transactions" :key="key" class="w-full my-5">
                    <div class="flex">
                        <a :href="route('events.show', { slug: transaction.event.slug})" class=" h-32 w-auto md:w-1/4">
                            <img :src="transaction.event.cover_url"
                                class="inset-0 h-full w-full object-cover object-center" />
                        </a>
                        <div class="ml-5 flex flex-col justify-between w-full">
                            <div class="flex">
                                <h2 class="text-white font-bold text-lg">{{ transaction.event.name }} - <span
                                        class="text-gray-500">#{{ transaction.transaction.toUpperCase() }}</span> /
                                </h2>
                                <div class="ml-2">
                                    <span v-if="transaction.status === 'completed'"
                                        class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-green-300 text-green-700">
                                        Payment Completed
                                    </span>
                                    <span v-if="transaction.status === 'pending'"
                                        class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-orange-300 text-orange-700">
                                        Payment Pending - <vue-countdown :time="transaction.expire_in"
                                            v-slot="{ minutes, seconds }">{{ minutes }} minutes {{ seconds }} seconds.
                                        </vue-countdown>
                                    </span>
                                    <span v-if="transaction.status === 'refunded'"
                                        class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-sky-300 text-sky-700">
                                        Refunded
                                    </span>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <div>
                                    <p class="text-gray-300">Date: {{ transaction.event.beautiful_date }}</p>
                                    <p class="text-gray-300">Total price: {{ transaction.total }}€</p>
                                    <p class="text-gray-300">Quantity: {{ transaction.count }}</p>
                                </div>
                                <div v-if="transaction.status !== 'refunded'">
                                    <p class="text-white font-bold mb-2">Actions</p>
                                    <JetDangerButton v-if="transaction.status === 'completed'"
                                        @click="confirmCancelOrder(transaction.transaction)">
                                        Cancel order
                                    </JetDangerButton>
                                    <a :href="route('events.show', { slug: transaction.event.slug }) + '?payment_intent=' + transaction.transaction"
                                        v-if="transaction.status === 'pending'">
                                        <JetButton type="button">
                                            Retry payment
                                        </JetButton>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4" v-if="transaction.status === 'completed'">
                        <h3 class=" text-white text-xl text-bold mb-2">Tickets of event</h3>
                        <div class="grid gap-x-12 gap-y-8 grid-cols-4">
                            <div v-for="ticket in transaction.tickets" :key="ticket.id"
                                class="bg-back p-4 rounded text-center">
                                <div class="flex justify-center" v-html="ticket.qrcode"></div>
                                <p class="text-gray-400 my-4">Placement:
                                    <span class="text-white capitalize">{{ticket.category.name }}</span>
                                </p>
                                <div>
                                    <span v-if="ticket.status === 'non-used'"
                                        class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-green-300 text-green-700">
                                        Non-Used
                                    </span>
                                    <span v-if="ticket.status === 'used'"
                                        class="whitespace-no-wrap px-2 py-1 rounded-full uppercase text-xs font-bold bg-red-300 text-red-700">
                                        Used
                                    </span>
                                </div>
                                <JetButton type="button" class="mt-4" v-if="ticket.status === 'non-used'">
                                    Resell
                                </JetButton>
                            </div>
                        </div>
                    </div>
                    <JetSectionBorder />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
