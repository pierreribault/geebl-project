<script setup>
import { ref, onMounted, reactive, defineComponent, defineAsyncComponent } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import axios from "axios";
import { loadStripe } from '@stripe/stripe-js';
import Header from "../../Components/Header.vue";
import MinusPlus from "../../Components/MinusPlus.vue";
import AppLayout from '@/Layouts/AppLayout.vue';
import Markdown from "vue3-markdown-it";

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
    order: {},
    stripeElements: null,
    stripeInstance: null,
    stripeElementsInstance: null,
    email: null,
    readyToAcceptOrder: false,
    readyToAcceptEmail: false,
    readyToAcceptPayment: false,
    readyToSuccessFullPayment: false,
    transaction: null,
  },
});

console.log(usePage().props.value.event);

const eventId = usePage().props.value.event.id;
const eventName = usePage().props.value.event.name;
const eventSlug = usePage().props.value.event.slug;

// ----------------------------------------------------------------------------------------------------------------
// Tickets                                                                                                  Tickets

const plus = (count, categoryId) => {
  state.count = state.count + 1;
  state.payment.order[categoryId] = {id: categoryId, quantity: count};
  state.payment.readyToAcceptOrder = state.count > 0;
  state.payment.readyToAcceptPayment = false;
  freshPrice();
}

const minus = (count, categoryId) => {
  state.count = state.count - 1;
  state.payment.order[categoryId] = {id: categoryId, quantity: count};
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

const paymentSetupEmail = async () => {
    state.payment.readyToAcceptEmail = true;
    state.payment.readyToAcceptOrder = false;

    if (usePage().props.value.user != undefined) {
        state.payment.email = usePage().props.value.user.email

        state.payment.readyToAcceptEmail = false;
        paymentSetupPayment()
    }
}

const paymentEmail = async () => {
  const response = await axios.post(`/events/${eventSlug}/payment/email`, {
    email: state.payment.email,
  });

  state.payment.readyToAcceptEmail = false;
  paymentSetupPayment()
}

const paymentSetupPayment = async () => {
  initStripe();

  const { data } = await axios.post(`/events/${eventSlug}/payment/setup`, {
    order: state.payment.order,
    email: state.payment.email,
  });

  state.payment.readyToAcceptOrder = false
  state.payment.readyToAcceptPayment = true

  const appearance = {
    theme: 'flat',

    variables: {
      colorPrimary: '#ffffff',
      colorBackground: '#0f172a',
      colorText: '#9ca3af',
      colorDanger: '#df1b41',
        fontFamily: 'Nunito, ui-sans-serif, system-ui',
      spacingUnit: '4px',
      borderRadius: '4px',
      // See all possible variables below
    }
  };

  const elements = state.payment.stripeInstance.elements({
    clientSecret: data.client_secret,
    appearance: appearance,
  });

  state.payment.transaction = data.transaction;

  state.payment.stripeElementsInstance = elements

  const stripeElements = elements.create('payment');
  stripeElements.mount(paymentElement.value);
}

const restorePayment = async () => {
    initStripe();

    const url = new URLSearchParams(window.location.search);

    state.payment.transaction = url.get('payment_intent');

    const { data } = await axios.post(`/events/${eventSlug}/payment/retry`, {
        transaction: state.payment.transaction,
    });

    if (data.redirect) {
        window.location.href = data.redirect;
    }

    state.payment.order = JSON.parse(data.order)

    paymentSetupPayment()
}

const pay = async () => {
    console.log(state.payment.stripeElementsInstance);

  const { error } = await state.payment.stripeInstance.confirmPayment({
    elements: state.payment.stripeElementsInstance,
    redirect: 'if_required',
  });

  if (error) {
    // This point will only be reached if there is an immediate error when
    // confirming the payment. Show error to your customer (for example, payment
    // details incomplete)
    const messageContainer = document.querySelector('#error-message');
    messageContainer.textContent = error.message;
  }

  const { data } = await axios.post(`/events/${eventSlug}/payment/verify`, {
    order: state.payment.transaction,
  });

  if (data.status === "succeeded") {
    window.location = data.redirect;
  }

  state.payment.readyToAcceptPayment = false;
  state.payment.readyToSuccessFullPayment = true;
}

const freshPrice = async () => {

  console.log(state.payment.order)

  const { data } = await axios.post(`/events/${eventSlug}/payment/price`, {
    order: state.payment.order,
  });

  state.price = data.price
}


if (new URLSearchParams(window.location.search).has('payment_intent')) {
    restorePayment()
}

</script>

<template>
    <AppLayout :title="$page.props.event.name">
        <div>
            <div class="w-min-full min-h-screen relative">
                <div class="px-4 h-screen rounded flex items-end" id="event">
                    <img style="box-shadow: inset 0em -2em 15px #121212;"
                        class="absolute opacity-75 b-none left-0 top-0 w-full h-screen"
                        :src="$page.props.event.cover_url">
                    <div class="container mx-auto pb-52 z-10">
                        <h1 class="text-white font-bold text-4xl">{{ $page.props.event.name }}</h1>
                        <p class="text-gray-400">{{ $page.props.event.date }}</p>
                        <p v-if="$page.props.event.artists" class="text-gray-400 relative top-8">
                            <span class="text-sm uppercase">Lineup</span>
                            <span v-bind:key="key" v-for="(artist, key, index) in $page.props.event.artists">
                                <Link :href="'/artists/' + artist.id" class="text-white px-3 py-1 text-sm">
                                {{ artist.name }}
                                </Link>
                                <span v-if="key !== $page.props.event.artists.length - 1" style="color: #c17afe"
                                    class="text-xs">■</span>
                            </span>
                        </p>
                        <p class="text-gray-400 uppercase relative top-9" v-if="$page.props.event.kinds">
                            <span style="color: #0079ff; background-color: #121b31;" v-bind:key="index"
                                v-for="(kind, index) in $page.props.event.kinds"
                                class="inline-block rounded px-3 py-1 text-sm mr-2">{{
                                kind.name.en
                                }}</span>
                        </p>
                        <a v-if="$page.props.event.categories.length > 0"
                            class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-20 px-12 py-3 rounded text-white uppercase font-bold text-sm"
                            href="#tickets">Tickets</a>
                    </div>
                </div>
            </div>
            <div class="flex container m-auto justify-between">
                <div class="w-2/3">
                    <!-- Description-->
                    <div>
                        <div id="description" class="container mx-auto">
                            <div class="py-4 max-w-md rounded-lg sm:py-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h5 class="text-xl font-bold leading-none text-white dark:text-white">Description
                                    </h5>
                                </div>
                                <Markdown class="text-white" :source="$page.props.event.description" />
                            </div>
                        </div>
                    </div>
                    <!-- Logistical information-->
                    <div>
                        <div id="description" class="container mx-auto">
                            <div class="py-4 max-w-lg rounded-lg sm:py-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h5 class="text-xl font-bold leading-none text-white dark:text-white">Logistical
                                        Information
                                    </h5>
                                </div>
                                <div class="flow-root text-white">
                                    <p>📅 {{ $page.props.event.beautiful_date }}</p>
                                    <p>📍 {{ $page.props.event.location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/3">
                    <div v-if="$page.props.event.categories.length > 0" id="tickets" class="container mx-auto">
                        <div class="p-4 max-w-md rounded-lg sm:p-8">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-xl font-bold leading-none text-white dark:text-white">Tickets</h5>
                            </div>
                            <div class="flow-root">
                                <ul role="list">
                                    <li v-bind:key="index" v-for="(category, index) in $page.props.event.categories"
                                        class="py-3 sm:py-4">
                                        <div class="flex bg-back p-4 rounded items-center space-x-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium truncate font-bold text-white">
                                                    {{ category.name }} <span class="text-gray-500"> - {{ category.price
                                                    }}€</span>
                                                </p>
                                                <p v-if="category.description"
                                                    class="text-sm text-gray-500 truncate text-gray-400">
                                                    {{ category.description }}
                                                </p>
                                            </div>
                                            <div
                                                class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                                <MinusPlus
                                                    v-bind:count="state.payment.order[category.id]?.quantity ?? 0"
                                                    v-on:plus="plus($event, category.id)"
                                                    v-on:minus="minus($event, category.id)" />
                                            </div>
                                        </div>
                                    </li>
                                    <li v-if="state.payment.readyToAcceptOrder" class="py-3 sm:py-4">
                                        <div class="flex bg-back p-4 rounded items-center justify-between space-x-4">
                                            <p class="text-sm font-medium text-white truncate">
                                                💰 Total price
                                            </p>
                                            <p class="text-sm font-medium text-white truncate">
                                                {{ state.price }}€
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div v-if="state.payment.readyToAcceptOrder" class="flex justify-between items-center mb-4">
                                <button @click="paymentSetupEmail()" v-if="state.payment.readyToAcceptOrder"
                                    class="w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-2 px-12 py-3 rounded text-white uppercase font-bold text-sm"
                                    href="#tickets">
                                    Valider
                                </button>
                            </div>
                            <div v-if="state.payment.readyToAcceptEmail" class="flex flex-col justify-between">
                                <input required v-model="state.payment.email"
                                    title="Si vous possédez un compte Geebl, votre ticket sera lié directement."
                                    type="email" class="bg-back p-4 rounded border-gray-700 text-white items-center space-x-4"
                                    placeholder="Email" />
                                <button @click="paymentEmail()" v-if="state.payment.readyToAcceptEmail"
                                    class="w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative top-2 px-12 py-3 rounded text-white uppercase font-bold text-sm"
                                    href="#tickets">
                                    Paiement
                                </button>
                            </div>
                            <form class="bg-back p-4 rounded border-gray-700"
                                v-bind:class="{ invisible: !state.payment.readyToAcceptPayment }" ref="paymentForm"
                                id="payment-form">
                                <div ref="paymentElement" id="payment-element"></div>
                                <div ref="paymentError" id="error-message"></div>
                            </form>
                            <div v-if="state.payment.readyToAcceptPayment"
                                class="flex justify-between items-center mt-3">
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
                </div>
            </div>
            <div class="container m-auto ">
                <!-- News -->
                <div class="py-4 sm:py-8">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-xl font-bold leading-none text-white dark:text-white">News</h5>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-white rounded bg-back p-4" v-for="actuality in $page.props.event.news"
                            :key="actuality.id">
                            <Markdown class="mb-5 text-white" :source="actuality.content" />
                            <p class="text-grey-300">{{ actuality.beautiful_date }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
