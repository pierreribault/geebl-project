<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import { QrStream, QrCapture, QrDropzone } from 'vue3-qr-reader';
import Header from '../../Components/Header.vue';
import { reactive, onMounted } from 'vue';

// ----------------------------------------------------------------------------------------------------------------
// Core                                                                                                  Core

onMounted(() => {
    toggleLoadingOverlay();
})
// ----------------------------------------------------------------------------------------------------------------
// Data                                                                                                  Data

const state = reactive({
    overlay: {
        active: false,
        text: '',
        icon: '',
    },

    // should be removed when the tickets will be refactored
    event: {
        name: 'test',
        image_url: 'https://shotgun.live/_next/image?url=https://res.cloudinary.com/shotgun/image/upload/v1652210863/production/artworks/jeu_p177tm.jpg&w=1920&q=75',
    },

    ticket: {
        name: 'Thibeault Chenu',
        type: 'Regular',
    }
});

// ----------------------------------------------------------------------------------------------------------------
// Overlay                                                                                                  Overlay
const toggleLoadingOverlay = () => {
    state.overlay.active = !state.overlay.active;
    state.overlay.text = state.overlay.text !== 'Loading ...' ? 'Loading ...' : '';
}

const toggleCapturedTicketOverlay = () => {
    state.overlay.active = !state.overlay.active;
    state.overlay.text = state.overlay.text !== 'Valid' ? 'Valid' : '';
}

const toggleUncapturedTicketOverlay = () => {
    state.overlay.active = !state.overlay.active;
    state.overlay.text = state.overlay.text !== 'Not Valid' ? 'Not Valid' : '';
}
// ----------------------------------------------------------------------------------------------------------------
// Decoder                                                                                                  Decoder

const init = () => {
    toggleLoadingOverlay();
}

const onDecode = async (value) => {
    const response = await axios.post('validator', {
        value,
    });

    state.overlay.active = true;
    // if (response.status === 200) {

    // }
}
</script>

<template>

    <Head title="Validator" />

    <div class=" grid place-items-center h-screen max-w-6xl h-full mx-auto sm:px-6 lg:px-8">
        <div class="
          mt-8
          bg-white
          relative
          flex
          justify-center
          items-center
        ">
            <div v-if="state.overlay.active" @click="state.overlay.active = false" class="translate-y-12 bg-white rounded shadow text-black mt-6 absolute z-10 h-80 w-80 rounded flex flex-col">
                <div class="h-60 flex flex-col">
                    <img class="h-40"
                        src="https://shotgun.live/_next/image?url=https%3A%2F%2Fres.cloudinary.com%2Fshotgun%2Fimage%2Fupload%2Fv1652978697%2Fproduction%2Fartworks%2F281745963_143964308179678_3501364114518875116_n_p9geld.jpg&w=3840&q=75" />
                    <div class="text-center h-20 flex items-center justify-center flex-col">
                        <h3>{{ state.ticket.name }}</h3>
                        <p class="text-gray-400">{{ state.ticket.type }}</p>
                    </div>
                </div>
                <div class="h-20 flex justify-center items-center text-white bg-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <p class="ml-2 text-sm font-medium">
                        Valide
                    </p>
                </div>
            </div>
            <qr-stream @init="init" @decode="onDecode"></qr-stream>
        </div>
    </div>
</template>
