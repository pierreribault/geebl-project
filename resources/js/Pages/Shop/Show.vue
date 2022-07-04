<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { ref, onMounted, reactive, defineComponent, defineAsyncComponent } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { loadStripe } from '@stripe/stripe-js';
import Header from "../../Components/Header.vue";
import MinusPlus from "../../Components/MinusPlus.vue";



// ----------------------------------------------------------------------------------------------------------------
// Core                                                                                                  Core

onMounted(() => {
    loadStripe(usePage().props.value.pspPublicKey);
})

const paymentForm = ref(null)
const paymentElement = ref(null)
const paymentError = ref(null)
// ----------------------------------------------------------------------------------------------------------------
// Data                                                                                                  Data

const state = reactive({
    count: 0,
    price: 0,
    payment: {
        stripeElements: null,
        stripeInstance: null,
        stripeElementsInstance: null,
        readyToAcceptOrder: false,
        readyToAcceptEmail: false,
        readyToAcceptPayment: false,
        readyToSuccessFullPayment: false,
        invoice: null,
    },
});

const product = usePage().props.value.product;

const plus = (count) => {
    state.count = state.count + 1;
    state.payment.readyToAcceptOrder = state.count > 0;
    state.payment.readyToAcceptPayment = false;
    freshPrice();
}

const minus = (count) => {
    state.count = state.count - 1;
    state.payment.readyToAcceptOrder = state.count > 0;
    state.payment.readyToAcceptPayment = false;
    freshPrice();
}


// ----------------------------------------------------------------------------------------------------------------
// Payment                                                                                                  Payment

const initStripe = () => {
    const stripe = window.Stripe

    if (stripe == undefined) {
        return;
    }

    state.payment.stripeInstance = window.Stripe(
        usePage().props.value.pspPublicKey
    )
}

const paymentSetupPayment = async () => {
    initStripe();

    const { data } = await axios.post(`/shop/${product.slug}/payment/setup`, {
        order: state.payment.order,
        quantity: state.count,
    });

    state.payment.invoice = data.invoice;
    state.payment.readyToAcceptOrder = false
    state.payment.readyToAcceptPayment = true

    const appearance = {
        theme: 'stripe',

        variables: {
            colorPrimary: '#0570de',
            colorBackground: '#ffffff',
            colorText: '#30313d',
            colorDanger: '#df1b41',
            fontFamily: 'Ideal Sans, system-ui, sans-serif',
            spacingUnit: '2px',
            borderRadius: '4px',
            // See all possible variables below
        }
    };

    const elements = state.payment.stripeInstance.elements({
        clientSecret: data.client_secret,
        appearance: appearance,
    });

    state.payment.stripeElementsInstance = elements

    const stripeElements = elements.create('payment');
    stripeElements.mount(paymentElement.value);
}

const pay = async () => {
    const { error } = await state.payment.stripeInstance.confirmPayment({
        elements: state.payment.stripeElementsInstance,
        redirect: 'if_required'
    });

    if (error) {
        // This point will only be reached if there is an immediate error when
        // confirming the payment. Show error to your customer (for example, payment
        // details incomplete)
        const messageContainer = document.querySelector('#error-message');
        messageContainer.textContent = error.message;
    }

    const { data } = await axios.post(`/shop/${product.slug}/payment/verify`, {
        invoice: state.payment.invoice,
    });

    console.log(data);

    if (data.status === "succeeded") {
        window.location = data.redirect;
    }

    state.payment.readyToAcceptPayment = false;
    state.payment.readyToSuccessFullPayment = true;
}

const freshPrice = () => {
    state.price = product.price * state.count
}

</script>

<template>
    <AppLayout :title="product.name">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-white text-3xl">{{ product.name }}</h1>
                <div>
                    <div class="flex">
                        <div class="w-1/2">
                            <img class="inset-0 h-full w-full object-cover object-center sm:rounded-lg"
                                :src="product.product_url" :alt="product.name">
                        </div>
                        <div class="w-1/2 ml-3 p-4 bg-back flex flex-col sm:rounded-lg">
                            <div class="flex justify-between">
                                <p class="text-white">Quantity</p>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-white dark:text-white">
                                    <MinusPlus v-on:plus="plus($event)" v-on:minus="minus($event)" />
                                </div>
                            </div>
                            <div class="flex justify-between my-5">
                                <p class="text-white">Total</p>
                                <p class="text-white">{{ Math.round(state.price * 100) / 100 }} €</p>
                            </div>
                            <button @click="paymentSetupPayment()"
                                v-if="state.count > 0 && !state.payment.readyToAcceptPayment"
                                class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-2 px-12 py-3 rounded text-white uppercase font-bold text-sm"
                                href="#tickets">
                                Paiement
                            </button>
                            <form class="text-white" v-bind:class="{ invisible: !state.payment.readyToAcceptPayment }"
                                ref="paymentForm" id="payment-form">
                                <div ref="paymentElement" id="payment-element"></div>
                                <div ref="paymentError" id="error-message"></div>
                            </form>
                            <div v-if="state.payment.readyToAcceptPayment"
                                class="flex justify-between items-center mt-5">
                                <button @click="pay()" v-if="state.payment.readyToAcceptPayment"
                                    class="w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-2 px-12 py-3 rounded text-white uppercase font-bold text-sm"
                                    href="#tickets">
                                    Payer
                                </button>
                            </div>
                            <div v-if="state.payment.readyToSuccessFullPayment"
                                class="flex items-center bg-green-500 p-4 rounded text-white mt-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                <p class="ml-2 text-sm font-medium">
                                    Paiement réussi
                                <p class="text-sm font-medium">
                                    Vous recevrez un email de confirmation
                                </p>
                                </p>
                            </div>
                        </div>
                    </div>
                    <Markdown class="mt-5 text-white" :source="product.description" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
    .p-FieldLabel {
        color: #fff !important;
    }
</style>
