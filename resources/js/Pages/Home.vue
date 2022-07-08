<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, reactive, toRef } from "vue";
import { Inertia } from '@inertiajs/inertia'
import { Head, Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import Header from '../Components/Header.vue';
import Footer from '../Components/Footer.vue';

const state = reactive({
  city: {},
  carousels: {},
});

const handleCityChanged = async (city) => {
  state.city = city;
  getCarousels();
}

const getCarousels = async () => {
    console.log(state.city)

  const city = state.city;
  const response = await axios.get("/api/carousels");

  if (response.data) {
    response.data.forEach(async (key) => {
      const { data } = await axios.get(`/api/carousels/${key}?city_id=${city.id}`);
      if (data.data.length > 0) {
        state.carousels[key] = data;
      }
    });
  } else {
    state.carousels = {};
  }



    console.log(state.carousels)
}

</script>

<template>
    <AppLayout @city-changed="handleCityChanged" title="💃 Tickets for Live Music Events">

        <div v-if="state.carousels" class="max-w-6xl mx-auto sm:px-6 lg:px-8 mb-8">
            <div v-bind:key="index" v-for="(carousel, index) in state.carousels" class="
          mt-12
          container
        ">
                <h3 class="text-2xl font-bold text-white">{{ carousel.label }}</h3>
                <div class="grid grid-cols-3 gap-8 items-center mt-4">
                    <a v-bind:key="index" v-for="(event, index) in carousel.data" :href="'/events/' + event.slug"
                        class="flex relative h-60 w-full shadow-md rounded">
                        <img class="inset-0 h-full w-full object-cover object-center absolute rounded-xl"
                            :src="event.cover_url">
                        <div class="block min-h-full min-w-full bg-black absolute opacity-25 rounded-xl"></div>
                        <div class="ml-2 flex flex-col mt-auto mb-4">
                            <h5 class="text-lg font-bold text-white max-w-xs relative">{{ event.name }}</h5>
                            <p class="text-sm text-gray-300 relative">{{ event.beautiful_date }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<style scoped>
.bg-gray-100 {
  background-color: #f7fafc;
  background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}

.border-gray-200 {
  border-color: #edf2f7;
  border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}

.text-gray-400 {
  color: #cbd5e0;
  color: rgba(203, 213, 224, var(--tw-text-opacity));
}

.text-gray-500 {
  color: #a0aec0;
  color: rgba(160, 174, 192, var(--tw-text-opacity));
}

.text-gray-600 {
  color: #718096;
  color: rgba(113, 128, 150, var(--tw-text-opacity));
}

.text-gray-700 {
  color: #4a5568;
  color: rgba(74, 85, 104, var(--tw-text-opacity));
}

.text-gray-900 {
  color: #1a202c;
  color: rgba(26, 32, 44, var(--tw-text-opacity));
}

@media (prefers-color-scheme: dark) {
  .dark\:bg-gray-800 {
    background-color: #2d3748;
    background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
  }

  .dark\:bg-gray-900 {
    background-color: #1a202c;
    background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
  }

  .dark\:border-gray-700 {
    border-color: #4a5568;
    border-color: rgba(74, 85, 104, var(--tw-border-opacity));
  }

  .dark\:text-white {
    color: #fff;
    color: rgba(255, 255, 255, var(--tw-text-opacity));
  }

  .dark\:text-gray-400 {
    color: #cbd5e0;
    color: rgba(203, 213, 224, var(--tw-text-opacity));
  }
}
</style>
