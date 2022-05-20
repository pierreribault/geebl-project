<script setup>
import { ref, onMounted, reactive, defineComponent, defineAsyncComponent } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { loadStripe } from '@stripe/stripe-js';

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
  payment: {
    stripeElements: null,
    stripeInstance: null,
    stripeElementsInstance: null,
    readyToAcceptOrder: false,
    readyToAcceptPayment: false,
    readyToSuccessFullPayment: false,
  },
});

const eventId = usePage().props.value.event.id;
const eventName = usePage().props.value.event.name;

// ----------------------------------------------------------------------------------------------------------------
// Tickets                                                                                                  Tickets

const chooseTicket = () => {
  state.payment.readyToAcceptOrder = true
  state.payment.readyToAcceptPayment = false
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

  const { data } = await axios.post(`/events/${eventId}/payment/setup`, {
    tickets: [{
      amount: 5, // @todo: should be removed when slots are implemented
    }],
  });

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

  state.payment.readyToSuccessFullPayment = true
}

</script>

<template>

  <Head :title="$page.props.event.name" />

  <div style="background-color: #121212">
    <div class="w-min-full min-h-screen">
      <div class="px-4 h-screen rounded flex items-end" id="event">
        <img style="box-shadow: inset 0em -2em 15px #121212;"
          class="absolute opacity-75 b-none left-0 top-0 w-full h-screen"
          src="https://shotgun.live/_next/image?url=https%3A%2F%2Fres.cloudinary.com%2Fshotgun%2Fimage%2Fupload%2Fv1652210863%2Fproduction%2Fartworks%2Fjeu_p177tm.jpg&w=1920&q=75">
        <div class="container mx-auto pb-36 z-10">
          <h1 class="text-white font-bold text-4xl">{{ $page.props.event.name }}</h1>
          <p class="text-gray-400">{{ $page.props.event.date }}</p>
          <p class="text-gray-400 uppercase relative top-8">Lineup : DJ Snake</p>
          <a class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-20 px-12 py-3 rounded text-white uppercase font-bold text-sm"
            href="#tickets">Tickets</a>
        </div>
      </div>
    </div>
    <div class="mt-5">
      <div id="tickets" class="container mx-auto">
        <div class="p-4 max-w-md rounded-lg shadow-md sm:p-8">
          <div class="flex justify-between items-center mb-4">
            <h5 class="text-xl font-bold leading-none text-white dark:text-white">🎫 Tickets</h5>
          </div>
          <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
              <li class="py-3 sm:py-4">
                <div class="flex bg-gray-800 p-4 rounded border-gray-700 items-center space-x-4">
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                      Regular
                    </p>
                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                      arrivée après 2H du matin
                    </p>
                  </div>
                  <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                    <input @click="chooseTicket()" type="checkbox" />
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div v-if="state.payment.readyToAcceptOrder" class="flex justify-between items-center mb-4">
            <button @click="paymentSetupPayment()" v-if="state.payment.readyToAcceptOrder"
              class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-2 px-12 py-3 rounded text-white uppercase font-bold text-sm"
              href="#tickets">
              Valider
            </button>
          </div>
          <form class="bg-gray-800 p-4 rounded border-gray-700"
            v-bind:class="{ invisible: !state.payment.readyToAcceptPayment }" ref="paymentForm" id="payment-form">
            <div ref="paymentElement" id="payment-element"></div>
            <div ref="paymentError" id="error-message"></div>
          </form>
          <div v-if="state.payment.readyToAcceptPayment" class="flex justify-between items-center">
            <button @click="pay()" v-if="state.payment.readyToAcceptPayment"
              class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-2 px-12 py-3 rounded text-white uppercase font-bold text-sm"
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
    </div>
  </div>
</template>
