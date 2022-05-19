<script setup>
import { reactive } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

const state = reactive({
  query: "",
  searchEvents: {},
  upcomingEvents: {},
  promotedEvents: {},
});

const search = async () => {
    const response = await axios.get("/api/events/search", {
        params: {
            query: state.query
        }
    });

    state.searchEvents = response.data.data;
}

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />

  <div
    class="
      relative
      items-top
      justify-center
      min-h-screen
      bg-gray-100
      dark:bg-gray-900
      sm:items-center sm:pt-0
    "
  >
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 pt-5">
      <div>
        <svg
          height="30"
          viewBox="0 0 524 202"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M109.692 139.989C109.692 139.989 92.9702 230.894 39.0763 192.575C-14.8175 154.256 109.692 139.989 109.692 139.989Z"
            fill="#667EEA"
          />
          <path
            d="M109.692 139.989C105.884 159.918 85.4502 233.284 32.155 186.848C32.155 186.848 84.9599 182.34 61.1116 170.993C46.5506 164.079 109.692 139.989 109.692 139.989Z"
            fill="#5A67D8"
          />
          <path
            d="M176.368 35.9398C143.814 35.9398 121.562 58.1635 121.562 86.9591C121.562 115.755 143.404 137.962 178.632 137.962C197.446 137.962 212.235 131.727 222.32 119.594C222.477 119.403 222.587 119.183 222.645 118.949C222.703 118.714 222.706 118.471 222.655 118.235C222.603 117.999 222.498 117.776 222.347 117.582C222.196 117.387 222.002 117.226 221.779 117.108L199.96 105.343C199.635 105.165 199.258 105.092 198.884 105.136C198.511 105.179 198.164 105.337 197.896 105.584C193.666 109.381 186.881 112.06 179.019 112.06C168.632 112.06 159.806 109.135 155.388 100.905C155.257 100.652 155.197 100.372 155.213 100.092C155.23 99.8114 155.322 99.5394 155.482 99.3018C155.642 99.0641 155.864 98.8686 156.126 98.7337C156.389 98.5988 156.684 98.529 156.984 98.5309H225.969C226.382 98.5327 226.782 98.399 227.101 98.1528C227.42 97.9067 227.636 97.5637 227.714 97.1833C228.358 93.8018 228.677 90.3728 228.666 86.9378C228.694 57.7785 206.442 35.9398 176.368 35.9398ZM195.581 76.9114H156.117C155.833 76.911 155.553 76.8461 155.302 76.7222C155.05 76.5984 154.834 76.4193 154.672 76.2003C154.51 75.9812 154.407 75.7287 154.372 75.4643C154.337 75.1998 154.37 74.9313 154.47 74.6816C157.833 66.2434 165.331 61.6447 176.134 61.6447C184.766 61.6447 193.392 65.1579 197.229 74.6762C197.328 74.9262 197.361 75.195 197.326 75.4597C197.291 75.7243 197.188 75.9771 197.026 76.1965C196.864 76.4159 196.649 76.5954 196.397 76.7199C196.146 76.8444 195.866 76.9101 195.581 76.9114V76.9114Z"
            fill="white"
          />
          <path
            d="M294.834 35.9398C262.286 35.9398 240.028 58.1635 240.028 86.9591C240.028 115.755 261.87 137.962 297.098 137.962C315.912 137.962 330.701 131.727 340.786 119.594C340.943 119.406 341.055 119.188 341.114 118.955C341.173 118.723 341.178 118.481 341.13 118.247C341.081 118.012 340.98 117.79 340.832 117.596C340.684 117.401 340.494 117.238 340.273 117.118L318.449 105.354C318.125 105.176 317.747 105.103 317.374 105.146C317 105.19 316.653 105.347 316.385 105.595C312.16 109.391 305.37 112.07 297.514 112.07C287.126 112.07 278.295 109.145 273.882 100.916C273.751 100.663 273.691 100.383 273.708 100.103C273.724 99.8221 273.817 99.5501 273.977 99.3125C274.137 99.0748 274.358 98.8793 274.621 98.7444C274.884 98.6095 275.179 98.5397 275.479 98.5416H344.464C344.878 98.5447 345.279 98.4115 345.599 98.1652C345.919 97.919 346.137 97.5753 346.214 97.194C346.859 93.8126 347.177 90.3835 347.166 86.9485C347.166 57.7785 324.914 35.9398 294.834 35.9398ZM314.048 76.9114H274.589C274.305 76.911 274.025 76.8461 273.774 76.7222C273.522 76.5984 273.306 76.4193 273.144 76.2003C272.982 75.9812 272.879 75.7287 272.844 75.4643C272.809 75.1998 272.842 74.9313 272.942 74.6816C276.305 66.2434 283.802 61.6447 294.606 61.6447C303.232 61.6447 311.858 65.1579 315.701 74.6762C315.802 74.9266 315.836 75.1962 315.801 75.4618C315.766 75.7274 315.663 75.9811 315.5 76.201C315.337 76.4209 315.12 76.6004 314.867 76.7241C314.614 76.8479 314.333 76.9122 314.048 76.9114V76.9114Z"
            fill="white"
          />
          <path
            d="M426.282 35.9397C414.35 35.9397 405.188 39.2658 398.523 44.918C398.273 45.1341 397.961 45.2772 397.625 45.3298C397.29 45.3823 396.945 45.342 396.634 45.2138C396.322 45.0856 396.057 44.875 395.871 44.6079C395.685 44.3407 395.587 44.0285 395.587 43.7095V1.64164C395.587 1.20625 395.402 0.788695 395.074 0.480827C394.746 0.172959 394.301 0 393.836 0V0L366.47 10.16C365.484 10.3953 364.715 10.898 364.715 11.8017V133.615C364.715 133.832 364.76 134.048 364.849 134.248C364.938 134.449 365.068 134.632 365.233 134.785C365.397 134.939 365.592 135.06 365.806 135.143C366.02 135.226 366.25 135.268 366.482 135.267H393.848C394.315 135.267 394.763 135.093 395.094 134.783C395.424 134.473 395.61 134.053 395.61 133.615V130.203C395.61 129.885 395.71 129.573 395.897 129.307C396.083 129.041 396.348 128.831 396.659 128.703C396.97 128.576 397.314 128.536 397.649 128.589C397.984 128.641 398.296 128.784 398.546 129C405.182 134.647 414.35 137.962 426.282 137.962C453.272 137.962 475.524 115.739 475.524 86.9484C475.524 58.1581 453.249 35.9397 426.282 35.9397ZM420.102 110.536C405.889 110.536 395.587 101.445 395.587 86.9591C395.587 72.473 405.889 63.3825 420.102 63.3825C434.315 63.3825 444.617 72.473 444.617 86.9591C444.617 101.445 434.321 110.536 420.102 110.536V110.536Z"
            fill="white"
          />
          <path
            d="M106.043 37.5867L88.1013 46.2227C88.0442 46.2493 87.9803 46.2604 87.9168 46.2547C87.8534 46.249 87.7929 46.2268 87.7421 46.1906C78.1755 39.484 66.5386 35.8852 54.5951 35.9397C24.3101 35.9397 0 58.1634 0 86.959C0 115.755 24.3101 137.962 54.5951 137.962C84.8801 137.962 109.196 115.739 109.196 86.9483V39.3995C109.193 39.0524 109.097 38.7116 108.915 38.4093C108.733 38.107 108.472 37.8532 108.156 37.6718C107.841 37.4903 107.481 37.3873 107.111 37.3725C106.742 37.3576 106.374 37.4313 106.043 37.5867V37.5867ZM54.6122 109.776C41.2257 109.776 30.9235 100.686 30.9235 86.9751C30.9235 73.2644 41.2086 64.1685 54.5951 64.1685C67.9816 64.1685 78.2895 73.2591 78.2895 86.9697C78.2895 100.68 67.9873 109.76 54.5951 109.76L54.6122 109.776Z"
            fill="#667EEA"
          />
          <path
            d="M494.343 10.331L519.742 0.743136C521.453 -0.299603 523.682 0.283261 523.682 2.22436V132.727C523.686 133.047 523.624 133.365 523.497 133.662C523.371 133.959 523.183 134.23 522.944 134.459C522.706 134.688 522.422 134.871 522.108 134.996C521.795 135.122 521.458 135.189 521.116 135.192H495.717C495.377 135.188 495.04 135.121 494.727 134.995C494.414 134.869 494.131 134.686 493.893 134.457C493.655 134.228 493.468 133.957 493.342 133.661C493.216 133.364 493.153 133.047 493.157 132.727V12.4111C493.156 11.9994 493.264 11.5938 493.471 11.2308C493.678 10.8677 493.978 10.5585 494.343 10.331V10.331Z"
            fill="white"
          />
        </svg>

         <input
            type="text"
            v-model="state.query"
            placeholder="Event, venue, artist..."
            @input="search"
         />

         <div v-if="state.searchEvents">
           <ul>
             <li v-bind:key="index" v-for="(event, index) in state.searchEvents">
              <a :href="'/events/' + event.slug">{{ event.name }}</a>
             </li>
           </ul>
         </div>
      </div>
    </div>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div
        class="
          mt-8
          bg-white
          dark:bg-gray-800
          overflow-hidden
          shadow
          sm:rounded-lg
        "
      >
        <div class="grid grid-cols-1 md:grid-cols-2">
          <div class="p-6">
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                />
              </svg>
              <div class="ml-4 text-lg leading-7 font-semibold">
                <a
                  href="https://laravel.com/docs"
                  class="underline text-gray-900 dark:text-white"
                  >Documentation</a
                >
              </div>
            </div>

            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Laravel has wonderful, thorough documentation covering every
                aspect of the framework. Whether you are new to the framework or
                have previous experience with Laravel, we recommend reading all
                of the documentation from beginning to end.
              </div>
            </div>
          </div>

          <div
            class="
              p-6
              border-t border-gray-200
              dark:border-gray-700
              md:border-t-0 md:border-l
            "
          >
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                />
                <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <div class="ml-4 text-lg leading-7 font-semibold">
                <a
                  href="https://laracasts.com"
                  class="underline text-gray-900 dark:text-white"
                  >Laracasts</a
                >
              </div>
            </div>

            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Laracasts offers thousands of video tutorials on Laravel, PHP,
                and JavaScript development. Check them out, see for yourself,
                and massively level up your development skills in the process.
              </div>
            </div>
          </div>

          <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                />
              </svg>
              <div class="ml-4 text-lg leading-7 font-semibold">
                <a
                  href="https://laravel-news.com/"
                  class="underline text-gray-900 dark:text-white"
                  >Laravel News</a
                >
              </div>
            </div>

            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Laravel News is a community driven portal and newsletter
                aggregating all of the latest and most important news in the
                Laravel ecosystem, including new package releases and tutorials.
              </div>
            </div>
          </div>

          <div
            class="
              p-6
              border-t border-gray-200
              dark:border-gray-700
              md:border-l
            "
          >
            <div class="flex items-center">
              <svg
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                viewBox="0 0 24 24"
                class="w-8 h-8 text-gray-500"
              >
                <path
                  d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <div
                class="
                  ml-4
                  text-lg
                  leading-7
                  font-semibold
                  text-gray-900
                  dark:text-white
                "
              >
                Vibrant Ecosystem
              </div>
            </div>

            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Laravel's robust library of first-party tools and libraries,
                such as
                <a href="https://forge.laravel.com" class="underline">Forge</a>,
                <a href="https://vapor.laravel.com" class="underline">Vapor</a>,
                <a href="https://nova.laravel.com" class="underline">Nova</a>,
                and
                <a href="https://envoyer.io" class="underline">Envoyer</a> help
                you take your projects to the next level. Pair them with
                powerful open source libraries like
                <a href="https://laravel.com/docs/billing" class="underline"
                  >Cashier</a
                >,
                <a href="https://laravel.com/docs/dusk" class="underline"
                  >Dusk</a
                >,
                <a
                  href="https://laravel.com/docs/broadcasting"
                  class="underline"
                  >Echo</a
                >,
                <a href="https://laravel.com/docs/horizon" class="underline"
                  >Horizon</a
                >,
                <a href="https://laravel.com/docs/sanctum" class="underline"
                  >Sanctum</a
                >,
                <a href="https://laravel.com/docs/telescope" class="underline"
                  >Telescope</a
                >, and more.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
