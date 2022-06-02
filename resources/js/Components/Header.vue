<script setup>
import { ref, onMounted, reactive } from "vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import axios from "axios";

const searchInput = ref(null);

const state = reactive({
    queryText: "",
    queryCity: "Paris",
    loading: false,
    dropdownResultsOpen: false,
    searchEventsModalOpen: false,
    searchEvents: {},
    upcomingEvents: {},
    promotedEvents: {},
});

const setCity = async (city) => {
    state.queryCity = city
    state.dropdownResultsOpen = false;
}

const search = async () => {
    state.loading = true;
    const response = await axios.get("/api/events/search", {
        params: {
            query: `${state.queryText}+${state.queryCity}`,
        }
    });

    state.loading = false;
    state.searchEvents = response.data.data;
}

onMounted(() => {
    searchInput.value.focus()
})

const openSearchEventsModal = async () => {
    state.searchEventsModalOpen = true
}
</script>


<template>
    <div v-bind:class="{ invisible: !state.searchEventsModalOpen }" class="h-full w-full z-50 absolute bg-gray-900">
        <div class="drop-shadow-md bg-gray-700">
            <div class="flex container mx-auto items-center px-4 py-4">
                <span class="text-white hover:text-blue-600 h-5 w-5" @click="state.searchEventsModalOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                <input ref="searchInput"
                    class="focus:border-transparent focus:ring-0 caret-slate-50 inline-flex border-none justify-center w-full text-sm font-medium text-white bg-transparent focus:outline-none  placeholder:text-xl"
                    type="text" v-model="state.queryText" placeholder="Event, venue, artist..." @input="search" />
            </div>
        </div>
        <div class="container mx-auto text-white mt-4" v-if="state.searchEvents && state.queryText">
            <h3 class="text-2xl font-bold">Results</h3>
            <div class="mt-4 grid grid-cols-3 gap-4">
                <div v-bind:key="index" v-for="(event, index) in state.searchEvents">
                    <a :href="'/events/' + event.slug"
                        class="block p-10 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{
                                event.name
                        }}</h5>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-900 dark:text-white">{{ event.date }}</span>
                            <a :href="'/events/' + event.slug"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Book</a>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div v-if="state.loading" class="container mx-auto mt-4">
            <svg role="status" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor" />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill" />
            </svg>
        </div>
    </div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 pt-5">
        <header>
            <div class="container mx-auto">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <a href="/">
                            <svg height="30" viewBox="0 0 524 202" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M109.692 139.989C109.692 139.989 92.9702 230.894 39.0763 192.575C-14.8175 154.256 109.692 139.989 109.692 139.989Z"
                                    fill="#667EEA" />
                                <path
                                    d="M109.692 139.989C105.884 159.918 85.4502 233.284 32.155 186.848C32.155 186.848 84.9599 182.34 61.1116 170.993C46.5506 164.079 109.692 139.989 109.692 139.989Z"
                                    fill="#5A67D8" />
                                <path
                                    d="M176.368 35.9398C143.814 35.9398 121.562 58.1635 121.562 86.9591C121.562 115.755 143.404 137.962 178.632 137.962C197.446 137.962 212.235 131.727 222.32 119.594C222.477 119.403 222.587 119.183 222.645 118.949C222.703 118.714 222.706 118.471 222.655 118.235C222.603 117.999 222.498 117.776 222.347 117.582C222.196 117.387 222.002 117.226 221.779 117.108L199.96 105.343C199.635 105.165 199.258 105.092 198.884 105.136C198.511 105.179 198.164 105.337 197.896 105.584C193.666 109.381 186.881 112.06 179.019 112.06C168.632 112.06 159.806 109.135 155.388 100.905C155.257 100.652 155.197 100.372 155.213 100.092C155.23 99.8114 155.322 99.5394 155.482 99.3018C155.642 99.0641 155.864 98.8686 156.126 98.7337C156.389 98.5988 156.684 98.529 156.984 98.5309H225.969C226.382 98.5327 226.782 98.399 227.101 98.1528C227.42 97.9067 227.636 97.5637 227.714 97.1833C228.358 93.8018 228.677 90.3728 228.666 86.9378C228.694 57.7785 206.442 35.9398 176.368 35.9398ZM195.581 76.9114H156.117C155.833 76.911 155.553 76.8461 155.302 76.7222C155.05 76.5984 154.834 76.4193 154.672 76.2003C154.51 75.9812 154.407 75.7287 154.372 75.4643C154.337 75.1998 154.37 74.9313 154.47 74.6816C157.833 66.2434 165.331 61.6447 176.134 61.6447C184.766 61.6447 193.392 65.1579 197.229 74.6762C197.328 74.9262 197.361 75.195 197.326 75.4597C197.291 75.7243 197.188 75.9771 197.026 76.1965C196.864 76.4159 196.649 76.5954 196.397 76.7199C196.146 76.8444 195.866 76.9101 195.581 76.9114V76.9114Z"
                                    fill="white" />
                                <path
                                    d="M294.834 35.9398C262.286 35.9398 240.028 58.1635 240.028 86.9591C240.028 115.755 261.87 137.962 297.098 137.962C315.912 137.962 330.701 131.727 340.786 119.594C340.943 119.406 341.055 119.188 341.114 118.955C341.173 118.723 341.178 118.481 341.13 118.247C341.081 118.012 340.98 117.79 340.832 117.596C340.684 117.401 340.494 117.238 340.273 117.118L318.449 105.354C318.125 105.176 317.747 105.103 317.374 105.146C317 105.19 316.653 105.347 316.385 105.595C312.16 109.391 305.37 112.07 297.514 112.07C287.126 112.07 278.295 109.145 273.882 100.916C273.751 100.663 273.691 100.383 273.708 100.103C273.724 99.8221 273.817 99.5501 273.977 99.3125C274.137 99.0748 274.358 98.8793 274.621 98.7444C274.884 98.6095 275.179 98.5397 275.479 98.5416H344.464C344.878 98.5447 345.279 98.4115 345.599 98.1652C345.919 97.919 346.137 97.5753 346.214 97.194C346.859 93.8126 347.177 90.3835 347.166 86.9485C347.166 57.7785 324.914 35.9398 294.834 35.9398ZM314.048 76.9114H274.589C274.305 76.911 274.025 76.8461 273.774 76.7222C273.522 76.5984 273.306 76.4193 273.144 76.2003C272.982 75.9812 272.879 75.7287 272.844 75.4643C272.809 75.1998 272.842 74.9313 272.942 74.6816C276.305 66.2434 283.802 61.6447 294.606 61.6447C303.232 61.6447 311.858 65.1579 315.701 74.6762C315.802 74.9266 315.836 75.1962 315.801 75.4618C315.766 75.7274 315.663 75.9811 315.5 76.201C315.337 76.4209 315.12 76.6004 314.867 76.7241C314.614 76.8479 314.333 76.9122 314.048 76.9114V76.9114Z"
                                    fill="white" />
                                <path
                                    d="M426.282 35.9397C414.35 35.9397 405.188 39.2658 398.523 44.918C398.273 45.1341 397.961 45.2772 397.625 45.3298C397.29 45.3823 396.945 45.342 396.634 45.2138C396.322 45.0856 396.057 44.875 395.871 44.6079C395.685 44.3407 395.587 44.0285 395.587 43.7095V1.64164C395.587 1.20625 395.402 0.788695 395.074 0.480827C394.746 0.172959 394.301 0 393.836 0V0L366.47 10.16C365.484 10.3953 364.715 10.898 364.715 11.8017V133.615C364.715 133.832 364.76 134.048 364.849 134.248C364.938 134.449 365.068 134.632 365.233 134.785C365.397 134.939 365.592 135.06 365.806 135.143C366.02 135.226 366.25 135.268 366.482 135.267H393.848C394.315 135.267 394.763 135.093 395.094 134.783C395.424 134.473 395.61 134.053 395.61 133.615V130.203C395.61 129.885 395.71 129.573 395.897 129.307C396.083 129.041 396.348 128.831 396.659 128.703C396.97 128.576 397.314 128.536 397.649 128.589C397.984 128.641 398.296 128.784 398.546 129C405.182 134.647 414.35 137.962 426.282 137.962C453.272 137.962 475.524 115.739 475.524 86.9484C475.524 58.1581 453.249 35.9397 426.282 35.9397ZM420.102 110.536C405.889 110.536 395.587 101.445 395.587 86.9591C395.587 72.473 405.889 63.3825 420.102 63.3825C434.315 63.3825 444.617 72.473 444.617 86.9591C444.617 101.445 434.321 110.536 420.102 110.536V110.536Z"
                                    fill="white" />
                                <path
                                    d="M106.043 37.5867L88.1013 46.2227C88.0442 46.2493 87.9803 46.2604 87.9168 46.2547C87.8534 46.249 87.7929 46.2268 87.7421 46.1906C78.1755 39.484 66.5386 35.8852 54.5951 35.9397C24.3101 35.9397 0 58.1634 0 86.959C0 115.755 24.3101 137.962 54.5951 137.962C84.8801 137.962 109.196 115.739 109.196 86.9483V39.3995C109.193 39.0524 109.097 38.7116 108.915 38.4093C108.733 38.107 108.472 37.8532 108.156 37.6718C107.841 37.4903 107.481 37.3873 107.111 37.3725C106.742 37.3576 106.374 37.4313 106.043 37.5867V37.5867ZM54.6122 109.776C41.2257 109.776 30.9235 100.686 30.9235 86.9751C30.9235 73.2644 41.2086 64.1685 54.5951 64.1685C67.9816 64.1685 78.2895 73.2591 78.2895 86.9697C78.2895 100.68 67.9873 109.76 54.5951 109.76L54.6122 109.776Z"
                                    fill="#667EEA" />
                                <path
                                    d="M494.343 10.331L519.742 0.743136C521.453 -0.299603 523.682 0.283261 523.682 2.22436V132.727C523.686 133.047 523.624 133.365 523.497 133.662C523.371 133.959 523.183 134.23 522.944 134.459C522.706 134.688 522.422 134.871 522.108 134.996C521.795 135.122 521.458 135.189 521.116 135.192H495.717C495.377 135.188 495.04 135.121 494.727 134.995C494.414 134.869 494.131 134.686 493.893 134.457C493.655 134.228 493.468 133.957 493.342 133.661C493.216 133.364 493.153 133.047 493.157 132.727V12.4111C493.156 11.9994 493.264 11.5938 493.471 11.2308C493.678 10.8677 493.978 10.5585 494.343 10.331V10.331Z"
                                    fill="white" />
                            </svg>
                        </a>
                        <div class="flex ml-4">
                            <div class="relative inline-block text-left">
                                <div>
                                    <button @click="state.dropdownResultsOpen = !state.dropdownResultsOpen"
                                        type="button"
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                                        id="menu-button" aria-expanded="true" aria-haspopup="true">
                                        {{ state.queryCity }}
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <div v-bind:class="{ invisible: !state.dropdownResultsOpen }"
                                    class="origin-top-left z-10 absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                    <div v-bind:key="country" v-for="(cities, country) in $page.props.countries"
                                        class="py-1" role="none">
                                        <a class="text-gray-700 font-bold uppercase block px-4 py-2 text-sm"
                                            role="menuitem">{{ country
                                            }}</a>
                                        <a v-bind:key="city" v-for="(city) in cities" @click="setCity(city)"
                                            class="text-gray-700 block px-4 py-2 text-sm cursor-pointer" role="menuitem"
                                            tabindex="-1" id="menu-item-0">{{ city }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4">
                                <input @click="openSearchEventsModal()"
                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                                    type="text" placeholder="Event, venue, artist..." />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <a href="#" class="text-gray-500 hover:text-gray-700">Organizers</a>
                        <a href="#" class="ml-4 text-gray-500 hover:text-gray-700">Account</a>
                    </div>
                </div>
            </div>
        </header>
    </div>
</template>