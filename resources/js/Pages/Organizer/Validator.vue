<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import { QrStream, QrCapture, QrDropzone } from 'vue3-qr-reader';
import Header from '../../Components/Header.vue';
import { reactive, onMounted } from 'vue';
import JetDangerButton from '@/Jetstream/DangerButton.vue';

// ----------------------------------------------------------------------------------------------------------------
// Core                                                                                                  Core


// ----------------------------------------------------------------------------------------------------------------
// Data                                                                                                  Data

const state = reactive({
    overlay: {
        active: false,
        text: '',
        icon: '',
    },

    ticket: {}
});

// ----------------------------------------------------------------------------------------------------------------
// Overlay                                                                                                  Overlay

const toggleCapturedTicketOverlay = () => {
    state.overlay.active = !state.overlay.active;
    state.overlay.text = state.overlay.text !== 'Valid' ? 'Valid' : '';
}

const toggleAlreadyUsedTicketOverlay = () => {
    state.overlay.active = !state.overlay.active;
    state.overlay.text = state.overlay.text !== 'Valid (used)' ? 'Valid (used)' : '';
}

const toggleUncapturedTicketOverlay = () => {
    state.overlay.active = !state.overlay.active;
    state.overlay.text = state.overlay.text !== 'Not Valid' ? 'Not Valid' : '';
}
// ----------------------------------------------------------------------------------------------------------------
// Decoder                                                                                                  Decoder

const onDecode = async (value) => {
    if (value != "") {
        state.overlay.active = false;
        const { data } = await axios.post(`/api/tickets/${value}/use`);
        console.log(data)
        state.overlay.active = true;
        state.ticket = data;
    }
}

const refund = async () => {
    const { data } = await axios.post(`/api/tickets/${state.ticket.id}/refund`);
    state.overlay.active = true;
    state.ticket = data;
}

</script>

<template>

    <Head title="Validator" />

    <div class="bg-slate-900 grid place-items-center h-screen w-screen h-full mx-auto sm:px-6 lg:px-8">
        <div class="
          mt-8
          bg-white
          relative
          flex
          justify-center
          items-center
        ">
            <div v-if="state.overlay.active" @click="state.overlay.active = false"
                class="bg-back rounded shadow text-black mt-6 absolute z-10 h-100 w-80 p-4 rounded flex flex-col">
                <div class="h-60 flex flex-col">
                    <img class="h-40" :src="state.ticket.event.cover_url" />
                    <div class="text-center h-20 flex items-center justify-center flex-col">
                        <h3 v-if="state.ticket.user">{{ state.ticket.user.name }}</h3>
                        <p class="text-gray-400">{{ state.ticket.category.name }}</p>
                        <p class="mt-2 mb-2 text-gray-300 text-xs">{{ state.ticket.id }}</p>
                    </div>
                </div>
                <div v-if="state.ticket.status === 'used'"
                    class="h-12 flex justify-center items-center text-white bg-green-400 border border-transparent rounded-md font-semibold text-xs uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <p class="ml-2 text-sm font-medium">
                        {{ state.ticket.status }}
                    </p>
                </div>

                <div v-if="state.ticket.status === 're-used'"
                    class="h-12 flex justify-center items-center text-white bg-blue-400 border border-transparent rounded-md font-semibold text-xs uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <p class="ml-2">
                        {{ state.ticket.status }}
                    </p>
                </div>

                <div v-if="state.ticket.status === 'refunded'"
                    class="h-12 flex justify-center items-center text-white bg-orange-400 border border-transparent rounded-md font-semibold text-xs uppercase">
                    <p class="ml-2">
                        {{ state.ticket.status }}
                    </p>
                </div>

                <JetDangerButton @click="refund" v-if="state.ticket.status != 'refunded'" class="mt-2">Refund</JetDangerButton>
            </div>
            <qr-stream :camera="state.camera" @init="init" @decode="onDecode"></qr-stream>
        </div>
    </div>
</template>
